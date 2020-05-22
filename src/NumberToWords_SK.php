<?php
/**
* Class for converting arbitrary float number to words into Slovak language
* This implementation replaces ICU [SK] implementation, which is buggy and does not implemented some specific Slovak declination rules.
*
* Version 1.0.0
* Release date: 2020-05-22
*
* Demo:
* https://ipdf.sk/slovenska-posta/postovy-poukaz-peniaze-na-adresu
* https://synet.sk/blog/php/330-cislo-na-slovo
*
* Original credits (site not available anymore):
* http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php
*/

namespace lubosdz\numberToWords;

class NumberToWords_SK
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
		$hyphen      = ''; // in english "-", v slovencine ziadny
		$conjunction = ' '; // in english ' and ' v slovencine nepouzivame
		$separator   = ' ';
		$negative    = 'mínus ';
		$decimal     = ' celé ';
		$dictionary  = array(
			0                   => 'nula',
			1                   => 'jeden', // jeden milion, jedna miliarda
			2                   => 'dva', // dvojtvar dve, dve - napr. 22000 - dvadsatDVA tisic, 200 = DVE sto
			3                   => 'tri',
			4                   => 'štyri',
			5                   => 'päť',
			6                   => 'šesť',
			7                   => 'sedem',
			8                   => 'osem',
			9                   => 'deväť',
			10                  => 'desať',
			11                  => 'jedenásť',
			12                  => 'dvanásť',
			13                  => 'trinásť',
			14                  => 'štrnásť',
			15                  => 'pätnásť',
			16                  => 'šestnásť',
			17                  => 'sedemnásť',
			18                  => 'osemnásť',
			19                  => 'devätnásť',
			20                  => 'dvadsať',
			30                  => 'tridsať',
			40                  => 'štyridsať',
			50                  => 'päťdesiat',
			60                  => 'šesťdesiat',
			70                  => 'sedemdesiat',
			80                  => 'osemdesiat',
			90                  => 'deväťdesiat',
			100                 => 'sto',
			1000                => 'tisíc',
			1000000             => 'milión',   // e6
			1000000000          => 'miliarda|miliardy|miliárd', // e9
			1000000000000       => 'bilión',   // e12
			1000000000000000    => 'biliarda|biliardy|biliárd', // e15
			1000000000000000000 => 'trilión',  // e18
			// https://sk.wikipedia.org/wiki/Veľké_čísla
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
						// ludia chcu "jednosto"
						$dict = ''; // nie jedentisic, jedensto
						if($level <= 1){ // first loop = 0, pridame "jedno"sto na zaciatku slova
							if($units == 100){
								$dict = 'jedno'; // jednosto
							}elseif(in_array($units, [1e3, 1e6])){
								$dict = 'jeden'; // jedentisic
							}elseif(in_array($units, [1e9, 1e15])){
								$dict = 'jedna'; // jedna miliarda
							}
						}
					}elseif($number == 2 && in_array($units, [1e3, 1e9, 1e15])){
						$dict = 'dve'; // dvetisic, nie dvatisic, dve miliardy nie dva miliardy
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
					// ludia chcu "jednosto"
					$dict = ''; // nie styritisic jedenstoosemdesiat, jedenstopatnast
					if(!$level){ // jednosto na zaciatku slova
						$dict = $dictionary[$hundreds]; // nie styritisic jedenstoosemdesiat, jedenstopatnast
						if($number < 200){
							$dict = 'jedno'; // jednostodvanast, nie jedensto
						}
					}
				}elseif($hundreds == 2){
					$dict = 'dve'; // dvesto, nie dvasto
				}else{
					$dict = $dictionary[$hundreds];
				}
				$string = $dict . $dictionary[100];
				if ($remainder) {
					$string .= self::convert($remainder, null, $level);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number - ($baseUnit * $numBaseUnits);
				// SK declination
				$append = $dictionary[$baseUnit];
				if($baseUnit > 1000){
					$bigNumSep = ' ';
					// nesklonujeme tisicky, len milion a vyssie
					if(in_array($baseUnit, [1e9, 1e15])){
						$append = explode('|', $append);
						if($numBaseUnits >= 2 && $numBaseUnits <= 4){
							$append = $append[1]; // 2, 3, 4 miliardy, biliardy
						}elseif($numBaseUnits >= 5 ){
							$append = $append[2]; // 5,6 ... 99 miliárd, biliárd
						}else{
							$append = $append[0]; // 1 miliarda, 1 biliarda
						}
					}else{
						if($numBaseUnits >= 2 && $numBaseUnits <= 4){
							$append .= 'y'; // 2, 3, 4 miliony, biliony, triliony, ..
						}elseif($numBaseUnits >= 5 ){
							$append .= 'ov'; // 5,6 ... 99 milionov
						}
					}
				}else{
					$bigNumSep = '';
				}
				$string = self::convert($numBaseUnits, $baseUnit, $level) . $bigNumSep . $append;
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
	* At least up to ICU 57.1 - slovak implementation is buggy - try e.g. 12456.78 - grammar typos "dvaásť tisíc .."
	* @param float|integer $num
	* @param string $langCode EN|DE|SK|CS ... case insensitive
	*/
	public static function convertIntl($num, $langCode = 'sk')
	{
		if(!extension_loaded('intl')){
			throw new \Exception("Cannot convert - missing INTL extension.");
		}
		$formatter = new \NumberFormatter($langCode, \NumberFormatter::SPELLOUT);
		return $formatter->format($num);
	}
}
