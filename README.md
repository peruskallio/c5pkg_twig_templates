# Twig templates for concrete5

This is a composer package that provides twig templates possibility for concrete5.
Currently it only works with single pages, in the future it should also work in
other areas of concrete5.

## How to use?

Add a composer.json file into your concrete5 package's directory. Into that
file, add the following content:

```
{
    "require": {
        "mainio/c5-twig-templates": "*"
    }
}
```

And then run `composer install` in the same directory. After this, add the
following on top of your package controller (after the namespace definition):

```php
include(dirname(__FILE__) . '/vendor/autoload.php');
```

When developing your twig views, it is also suggested to add the following
configuration to your `application/config/app.php`:

```php
return array(
    // ... some other configs ...
    'twig_debug' => true
    // ... some other configs ...
);
```

This prevents the templates from being loaded from the compile cache which
makes developing a lot more fun as you do not need to flush your cache after
every page load.

If you are looking for code examples, e.g. the following package utilizes this package:

https://github.com/mainio/c5_symfony_forms_example


## Roadmap

- Make the templates work in themes
- Make the templates work in block views

## License

Licensed under the MIT license. See LICENSE for more information.

Copyright (c) 2015 Mainio Tech Ltd.
