<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('m_voucher', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('m_customer_id')
                ->comment('Fill with id of m_customer');
            $table->string('m_promo_id')
                ->comment('Fill with id of m_promo');
            $table->date('start_time')
                ->comment('Fill with date when voucher can be used');
            $table->date('end_time')
                ->comment('Fill with date when voucher is expired');
            $table->integer('total_voucher')
                ->comment('Fill with total voucher can be used between start and end time');
            $table->double('nominal_rupiah')
                ->comment('Fill with nominal of voucher');
            $table->string('photo', 100)
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->index('m_customer_id');
            $table->index('m_promo_id');
            $table->index('start_time');
            $table->index('end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_voucher');
    }
}
