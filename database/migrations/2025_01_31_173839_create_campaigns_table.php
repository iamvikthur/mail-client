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
            $table->foreignUlid('template_id')->references('id')->on('templates')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('subject');
            $table->dateTime('send_time');
            $table->enum('status', [
                CampaignStatusEnum::DRAFT->value,
                CampaignStatusEnum::PUBLISHED->value,
                CampaignStatusEnum::ONGOING->value,
                CampaignStatusEnum::SENT->value
            ])->default(CampaignStatusEnum::DRAFT->value);
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
