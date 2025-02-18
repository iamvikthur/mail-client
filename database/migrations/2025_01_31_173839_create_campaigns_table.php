<?php

use App\Enums\CampaignStatusEnum;
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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUlid('mailbox_id')->references('id')->on('mailboxes')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('subject');
            $table->dateTime('send_time');
            $table->enum('status', [
                CampaignStatusEnum::DRAFT->value,
                CampaignStatusEnum::PUBLISHED->value,
                CampaignStatusEnum::ONGOING->value,
                CampaignStatusEnum::SENT->value
            ])->default(CampaignStatusEnum::DRAFT->value);
            $table->longText('template')->nullable();
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
