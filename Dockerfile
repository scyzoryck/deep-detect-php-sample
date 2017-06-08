FROM php:7.1

#composer
RUN apt-get update && apt-get install -y \
        unzip \
        git

RUN  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    &&  php composer-setup.php \
    &&  php -r "unlink('composer-setup.php');" \
    &&  mv composer.phar /usr/local/bin/composer
