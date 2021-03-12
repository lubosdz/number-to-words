<?php
/**
* Test class for converting float or integer numbers to stringual words.
* No dependencies (e.g. PhpUnit) just include this file and see output.
*
* Version 1.0.1
* Release date: 2021-03-12
*
* Links:
* Demo - https://synet.sk/blog/php/330-cislo-na-slovo
* Repo - https://github.com/lubosdz/number-to-words
*/

namespace lubosdz\numberToWords;

class NumberToWords_Test
{
	public static function run()
	{
		require __DIR__ . '/NumberToWords.php';
		require __DIR__ . '/NumberToWords_SK.php';
		require __DIR__ . '/NumberToWords_CZ.php';
		require __DIR__ . '/NumberToWords_EN.php';

		echo "Slovak:<br>";

		echo '[factory] 123.45 = '.NumberToWords::convert(123.45, 'sk').'<br>';
		echo '[php] 123.45 = '.NumberToWords_SK::convert(123.45).'<br>';
		// jednostodvadsaťtri celé štyridsaťpäť
		echo "[intl] 123.45 = ".NumberToWords_SK::convertIntl(123.45).'<br>';
		// jedna­sto dvasať­tri čiarka štyri päť (áno, ICU vracia "dvasať", bug)

		echo "[php] 987654321.123 = ".NumberToWords_SK::convert(987654321.123).'<br>';
		// deväťstoosemdesiatsedem miliónov šesťstopäťdesiatštyritisíc tristodvadsaťjeden celé jednostodvadsaťtri

		echo "[intl] 987654321.123 = ".NumberToWords_SK::convertIntl(987654321.123).'<br>';
		// deväť­sto osemdesiat­sedem miliónov šesť­sto päťdesiat­štyri tisíc tri­sto dvasať­jeden čiarka jeden dva tri

		echo "<br>Česky:<br>";

		echo '[factory] 123.45 = '.NumberToWords::convert(123.45, 'cz').'<br>'; // cz or cs
		echo '[php] 123.45 = '.NumberToWords_CZ::convert(123.45).'<br>';
		// sto dvacet tři čárka čtyřicet pět

		echo "[intl] 123.45 = ".NumberToWords_CZ::convertIntl(123.45).'<br>';
		// sto dvacet tři čárka čtyři pět

		echo "[php] 987654321.123 = ".NumberToWords_CZ::convert(987654321.123).'<br>';
		// devět set osmdesát sedm miliónů šest set padesát čtyři tisíc tři sta dvacet jeden čárka sto dvacet tři

		echo "[intl] 987654321.123 = ".NumberToWords_CZ::convertIntl(987654321.123).'<br>';
		// devět set osmdesát sedm miliónů šest set padesát čtyři tisíc tři sta dvacet jeden čárka jeden dva tři

		echo "<br>English:<br>";

		echo '[factory] 123.45 = '.NumberToWords::convert(123.45).'<br>'; // lang code not needed since english is default output, INTL is least buggy
		echo '[php] 123.45 = '.NumberToWords_EN::convert(123.45).'<br>';
		// one hundred twenty-three point fourty-five

		echo "[intl] 123.45 = ".NumberToWords_EN::convertIntl(123.45).'<br>';
		// one hundred twenty-three point four five

		echo "[php] 987654321.123 = ".NumberToWords_EN::convert(987654321.123).'<br>';
		// nine hundred eighty-seven million, six hundred fifty-four thousand, three hundred twenty-one point one hundred twenty-three

		echo "[intl] 987654321.123 = ".NumberToWords_EN::convertIntl(987654321.123).'<br>';
		// nine hundred eighty-seven million six hundred fifty-four thousand three hundred twenty-one point one two three
	}
}

// lanch test - no dependencies e.g. phpunit
NumberToWords_Test::run();
