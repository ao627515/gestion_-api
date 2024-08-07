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
        Schema::create('user_role_access_rights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_role_id')->constrained('user_roles');
            $table->foreignId('access_right_id')->constrained('access_rights');
            $table->foreignId('attribute_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_access_rights');
    }
};
