Laravel Events
==============

A Laravel 4 package to add events listings to a site

## Events have
* start/end datetimes, and a text based date field for things like "Fri, 21st Feb. 9pm 'til late!"
* title, summary and content fields
* main image or YouTube Video
* map showing location (control the marker centre, map centre, map zoom and marker title)
* link to more info about the event (control the URL and the link text)
* separate page title, meta description and keywords fields, also a slug field which is automatically generated from the title
* Draft/Approved status
* published date for future publishing

## The package comes with
* Optional routes file with configurable URL prefix, or you can choose to use your own routes
* EventsController with actions for listing events, viewing individual events and an RSS feed of events.
* A migration for the events table, and a faker seed to populate it.
* The Event model for interacting with Event data
* Views and partials for showing the event listings and event details.

## Installation

Add the following to you composer.json file (Recommend swapping "dev-master" for the latest release)

    "fbf/laravel-events": "dev-master"

Run

    composer update

Add the following to app/config/app.php

    'Fbf\LaravelEvents\LaravelEventsServiceProvider'

Run the package migration

    php artisan migrate --package=fbf/laravel-events

Publish the config

    php artisan config:publish fbf/laravel-events

Optionally tweak the settings in the many config files for your app

Optionally copy the administrator config file (`src/config/administrator/events.php`) to your administrator model config directory.

Create the relevant image upload directories that you specify in your config, e.g.

    public/uploads/packages/fbf/laravel-events/main_image/original
    public/uploads/packages/fbf/laravel-events/main_image/thumbnail
    public/uploads/packages/fbf/laravel-events/main_image/resized

## Faker seed

The package comes with a seed that can populate the table with a whole bunch of sample events. There are some configuration options for the seeder in the seed config file. To run it:

    php artisan db:seed --class="Fbf\LaravelEvents\FakeEventsSeeder"

## Configuration

See the many configuration options in the files in the config directory

## Administrator

You can use the excellent Laravel Administrator package by frozennode to administer your data.

http://administrator.frozennode.com/docs/installation

A ready-to-use model config file for the `Event` model (`events.php`) is provided in the `src/config/administrator` directory of the package, which you can copy into the `app/config/administrator` directory (or whatever you set as the `model_config_path` in the administrator config file).

## Usage

The package's views are actually really simple, and most of the presentation is done in partials. This is deliberate so you
 can override the package's views in your own app, so you can include your own chrome, navigation and sidebars etc, yet
 you can also still make use of the partials provided, if you want to.

To override any view in your own app, just create the following directories and copy the file from the package into it
* `app/views/packages/fbf/laravel-events/events`
* `app/views/packages/fbf/laravel-events/partials`

## Extending

Let's say each event in your site can have a testimonial on it.

* After installing the package you can create the testimonials table and model etc (or use the fbf/laravel-testimonials package)
* Create the migration to add a testimonial_id field to the fbf_events table, and run it

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkEventsToTestimonials extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fbf_events', function(Blueprint $table)
		{
			$table->integer('testimonial_id')->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fbf_events', function(Blueprint $table)
		{
			$table->dropColumn('testimonial_id');
		});
	}

}
```

* Create a model in you app/models directory that extends the package model and includes the relationship

```php
<?php

class Event extends Fbf\LaravelEvents\Event {

	public function testimonial()
	{
		return $this->belongsTo('Fbf\LaravelTestimonials\Testimonial');
	}

}
```

* If you are using FrozenNode's Administrator package, update the events config file to use your new model, and to allow selecting the testimonial to attach to the page:

```php
	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Event',
```
...
```php
		'testimonial' => array(
			'title' => 'Testimonial',
			'type' => 'relationship',
			'name_field' => 'title',
		),
```

* Finally, update the IoC Container to inject an instance of your model into the controller, instead of the package's model, e.g. in `app/start/global.php`

```php
App::bind('Fbf\LaravelEvents\EventsController', function() {
    return new Fbf\LaravelEvents\EventsController(new Event);
});
```