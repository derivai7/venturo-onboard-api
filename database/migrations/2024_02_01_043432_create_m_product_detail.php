<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMProductDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_product_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('m_product_id')
                ->comment('Fill with id from table m_product');
            $table->enum('type', ['Level', 'Toping'])
                ->comment('Fill with type of detail product');
            $table->string('description', 255)
                ->comment('Fill with description of detail product, ex : Topping Telur');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->index('m_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_product_detail');
    }
}
