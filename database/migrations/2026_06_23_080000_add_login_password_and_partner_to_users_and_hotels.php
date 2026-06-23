<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_password')->nullable()->unique()->after('password');
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->foreignId('partner_id')
                ->nullable()
                ->after('is_active')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['login_password']);
            $table->dropColumn('login_password');
        });
    }
};
