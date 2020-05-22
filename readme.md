Number to words - PHP Converter
===============================

Helper utility for converting arbitrary float number to words.
Available for English, Slovak and Czech localizations.

Installation
============

* via composer:

```bash
$ composer require "lubosdz/number-to-words" : "~1.0.0"
```

Demo
====

* available only for [Slovak language](https://synet.sk/blog/php/330-cislo-na-slovo)


Examples
--------

```php

use lubosdz\numberToWords\NumberToWords_SK;
use lubosdz\numberToWords\NumberToWords_EN;
use lubosdz\numberToWords\NumberToWords_CZ;

echo "<hr>English:<hr/>";

echo NumberToWords_EN::convert(123.45);
// one hundred twenty-three point fourty-five

echo "<br>".NumberToWords_EN::convertIntl(123.45);
// one hundred twenty-three point four five

echo "<br>".NumberToWords_EN::convert(987654321.123);
// nine hundred eighty-seven million, six hundred fifty-four thousand, three hundred twenty-one point one hundred twenty-three

echo "<br>".NumberToWords_EN::convertIntl(987654321.123);
// nine hundred eighty-seven million six hundred fifty-four thousand three hundred twenty-one point one two three

echo "<hr/>Slovak:<hr/>";

echo NumberToWords_SK::convert(123.45);
// jednostodvadsaťtri celé štyridsaťpäť

echo "<br>".NumberToWords_SK::convertIntl(123.45);
// jedna­sto dvasať­tri čiarka štyri päť (áno, ICU vracia "dvasať", nie je to preklep)

echo "<br>".NumberToWords_SK::convert(987654321.123);
// deväťstoosemdesiatsedem miliónov šesťstopäťdesiatštyritisíc tristodvadsaťjeden celé jednostodvadsaťtri

echo "<br>".NumberToWords_SK::convertIntl(987654321.123);
// deväť­sto osemdesiat­sedem miliónov šesť­sto päťdesiat­štyri tisíc tri­sto dvasať­jeden čiarka jeden dva tri

echo "<hr/>Česky:<hr/>";

echo NumberToWords_CZ::convert(123.45);
// sto dvacet tři čárka čtyřicet pět

echo "<br>".NumberToWords_CZ::convertIntl(123.45);
// sto dvacet tři čárka čtyři pět

echo "<br>".NumberToWords_CZ::convert(987654321.123);
// devět set osmdesát sedm miliónů šest set padesát čtyři tisíc tři sta dvacet jeden čárka sto dvacet tři

echo "<br>".NumberToWords_CZ::convertIntl(987654321.123);
// devět set osmdesát sedm miliónů šest set padesát čtyři tisíc tři sta dvacet jeden čárka jeden dva tři

```

Changelog
---------

* 1.0.0 - [22.05.2020] initial release (SK, EN)
