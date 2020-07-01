# phpcl_jumpstart_drupal
Drupal 8 Module Development

## Setup
* Install `docker`
  * CentOS: https://docs.docker.com/install/linux/docker-ce/centos/#install-docker-ce
  * Debian: https://docs.docker.com/install/linux/docker-ce/debian/
  * Fedora: https://docs.docker.com/install/linux/docker-ce/fedora/
  * Ubuntu: https://docs.docker.com/install/linux/docker-ce/ubuntu/
  * Windows: https://docs.docker.com/docker-for-windows/install/
  * Mac: https://docs.docker.com/docker-for-mac/install/
* Install `docker-compose`
  * https://docs.docker.com/compose/install/
* Clone this repository into some directory (e.g. `/path/to/repo`)
* From a terminal window (or command prompt):
```
cd /path/to/repo
docker-compose build
docker-compose up
```
* Don't worry about the messages which appear: there's a service daemon running that constantly checks the status of Apache, PHP, etc. and restarts if needed.

## Access the Container
* From your browser:
  * http://localhost:8899/  --or--
  * http://172.16.9.99/
* To open a BASH shell into the running container:
  * Open up another terminal window / command prompt
  * Execute this command:
```
docker exec -it phpcl_jumpstart_drupal /bin/bash
```
* The Drupal installation is located in this directory: `/srv/tempo/drupal`

## `jumpstart` Database
You can use the `jumpstart` database for testing purposes
* Open a BASH shell (see above)
* Restore database and assign privileges to user `test` with password `password`
```
/srv/jumpstart/restore_data.sh
```

## Modify the source code:
The code in the repository is mapped to `/srv/jumpstart` in the container
* You can use any code editor on your local computer to create code from inside the repo directory structure
* You can then copy that code from a BASH shell in the container:
```
cp /srv/jumpstart/your/code /srv/tempo/drupal/modules/custom/your_module
```
