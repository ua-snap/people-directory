# IARC Directory Kiosk

## Local development instance with Docker

### Setup Docker containers

1. Install [Docker](https://www.docker.com/) if you have not already.  You'll also need [compass](http://compass-style.org/install/) to compile the stylesheets.

1. Download the [current version of Drupal](https://ftp.drupal.org/files/projects/drupal-7.56.tar.gz).

1. Grab Drupal site files and database (talk to peer to get).  We'll call those `files.bz2` and `database.bz2`.  Unpack the `database.bz2` file to yield `database.sql`.

1. Here's a list of the placeholders we'll be using, and which should each be changed in the instructions below to match your own environment & filenames:
  * **[~/directory]** - this is where we'll install all this stuff.
  * **[drupal-7.56.tar.gz]** - file name of downloade Drupal 7 core
  * **[files.bz2]** - compressed folder with all site files (photos etc)
  * **[database.sql]** - uncompressed `.sql` database


1. Clone this repository and extract the Drupal site files into their proper location.  Directory names are placeholders, use whatever is appropriate on your system.

   ```bash
   mkdir -p [~/directory] # change this folder to where you want the site to go
   cd [~/directory]

   # Copy the Drupal downloaded file to this location,
   # then unpack it
   tar --strip-components=1 -xvzf [drupal-7.56.tar.gz]

   # Clone our custom theme/etc code
   cd sites && rm -rf all
   git clone https://github.com/ua-snap/people-directory.git
   mv people-directory all

   # Fetch Bootstrap via submodule inclusion
   cd all
   git submodule init && git submodule update

   # Copy the `files.bz2` here, then extract Drupal's content files (photos, documents, etc).
   cd ../default
   tar -jxvf [files.bz2]

   # Compile the stylesheets
   cd ../all/themes/directory
   compass compile
   ```

1. Download Drupal settings file that reads MySQL host and root password from Docker environment variables.

   ```bash
   cd [~/directory]/sites/default # make sure you're in the right spot
   curl -O 'https://raw.githubusercontent.com/ua-snap/docker-drupal-settings/master/settings.php'
   ```

1. Set up persistent MySQL database container. You may need to wait 10-20 seconds after this command returns before you can connect to the MySQL server in the next step.

   ```bash
   docker run --name iarc-people-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest
   # Wait 20 seconds or so
   ```

1. Spawn temporary container that links to MySQL container and creates database.

   ```bash
   docker run -it --link iarc-people-mysql:mysql --rm mysql sh -c 'exec mysql \-h "$MYSQL_PORT_3306_TCP_ADDR" -P "$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE drupal7;"'
   ```

1. Spawn temporary container that links to MySQL container and imports database dump.

   ```bash
   docker run -i --link iarc-people-mysql:mysql --rm mysql sh -c 'exec mysql \-h "$MYSQL_PORT_3306_TCP_ADDR" -P "$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD" drupal7' < [database.sql]
   ```

1. Set up persistent Drupal container that links to MySQL container.

   ```bash
   docker run --name iarc-people-drupal -p 8080:80 --link iarc-people-mysql:mysql -v [~/directory]:/var/www/html -d drupal:7
   ```

After everything is done, you should see a valid Drupal instance at `localhost:8080`.

### Stop Docker containers

The Docker containers can be stopped at any time with the following command:

```bash
docker stop iarc-people-drupal iarc-people-mysql
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
   docker stop iarc-people-drupal iarc-people-mysql
   ```

1. Remove the Drupal and MySQL containers:

   ```bash
   docker rm iarc-people-drupal iarc-people-mysql
   ```
