<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pheramor_id')->unique();
            $table->string('sales_email')->unique();
            $table->string('account_email')->nullable();
            $table->string('source');
            $table->date('sales_date')->nullable();
            $table->date('ship_date')->nullable();
            $table->date('account_connected_date')->nullable();
            $table->date('swab_returned_date')->nullable();
            $table->date('ship_to_lab_date')->nullable();
            $table->date('lab_received_date')->nullable();
            $table->date('sequenced_date')->nullable();
            $table->date('uploaded_to_server_date')->nullable();
            $table->date('bone_marrow_consent_date')->nullable();
            $table->date('bone_marrow_shared_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
