/etc/init.d/mysql start
sleep 5
mysql -uroot -e "CREATE DATABASE jumpstart;"
mysql -uroot -e "CREATE DATABASE drupal;"
mysql -uroot -e "CREATE USER 'jumpstart'@'localhost' IDENTIFIED BY 'password';"
mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'jumpstart'@'localhost';"
mysql -uroot -e "FLUSH PRIVILEGES;"
mysql -uroot -e "SOURCE /tmp/jumpstart.sql;" jumpstart
mysql -uroot -e "SOURCE /tmp/drupal.sql;" drupal
echo "ServerName jumpstart" >> /etc/httpd/httpd.conf
echo "Include /etc/httpd/extra/httpd-vhosts.conf" >> /etc/httpd/httpd.conf
