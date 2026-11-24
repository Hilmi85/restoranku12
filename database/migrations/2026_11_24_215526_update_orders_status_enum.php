<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending', 'settlement', 'coocked', 'cooked') NOT NULL");

        DB::table('orders')
            ->where('status', 'coocked')
            ->update(['status' => 'cooked']);

        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending', 'settlement', 'cooked') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending', 'settlement', 'coocked', 'cooked') NOT NULL");

        DB::table('orders')
            ->where('status', 'cooked')
            ->update(['status' => 'coocked']);

        DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('pending', 'settlement', 'coocked') NOT NULL");
    }
};
