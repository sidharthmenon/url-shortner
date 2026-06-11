<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shorten_tracking_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shorten_id')->constrained('shortens')->cascadeOnDelete();
            $table->string('code')->index();
            $table->date('snapshot_date')->index();
            $table->string('dimension_type', 32)->index();
            $table->string('dimension_value')->default('');
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->timestamps();

            $table->unique(['shorten_id', 'snapshot_date', 'dimension_type', 'dimension_value'], 'shorten_tracking_snapshots_unique');
            $table->index(['shorten_id', 'snapshot_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shorten_tracking_snapshots');
    }
};
