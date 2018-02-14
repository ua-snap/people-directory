# IARC Directory Kiosk

## Local development instance with Docker

### Setup Docker containers

1. Install [Docker](https://www.docker.com/) if you have not already.

1. *Not implemented yet* Grab files and database dump from TBD.  You should end up with an equivalent of an SQL dump in this location for the directions below: `~/Downloads/directory_02_06_18-00-02-01.sql`

1. Clone this repository and extract the Drupal files into their proper location.  Directory names are placeholders, use whatever is appropriate on your system.

   ```bash
   mkdir -p ~/docker
   cd ~/docker
   git clone https://github.com/ua-snap/people-directory.git
   [[ Placeholder: if we need it...see step 2.
   cd people-directory/sites/default
   tar -jxvf ~/Downloads/TBD
   ]]
   ```

1. Download Drupal settings file that reads MySQL host and root password from Docker environment variables.

   ```bash
   curl -O 'https://raw.githubusercontent.com/ua-snap/docker-drupal-settings/master/settings.php'
   ```

1. Set up persistent MySQL database container. You may need to wait 10-20 seconds after this command returns before you can connect to the MySQL server in the next step.

   ```bash
   docker run --name iarc-people-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest
   ```

1. Spawn temporary container that links to MySQL container and creates database.

   ```bash
   docker run -it --link iarc-people-mysql:mysql --rm mysql sh -c 'exec mysql \-h "$MYSQL_PORT_3306_TCP_ADDR" -P "$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE drupal7;"'
   ```

1. Spawn temporary container that links to MySQL container and imports database dump.

   ```bash
   docker run -i --link iarc-people-mysql:mysql --rm mysql sh -c 'exec mysql \-h "$MYSQL_PORT_3306_TCP_ADDR" -P "$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD" drupal7' < ~/Downloads/directory_02_06_18-00-02-01.sql
   ```

1. Set up persistent Drupal container that links to MySQL container.

   ```bash
   docker run --name iarc-people-drupal -p 8080:80 --link accap-mysql:mysql -v ~/docker/accap-www:/var/www/html -d drupal:7
   ```

After everything is done, you should see a valid Drupal instance at `localhost:8080`.

### Stop Docker containers

The Docker containers can be stopped at any time with the following command:

```bash
docker stop
```

This is useful if you need to start the Docker containers for a different website.

### Start Docker containers

Stopped Docker containers can be started with the following command:

```bash
docker start iarc-people-drupal iarc-people-mysql
```

### List Docker containers

The following command will list all of the Docker containers on your machine, both running and not running:

```bash
docker ps -a
```

### Remove Docker containers

If something goes wrong with your Docker containers and you would like to go through the setup instructions again, you will first need to remove your existing Docker containers for the ACCAP website with the following commands.

1. Stop the Drupal and MySQL containers:

   ```bash
   docker stop accap-drupal accap-mysql
   ```

1. Remove the Drupal and MySQL containers:

   ```bash
   docker rm accap-drupal accap-mysql
   ```
