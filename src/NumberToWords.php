<?php
/**
* Simple factory class for converting float number to words.
* Implemented due to many bugs in INTL PHP extension (at least for Slovak lang).
*
* Version 1.0.1
* Release date: 2021-03-12
*
* Links:
* Demo - https://synet.sk/blog/php/330-cislo-na-slovo
* Repo - https://github.com/lubosdz/number-to-words
*/

namespace lubosdz\numberToWords;

class NumberToWords
{
	/**
	* Return supplied number as words
	*
	* @param float|integer $num e.g. 123456.78
	* @param string $lang 2-character language code, e.g. "en" or "sk", acc. to ISO-639-1
	* @param bool $forceIntl If TRUE prefer conversion via PHP INTL extension, which might be faster, but also buggy
	* @return string Number converted to words
	*/
	public static function convert($num, $lang = 'en', $forceIntl = false)
	{
		$lang = substr(trim(strtolower($lang)), 0, 2);

		switch($lang){
			case 'sk':
				return $forceIntl ? NumberToWords_SK::convertIntl($num) : NumberToWords_SK::convert($num);
				break;
			case 'cs':
			case 'cz':
				return $forceIntl ? NumberToWords_CZ::convertIntl($num) : NumberToWords_CZ::convert($num);
				break;
			case 'en':
			default:
				return $forceIntl ? NumberToWords_EN::convertIntl($num) : NumberToWords_EN::convert($num);
				break;
		}
	}
}
