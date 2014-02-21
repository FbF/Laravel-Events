@extends('layouts.master')

@section('title')
	{{ $event->page_title }}
@endsection

@section('meta_description')
	{{ $event->meta_description }}
@endsection

@section('meta_keywords')
	{{ $event->meta_keywords }}
@endsection

@section('content')
	@include('laravel-events::partials.details')
	@include('laravel-events::partials.archives')
@stop