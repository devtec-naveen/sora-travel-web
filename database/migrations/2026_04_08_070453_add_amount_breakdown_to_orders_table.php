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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('base_amount', 10, 2)->default(0)->after('notes');
            $table->decimal('platform_fee', 10, 2)->default(0)->after('base_amount');
            $table->decimal('addons_amount', 10, 2)->default(0)->after('platform_fee');
            $table->decimal('seat_amount', 10, 2)->default(0)->after('addons_amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('seat_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'base_amount',
                'platform_fee',
                'addons_amount',
                'seat_amount',
                'total_amount',
            ]);
        });
    }
};
