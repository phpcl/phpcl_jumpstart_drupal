#!/bin/bash
echo "ServerName jumpstart" >> /etc/httpd/httpd.conf
echo "Include /etc/httpd/extra/httpd-drupal.conf" >> /etc/httpd/httpd.conf
echo "Include /etc/httpd/extra/httpd-vhosts.conf" >> /etc/httpd/httpd.conf
