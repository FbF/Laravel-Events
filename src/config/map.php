<?php

return array(

	/**
	 * Determines whether the map fields are shown in the administrator screens and whether seed events have maps
	 */
	'show' => true,

	/**
	 * Determines whether the map latitude and longitude fields are shown in the administrator screens. If false and
	 * map.show = true, then the map latitude and longitude fields aren't shown and the map is centred on the marker
	 * latitude and longitude.
	 */
	'map_centre_different_to_marker' => true,

	/**
	 * Determines whether the map zoom field is shown in the administrator screens. If false and map.show = true, then
	 * the map zoom field isn't shown and the map zoom is the default_map_zoom config setting (see below).
	 */
	'variable_map_zoom' => true,

	/**
	 * Default map zoom level, used if the 'variable_map_zoom' config setting is false.
	 */
	'default_map_zoom' => 10,

	/**
	 * Width of the map
	 */
	'width' => 400,

	/**
	 * Height of the map
	 */
	'height' => 300,

);