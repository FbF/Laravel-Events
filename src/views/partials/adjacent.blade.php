<div class="adjacent-items">

	@if ($newer)
		<p class="adjacent-item adjacent-item__next">
			<a href="{{ $newer->getUrl() }}">
				{{ trans('laravel-events::messages.adjacent.next_link_text', array('title' => $newer->title)) }}
			</a>
		</p>
	@endif

	@if ($older)
		<p class="adjacent-item adjacent-item__prev">
			<a href="{{ $older->getUrl() }}">
				{{ trans('laravel-events::messages.adjacent.prev_link_text', array('title' => $older->title)) }}
			</a>
		</p>
	@endif

</div>