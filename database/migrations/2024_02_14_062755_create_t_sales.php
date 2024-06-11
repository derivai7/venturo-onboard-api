<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('t_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('m_customer_id')
                ->comment('Fill with id of m_customer');
            $table->string('m_voucher_id')
                ->comment('Fill with id of m_voucher')
                ->default(null)
                ->nullable();
            $table->double('voucher_nominal')
                ->comment('Fill with nominal voucher in rupiah')
                ->default(0);
            $table->bigInteger('m_discount_id')
                ->comment('Fill with id of m_discount')
                ->default(null)
                ->nullable();
            $table->dateTime('date');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->index('m_customer_id');
            $table->index('m_voucher_id');
            $table->index('m_discount_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_sales');
    }
}
