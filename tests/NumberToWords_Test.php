<?php
/**
* Tests for converting numbers to words.
*
* Links:
* Demo - https://synet.sk/blog/php/330-cislo-na-slovo
* Repo - https://github.com/lubosdz/number-to-words
*/

namespace lubosdz\numberToWords;
use PHPUnit\Framework\TestCase;

class NumberToWords_Test extends TestCase
{
	// Slovak tests
	public function testSK()
	{
		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'sk');
		$res_B = NumberToWords_SK::convert($num);
		$this->assertTrue($res_A == 'jednostodvadsaťtri celé štyridsaťpäť');
		$this->assertTrue($res_A == $res_B);

		// output via PHP extension INTL may vary depending ICU version, has many bugs
		$res_A = NumberToWords_SK::convertIntl($num);
		$this->assertTrue(false !== strpos($res_A, 'sto') && false !== strpos($res_A, 'dvadsať'));

		// with fraction
		NumberToWords::$decimalsAsFraction = true;

		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'sk');
		$this->assertTrue($res_A == 'jednostodvadsaťtri (45/100)');

		$num = 123.05;
		$res_A = NumberToWords_SK::convert($num, 'sk');
		$this->assertTrue($res_A == 'jednostodvadsaťtri (5/100)');

		$num = 234.500;
		$res_A = NumberToWords::convert($num, 'sk');
		$this->assertTrue($res_A == 'dvestotridsaťštyri (5/10)');

		// note - if you supply as string, it will keep right-sided zeroes
		$num = '234.500';
		$res_A = NumberToWords::convert($num, 'sk');
		$this->assertTrue($res_A == 'dvestotridsaťštyri (500/1000)');

		NumberToWords::$decimalsAsFraction = false;

		// big number
		$num = 987654321.123;
		$res_A = NumberToWords::convert($num, 'sk');
		$res_B = NumberToWords_SK::convert($num);
		$this->assertTrue($res_A == 'deväťstoosemdesiatsedem miliónov šesťstopäťdesiatštyritisíc tristodvadsaťjeden celé jednostodvadsaťtri');
		$this->assertTrue($res_A == $res_B);

		// output may vary depending ICU version
		$res_A = NumberToWords_SK::convertIntl($num);
		$this->assertTrue(false !== strpos($res_A, 'osemdesiat') && false !== strpos($res_A, 'čiarka jeden dva tri'));
	}

	// Czech tests
	public function testCZ()
	{
		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'cz'); // cz or cs
		$res_B = NumberToWords_CZ::convert($num);
		$this->assertTrue($res_A == 'sto dvacet tři čárka čtyřicet pět');
		$this->assertTrue($res_A == $res_B);

		// output via PHP extension INTL may vary depending ICU version, has many bugs
		$res_A = NumberToWords_CZ::convertIntl($num);
		$this->assertTrue(false !== strpos($res_A, 'dvacet') && false !== strpos($res_A, 'čárka čtyři'));

		// with fraction
		NumberToWords::$decimalsAsFraction = true;

		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'cz');
		$this->assertTrue($res_A == 'sto dvacet tři (45/100)');

		$num = 123.05;
		$res_A = NumberToWords_CZ::convert($num, 'cz');
		$this->assertTrue($res_A == 'sto dvacet tři (5/100)');

		$num = 234.500;
		$res_A = NumberToWords::convert($num, 'cz');
		$this->assertTrue($res_A == 'dvě stě třicet čtyři (5/10)');

		// note - if you supply as string, it will keep right-sided zeroes
		$num = '234.500';
		$res_A = NumberToWords::convert($num, 'cz');
		$this->assertTrue($res_A == 'dvě stě třicet čtyři (500/1000)');

		NumberToWords::$decimalsAsFraction = false;

		// big number
		$num = 987654321.123;
		$res_A = NumberToWords::convert($num, 'cz');
		$res_B = NumberToWords_CZ::convert($num);
		$this->assertTrue($res_A == 'devět set osmdesát sedm miliónů šest set padesát čtyři tisíc tři sta dvacet jeden čárka sto dvacet tři');
		$this->assertTrue($res_A == $res_B);

		// output may vary depending ICU version
		$res_A = NumberToWords_CZ::convertIntl($num);
		$this->assertTrue(false !== strpos($res_A, 'osmdesát') && false !== strpos($res_A, 'čárka jeden dva tři'));
	}

	// English + other lang tests
	public function testEN()
	{
		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'en');
		$res_B = NumberToWords_EN::convert($num);
		$this->assertTrue($res_A == 'one hundred twenty-three point fourty-five');
		$this->assertTrue($res_A == $res_B);

		// output via PHP extension INTL may vary depending ICU version, has many bugs
		$res_A = NumberToWords_EN::convertIntl($num);
		$this->assertTrue(false !== strpos($res_A, 'twenty') && false !== strpos($res_A, 'point four'));

		// with fraction
		NumberToWords::$decimalsAsFraction = true;

		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'en');
		$this->assertTrue($res_A == 'one hundred twenty-three (45/100)');

		$num = 123.05;
		$res_A = NumberToWords_EN::convert($num, 'en');
		$this->assertTrue($res_A == 'one hundred twenty-three (5/100)');

		$num = 234.500;
		$res_A = NumberToWords::convert($num, 'en');
		$this->assertTrue($res_A == 'two hundred thirty-four (5/10)');

		// note - if you supply as string, it will keep right-sided zeroes
		$num = '234.500';
		$res_A = NumberToWords::convert($num, 'en');
		$this->assertTrue($res_A == 'two hundred thirty-four (500/1000)');

		NumberToWords::$decimalsAsFraction = false;

		// big number
		$num = 987654321.123;
		$res_A = NumberToWords::convert($num, 'en');
		$res_B = NumberToWords_EN::convert($num);
		$this->assertTrue($res_A == 'nine hundred eighty-seven million six hundred fifty-four thousand three hundred twenty-one point one hundred twenty-three');
		$this->assertTrue($res_A == $res_B);

		// output may vary depending ICU version
		$res_A = NumberToWords_EN::convertIntl($num);
		$this->assertTrue(false !== strpos($res_A, 'seven million') && false !== strpos($res_A, 'point one two three'));

		// Russian
		$num = 123.45;
		$res_A = NumberToWords::convert($num, 'ru');
		$this->assertTrue($res_A == 'сто двадцать три целых сорок пять сотых');

		// German
		$res_A = NumberToWords::convert($num, 'de');
		$this->assertTrue($res_A == 'ein­hundert­drei­und­zwanzig Komma vier fünf');

		// French
		$res_A = NumberToWords::convert($num, 'fr');
		$this->assertTrue($res_A == 'cent vingt-trois virgule quatre cinq');
	}
}
