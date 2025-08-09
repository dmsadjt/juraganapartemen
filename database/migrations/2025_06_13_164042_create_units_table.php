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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'apartemen' or 'rumah'
            $table->string('name'); // e.g. 'Unit A12'
            $table->string('tower')->nullable();
            $table->integer('floor')->nullable();
            $table->float('size'); // in m²
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->enum('listing_type', ['rent', 'sell'])->default('sell');
            $table->string('room_number')->nullable();
            $table->string('electricity')->nullable();
            $table->decimal('price_sell', 12, 2)->nullable(); // for sale
            $table->decimal('price_rent_month', 12, 2)->nullable(); // for rent per month
            $table->decimal('price_rent_year', 12, 2)->nullable(); // for rent per year
            $table->decimal('deposit', 12, 2)->nullable(); // deposit for rent
            $table->decimal('service_charge', 12, 2)->nullable(); // monthly service fee
            $table->string('surat_type')->nullable(); // e.g. PPJB, SHM
            $table->enum('status', ['available', 'booked', 'sold'])->default('available');
            $table->text('description')->nullable();
            $table->text('map_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
