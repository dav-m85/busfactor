#busfactor

[![Build Status](https://travis-ci.org/dav-m85/busfactor.png?branch=master)](https://travis-ci.org/dav-m85/busfactor)

*busfactor* generates a coverage report indicating how much developers worked on each file of a git repo. It helps pinpoint part of the code that are poorly maintained / known by the teams working on it.

![screenshot](https://github.com/dav-m85/busfactor/raw/master/doc/screenshot.png)

If someone get hit by a bus, maybe some files becomes difficult to maintain further.

It was inspired by a GoogleIO talk given by Brian Fitzpatrick, Ben Collins-Sussman, "The Myth of the Genius Programer".

Installation
------------

### With composer

```bash
composer global require "dav-m85/busfactor=0.2.*"
busfactor ...
```

Make sure your global composer folder is in your PATH.

### By cloning the repo

```bash
git clone http://github.com/dav-m85/busfactor.git
cd busfactor
composer install
./busfactor ...
```

### As a dependency in another composer project

Add the following in your composer.json
```json
{
    "require-dev": {
        "dav-m85/busfactor": "0.2.*"
    }
}
```

Usage
-----------

Given you have a /my/git/repository, do as follows (paths have to be absolute, output parent folder has to be writeable)
```bash
busfactor generate /my/git/repository/.git /home/me/out
```

Then open /home/me/out/index.html with your browser.

If you intend to use serve generated files, specify an asset-url options like this
```
busfactor generate /my/git/repository/.git /home/me/out --asset-url http://example.com/root/path
```

Contributing
------------

You can contribute in various ways :

*Report bugs* in the projects "issues" section. Please make sure you know how to report one, general understanding of [this
document](http://www.chiark.greenend.org.uk/~sgtatham/bugs.html) could help ;)

You want to *fix a bug* ? Take an issue or fill one, assign yourself on it and when done, submit a Pull Request. I'll do
my best to read it in a timely fashion and approve it.

Note that this project is following [Semantic Versioning 2.0.0](http://semver.org/).

You like this project ? Fork it, star it, talk about it !

Testing
----------------------

There is no tests yet.

Credits
-------
Maintainer : [dav-m85](http://github.com/dav-m85)

Contributors : you ?

License
-------
*busfactor* uses the MIT license. A copy can be found inside the project, or at http://opensource.org/licenses/mit-license.php

Related
-------
https://github.com/lafourchette/gitmirror

https://www.youtube.com/watch?v=0SARbwvhupQ
