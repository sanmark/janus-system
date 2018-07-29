# Start
FROM a2way/dunp:v0.1.0

# Install required packages.
RUN apt-get install -y php-mbstring php-dom php-mysql

# Remove default "index.html" file of A2Way DUNP.
WORKDIR /app
RUN rm index.html

# Place final file system.
COPY /container-fs-final /

# Place boot files.
COPY /boot /app-boot

# Place source code.
COPY /src/vendor /app/vendor
COPY /src/bootstrap /app/bootstrap
COPY /src/config /app/config
COPY /src/database /app/database
COPY /src/public /app/public
COPY /src/resources /app/resources
COPY /src/routes /app/routes
COPY /src/storage /app/storage
COPY /src/artisan /app/artisan
COPY /src/composer.json /app/composer.json
COPY /src/composer.lock /app/composer.lock
COPY /src/tests /app/tests
COPY /src/.env /app/.env
COPY /src/app /app/app

# Make "www-data:www-data" owner of certain dirs.
WORKDIR /app/public
RUN chown -R www-data:www-data .
WORKDIR /app/storage
RUN chown -R www-data:www-data .

CMD ["bash", "/app-boot/boot.sh"]
