<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fbf_events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->dateTime('starts');
			$table->dateTime('ends');
			$table->string('text_date')->nullable();
			$table->string('main_image')->nullable();
			$table->string('main_image_alt')->nullable();
			$table->string('you_tube_video_id')->nullable();
			$table->text('summary');
			$table->text('content');
			$table->string('link_text')->nullable();
			$table->string('link_url')->nullable();
			$table->float('map_latitude', 15, 8)->nullable()->default(0);
			$table->float('map_longitude', 15, 8)->nullable()->default(0);
			$table->tinyInteger('map_zoom')->unsigned()->nullable()->default(10);
			$table->float('marker_latitude', 15, 8)->nullable()->default(0);
			$table->float('marker_longitude', 15, 8)->nullable()->default(0);
			$table->string('marker_title')->nullable();
			$table->boolean('in_rss')->default(0);
			$table->string('slug')->unique();
			$table->string('page_title')->nullable();
			$table->text('meta_description')->nullable();
			$table->text('meta_keywords')->nullable();
			$table->enum('status', array('DRAFT', 'APPROVED'))->default('DRAFT');
			$table->dateTime('published_date')->nullable();
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
		Schema::drop('fbf_events');
	}

}
