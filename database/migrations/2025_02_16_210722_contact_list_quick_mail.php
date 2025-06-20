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
        Schema::create("contact_list_quick_mail", function (Blueprint $table) {
            $table->foreignUlid('quick_mail_id')->references('id')->on('campaigns')->cascadeOnDelete();
            $table->foreignUlid('contact_list_id')->references('id')->on('contact_lists')->cascadeOnDelete();
            $table->primary(['quick_mail_id', 'contact_list_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_list_quick_mail');
    }
};
