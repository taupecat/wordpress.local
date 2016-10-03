#!/usr/bin/env bash

echo "Fixing a network issue..."
cp /vagrant/scripts/eth1.cfg /etc/network/interfaces.d/eth1.cfg

apt-get update >/dev/null 2>&1

echo "Installing php7..."
apt-get install php php-cli -y >/dev/null 2>&1

echo "Installing nginx..."
apt-get install -y nginx php-gd php-curl php-memcached php-fpm >/dev/null 2>&1
cp /vagrant/scripts/default /etc/nginx/sites-available/default
sed -i 's/www-data/vagrant/' /etc/nginx/nginx.conf
sed -i 's/www-data/vagrant/' /etc/php/7.0/fpm/pool.d/www.conf

echo "Installing MariaDB..."
debconf-set-selections <<< 'mysql-server mysql-server/root_password password password'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password password'
apt-get -y install mariadb-server >/dev/null 2>&1
mysql --user=root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY 'password' WITH GRANT OPTION; FLUSH PRIVILEGES;"
mysqladmin --user=root --password=password create wordpress
mysql --user=root --password=password wordpress < /vagrant/scripts/database.sql

echo "Getting php7 & mysql to talk to each other..."
apt-get install -y php7.0-mysql >/dev/null 2>&1

echo "Reloading nginx configuration"
service nginx reload
