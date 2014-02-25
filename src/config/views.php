<?php

/**
 * Configuration options for the built-in views
 */
return array(

	/**
	 * Configuration options for the index page
	 */
	'index_page' => array(

		/**
		 * The view to use for the events index page. You can change this to a view in your
		 * app, and inside your own view you can @include the various partials in the package
		 * or you can use this one provided, but there's no layout or anything.
		 */
		'view' => 'laravel-events::events.index',

		/**
		 * Determines whether to show the archives on the index page
		 *
		 * @type bool
		 */
		'show_archives' => true,

		/**
		 * The number of events to show per page on the index
		 *
		 * @type int
		 */
		'results_per_page' => 4,

	),

	/**
	 * Configuration options for the view page
	 */
	'view_page' => array(

		/**
		 * The view to use for the event detail page. You can change this to a view in your
		 * app, and inside your own view you can @include the various partials in the package
		 * or you can use this one provided, but there's no layout or anything.
		 */
		'view' => 'laravel-events::events.view',

		/**
		 * Determines whether to show the archives on the view page
		 *
		 * @type bool
		 */
		'show_archives' => true,

		/**
		 * Determines whether to show links to adjacent (i.e. previous and next) items on the view page
		 *
		 * @type bool
		 */
		'show_adjacent_items' => true,

		/**
		 * Determines whether to show the share partial on the event view page
		 *
		 * @type bool
		 */
		'show_share_partial' => true,
	),

);