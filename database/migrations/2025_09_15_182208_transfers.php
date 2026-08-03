<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id('transfer_id');

            $table->foreignId('dari_warehouse_id')
                ->constrained('warehouses', 'warehouse_id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreignId('ke_warehouse_id')
                ->constrained('warehouses', 'warehouse_id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreignId('user_id') // user yang membuat transfer
                ->nullable()
                ->constrained('m_users', 'user_id')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreignId('approved_by_user_id') // user yang approve
                ->nullable()
                ->constrained('m_users', 'user_id')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->enum('status', [
                'pending',
                'approved',
                'in_transit',
                'completed',
                'rejected'
            ])->default('pending');

            // Timestamp tambahan
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('in_transit_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
