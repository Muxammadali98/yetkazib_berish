<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id')->unsigned()->index();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->bigInteger('supplier_action_id')->unsigned()->index();
            $table->foreign('supplier_action_id')->references('id')->on('supplier_actions')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_files', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['supplier_action_id']);
        });
        Schema::dropIfExists('supplier_files');
    }
};
