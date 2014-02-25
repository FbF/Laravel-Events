<div class="share-buttons">
	<p>{{ trans('laravel-events::messages.share.label') }}</p>
	<p class="share-button share-button__twitter">
		<a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title . ' ' . $event->getUrl()) }}" target="_blank">
			{{ trans('laravel-events::messages.share.twitter') }}
		</a>
	</p>
	<p class="share-button share-button__facebook">
		<a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($event->getUrl()) }}" target="_blank">
			{{ trans('laravel-events::messages.share.facebook') }}
		</a>
	</p>
</div>