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
        Schema::create('addresses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('contact_id');
            $table->string('street', 200);
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('country', 100);
            $table->string('postal_code', 10);
            $table->timestamps();

            $table->foreign('contact_id')->on("contacts")->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
