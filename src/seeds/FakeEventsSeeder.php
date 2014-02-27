<?php namespace Fbf\LaravelEvents;

class FakeEventsSeeder extends \Seeder {

	protected $event;

	public function run()
	{
		$this->truncate();

		$this->faker = \Faker\Factory::create();

		$numberToCreate = \Config::get('laravel-events::seed.number');

		for ($i = 0; $i < $numberToCreate; $i++)
		{
			$this->create();
		}

		echo 'Database seeded' . PHP_EOL;
	}

	protected function truncate()
	{
		$replace = \Config::get('laravel-events::seed.replace');
		if ($replace)
		{
			\DB::table('fbf_events')->delete();
		}
	}

	protected function create()
	{
		$this->event = new Event();
		$this->setTitle();
		$this->setDates();
		$this->setMedia();
		$this->setSummary();
		$this->setContent();
		$this->setLink();
		$this->setMap();
		$this->setIsSticky();
		$this->setInRss();
		$this->setPageTitle();
		$this->setMetaDescription();
		$this->setMetaKeywords();
		$this->setStatus();
		$this->setPublishedDate();
		$this->event->save();
	}

	protected function setTitle()
	{
		$title = $this->faker->sentence(rand(1, 10));
		$this->event->title = $title;
	}

	protected function setDates()
	{
		$this->setStarts();
		$this->setEnds();
		$this->setTextDate();
	}

	protected function setTextDate()
	{
		$this->event->text_date = $this->faker->randomElement(\Config::get('laravel-events::seed.dates.text_dates'));
	}

