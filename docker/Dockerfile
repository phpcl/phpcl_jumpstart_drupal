#
# PHP-CL JumpStart Drupal
#

# Pull base image.
FROM asclinux/linuxforphp-8.2-ultimate:7.4-nts

# Run init script
COPY ./init.sh /tmp/init.sh
RUN chmod +x /tmp/init.sh
RUN /tmp/init.sh
