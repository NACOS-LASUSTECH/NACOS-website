<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vote_transactions')) {
            DB::statement("ALTER TABLE `vote_transactions` MODIFY `payment_method` ENUM('paystack','korapay','bank_transfer') NOT NULL DEFAULT 'paystack'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vote_transactions')) {
            DB::statement("ALTER TABLE `vote_transactions` MODIFY `payment_method` ENUM('paystack','bank_transfer') NOT NULL DEFAULT 'paystack'");
        }
    }
};
