Run `composer install`

Run `php ./vendor/bin/simple-phpunit`

```
PHPUnit 9.5.10 by Sebastian Bergmann and contributors.

Warning:       Your XML configuration validates against a deprecated schema.
Suggestion:    Migrate your XML configuration using "--migrate-configuration"!

Testing 
PHP Fatal error:  Cannot declare interface PhpParser\Parser, because the name is already in use in /home/gabriel/personal/rector-phpunit-bug/vendor/rector/rector/vendor/nikic/php-parser/lib/PhpParser/Parser.php on line 6
```