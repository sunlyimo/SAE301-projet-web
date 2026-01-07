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
        Schema::create('responsable_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('user_id');
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'accepted', 'refused'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('refused_at')->nullable();
            $table->timestamps();

            $table->index('club_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsable_invitations');
    }
};