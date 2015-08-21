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

	/**
	 * Get post date in words - "5 days ago"
	 * @param  string $date_string Date to format
	 * @return string Post date in words
	 */
	protected static function format_date_since($date_string){

		if ($date_string){

			$time = time() - strtotime($date_string);

			$tokens = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);

			foreach ($tokens as $unit => $text) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'') . ' ago';
			}

		}

	}

}