	protected function setStarts()
	{
		$starts = $this->faker->dateTimeBetween('-2 years', '+1 year')->format('Y-m-d H:i:s');
		$starts = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $starts);
		$makeStartOfDay = rand(0, 1);
		if ($makeStartOfDay)
		{
			$starts = $starts->startOfDay();
		}
		$this->event->starts = $starts->format('Y-m-d H:i:s');
	}

	protected function isMultiDay()
	{
		$multiDayFreq = \Config::get('laravel-events::seed.dates.multi_day_freq');
		$isMultiDay = $multiDayFreq > 0 && rand(1, $multiDayFreq) == $multiDayFreq;
		return $isMultiDay;
	}

	protected function setEnds()
	{
		$starts = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->event->starts);
		if ($this->isMultiDay())
		{
			$daysLater = rand(1, 3);
			$ends = $starts->addDays($daysLater)->endOfDay();
		}
		else
		{
			$ends = $starts->endOfDay();
		}
		$ends = $this->faker->dateTimeBetween($this->event->starts, $ends->format('Y-m-d H:i:s'));
		$makeEndOfDay = rand(0, 1);
		if ($makeEndOfDay)
		{
			$ends = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ends->format('Y-m-d H:i:s'));
			$ends = $ends->endOfDay();
		}
		$this->event->ends = $ends->format('Y-m-d H:i:s');
	}

	protected function setMedia()
	{
		if ($this->hasYouTubeVideos())
		{
			$this->setYouTubeVideoId();
		}
		elseif ($this->hasMainImage())
		{
			$this->doMainImage();
		}
	}

	protected function hasYouTubeVideos()
	{
		$youTubeVideoFreq = \Config::get('laravel-events::seed.you_tube.freq');
		$hasYouTubeVideos = $youTubeVideoFreq > 0 && rand(1, $youTubeVideoFreq) == $youTubeVideoFreq;
		return $hasYouTubeVideos;
	}

	protected function setYouTubeVideoId()
	{
		$this->event->you_tube_video_id = $this->faker->randomElement(\Config::get('laravel-events::seed.you_tube.video_ids'));
	}

	protected function hasMainImage()
	{
		$mainImageFreq = \Config::get('laravel-events::seed.images.main_image.freq');
		$hasMainImage = $mainImageFreq > 0 && rand(1, $mainImageFreq) == $mainImageFreq;
		return $hasMainImage;
	}

	protected function doMainImage()
	{
		$imageOptions = \Config::get('laravel-events::images.main_image');
		if (!$imageOptions['show'])
		{
			return false;
		}
		$seedOptions = \Config::get('laravel-events::seed.images.main_image');
		$original = $this->faker->image(
			public_path($imageOptions['original']['dir']),
			$seedOptions['original_width'],
			$seedOptions['original_height'],
			$seedOptions['category']
		);
		$filename = basename($original);
		foreach ($imageOptions['sizes'] as $sizeOptions)
		{
			$image = $this->faker->image(
				public_path($sizeOptions['dir']),
				$sizeOptions['width'],
				$sizeOptions['height']
			);
			rename($image, public_path($sizeOptions['dir']) . $filename);
		}
		$this->event->main_image = $filename;
		$this->event->main_image_alt = $this->event->title;
	}

	protected function setSummary()
	{
		$this->event->summary = '<p>'.implode('</p><p>', $this->faker->paragraphs(rand(1, 2))).'</p>';
	}

	protected function setContent()
	{
		$this->event->content = '<p>'.implode('</p><p>', $this->faker->paragraphs(rand(4, 10))).'</p>';
	}

	protected function setLink()
	{
		if ($this->hasLink())
		{
			$this->setLinkText();
			$this->setLinkUrl();
		}
	}

	protected function hasLink()
	{
		$showLink =  \Config::get('laravel-events::link.show');
		if (!$showLink)
		{
			return false;
		}
		$linkFreq = \Config::get('laravel-events::seed.link.freq');
		$hasLink = $linkFreq > 0 && rand(1, $linkFreq) == $linkFreq;
		return $hasLink;
	}

	protected function setLinkText()
	{
		$linkTexts = \Config::get('laravel-events::seed.link.texts');
		$this->event->link_text = $this->faker->randomElement($linkTexts);
	}

	protected function setLinkUrl()
	{
		$linkUrls = \Config::get('laravel-events::seed.link.urls');
		$this->event->link_url = $this->faker->randomElement($linkUrls);
	}

	protected function setMap()
	{
		if (!$this->hasMap())
		{
			return false;
		}
		$this->setMarkerLatLong();
		$this->setMapZoom();
		$this->setMapLatLong();
		$this->setMarkerTitle();
	}

	protected function setMarkerLatLong()
	{
		$markerLatMin = \Config::get('laravel-events::seed.map.marker.latitude.min');
		$markerLatMax = \Config::get('laravel-events::seed.map.marker.latitude.max');
		$markerLonMin = \Config::get('laravel-events::seed.map.marker.longitude.min');
		$markerLonMax = \Config::get('laravel-events::seed.map.marker.longitude.max');
		$this->event->marker_latitude = rand($markerLatMin*1000000,$markerLatMax*1000000) / 1000000;
		$this->event->marker_longitude = rand($markerLonMin*1000000,$markerLonMax*1000000) / 1000000;
	}

	protected function setMapZoom()
	{
		$variableMapZoom = \Config::get('laravel-events::map.variable_map_zoom');
		if (!$variableMapZoom)
		{
			$defaultMapZoom = \Config::get('laravel-events::map.default_map_zoom');
			$this->event->map_zoom = $defaultMapZoom;
		}
		else
		{
			$this->event->map_zoom = rand(6,14);
		}
	}

	protected function setMapLatLong()
	{
		$mapCentreDifferentToMarker = \Config::get('laravel-events::map.map_centre_different_to_marker');
		if (!$mapCentreDifferentToMarker)
		{
			return false;
		}
		$this->event->map_latitude = $this->event->marker_latitude + $this->getMapCentreOffsetByZoom();
		$this->event->map_longitude = $this->event->marker_longitude + $this->getMapCentreOffsetByZoom();
	}

	protected function getMapCentreOffsetByZoom()
	{
		$maxOffsets = array(
			0 => 90,
			1 => 45,
			2 => 22.5,
			3 => 10,
			4 => 5,
			5 => 3,
			6 => 2,
			7 => 1,
			8 => 0.5,
			9 => 0.25,
			10 => 0.125,
			11 => 0.05,
			12 => 0.025,
			13 => 0.0125,
			14 => 0.005,
			15 => 0.0025,
			16 => 0.00125,
			17 => 0.0005,
			18 => 0.00025,
			19 => 0.000125,
		);
		$maxOffset = $maxOffsets[$this->event->map_zoom];
		return rand($maxOffset*-1000000, $maxOffset*1000000) / 1000000;
	}

	protected function hasMap()
	{
		$showMap = \Config::get('laravel-events::map.show');
		if (!$showMap)
		{
			return false;
		}
		$mapFreq = \Config::get('laravel-events::seed.map.freq');
		$hasMap = $mapFreq > 0 && rand(1, $mapFreq) == $mapFreq;
		return $hasMap;
	}

	protected function setMarkerTitle()
	{
		$this->event->marker_title = $this->faker->words(rand(1, 6), true);
	}

	protected function setIsSticky()
	{
		$this->event->is_sticky = (bool) rand(0, 1);
	}

	protected function setInRss()
	{
		$this->event->in_rss = (bool) rand(0, 1);
	}

	protected function setPageTitle()
	{
		$this->event->page_title = $this->event->title;
	}

	protected function setMetaDescription()
	{
		$this->event->meta_description = $this->faker->paragraph(rand(1, 2));
	}

	protected function setMetaKeywords()
	{
		$this->event->meta_keywords = $this->faker->words(10, true);
	}

	protected function setStatus()
	{
		$statuses = array(
			Event::DRAFT,
			Event::APPROVED
		);
		$this->event->status = $this->faker->randomElement($statuses);
	}

	protected function setPublishedDate()
	{
		$this->event->published_date = $this->faker->dateTimeBetween('-2 years', '+1 month')->format('Y-m-d H:i:s');
	}

}