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
            $table->string('shipping_province_id', 10)->nullable()->after('shipping_address');
            $table->string('shipping_province')->nullable()->after('shipping_province_id');
            $table->string('shipping_city_id', 10)->nullable()->after('shipping_province');
            $table->string('shipping_city')->nullable()->after('shipping_city_id');
            $table->string('shipping_district_id', 10)->nullable()->after('shipping_city');
            $table->string('shipping_district')->nullable()->after('shipping_district_id');
            $table->string('shipping_village_id', 10)->nullable()->after('shipping_district');
            $table->string('shipping_village')->nullable()->after('shipping_village_id');
            $table->string('shipping_postal_code', 10)->nullable()->after('shipping_village');
            $table->text('shipping_address_detail')->nullable()->after('shipping_postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_province_id',
                'shipping_province',
                'shipping_city_id',
                'shipping_city',
                'shipping_district_id',
                'shipping_district',
                'shipping_village_id',
                'shipping_village',
                'shipping_postal_code',
                'shipping_address_detail',
            ]);
        });
    }
};
