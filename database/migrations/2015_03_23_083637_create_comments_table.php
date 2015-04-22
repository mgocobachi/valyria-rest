<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('comments')) {
			Schema::create('comments', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->longText('body');
				$table->timestamps();
				$table->bigInteger('created_by')->unsigned();
				$table->foreign('created_by')->references('id')->on('consumers')->onDelete('cascade');
				$table->bigInteger('image_id')->unsigned();
				$table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('comments');
	}
}