<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shorten_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shorten_id')->constrained('shortens')->cascadeOnDelete();
            $table->string('code')->index();
            $table->timestamp('clicked_at')->index();
            $table->string('page_url', 2048);
            $table->string('destination_url', 2048);
            $table->text('referrer')->nullable();
            $table->string('referrer_host')->default('Direct')->index();
            $table->string('country', 16)->default('Unknown')->index();
            $table->string('language', 32)->nullable();
            $table->string('timezone', 128)->nullable();
            $table->unsignedInteger('screen_width')->nullable();
            $table->unsignedInteger('screen_height')->nullable();
            $table->string('browser', 64)->default('Unknown')->index();
            $table->string('os', 64)->default('Unknown');
            $table->string('device_type', 32)->default('Unknown')->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable()->index();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->timestamps();

            $table->index(['shorten_id', 'clicked_at']);
            $table->index(['code', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shorten_tracking_events');
    }
};
