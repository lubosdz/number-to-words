Number to words - PHP Converter
===============================

PHP utility for converting arbitrary float or integer number to words, mainly in English, Slovak and Czech.
These have custom PHP implementation, which will fix INTL/ICU bugs and allows for output customization.
Other languages are supported too, but only using INTL/ICU PHP extension, which might be buggy.

Installation
============

* via composer:

```bash
$ composer require "lubosdz/number-to-words" : "~1.0.0"
```

Demo & repo
===========

* demo: available only for [Slovak language](https://synet.sk/blog/php/330-cislo-na-slovo)
* repo: [https://github.com/lubosdz/number-to-words](https://github.com/lubosdz/number-to-words)

Usage
-----

```php

// use either factory ..
use lubosdz\numberToWords\NumberToWords;

// .. or language specific implementation
use lubosdz\numberToWords\NumberToWords_SK;
use lubosdz\numberToWords\NumberToWords_CZ;
use lubosdz\numberToWords\NumberToWords_EN;

// Slovensky / Slovak
NumberToWords::convert(123.45, 'sk'); // jednostodvadsaťtri celé štyridsaťpäť
NumberToWords_SK::convert(123.45); // jednostodvadsaťtri celé štyridsaťpäť
NumberToWords_SK::convertIntl(123.45); // jedna­sto dvasať­tri čiarka štyri päť (ICU returns "dvasať", bug)

// with decimals as a fraction - useful in accounting
NumberToWords::$decimalsAsFraction = true;
NumberToWords::convert(123.45, 'sk'); // jednostodvadsaťtri (45/100)

// Česky / Czech:
NumberToWords::convert(123.45, 'cz'); // allowed cz or cs, // sto dvacet tři čárka čtyřicet pět
NumberToWords_CZ::convert(123.45); // sto dvacet tři čárka čtyřicet pět
NumberToWords_CZ::convertIntl(123.45); // sto dvacet tři čárka čtyři pět

// English:
NumberToWords::convert(123.45); // lang code not needed since english is default
NumberToWords_EN::convert(123.45); // one hundred twenty-three point fourty-five
NumberToWords_EN::convertIntl(123.45); // one hundred twenty-three point four five

// really big number:
NumberToWords_EN::convert(987654321.123);
// nine hundred eighty-seven million, six hundred fifty-four thousand, three hundred twenty-one point one hundred twenty-three

NumberToWords_EN::convertIntl(987654321.123);
// nine hundred eighty-seven million six hundred fifty-four thousand three hundred twenty-one point one two three

// Russian:
NumberToWords::convert(123.45, 'ru'); // сто двадцать три целых сорок пять сотых

// German:
NumberToWords::convert(123.45, 'de'); // ein­hundert­drei­und­zwanzig Komma vier fünf

// French:
NumberToWords::convert(123.45, 'fr'); // cent vingt-trois virgule quatre cinq

```

Changelog
---------

1.0.4 - 04.02.2024
------------------
* Support decimals as a fraction
* Fixed zeroes in decimal part on left side
* added unit tests

1.0.3 - 02.07.2023
------------------
* Fixed PHP 8.2 compatability

1.0.2 - 12.03.2021
------------------
* extended support for any language via INTL/ICU extension

1.0.1 - 12.03.2021
------------------
* added factory class `NumberToWords::convert($num, $lang)`

1.0.0 - 22.05.2020
------------------
* initial release (SK, EN, CZ)
