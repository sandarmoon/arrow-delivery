<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendergateIdToTownshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('townships', function (Blueprint $table) {
             $table->unsignedBigInteger('sender_gate_id')->nullable();
             $table->foreign('sender_gate_id')
                    ->references('id')->on('sender_gates')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->dropColumn('sender_gate_id');
        });
    }
}
