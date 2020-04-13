#
# PHP-CL JumpStart Drupal
#

# Pull base image.
FROM asclinux/linuxforphp-8.2-ultimate:7.4-nts

# Copy database backups + conf file(s)
COPY ./init.sh /tmp/init.sh
COPY ./creates_dbs.sql /tmp/creates_dbs.sql
COPY ./drupal.sql /tmp/drupal.sql
COPY ./jumpstart.sql /tmp/jumpstart.sql
COPY ./httpd-vhosts.conf /etc/httpd/extra/httpd-vhosts.conf

# Init database + web server config
RUN chmod +x /tmp/init.sh
RUN /tmp/init.sh
