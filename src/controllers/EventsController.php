<?php namespace Fbf\LaravelEvents;

class EventsController extends \BaseController {

	protected $event;

	public function __construct(Event $event)
	{
		$this->event = $event;
	}

	public function index($year = null, $month = null)
	{
		// Initiate query, don't paginate on it yet in case we want the year/month condition added
		$query = $this->event->live();

		// If year and month passed in the URL, add this condition
		if ($year && $month)
		{
			$query = $query->where(\DB::raw('DATE_FORMAT(starts, "%Y%m")'), '=', $year.$month);
		}

		// Get the current page's events
		$viewData['events'] = $query
			->orderBy('is_sticky', 'desc')
			->orderBy('starts', 'desc')
			->paginate(\Config::get('laravel-events::views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (\Config::get('laravel-events::views.index_page.show_archives'))
		{
			$viewData['archives'] = $this->event->archives();
			if ($year && $month)
			{
				$viewData['selectedYear'] = $year;
				$viewData['selectedMonth'] = $month;
			}
		}

		return \View::make(\Config::get('laravel-events::views.index_page.view'), $viewData);
	}

	public function view($slug) {

		// Get the selected event
		$viewData['event'] = $event = $this->event->live()
			->where('slug', '=', $slug)
			->firstOrFail();

		// Get the next newest and next oldest event if the config says to show these links on the view page
		if (\Config::get('laravel-events::views.view_page.view'))
		{
			$viewData['newer'] = $this->event->live()
				->where('starts', '>=', $event->starts)
				->where('id', '<>', $event->id)
				->orderBy('starts', 'asc')
				->orderBy('id', 'asc')
				->first();

			$viewData['older'] = $this->event->live()
				->where('starts', '<=', $event->starts)
				->where('id', '<>', $event->id)
				->orderBy('starts', 'desc')
				->orderBy('id', 'desc')
				->first();
		}

		if (\Config::get('laravel-events::views.view_page.show_archives'))
		{
			$viewData['archives'] = $this->event->archives();
		}

		return \View::make(\Config::get('laravel-events::views.view_page.view'), $viewData);

	}

	public function rss()
	{

		$feed = Rss::feed('2.0', 'UTF-8');
		$feed->channel(array(
			'title' => \Config::get('laravel-events::rss_feed_title'),
			'description' => \Config::get('laravel-events::rss_feed_description'),
			'link' => \URL::current(),
		));
		$events = $this->event->live()
			->where('in_rss', '=', true)
			->orderBy('starts', 'desc')
			->take(10)
			->get();
		foreach ($events as $event){
			$feed->item(array(
				'title' => $event->title,
				'description' => $event->summary,
				'link' => $event->getUrl(),
			));
		}

		return \Response::make($feed, 200, array('Content-Type', 'application/rss+xml'));

	}

}
