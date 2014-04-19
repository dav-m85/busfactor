build: composer.phar
	/usr/bin/php composer.phar install --dev

composer.phar:
	wget -nc http://getcomposer.org/composer.phar

.PHONY : build clean

clean:
	rm composer.phar
	rm -rf vendor/