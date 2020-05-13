#!/bin/bash
/etc/init.d/mysql start
sleep 5
mysql -uroot -v -e "CREATE DATABASE jumpstart;"
mysql -uroot -v -e "CREATE DATABASE drupal;"
mysql -uroot -v -e "CREATE USER 'jumpstart'@'localhost' IDENTIFIED BY 'password';"
mysql -uroot -v -e "GRANT ALL PRIVILEGES ON *.* TO 'jumpstart'@'localhost';"
mysql -uroot -v -e "FLUSH PRIVILEGES;"
mysql -uroot -v -e "SOURCE /srv/jumpstart/sample_data/jumpstart.sql;" jumpstart
mysql -uroot -v -e "SOURCE /srv/jumpstart/sample_data/drupal.sql;" drupal
chgrp -R apache /srv/jumpstart/drupal_project
chmod -R 775 /srv/jumpstart/drupal_project
