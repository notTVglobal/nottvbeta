# Use the official Laravel Sail image as the base image
FROM laravelsail/php82-composer:latest

# Set default values for ARG variables
ARG WWWGROUP=1000
ARG WWWUSER=1000

# Install ClamAV and ExifTool
RUN apt-get update && \
    apt-get install -y clamav clamav-daemon libimage-exiftool-perl && \
    freshclam && \
    echo "ClamAV and ExifTool installed successfully"

# Verify installations
RUN which clamscan && clamscan --version && echo "ClamAV verification complete"
RUN which exiftool && exiftool -ver && echo "ExifTool verification complete"

# Create group and user with passed or default ARG values
RUN groupadd --force -g $WWWGROUP sail && \
    useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER sail && \
    echo "User and group created successfully"

# Start ClamAV daemon
RUN mkdir -p /var/run/clamav && \
    touch /var/run/clamav/clamd.sock && \
    chown clamav:clamav /var/run/clamav/clamd.sock && \
    echo "ClamAV daemon setup complete"

# Copy the existing application
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install dependencies
RUN composer install && echo "Composer install complete"

# Set proper permissions for storage and bootstrap cache
RUN chown -R www-data:www-data storage bootstrap/cache && \
    echo "Permissions set successfully"

# Expose the application port
EXPOSE 8000

# Start services
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
