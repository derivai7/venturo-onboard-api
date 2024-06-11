<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)
                ->comment('Fill with name of customer');
            $table->string('email', 50)->nullable()
                ->comment('Fill with email of customer');
            $table->string('phone_number', 25)->nullable()
                ->comment('Fill with phone_number of customer');
            $table->date('date_of_birth')->nullable()
                ->comment('Fill with date of birth of customer');
            $table->string('photo', 100)->nullable()
                ->comment('Fill with customer profile picture');
            $table->tinyInteger('is_verified')->default(0)
                ->comment('Fill with "1" if customer already verified. Fill with "0" if customer not verified');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->index('name');
            $table->index('email');
            $table->index('phone_number');
            $table->index('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_customer');
    }
}
