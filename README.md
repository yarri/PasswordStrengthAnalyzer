PasswordStrengthAnalyzer
========================

[![Build Status](https://app.travis-ci.com/yarri/PasswordStrengthAnalyzer.svg?branch=master)](https://app.travis-ci.com/yarri/PasswordStrengthAnalyzer)
[![Downloads](https://img.shields.io/packagist/dt/yarri/password-strength-analyzer.svg)](https://packagist.org/packages/yarri/password-strength-analyzer)

PasswordStrengthAnalyzer analyzes given password and scores its strength from 0 to 100%.

Usage
-----

    $analyzer = new Yarri\PasswordStrengthAnalyzer();
    $score = $analyzer->analyze("someFAIRpasswd"); // 26

Usually, passwords with letters, numbers, symbols are considered as strong. It is also true for PasswordStrengthAnalyzer.

    echo $analyzer->analyze("SomW2!3RE#"); // 91

PasswordStrengthAnalyzer will also rate letter-only passwords high if they are long enough.

    echo $analyzer->analyze("someBOYS"); // 9
    echo $analyzer->analyze("someBOYSdontCRY"); // 38
    echo $analyzer->analyze("someBOYSdontCRYmuch"); // 71

But not all long passwords are rated high.

    echo $analyzer->analyze("somesomesomesomesomesome"); // 4

Groups of 3 or more consecutive characters have impact on the final score.

    echo $analyzer->analyze("Secret18239!"); // 58
    echo $analyzer->analyze("Secret12345!"); // 45
    echo $analyzer->analyze("Secret76543!"); // 45

    echo $analyzer->analyze("OpenAFBGCED!"); // 42
    echo $analyzer->analyze("OpenABCDEFG!"); // 28

Installation
------------

Just use the Composer:

    composer require yarri/password-strength-analyzer

Live testing
------------

In the package, there is a script runnable in shell which can be used for live testing.

    $ php test/test_cli.php

    base score: 50%
    coefficients:
    * unique_chars: 1.8
    * password_length: 1.2
    * types_used: 0.675
    * type_transitions: 0.7465
    * simplicity_factor: 1
    SCORE: 54%
    password: THISisS3CR3T

License
-------

PasswordStrengthAnalyzer is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)


[//]: # ( vim: set ts=2 et: )
