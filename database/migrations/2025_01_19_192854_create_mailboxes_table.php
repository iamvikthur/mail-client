<?php

use App\Enums\MailboxStateEnum;
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
        Schema::create('mail_boxes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('title');

            $table->string('smtp_host');
            $table->string('smtp_port');
            $table->string('smtp_username');
            $table->string('smtp_password');
            $table->string('smtp_encryption')->nullable();

            $table->string('imap_host')->nullable();
            $table->string('imap_port')->nullable();
            $table->string('imap_username')->nullable();
            $table->string('imap_password')->nullable();
            $table->string('imap_encryption')->nullable();

            $table->enum('state', [
                MailboxStateEnum::ONLINE->value,
                MailboxStateEnum::OFFLINE->value
            ])->default(MailboxStateEnum::OFFLINE->value);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_boxes');
    }
};
