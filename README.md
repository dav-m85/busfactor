README
======

What is busfactor
-----------------

*busfactor* generates a coverage report indicating how much developers worked on each file of a git repo. It helps pinpoint part of the code that are poorly maintained / known by the teams working on it.

![screenshot](https://github.com/dav-m85/busfactor/raw/master/doc/screenshot.png)

If someone get hit by a bus, maybe some files becomes difficult to maintain further.

It was inspired by a GoogleIO talk given by Brian Fitzpatrick, Ben Collins-Sussman, "The Myth of the Genius Programer".

Requirements
------------

gnu make, php5.3

Usage
------------

Copy config.yml.dist to config.yml, and setup the correct key values (you need a github API access token).
```bash
# Installs deps
make build

# Mirror the files
./gitbf build
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
