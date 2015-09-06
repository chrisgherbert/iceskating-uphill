<?php

class ExtendBase extends IceskatingUphillBase {

	public function expose_format_date_since($date_string = null){

		return self::format_date_since($date_string);

	}

	public function expose_shorten_string_by_words($string = null, $words = null, $suffix = null){

		return self::shorten_string_by_words($string, $words, $suffix);

	}

}

class IceskatingUphillBaseTest extends WP_UnitTestCase {

	public function test_format_date_since_returns_null_if_date_in_future(){

		$base = new ExtendBase;

		$date = 'January 1, 2016';

		$this->assertNull($base->expose_format_date_since($date));

	}

	public function test_format_date_since_returns_string_if_date_in_past(){

		$base = new ExtendBase;

		$date = 'January 1, 2011';

		$this->assertInternalType('string', $base->expose_format_date_since($date));

	}

	public function test_shorten_string_by_words_returns_string(){

		$base = new ExtendBase;

		$string = 'This is a test string';

		$this->assertInternalType('string', $base->expose_shorten_string_by_words($string));

	}

}