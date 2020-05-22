<?php
/**
* Class for converting arbitrary float number to words into Czech language
* This implementation is very close to ICU [CS|CZ] output.
*
* Version 1.0.0
* Release date: 2020-05-22
*
* Original credits (site not available anymore):
* http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php
*/

namespace lubosdz\numberToWords;

class NumberToWords_CZ
{
	/**
	* Release version
	*/
	const VERSION = '1.0.0';

	/**
	* Return converted number as a string
	* @param float $number
	* @param int $units
	* @param int $level
	*/
	public static function convert($number, $units = null, $level = -1)
	{
		++$level;
		$hyphen      = ' ';
		$conjunction = ' ';
		$separator   = ' ';
		$negative    = 'mínus ';
		$decimal     = ' čárka ';
		$dictionary  = array(
			0                   => 'nula',
			1                   => 'jeden',
			2                   => 'dva',
			3                   => 'tři',
			4                   => 'čtyři',
			5                   => 'pět',
			6                   => 'šest',
			7                   => 'sedm',
			8                   => 'osm',
			9                   => 'devět',
			10                  => 'deset',
			11                  => 'jedenáct',
			12                  => 'dvanáct',
			13                  => 'třináct',
			14                  => 'čtrnáct',
			15                  => 'patnáct',
			16                  => 'šestnáct',
			17                  => 'sedmnáct',
			18                  => 'osmnáct',
			19                  => 'devatenáct',
			20                  => 'dvacet',
			30                  => 'třicet',
			40                  => 'čtyřicet',
			50                  => 'padesát',
			60                  => 'šedesát',
			70                  => 'sedmdesát',
			80                  => 'osmdesát',
			90                  => 'devadesát',
			100                 => 'sto',
			1000                => 'tisíc',
			1000000             => 'milión',   // e6
			1000000000          => 'miliarda|miliardy|miliárd', // e9
			1000000000000       => 'bilión',   // e12
			1000000000000000    => 'biliarda|biliardy|biliárd', // e15
			1000000000000000000 => 'trilión',  // e18
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

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$dict = $dictionary[$number];
				if($units){
					if($number == 1){
						$dict = 'jedna';
					}elseif($number == 2 && in_array($units, [1e3, 1e9, 1e15])){
						$dict = 'dvě';
					}
				}
				$string = $dict;
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = floor($number / 100);
				$remainder = $number % 100;
				if($hundreds == 1){
					$dict = '';
					$string = $dictionary[100];
				}elseif($hundreds == 2){
					$dict = 'dvě';
					$string = 'stě';
				}else{
					$dict = $dictionary[$hundreds];
					if($hundreds >= 3 && $hundreds <= 4){
						$string = 'sta';
					}else{
						$string = 'set';
					}
				}
				$string = trim($dict .' '. $string);
				if ($remainder) {
					$string .= ' '.self::convert($remainder, null, $level);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number - ($baseUnit * $numBaseUnits);
				$append = $dictionary[$baseUnit];
				if(in_array($baseUnit, [1e9, 1e15])){
					$append = explode('|', $append);
					if($numBaseUnits >= 2 && $numBaseUnits <= 4){
						$append = $append[1]; // 2, 3, 4 miliardy, biliardy
					}elseif($numBaseUnits >= 5 ){
						$append = $append[2]; // 5,6 ... 99 miliárd, biliárd
					}else{
						$append = $append[0]; // 1 miliarda, 1 biliarda
					}
				}elseif($baseUnit == 1000){
					if($numBaseUnits >= 2 && $numBaseUnits <= 4){
						$append = 'tisíce';
					}
				}else{
					if($numBaseUnits >= 2 && $numBaseUnits <= 4){
						$append .= 'y'; // 2, 3, 4 miliony, biliony, triliony, ..
					}elseif($numBaseUnits >= 5 ){
						$append .= 'ů'; // 5,6 ... 99 miliónů
					}
				}
				$string = self::convert($numBaseUnits, $baseUnit, $level) .' '. $append;
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= self::convert($remainder, null, $level);
				}
				break;
		}

		if ('' !== trim($fraction) && is_numeric($fraction)) {
			$string .= $decimal;
			$fraction = intval($fraction);
			if($fraction < 1000){
				// up to 3 decimals - full convert
				$string .= self::convert($fraction);
			}else{
				// 3+ decimals - spell out single digits
				$words = [];
				foreach (str_split((string) $fraction) as $number) {
					$words[] = $dictionary[$number];
				}
				$string .= implode(' ', $words);
			}
		}

		return $string;
	}

	/**
	* Convert number ot words by using intl / ICU formatting
	* @param float|integer $num
	* @param string $langCode EN|DE|SK|CS ... case insensitive
	*/
	public static function convertIntl($num, $langCode = 'cs')
	{
		if(!extension_loaded('intl')){
			throw new \Exception("Cannot convert - missing INTL extension.");
		}
		$formatter = new \NumberFormatter($langCode, \NumberFormatter::SPELLOUT);
		return $formatter->format($num);
	}
}
