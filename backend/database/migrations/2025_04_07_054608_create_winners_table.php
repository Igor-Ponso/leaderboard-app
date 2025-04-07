<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Player::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('score');
            $table->timestamp('created_at'); // only created_at because represents only the "victory moment"
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};
