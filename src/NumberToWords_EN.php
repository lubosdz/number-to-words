<?php
/**
* Class for converting arbitrary float number to words into English language.
* English ICU/INTL implementation is almost identical with PHP output - almost no bug noted.
*
* Links:
* Demo - https://synet.sk/blog/php/330-cislo-na-slovo
* Repo - https://github.com/lubosdz/number-to-words
*
* Original credits (site not available anymore):
* http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php
*/

namespace lubosdz\numberToWords;

class NumberToWords_EN
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
	* @var string The separator word for the decimal part
	*/
	public static $txtDecimal = " point ";

	/**
	* @var null|int Set the number of enforced decimals, must be > 0
	*/
	public static $numberOfdecimals = null;

	/**
	* Return converted number as a string
	* @param float $number
	* @param int $units
	* @param int $level
	*/
	public static function convert($number, $units = null, $level = -1)
	{
		++$level;
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ' ';
		$negative    = 'negative ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);

		// fix common typo [,] instead of dot
		$number = str_replace(',', '.', $number);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			throw new \Exception('Invalid number range - value must be between ' . PHP_INT_MAX . ' and ' . PHP_INT_MAX.'.');
		}

		if ($number < 0) {
			return $negative . self::convert(abs($number));
		}

		$string = $fraction = '';

		if (strpos($number, '.') !== false) {
			if ( (int) self::$numberOfdecimals > 0 ) {
				$number = number_format($number, (int) self::$numberOfdecimals, '.', '');
			}
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$dict = $dictionary[$number];
				$string = $dict;
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = ' '.$dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = floor($number / 100);
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] .' '. $dictionary[100];
				if ($remainder) {
					$string .= self::convert($remainder, null, $level);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number - ($baseUnit * $numBaseUnits);
				$append = $dictionary[$baseUnit];
				$string = self::convert($numBaseUnits, $baseUnit, $level) . ' ' . $append;
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= self::convert($remainder, null, $level);
				}
				break;
		}

		if ('' !== trim($fraction) && is_numeric($fraction)) {
			if(self::$decimalsAsFraction){
				$fraction = trim($fraction); // (!) keep zeroes on left and right
				$base = pow(10, strlen($fraction));
				$string .= " ".sprintf(self::$templateFraction, intval($fraction), $base); // ie. 99/100
			}else{
				$string .= self::$txtDecimal;
				if('0' !== substr($fraction, 0, 1) && intval($fraction) < 1000){
					// up to 3 decimals and not zeroes on left - full convert
					$string .= trim(self::convert($fraction));
				}else{
					// 3+ decimals or zeroes on left - spell out single digits
					$words = [];
					foreach (str_split((string) $fraction) as $number) {
						$words[] = $dictionary[$number];
					}
					$string .= implode(' ', $words);
				}
			}
		}

		return $string;
	}

	/**
	* Convert number ot words by using intl / ICU formatting
	* @param float|integer $num
	* @param string $langCode EN|DE|SK|CS ... case insensitive
	*/
	public static function convertIntl($num, $langCode = 'en')
	{
		if(!extension_loaded('intl')){
			throw new \Exception("Cannot convert - missing INTL extension.");
		}
		$formatter = new \NumberFormatter($langCode, \NumberFormatter::SPELLOUT);
		return $formatter->format($num);
	}
}
