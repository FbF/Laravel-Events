<?php namespace Fbf\LaravelEvents;

class Event extends \Eloquent {

	/**
	 * Status values for the database
	 */
	const DRAFT = 'DRAFT';
	const APPROVED = 'APPROVED';

	/**
	 * Name of the table to use for this model
	 * @var string
	 */
	protected $table = 'fbf_events';

	/**
	 * Used for Cviebrock/EloquentSluggable
	 * @var array
	 */
	public static $sluggable = array(
		'build_from' => 'title',
		'save_to' => 'slug',
		'separator' => '-',
		'unique' => true,
		'include_trashed' => true,
	);

	public function scopeLive($query)
	{
		return $query->where('status', '=', self::APPROVED)
			->where('published_date', '<=', \Carbon\Carbon::now());
	}

	public static function archives()
	{
		$archives = self::live()
			->select(\DB::raw('
				YEAR(`starts`) AS `year`,
				DATE_FORMAT(`starts`, "%m") AS `month`,
				MONTHNAME(`starts`) AS `monthname`,
				COUNT(*) AS `count`
			'))
			->groupBy(\DB::raw('DATE_FORMAT(`starts`, "%Y%m")'))
			->orderBy('starts', 'desc')
			->get();
		$results = array();
		foreach ($archives as $archive)
		{
			$results[$archive->year][$archive->month] = array(
				'monthname' => $archive->monthname,
				'count' => $archive->count,
			);
		}
		return $results;
	}

	/**
	 * Returns the URL of the event
	 * @return string
	 */
	public function getUrl()
	{
		return \URL::action('Fbf\LaravelEvents\EventsController@view', array('slug' => $this->slug));
	}

	/**
	 * Returns the HTML img tag for the requested image type and size for this event
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImage($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		$html = '<img src="' . $this->getImageSrc($type, $size) . '"';
		$html .= ' alt="' . $this->{$type.'_alt'} . '"';
		$html .= ' width="' . $this->getImageWidth($type, $size) . '"';
		$html .= ' height="' . $this->getImageWidth($type, $size) . '" />';
		return $html;
	}

	/**
	 * Returns the value for use in the src attribute of an img tag for the given image type and size
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImageSrc($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		return self::getImageConfig($type, $size, 'dir') . $this->$type;
	}

	/**
	 * Returns the value for use in the width attribute of an img tag for the given image type and size
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImageWidth($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		$method = self::getImageConfig($type, $size, 'method');

		// Width varies for images that are 'portrait', 'auto', 'fit', 'crop'
		if (in_array($method, array('portrait', 'auto', 'fit', 'crop')))
		{
			list($width) = $this->getImageDimensions($type, $size);
			return $width;
		}
		return self::getImageConfig($type, $size, 'width');
	}

	/**
	 * Returns the value for use in the height attribute of an img tag for the given image type and size
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImageHeight($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		$method = self::getImageConfig($type, $size, 'method');

		// Height varies for images that are 'landscape', 'auto', 'fit', 'crop'
		if (in_array($method, array('landscape', 'auto', 'fit', 'crop')))
		{
			list($width, $height) = $this->getImageDimensions($type, $size);
			return $height;
		}
		return self::getImageConfig($type, $size, 'height');
	}

	/**
	 * Returns an array of the width and height of the current instance's image $type and $size
	 *
	 * @param $type
	 * @param $size
	 * @return array
	 */
	protected function getImageDimensions($type, $size)
	{
		$pathToImage = public_path(self::getImageConfig($type, $size, 'dir') . $this->$type);
		if (is_file($pathToImage) && file_exists($pathToImage))
		{
			list($width, $height) = getimagesize($pathToImage);
		}
		else
		{
			$width = $height = false;
		}
		return array($width, $height);
	}

	/**
	 * Returns the config setting for an image
	 *
	 * @param $imageType
	 * @param $size
	 * @param $property
	 * @internal param $type
	 * @return mixed
	 */
	public static function getImageConfig($imageType, $size, $property)
	{
		$config = 'laravel-events::images.' . $imageType . '.';
		if ($size == 'original')
		{
			$config .= 'original.';
		}
		elseif (!is_null($size))
		{
			$config .= 'sizes.' . $size . '.';
		}
		$config .= $property;
		return \Config::get($config);
	}

	public function getYouTubeThumbnailImage()
	{
		return str_replace('%YOU_TUBE_VIDEO_ID%', $this->you_tube_video_id, \Config::get('laravel-events::you_tube.thumbnail_code'));
	}

	public function getYouTubeEmbedCode()
	{
		return str_replace('%YOU_TUBE_VIDEO_ID%', $this->you_tube_video_id, \Config::get('laravel-events::you_tube.embed_code'));
	}

	public function getMapZoom()
	{
		if (\Config::get('laravel-events::map.variable_map_zoom'))
		{
			return $this->map_zoom;
		}
		return \Config::get('laravel-events::map.default_map_zoom');
	}

	public function getMapLatitude()
	{
		if (\Config::get('laravel-events::map.map_centre_different_to_marker'))
		{
			return $this->map_latitude;
		}
		return $this->getMarkerLatitude();
	}

	public function getMapLongitude()
	{
		if (\Config::get('laravel-events::map.map_centre_different_to_marker'))
		{
			return $this->map_longitude;
		}
		return $this->getMarkerLongitude();
	}

	public function getMarkerLatitude()
	{
		return $this->marker_latitude;
	}

	public function getMarkerLongitude()
	{
		return $this->marker_longitude;
	}

	public function hasMap()
	{
		return $this->marker_latitude != 0 && $this->marker_longitude != 0;
	}
}