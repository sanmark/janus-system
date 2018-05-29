# Start
FROM a2way/dunp:v0.1.0

# Install required packages.
RUN apt-get install -y composer unzip php-mbstring php-dom php-mysql

# Remove default "index.html" file of A2Way DUNP.
WORKDIR /app
RUN rm index.html

# Place final file system.
COPY /container-fs-final /

# Place boot files.
COPY /boot /app-boot

# Place source code.
COPY /src /app

# Make "www-data:www-data" owner of certain dirs.
WORKDIR /app/public
RUN chown -R www-data:www-data .
WORKDIR /app/storage
RUN chown -R www-data:www-data .

CMD ["bash", "/app-boot/boot.sh"]
