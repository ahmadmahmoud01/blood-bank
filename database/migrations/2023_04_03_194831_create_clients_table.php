<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->string('password');
			$table->string('phone');
			$table->date('d_o_b');
			$table->date('last_donation_date');
			$table->integer('pin_code');

            // $table->unsignedBigInteger('city_id');

            // $table->foreign('city_id')->references('id')->on('cities');

            // $table->unsignedBigInteger('blood_type_id');

            // $table->foreign('blood_type_id')->references('id')->on('blood_types');


			// $table->foreignId('city_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
			// $table->foreignId('blood_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('api_token')->unique()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}
