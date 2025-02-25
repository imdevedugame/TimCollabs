<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // pastikan DB facade di-import

class AddUserIdToTasksTable extends Migration
{
    public function up()
    {
        // Jika kolom user_id belum ada, tambahkan sebagai nullable
        if (!Schema::hasColumn('tasks', 'user_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // Update record yang memiliki user_id null atau kosong
        DB::table('tasks')
            ->whereNull('user_id')
            ->orWhere('user_id', '')
            ->update(['user_id' => 1]); // Pastikan user dengan id=1 ada!

        // Ubah kolom menjadi not nullable dan tambahkan foreign key
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
