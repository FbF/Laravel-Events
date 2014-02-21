<div class="item-list">

	@if (!$events->isEmpty())

		@foreach ($events as $event)

			<div class="item">

				<h2 class="item--title">
					<a href="{{ $event->getUrl() }}" title="{{ $event->title }}">
						{{ $event->title }}
					</a>
				</h2>

				<p class="item--date">
					{{ $event->text_date }}
				</p>

				@if (!empty($event->you_tube_video_id))
					<div class="item--thumb item--thumb__youtube">
						<a href="{{ $event->getUrl() }}" title="{{ $event->title }}">
							{{ $event->getYouTubeThumbnailImage() }}
						</a>
					</div>
				@elseif (!empty($event->main_image))
					<div class="item--thumb item--thumb__image">
						<a href="{{ $event->getUrl() }}" title="{{ $event->title }}">
							{{ $event->getImage('main_image', 'thumbnail') }}
						</a>
					</div>
				@endif

				<div class="item--summary">
					{{ $event->summary }}
				</div>

				<p class="item--more-link">
					<a href="{{ $event->getUrl() }}" title="{{ $event->title }}">
						{{ trans('laravel-events::messages.list.more_link_text') }}
					</a>
				</p>

			</div>

		@endforeach

		{{ $events->links() }}

	@else

		<p class="item-list--empty">
			{{ trans('laravel-events::messages.list.no_items') }}
		</p>

	@endif

</div>