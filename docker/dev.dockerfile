FROM php:8.4-apache

# Install composer
COPY --from=composer:latest --chmod=0555 /usr/bin/composer /usr/bin/composer

# Install needed software
RUN apt update && apt install -y vim zip libzip-dev \
    && docker-php-ext-install zip

# Create the directory we will mount the application into
VOLUME /app
WORKDIR /app

# Create localuser, so that we don't choke when mounting the application into the container
RUN groupadd localuser && useradd -m -g localuser localuser

# Tell the Apache that we want it to run as localuser, so that it can actually read the project
ENV APACHE_RUN_USER=localuser
ENV APACHE_RUN_GROUP=localuser
# Also install our custom Apache configuration
COPY apache-default.conf /etc/apache2/sites-enabled/000-default.conf

# Install our custom entrypoint
COPY --chmod=0555 --chown=root:root entrypoint-dev.sh /usr/local/bin/custom-entrypoint-dev
ENTRYPOINT ["custom-entrypoint-dev"]
