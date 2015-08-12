<?php

abstract class IceskatingUphillBase {

	/**
	 * Shorten a string by words. If the original string is already that short,
	 * just return it.
	 * @param  string  $string String that needs shortening
	 * @param  integer $words  The number of words in the string to return
	 * @param  string  $suffix The string to append to the shortened string
	 * @return string          Shortened string, followed by suffix.
	 */
	protected static function shorten_string_by_words($string, $words = 20, $suffix = '&hellip;'){

		$words_array = explode(' ', $string);

		if (count($words_array) > $words){

			$words_array = array_slice($words_array, 0, $words);

			return implode(' ', $words_array) . $suffix;

		}
		else {
			return $string;
		}

	}

	protected static function format_date_string($date_string, $format='F j, Y'){

		$time = strtotime($date_string);

		if ($time !== false){
			return date($format, $time);
		}

	}

}