# Twig templates for concrete5

This is a composer package that provides twig templates possibility for concrete5.
Currently it only works with single pages, in the future it should also work in
other areas of concrete5.

## How to use?

Add a composer.json file into your concrete5 package's directory. Into that
file, add the following content:

```
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/mainio/c5pkg_twig_templates.git"
        }
    ],
    "config": {
        "optimize-autoloader": true
    },
    "require": {
        "mainio/c5-twig-templates": "dev-master",
    }
}
```

If you are looking for code examples, e.g. the following package utilizes this package:

https://github.com/mainio/c5_symfony_forms_example


## Roadmap

- Make the templates work in themes
- Make the templates work in block views

## License

Licensed under the MIT license. See LICENSE for more information.

Copyright (c) 2015 Mainio Tech Ltd.
