<?php

namespace App\Services;

use App\Models\StockTransfer;
use App\Models\User;

class StockTransferApprovalService
{
    // Role IDs sesuai tabel roles: 1=Admin, 2=Supervisor, 3=Staff
    private const ADMIN = 1;
    private const SUPERVISOR = 2;
    private const STAFF = 3;

    /**
     * Staff boleh submit draft jadi menunggu_approval, kalau dia
     * terdaftar di gudang asal transfer tsb.
     */
    public function canSubmit(User $user, StockTransfer $transfer): bool
    {
        if ($transfer->status !== 'draft') {
            return false;
        }

        if ($user->role_id !== self::STAFF) {
            return false;
        }

        return $this->isAssignedTo($user, $transfer->from_warehouse_id)
            && $transfer->requested_by === $user->user_id;
    }

    /**
     * Admin selalu boleh approve/reject.
     * Supervisor boleh, TAPI:
     *  - harus mengawasi gudang asal transfer ini
     *  - tidak boleh approve transfer yang dia buat sendiri (Segregation of Duties)
     */
    public function canApproveOrReject(User $user, StockTransfer $transfer): bool
    {
        if ($transfer->status !== 'menunggu_approval') {
            return false;
        }

        if ($user->role_id === self::ADMIN) {
            return true;
        }

        if ($user->role_id !== self::SUPERVISOR) {
            return false;
        }

        $supervisesOrigin = $this->isAssignedTo($user, $transfer->from_warehouse_id);

        if (!$supervisesOrigin) {
            return false;
        }

        // Inti Segregation of Duties: tidak boleh approve permintaan sendiri
        if ($user->user_id === $transfer->requested_by) {
            return false;
        }

        return true;
    }

    /**
     * Staff gudang asal yang mengeksekusi pengiriman fisik.
     */
    public function canShip(User $user, StockTransfer $transfer): bool
    {
        if ($transfer->status !== 'disetujui') {
            return false;
        }

        if ($user->role_id !== self::STAFF) {
            return false;
        }

        return $this->isAssignedTo($user, $transfer->from_warehouse_id);
    }

    /**
     * Staff gudang tujuan yang mengeksekusi penerimaan fisik
     * (baik diterima maupun ditolak-saat-diterima).
     */
    public function canReceiveOrRejectDelivery(User $user, StockTransfer $transfer): bool
    {
        if ($transfer->status !== 'dikirim') {
            return false;
        }

        if ($user->role_id !== self::STAFF) {
            return false;
        }

        return $this->isAssignedTo($user, $transfer->to_warehouse_id);
    }

    private function isAssignedTo(User $user, int $warehouseId): bool
    {
        // Admin dianggap punya akses ke semua gudang meski tidak
        // punya baris assignment eksplisit
        if ($user->role_id === self::ADMIN) {
            return true;
        }

        return $user->warehouseAssignments()
            ->where('warehouse_id', $warehouseId)
            ->exists();
    }
}
