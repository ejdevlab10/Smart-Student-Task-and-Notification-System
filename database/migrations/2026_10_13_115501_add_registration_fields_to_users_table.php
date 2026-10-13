<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('student_id')->nullable()->unique();
            $table->string('employee_id')->nullable()->unique();

            $table->string('profile_picture')->nullable();

            // Existing accounts remain approved.
            // New teacher registrations will explicitly be pending.
            $table->boolean('is_approved')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['student_id']);
            $table->dropUnique(['employee_id']);

            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'student_id',
                'employee_id',
                'profile_picture',
                'is_approved',
            ]);
        });
    }
};