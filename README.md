PasswordStrengthAnalyzer
========================

PasswordStrengthAnalyzer analyzes given password and scores its strength from 0 to 100%.

Usage
-----

    $analyzer = new Yarri\PasswordStrengthAnalyzer();
    $score = $analyzer->analyze("someFAIRpasswd"); // 32

Usually, passwords with letters, numbers, symbols are considered as strong. It is also true for PasswordStrengthAnalyzer.

    echo $analyzer->analyze("SomW23!3RE#"); // 91

PasswordStrengthAnalyzer will also rate letter-only passwords high if they are long enough.

    echo $analyze->analyze("someBOYS"); // 2
    echo $analyzer->analyze("someBOYSdontCRY"); // 61
    echo $analyzer->analyze("someBOYSdontCRYmuch"); // 100

But not all long passwords are rated high.

    echo $analyzer->analyze("somesomesomesomesomesome"); // 9

License
-------

PasswordStrengthAnalyzer is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)


[//]: # ( vim: set ts=2 et: )
