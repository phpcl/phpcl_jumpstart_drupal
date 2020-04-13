/etc/init.d/mysql start
sleep 5
mysql -uroot -e "SOURCE /tmp/creates_dbs.sql;"
mysql -uroot -e "CREATE USER 'jumpstart'@'localhost' IDENTIFIED BY 'password';"
mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'jumpstart'@'localhost';"
mysql -uroot -e "FLUSH PRIVILEGES;"
mysql -uroot -e "SOURCE /tmp/jumpstart.sql;" jumpstart
mysql -uroot -e "SOURCE /tmp/drupal.sql;" drupal
echo "ServerName jumpstart" >> /etc/httpd/httpd.conf
echo "Include extra/httpd-vhosts.conf" >> /etc/httpd/httpd.conf
