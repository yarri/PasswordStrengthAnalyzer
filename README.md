PasswordStrengthAnalyzer
========================

[![Build Status](https://app.travis-ci.com/yarri/PasswordStrengthAnalyzer.svg?branch=master)](https://app.travis-ci.com/yarri/PasswordStrengthAnalyzer)
[![Downloads](https://img.shields.io/packagist/dt/yarri/password-strength-analyzer.svg)](https://packagist.org/packages/yarri/password-strength-analyzer)

PasswordStrengthAnalyzer analyzes given password and scores its strength from 0 to 100%.

Usage
-----

    $analyzer = new Yarri\PasswordStrengthAnalyzer();
    $score = $analyzer->analyze("someFAIRpasswd"); // 29

Usually, passwords with letters, numbers, symbols are considered as strong. It is also true for PasswordStrengthAnalyzer.

    echo $analyzer->analyze("SomW23!3RE#"); // 91

PasswordStrengthAnalyzer will also rate letter-only passwords high if they are long enough.

    echo $analyzer->analyze("someBOYS"); // 2
    echo $analyzer->analyze("someBOYSdontCRY"); // 61
    echo $analyzer->analyze("someBOYSdontCRYmuch"); // 100

But not all long passwords are rated high.

    echo $analyzer->analyze("somesomesomesomesomesome"); // 4

Groups of 3 or more consecutive characters have impact on the final score.

    echo $analyzer->analyze("Secret18239!"); // 50
    echo $analyzer->analyze("Secret12345!"); // 31
    echo $analyzer->analyze("Secret76543!"); // 31

    echo $analyzer->analyze("OpenAFBGCED!"); // 41
    echo $analyzer->analyze("OpenABCDEFG!"); // 22

Installation
------------

Just use the Composer:

    composer require yarri/password-strength-analyzer

Live testing
------------

In the package, there is a script runnable in shell which can be used for live testing.

    $ php test/test_cli.php

    coefficients:
    * unique_chars: 1.2479
    * password_length: 1.0051
    * types_used: 0.675
    * type_transitions: 1.1139
    * simplicity_factor: 1
    SCORE: 47%
    password: THISisS3CR3T

License
-------

PasswordStrengthAnalyzer is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)


[//]: # ( vim: set ts=2 et: )
