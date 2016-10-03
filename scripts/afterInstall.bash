#!/bin/bash

# Link files needed in the document root to the EFS versions

sudo -u www-data ln -s /var/www/assets/robots.txt /var/www/live/robots.txt
rm /var/www/live/wp-config-local.php
sudo -u www-data ln -s /var/www/assets/wp-config-local.php /var/www/live/wp-config-local.php
sudo -u www-data ln -s /var/www/assets/advanced-cache.php /var/www/live/wp-content/advanced-cache.php
sudo -u www-data ln -s /var/www/assets/uploads /var/www/live/wp-content/uploads
sudo -u www-data ln -s /var/www/assets/wp-cache-config.php /var/www/live/wp-content/wp-cache-config.php
