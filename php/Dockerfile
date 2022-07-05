FROM php:8.0-fpm

# PHP extensions
RUN apt update \
    && apt install -y zlib1g-dev curl g++ git libicu-dev wget zip libzip-dev zip libffi-dev librabbitmq-dev libssh-dev libgmp-dev libssl-dev rabbitmq-c-dev vim \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo \
    && docker-php-ext-install sockets \
    && docker-php-ext-install pdo_mysql \
    && pecl install apcu \
    && pecl install amqp \
    && docker-php-ext-enable apcu \
    && docker-php-ext-enable amqp


# Install panther driver for firefox
RUN echo "deb http://deb.debian.org/debian/ unstable main contrib non-free" >> /etc/apt/sources.list.d/debian.list
RUN apt update
RUN apt install -y --no-install-recommends firefox


# Install javascript dependencies
RUN apt install npm -y


# Set the working directory
WORKDIR /var/www/instagram_parser


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Install the Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN git config --global user.email "you@example.com" \
    && git config --global user.name "user"


# Allowed memory size
ENV PHP_MEMORY_LIMIT=812M