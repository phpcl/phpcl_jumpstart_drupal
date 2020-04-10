#!/bin/bash
/etc/init.d/mysql stop
/etc/init.d/mysql start
mysql < /tmp/restore_perms.sql
mysql jumpstart < /tmp/jumpstart.sql
/etc/init.d/mysql stop
/etc/init.d/mysql start
