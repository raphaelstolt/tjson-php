Tjson
================
[![Build Status](https://travis-ci.org/raphaelstolt/tjson-php.svg?branch=master)](https://travis-ci.org/raphaelstolt/tjson-php)
![PHP Version](http://img.shields.io/badge/php-5.6+-ff69b4.svg)

This library is a PHP implementation of the [TJSON (Tagged JSON)](https://www.tjson.org/) draft.

#### Installation via Composer
``` bash
$ composer require stolt/tjson
```

#### Usage

The TJSON class provides two methods; one to transform TJSON to JSON as shown next.

```php
$tjson = '{"example:A<O>": [{"a:i": "1"}, {"b:i": "2"}]}';
$json = (new Tjson())->toJson($json);
echo $tjson; // {"example": [{"a": "1"}, {"b": "2"}]}
```

And one to transform JSON to TJSON as shown next.

```php
$json = '{"example": [{"a": "1"}, {"b": "2"}]}';
$tjson = (new Tjson())->fromJson();
echo $tjson; // {"example:A<O>": [{"a:i": "1"}, {"b:i": "2"}]}
```

#### Running tests
``` bash
$ composer test
```

#### License
This library is licensed under the MIT license. Please see [LICENSE](LICENSE.md) for more details.

#### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more details.

#### Contributing
Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for more details.
