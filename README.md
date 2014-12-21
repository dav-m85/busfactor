#busfactor

[![Build Status](https://travis-ci.org/dav-m85/busfactor.png?branch=master)](https://travis-ci.org/dav-m85/busfactor)

*busfactor* generates a coverage report indicating how much each contributor worked on each file of a git repo.
It helps pinpoint part of the code that are poorly maintained/known by the teams working on it.

![screenshot](https://github.com/dav-m85/busfactor/raw/master/doc/screenshot.png)

If someone gets hit by a bus, some files may become more difficult to maintain further.

It was inspired by a GoogleIO talk given by Brian Fitzpatrick, Ben Collins-Sussman,
[The Myth of the Genius Programmer](https://www.youtube.com/watch?v=0SARbwvhupQ).

Installation
------------

### With composer

```bash
composer global require "dav-m85/busfactor=0.2.*"
```

Make sure your global composer folder is in your PATH.

### By cloning the repo

```bash
git clone http://github.com/dav-m85/busfactor.git
cd busfactor
composer install
```

### As a dependency in another composer project

Add the following to your composer.json
```json
{
    "require-dev": {
        "dav-m85/busfactor": "0.2.*"
    }
}
```
Then in your project folder:
```bash
composer install
```

Usage
-----

Given you have a repository `my/git/repository`, you can generate a report with following command
(`output/parent/folder` has to be writeable):
```bash
busfactor generate my/git/repository output/parent/folder
```

Then open `output/parent/folder/index.html` with your browser.

If you don't specify an output folder, the report will automatically be generated to `out/` folder relative to
[busfactor](busfactor) file.

If you intend to serve generated files, specify an asset-url options like this:
```
busfactor generate my/git/repository output/parent/folder --asset-url http://example.com/root/path
```

Contributing
------------

You can contribute in various ways:

*Report bugs* in the project [issues](../../issues) section.
Please make sure you know how to report one, general understanding of
[this document](http://www.chiark.greenend.org.uk/~sgtatham/bugs.html) may help ;)

You want to *fix a bug*? Take an issue or fill one, assign yourself on it and when done, submit a Pull Request. I'll do
my best to read it in a timely fashion and approve it.

Note that this project is following [Semantic Versioning 2.0.0](http://semver.org/).

You like this project? Fork it, star it, talk about it!

Testing
-------

Tests use [PHPUnit](https://phpunit.de).
Simply run the following command in busfactor project directory:
```
vendor/bin/phpunit
```

Credits
-------
Maintainer: [dav-m85](http://github.com/dav-m85)

Contributors: [Triiistan], you?

License
-------
*busfactor* is released under the MIT license.
A copy can be found inside the project [here](LICENSE.txt), or at http://opensource.org/licenses/mit-license.php

Related
-------
*  https://github.com/lafourchette/gitmirror (Repository class was borrowed from this project)
