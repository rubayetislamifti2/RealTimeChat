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
        Schema::table('one_to_ones', function (Blueprint $table) {
            $table->dropForeign(['to_user_id']);
            $table->renameColumn('to_user_id', 'chat_room_id');
        });

        Schema::table('one_to_ones', function (Blueprint $table) {
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('one_to_ones', function (Blueprint $table) {
            //
        });
    }
};
