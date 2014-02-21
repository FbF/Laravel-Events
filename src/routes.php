<?php

// e.g. http://domain.com/events or http://domain.com/events/yyyy/mm
Route::get(Config::get('laravel-events::routes.uri').'/{year?}/{month?}', 'Fbf\LaravelEvents\EventsController@index')->where(array('year' => '\d{4}', 'month' => '\d{2}'));

// e.g. http://domain.com/events/my-event-slug
Route::get(Config::get('laravel-events::routes.uri').'/{slug}', 'Fbf\LaravelEvents\EventsController@view');

// e.g. http://domain.com/events.rss
Route::get(Config::get('laravel-events::routes.uri').'.rss', 'Fbf\LaravelEvents\EventsController@rss');