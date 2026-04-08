PasswordStrengthAnalyzer
========================

[![Tests](https://github.com/yarri/PasswordStrengthAnalyzer/actions/workflows/tests.yml/badge.svg)](https://github.com/yarri/PasswordStrengthAnalyzer/actions/workflows/tests.yml)
[![Downloads](https://img.shields.io/packagist/dt/yarri/password-strength-analyzer.svg)](https://packagist.org/packages/yarri/password-strength-analyzer)

Scores password strength from 0% to 100%, penalizing repeated patterns and consecutive character sequences. Unicode and diacritics are supported.

Usage
-----

    $analyzer = new Yarri\PasswordStrengthAnalyzer();
    $score = $analyzer->analyze("someFAIRpasswd"); // 26

Passwords with letters, numbers, and symbols are rated high.

    echo $analyzer->analyze("SomW2!3RE#"); // 91

Letter-only passwords can also score high if they are long enough.

    echo $analyzer->analyze("someBOYS"); // 9
    echo $analyzer->analyze("someBOYSdontCRY"); // 38
    echo $analyzer->analyze("someBOYSdontCRYmuch"); // 71

But not all long passwords are rated high.

    echo $analyzer->analyze("somesomesomesomesomesome"); // 4

Groups of 3 or more consecutive characters have a negative impact on the final score.

    echo $analyzer->analyze("Secret18239!"); // 58
    echo $analyzer->analyze("Secret12345!"); // 45
    echo $analyzer->analyze("Secret76543!"); // 45

    echo $analyzer->analyze("OpenAFBGCED!"); // 42
    echo $analyzer->analyze("OpenABCDEFG!"); // 30

Use `getCoefficients()` to inspect the individual scoring factors after an analysis:

    $analyzer->analyze("SomW2!3RE#"); // 91
    print_r($analyzer->getCoefficients());
    // [
    //   'unique_chars'      => ...,
    //   'password_length'   => ...,
    //   'types_used'        => ...,
    //   'type_transitions'  => ...,
    //   'simplicity_factor' => ...,
    // ]

Installation
------------

Use Composer:

    composer require yarri/password-strength-analyzer

Live testing
------------

In the package, there is a script runnable in shell which can be used for live testing.

    $ php test/test_cli.php

    initial score: 50%
    coefficients:
    * unique_chars: 1.8
    * password_length: 1.2
    * types_used: 0.675
    * type_transitions: 0.7465
    * simplicity_factor: 1
    FINAL SCORE: 54%
    password: THISisS3CR3T

License
-------

PasswordStrengthAnalyzer is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)


[//]: # ( vim: set ts=2 et: )
