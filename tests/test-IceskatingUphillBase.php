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

	/**
	 * @dataProvider not_string_or_int
	 */
	public function test_format_date_since_returns_null_if_date_not_string_or_int($variable){

		$base = new ExtendBase;

		$this->assertNull($base->expose_format_date_since($variable));

	}

	public function not_string_or_int(){

		return array(
			array(1.5765),
			array(array(1, 2, 3)),
			array(new stdClass)
		);

	}

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

	public function test_shorten_string_by_words_returns_string_same_string_passed_when_under_word_count(){

		$string = 'This is a string';

		$base = new ExtendBase;

		$this->assertSame($string, $base->expose_shorten_string_by_words($string, 20));

	}

	public function test_shorten_string_by_words_returns_string_of_correct_number_of_words(){

		$count = 5;

		$string = 'This is a string that contains more than five words';

		$base = new ExtendBase;

		$shortened_string = $base->expose_shorten_string_by_words($string, $count);

		$exploded = explode(' ', $shortened_string);

		$this->assertCount($count, $exploded);

	}

	public function test_shorten_string_by_words_includes_suffix(){

		$suffix = 'hello there';

		$string = 'This is a string that countains more than five words';

		$base = new ExtendBase;

		$shortened_string = $base->expose_shorten_string_by_words($string, 5, $suffix);

		$this->assertContains($suffix, $shortened_string);

	}

	public function test_shorten_string_by_words_does_not_include_suffix_when_string_is_shorter_than_maximum_length(){

		$suffix = 'Hello there';

		$string = 'This is a short string';

		$base = new ExtendBase;

		$shortened_string = $base->expose_shorten_string_by_words($string, 20, $suffix);

		$this->assertEquals(false, strpos($shortened_string, $suffix));

	}

	/**
	 * @dataProvider non_strings
	 */
	public function test_shorten_string_by_words_returns_null_when_passed_nonstring($variable){

		$base = new ExtendBase;

		$this->assertNull($base->expose_shorten_string_by_words($variable));

	}

	public function non_strings(){

		return array(
			array(10),
			array(array(1, 2, 3)),
			array(new stdClass),
			array(true)
		);

	}

}