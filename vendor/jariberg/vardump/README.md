## vardump

zero dependency replacement of php dump functions var_dump and print_r with beautiful styling and support for cli-mode

### Installation

```shell
composer require jariberg/vardump
```

composer.json:

```js
"require":{
	"jariberg/vardump": "*"
}
```

### Usage

```php
require __DIR__ . '/vendor/autoload.php';
\Vardump::singleton()->dump($_SERVER);
```

See test folder

### Examples

![alt tag](https://raw.github.com/jariberg/vardump/master/docs/1.png)

![alt tag](https://raw.github.com/jariberg/vardump/master/docs/2.png)

![alt tag](https://raw.github.com/jariberg/vardump/master/docs/3.png)


### License

Copyright (c) 2014 Jari Berg

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

