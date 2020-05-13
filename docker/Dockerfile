#
# PHP-CL JumpStart Drupal
#

# Pull base image.
FROM asclinux/linuxforphp-8.2-ultimate:7.4-nts

# Copy database backups + conf file(s)
COPY ./init.sh /tmp/init.sh
COPY ./httpd-vhosts.conf /etc/httpd/extra/httpd-vhosts.conf
COPY ./httpd-drupal.conf /etc/httpd/extra/httpd-drupal.conf

# Init database + web server config
RUN chmod +x /tmp/init.sh
RUN /tmp/init.sh
RUN echo "Don't forget to run /srv/jumpstart/restore_data.sh the first time you run the container"