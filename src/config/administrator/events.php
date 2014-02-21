<?php

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Events',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'event',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Fbf\LaravelEvents\Event',

	/**
	 * The columns array
	 *
	 * @type array
	 */
	'columns' => array(
		'title' => array(
			'title' => 'Title'
		),
		'starts' => array(
			'title' => 'Event Starts',
		),
		'ends' => array(
			'title' => 'Event Ends',
		),
		'text_date' => array(
			'title' => 'Event Date (Text)',
		),
		'status' => array(
			'title' => 'Status',
			'select' => "CASE (:table).status WHEN '".Fbf\LaravelEvents\Event::APPROVED."' THEN 'Approved' WHEN '".Fbf\LaravelEvents\Event::DRAFT."' THEN 'Draft' END",
		),
		'updated_at' => array(
			'title' => 'Last Updated'
		),
	),

	/**
	 * The edit fields array
	 *
	 * @type array
	 */
	'edit_fields' => array(
		'title' => array(
			'title' => 'Title',
			'type' => 'text',
		),
		'starts' => array(
			'title' => 'Starts',
			'type' => 'datetime',
			'date_format' => 'yy-mm-dd', //optional, will default to this value
			'time_format' => 'HH:mm',    //optional, will default to this value
		),
		'ends' => array(
			'title' => 'Ends',
			'type' => 'datetime',
			'date_format' => 'yy-mm-dd', //optional, will default to this value
			'time_format' => 'HH:mm',    //optional, will default to this value
		),
		'text_date' => array(
			'title' => 'Event Date (Text)',
			'type' => 'text',
		),
		'main_image' => array(
			'title' => 'Image',
			'type' => 'image',
			'naming' => 'random',
			'location' => public_path() . Config::get('laravel-events::images.main_image.original.dir'),
			'size_limit' => 5,
			'sizes' => array(
				array(
					Config::get('laravel-events::images.main_image.sizes.resized.width'),
					Config::get('laravel-events::images.main_image.sizes.resized.height'),
					Config::get('laravel-events::images.main_image.sizes.resized.method'),
					public_path(Config::get('laravel-events::images.main_image.sizes.resized.dir')),
					100
				),
				array(
					Config::get('laravel-events::images.main_image.sizes.thumbnail.width'),
					Config::get('laravel-events::images.main_image.sizes.thumbnail.height'),
					Config::get('laravel-events::images.main_image.sizes.thumbnail.method'),
					public_path(Config::get('laravel-events::images.main_image.sizes.thumbnail.dir')),
					100
				),
			),
			'visible' => Config::get('laravel-events::images.main_image.show'),
		),
		'main_image_alt' => array(
			'title' => 'Image ALT text',
			'type' => 'text',
			'visible' => Config::get('laravel-events::images.main_image.show'),
		),
		'you_tube_video_id' => array(
			'title' => 'YouTube Video ID',
			'type' => 'text',
			'visible' => Config::get('laravel-events::you_tube.show'),
		),
		'summary' => array(
			'title' => 'Summary',
			'type' => 'wysiwyg',
		),
		'content' => array(
			'title' => 'Content',
			'type' => 'wysiwyg',
		),
		'link_text' => array(
			'title' => 'Link Text',
			'type' => 'text',
			'visible' => Config::get('laravel-events::link.show'),
		),
		'link_url' => array(
			'title' => 'Link URL',
			'type' => 'text',
			'visible' => Config::get('laravel-events::link.show'),
		),
		'map_latitude' => array(
			'title' => 'Map Latitude',
			'type' => 'number',
			'decimals' => 6,
			'visible' => Config::get('laravel-events::map.show') && Config::get('laravel-events::map.map_centre_different_to_marker'),
		),
		'map_longitude' => array(
			'title' => 'Map Longitude',
			'type' => 'number',
			'decimals' => 6,
			'visible' => Config::get('laravel-events::map.show') && Config::get('laravel-events::map.map_centre_different_to_marker'),
		),
		'map_zoom' => array(
			'title' => 'Map Zoom',
			'type' => 'enum',
			'options' => range(1,20),
			'visible' => Config::get('laravel-events::map.show') && Config::get('laravel-events::map.variable_map_zoom'),
		),
		'marker_latitude' => array(
			'title' => 'Map Marker Latitude',
			'type' => 'number',
			'decimals' => 6,
			'visible' => Config::get('laravel-events::map.show'),
		),
		'marker_longitude' => array(
			'title' => 'Map Marker Longitude',
			'type' => 'number',
			'decimals' => 6,
			'visible' => Config::get('laravel-events::map.show'),
		),
		'marker_title' => array(
			'title' => 'Map Marker Title',
			'type' => 'text',
			'visible' => function($model)
				{
					return Config::get('laravel-events::map.marker_title');
				},
		),
		'in_rss' => array(
			'title' => 'In RSS Feed?',
			'type' => 'bool',
		),
		'slug' => array(
			'title' => 'Slug',
			'type' => 'text',
			'visible' => function($model)
				{
					return $model->exists;
				},
		),
		'page_title' => array(
			'title' => 'Page Title',
			'type' => 'text',
		),
		'meta_description' => array(
			'title' => 'Meta Description',
			'type' => 'textarea',
			'height' => 130, //optional, defaults to 100
		),
		'meta_keywords' => array(
			'title' => 'Meta Keywords',
			'type' => 'textarea',
		),
		'published_date' => array(
			'title' => 'Published Date',
			'type' => 'datetime',
			'date_format' => 'yy-mm-dd', //optional, will default to this value
			'time_format' => 'HH:mm',    //optional, will default to this value
		),
		'status' => array(
			'type' => 'enum',
			'title' => 'Status',
			'options' => array(
				Fbf\LaravelEvents\Event::DRAFT => 'Draft',
				Fbf\LaravelEvents\Event::APPROVED => 'Approved',
			),
		),
		'created_at' => array(
			'title' => 'Created',
			'type' => 'datetime',
			'editable' => false,
		),
		'updated_at' => array(
			'title' => 'Updated',
			'type' => 'datetime',
			'editable' => false,
		),
	),

	/**
	 * The filter fields
	 *
	 * @type array
	 */
	'filters' => array(
		'title' => array(
			'title' => 'Title',
		),
		'starts' => array(
			'title' => 'Starts',
			'type' => 'datetime',
		),
		'ends' => array(
			'title' => 'Ends',
			'type' => 'datetime',
		),
		'summary' => array(
			'type' => 'text',
			'title' => 'Summary',
		),
		'content' => array(
			'type' => 'text',
			'title' => 'Content',
		),
		'published_date' => array(
			'title' => 'Published Date',
			'type' => 'datetime',
		),
		'status' => array(
			'type' => 'enum',
			'title' => 'Status',
			'options' => array(
				Fbf\LaravelEvents\Event::DRAFT => 'Draft',
				Fbf\LaravelEvents\Event::APPROVED => 'Approved',
			),
		),
	),

	/**
	 * The width of the model's edit form
	 *
	 * @type int
	 */
	'form_width' => 500,

	/**
	 * The validation rules for the form, based on the Laravel validation class
	 *
	 * @type array
	 */
	'rules' => array(
		'title' => 'required|max:255',
		'starts' => 'required|date_format:"Y-m-d H:i:s"|date',
		'ends' => 'required|date_format:"Y-m-d H:i:s"|date|after:starts',
		'main_image' => 'max:50',
		'main_image_alt' => 'required_with:image|max:255',
		'you_tube_video_id' => 'max:20',
		'summary' => 'required',
		'content' => 'required',
		'link_text' => 'max:50',
		'link_url' => 'max:255',
		'map_latitude' => 'numeric|between:-90,90',
		'map_longitude' => 'numeric|between:-180,180',
		'map_zoom' => 'integer|between:1,20',
		'marker_latitude' => 'numeric|between:-90,90',
		'marker_longitude' => 'numeric|between:-180,180',
		'slug' => 'alpha_dash|max:255',
		'page_title' => 'required|max:255',
		'status' => 'required|in:'.Fbf\LaravelEvents\Event::DRAFT.','.Fbf\LaravelEvents\Event::APPROVED,
		'published_date' => 'required|date_format:"Y-m-d H:i:s"|date',
	),

	/**
	 * The sort options for a model
	 *
	 * @type array
	 */
	'sort' => array(
		'field' => 'updated_at',
		'direction' => 'desc',
	),

	/**
	 * If provided, this is run to construct the front-end link for your model
	 *
	 * @type function
	 */
	'link' => function($model)
		{
			return $model->getUrl();
		},

);