# L5Imdb

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require firmantr3/l5imdb
```

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Examples

```php
// get movie data by id
\L5Imdb::title('0944947')->all(); // game of throne

// get actor/actress data by id
\L5Imdb::person('1785339')->all() // rami malek

// search movies
\L5Imdb::searchTitle('Deadpool')->all();

// search actor/actress
\L5Imdb::searchPerson('Dwayne Johnson')->all();

```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email firmantr3@gmail.com instead of using the issue tracker.

## Credits

- [Firman Taruna Nugraha][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/firmantr3/l5imdb.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/firmantr3/l5imdb.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/firmantr3/l5imdb/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/firmantr3/l5imdb
[link-downloads]: https://packagist.org/packages/firmantr3/l5imdb
[link-travis]: https://travis-ci.org/firmantr3/l5imdb
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/firmantr3
[link-contributors]: ../../contributors]
