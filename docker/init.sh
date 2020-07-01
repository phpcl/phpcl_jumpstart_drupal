#!/bin/bash
echo "ServerName jumpstart" >> /etc/httpd/httpd.conf
echo "Installing drupal ..."
/bin/lfphp-get cms drupal drupal
echo "Installing Drupal Console ... "
cd /srv/tempo/drupal
composer require drupal/console
echo "Configuring permissions ... "
chgrp -R apache /srv/tempo/drupal
chmod -R 775 /srv/tempo/drupal
chgrp apache /srv/www
echo "*************************************************************************"
echo "Please run the Web installer and use the following database credentials :"
echo "URL      : http://localhost:8899 or http://172.16.9.99"
echo "USER     : cmsuser"
echo "PASSWORD : testpass"
echo "DATABASE : cms"
echo "SERVER   : localhost"
echo "*************************************************************************"
