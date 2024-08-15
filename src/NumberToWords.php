<?php
/**
* Simple factory class for converting numbers to words.
* Implemented due to many bugs in INTL PHP extension (at least for Slovak lang).
*
* Links:
* Demo - https://synet.sk/blog/php/330-cislo-na-slovo
* Repo - https://github.com/lubosdz/number-to-words
*/

namespace lubosdz\numberToWords;

class NumberToWords
{
	/**
	* @var bool When true return decimal part as a fraction ie. 1/10 or 99/100
	*/
	public static $decimalsAsFraction = false;

	/**
	* @var string Output template when returning decimal part as a fraction (formatted with sprintf)
	* First placeholder %s is for the fraction, second %s for the base ie. (99/100)
	*/
	public static $templateFraction = "(%s/%s)";

	/**
	* @var null|int Set the number of enforced decimals, must be > 0
	*/
	public static $numberOfdecimals = null;

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
				NumberToWords_SK::$decimalsAsFraction = self::$decimalsAsFraction;
				NumberToWords_SK::$templateFraction = self::$templateFraction;
				NumberToWords_SK::$numberOfdecimals = self::$numberOfdecimals;
				return $forceIntl ? NumberToWords_SK::convertIntl($num) : NumberToWords_SK::convert($num);
			case 'cs':
			case 'cz':
				NumberToWords_CZ::$decimalsAsFraction = self::$decimalsAsFraction;
				NumberToWords_CZ::$templateFraction = self::$templateFraction;
				NumberToWords_CZ::$numberOfdecimals = self::$numberOfdecimals;
				return $forceIntl ? NumberToWords_CZ::convertIntl($num) : NumberToWords_CZ::convert($num);
			case 'en':
				NumberToWords_EN::$decimalsAsFraction = self::$decimalsAsFraction;
				NumberToWords_EN::$templateFraction = self::$templateFraction;
				NumberToWords_EN::$numberOfdecimals = self::$numberOfdecimals;
				return $forceIntl ? NumberToWords_EN::convertIntl($num) : NumberToWords_EN::convert($num);
			default:
				// not directly implemented language
				// we can only convert via INTL, supplied lang code must comply with ISO-639-1 lang codes
				return NumberToWords_EN::convertIntl($num, $lang);
		}
	}
}
