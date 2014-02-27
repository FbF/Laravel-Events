<?php

// Main default listing e.g. http://domain.com/events
Route::get(Config::get('laravel-events::routes.base_uri'), 'Fbf\LaravelEvents\EventsController@index');

// Archive (year / month) filtered listing e.g. http://domain.com/events/yyyy/mm
Route::get(Config::get('laravel-events::routes.base_uri').'/{year}/{month}', 'Fbf\LaravelEvents\EventsController@indexByYearMonth')->where(array('year' => '\d{4}', 'month' => '\d{2}'));

if (Config::get('laravel-events::routes.relationship_uri_prefix'))
{
	// Relationship filtered listing, e.g. by category or tag, e.g. http://domain.com/events/category/my-category
	Route::get(Config::get('laravel-events::routes.base_uri').'/'.Config::get('laravel-events::routes.relationship_uri_prefix').'/{relationshipIdentifier}', 'Fbf\LaravelEvents\EventsController@indexByRelationship');
}

// Event detail page e.g. http://domain.com/events/my-post
Route::get(Config::get('laravel-events::routes.base_uri').'/{slug}', 'Fbf\LaravelEvents\EventsController@view');

// RSS feed URL e.g. http://domain.com/events.rss
Route::get(Config::get('laravel-events::routes.base_uri').'.rss', 'Fbf\LaravelEvents\EventsController@rss');