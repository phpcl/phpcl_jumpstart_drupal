#
# PHP-CL JumpStart Drupal
#

# Pull base image.
FROM asclinux/linuxforphp-8.2-ultimate:7.4-nts

COPY ./init.sh /tmp/init.sh
COPY ./restore_perms.sql /tmp/restore_perms.sql
COPY ./jumpstart.sql /tmp/jumpstart.sql
RUN  chmod +x /tmp/init.sh
RUN  /tmp/init.sh
RUN  echo "Include /etc/httpd/extra/httpd-vhosts.conf" >> /etc/httpd/httpd.conf