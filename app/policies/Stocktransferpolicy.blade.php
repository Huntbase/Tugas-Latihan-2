<?php

namespace App\Policies;

use App\Models\StockTransfer;
use App\Models\User;
use App\Services\StockTransferApprovalService;

class StockTransferPolicy
{
    public function __construct(private StockTransferApprovalService $service) {}

    public function submit(User $user, StockTransfer $transfer): bool
    {
        return $this->service->canSubmit($user, $transfer);
    }

    public function approve(User $user, StockTransfer $transfer): bool
    {
        return $this->service->canApproveOrReject($user, $transfer);
    }

    public function reject(User $user, StockTransfer $transfer): bool
    {
        return $this->service->canApproveOrReject($user, $transfer);
    }

    public function ship(User $user, StockTransfer $transfer): bool
    {
        return $this->service->canShip($user, $transfer);
    }

    public function receive(User $user, StockTransfer $transfer): bool
    {
        return $this->service->canReceiveOrRejectDelivery($user, $transfer);
    }

    public function rejectDelivery(User $user, StockTransfer $transfer): bool
    {
        return $this->service->canReceiveOrRejectDelivery($user, $transfer);
    }
}
