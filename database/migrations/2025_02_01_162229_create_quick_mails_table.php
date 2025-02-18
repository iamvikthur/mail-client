<?php

use App\Enums\QuickMailStateEnum;
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
        Schema::create('quick_mails', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUlid('mailbox_id')->references('id')->on('mailboxes')->cascadeOnDelete();
            $table->foreignUlid('template_id')->nullable()->references('id')->on('templates')->cascadeOnDelete();
            $table->string('subject');
            $table->longText('body')->nullable();
            $table->json('recipients')->nullable();
            $table->dateTime('send_time')->default(now());
            $table->enum('state', [
                QuickMailStateEnum::DORMANT->value,
                QuickMailStateEnum::INQUEUE->value,
                QuickMailStateEnum::SENDING->value,
                QuickMailStateEnum::FINISHED->value,
            ])->default(QuickMailStateEnum::DORMANT->value);
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('meta')->nullable();
            $table->$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_mails');
    }
};
