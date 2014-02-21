<div class="item">

	<p class="item--all-link">
		<a href="{{ action('Fbf\LaravelEvents\EventsController@index') }}">
			{{ trans('laravel-events::messages.details.all_link_text') }}
		</a>
	</p>

	<h2 class="item--title">
		{{ $event->title }}
	</h2>

	<p class="item--date">
		{{ $event->text_date }}
	</p>

	<div class="item--summary">
		{{ $event->summary }}
	</div>

	@if (Config::get('laravel-events::views.view_page.show_share_partial'))
		@include('laravel-events::partials.share')
	@endif

	@if (!empty($event->you_tube_video_id))
		<div class="item--media item--media__youtube">
			<a href="{{ $event->getUrl() }}" title="{{ $event->title }}">
				{{ $event->getYouTubeEmbedCode() }}
			</a>
		</div>
	@elseif (!empty($event->main_image))
		<div class="item--media item--media__image">
			<a href="{{ $event->getUrl() }}" title="{{ $event->title }}">
				{{ $event->getImage('main_image', 'resized') }}
			</a>
		</div>
	@endif

	{{ $event->content }}

	@if (Config::get('laravel-events::link.show') && !empty($event->link_url) && !empty($event->link_text))
		<p class="item--external-link">
			<a href="{{ $event->link_url }}">
				{{ $event->link_text }}
			</a>
		</p>
	@endif

</div>

@if (Config::get('laravel-events::map.show') && $event->hasMap())
	@include('laravel-events::partials.map')
@endif

@if (Config::get('laravel-events::views.view_page.show_adjacent_events') && ($newer || $older))
	@include('laravel-events::partials.adjacent')
@endif