<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration {

	public function up()
	{
		Schema::create('cities', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->foreignId('governorate_id')->constrained();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('cities');
	}
}
