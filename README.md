PHP KeyStore-Filesystem
===========

[kumatch/keystore](https://github.com/kumatch/php-keystore) driver for file system.

[![Build Status](https://travis-ci.org/kumatch/php-keystore-filesystem.png?branch=master)](https://travis-ci.org/kumatch/php-keystore-filesystem)


Install
-----

Add "kumatch/fs-keystore-filesystem" as a dependency in your project's composer.json file.


    {
      "require": {
        "kumatch/fs-keystore-filesystem": "*"
      }
    }

And install your dependencies.

    $ composer install



Usage
-----

```php
use Kumatch\KeyStore\Filesystem\Driver;

$rootPath = "/path/to/storage";
$driver = new Driver($rootPath);
```

### __construct ($rootPath)

Set a file system directory path for file storage.

If $rootPath is not direcotry or not exists, throw exception `Kumatch\KeyStore\Filesystem\Exception\InvalidArgumentException`.



License
--------

Licensed under the MIT License.

Copyright (c) 2013 Yosuke Kumakura

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
