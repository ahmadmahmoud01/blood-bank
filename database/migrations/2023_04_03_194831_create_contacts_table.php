<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {

	public function up()
	{
		Schema::create('contacts', function(Blueprint $table) {
			$table->increments('id');

			$table->string('subject');
			$table->text('message');

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('contacts');
	}
}
