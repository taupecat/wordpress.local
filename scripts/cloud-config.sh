#cloud-config
package_upgrade: true
packages:
- nfs-utils
runcmd:
- echo "$(curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone).fs-8a9958c3.efs.us-east-1.amazonaws.com:/ /var/www/assets nfs defaults,vers=4.1 0 0" >> /etc/fstab
- mount -a -t nfs
- ln -s /var/www/assets/config.d/nginx.conf /etc/nginx/nginx.conf
- ln -s /var/www/assets/config.d/rp3agency.conf /etc/nginx/sites-enabled/default
- ln -s /var/www/assets/config.d/php.ini /etc/php/7.0/fpm/php.ini
- service php7.0-fpm restart
- service nginx restart
