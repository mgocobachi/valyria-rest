<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumersTable extends Migration
{
	/**
	 * Run the migrations
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('consumers')) {
			Schema::create('consumers', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('username', 128)->unique();
				$table->string('password', 64);
				$table->timestamps();
			});
		}
	}

	/**
	 * Reverse the migrations
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('consumers');
	}
}