<?php namespace Fbf\LaravelEvents;

class EventsController extends \BaseController {

	/**
	 * @var \Fbf\LaravelEvents\Event
	 */
	protected $event;

	/**
	 * @param \Fbf\LaravelEvents\Event $event
	 */
	public function __construct(Event $event)
	{
		$this->event = $event;
	}

	/**
	 * @return mixed
	 */
	public function index()
	{
		// Get the selected events
		$events = $this->event->live()
			->orderBy($this->event->getTable().'.is_sticky', 'desc')
			->orderBy($this->event->getTable().'.starts', 'desc')
			->orderBy($this->event->getTable().'.published_date', 'desc')
			->paginate(\Config::get('laravel-events::views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (\Config::get('laravel-events::views.index_page.show_archives'))
		{
			$archives = $this->event->archives();
		}

		return \View::make(\Config::get('laravel-events::views.index_page.view'), compact('events', 'archives'));
	}

	/**
	 * @param $selectedYear
	 * @param $selectedMonth
	 * @return mixed
	 */
	public function indexByYearMonth($selectedYear, $selectedMonth)
	{
		// Get the selected events
		$events = $this->event->live()
			->byYearMonth($selectedYear, $selectedMonth)
			->orderBy($this->event->getTable().'.is_sticky', 'desc')
			->orderBy($this->event->getTable().'.starts', 'desc')
			->orderBy($this->event->getTable().'.published_date', 'desc')
			->paginate(\Config::get('laravel-events::views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (\Config::get('laravel-events::views.index_page.show_archives'))
		{
			$archives = $this->event->archives();
		}

		return \View::make(\Config::get('laravel-events::views.index_page.view'), compact('events', 'selectedYear', 'selectedMonth', 'archives'));
	}

	/**
	 * @param $relationshipIdentifier
	 * @return mixed
	 */
	public function indexByRelationship($relationshipIdentifier)
	{
		// Get the selected events
		$events = $this->event->live()
			->byRelationship($relationshipIdentifier)
			->orderBy($this->event->getTable().'.is_sticky', 'desc')
			->orderBy($this->event->getTable().'.starts', 'desc')
			->orderBy($this->event->getTable().'.published_date', 'desc')
			->paginate(\Config::get('laravel-events::views.index_page.results_per_page'));

		// Get the archives data if the config says to show the archives on the index page
		if (\Config::get('laravel-events::views.index_page.show_archives'))
		{
			$archives = $this->event->archives();
		}

		return \View::make(\Config::get('laravel-events::views.index_page.view'), compact('events', 'archives', 'relationshipIdentifier'));
	}

	/**
	 * @param $slug
	 * @return mixed
	 */
	public function view($slug)
	{
		// Get the selected event
		$event = $this->event->live()
			->where($this->event->getTable().'.slug', '=', $slug)
			->firstOrFail();

		// Get the next newest and next oldest event if the config says to show these links on the view page
		$newer = $older = false;
		if (\Config::get('laravel-events::views.view_page.show_adjacent_items'))
		{
			$newer = $event->newer();
			$older = $event->older();
		}

		// Get the archives data if the config says to show the archives on the view page
		if (\Config::get('laravel-events::views.view_page.show_archives'))
		{
			$archives = $this->event->archives();
		}

		return \View::make(\Config::get('laravel-events::views.view_page.view'), compact('event', 'newer', 'older', 'archives'));

	}

	/**
	 * @return mixed
	 */
	public function rss()
	{
		$feed = Rss::feed('2.0', 'UTF-8');
		$feed->channel(array(
			'title' => \Config::get('laravel-events::meta.rss_feed.title'),
			'description' => \Config::get('laravel-events::meta.rss_feed.description'),
			'link' => \URL::current(),
		));
		$events = $this->event->live()
			->where($this->event->getTable().'.in_rss', '=', true)
			->orderBy($this->event->getTable().'.published_date', 'desc')
			->take(10)
			->get();
		foreach ($events as $event){
			$feed->item(array(
				'title' => $event->title,
				'description' => $event->summary,
				'link' => \URL::action('Fbf\LaravelEvents\EventsController@view', array('slug' => $event->slug)),
			));
		}
		return \Response::make($feed, 200, array('Content-Type', 'application/rss+xml'));
	}

}