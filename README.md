PasswordStrengthAnalyzer
========================

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

License
-------

PasswordStrengthAnalyzer is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)


[//]: # ( vim: set ts=2 et: )
