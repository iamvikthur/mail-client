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
        Schema::create("campaign_contact_list", function (Blueprint $table) {
            $table->foreignUlid('campaign_id')->references('id')->on('campaigns')->cascadeOnDelete();
            $table->foreignUlid('contact_list_id')->references('id')->on('contact_lists')->cascadeOnDelete();
            $table->primary(['campaign_id', 'contact_list_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_contact_list');
    }
};
