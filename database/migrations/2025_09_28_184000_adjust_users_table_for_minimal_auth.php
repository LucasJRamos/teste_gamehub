<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover 'name' se existir
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            // Garantir 'username' (único)
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('id');
            }
            // Garantir 'data_nascimento'
            if (!Schema::hasColumn('users', 'data_nascimento')) {
                $table->date('data_nascimento')->after('email');
            }
            // Garantir email único (na maioria já é)
            // if (!\DB::select("SHOW INDEX FROM users WHERE Column_name = 'email' AND Non_unique = 0")) {
            //     $table->unique('email');
            // }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable();
            }
            if (Schema::hasColumn('users', 'data_nascimento')) {
                $table->dropColumn('data_nascimento');
            }
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique(['username']);
                $table->dropColumn('username');
            }
        });
    }
};

