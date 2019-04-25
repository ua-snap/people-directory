# IARC People Directory

## Local development instance with Docker

### Setup Docker containers

1. Install [Docker](https://www.docker.com/) if you have not already.

1. Grab directory files and database dump, save them to your ~/Downloads folder.  These directions assume these files are named `people-directory.sql` and `files-people-directory.bz2` respectively.
1. Clone this repository and extract the Drupal files into their proper location:

   ```bash
   git clone https://github.com/ua-snap/people-directory.git
   cd people-directory

   # Put the database into a folder where it will be ingested.
   mv ~/Downloads/people-directory.sql database/

   # Add site files and proper settings for local dev.

   tar -jxvf ~/Downloads/files-people-directory.bz2 --strip-components=1 -C files/

   # Launch containers and disable IP restrictions
   # NOTE -- sometimes the name of the Docker image (people-directory_drupal_1) may be different.
   # Use `docker ps` to find the right name of the image..
   docker-compose up &

   # Compile styles
   cd themes/directory && compass compile
   ```

### Stop Docker containers

The Docker containers can be stopped at any time with the following command:

```bash
docker-compose stop
```

This is useful if you need to start the Docker containers for a different website.

### Start Docker containers

Stopped Docker containers can be started with the following command:

```bash
docker-compose up
```

### Remove Docker containers

If something goes wrong with your Docker containers and you would like to go through the setup instructions again, you will first need to remove your existing Docker containers for the directory website with the following commands.

 ```bash
 docker-compose rm
 ```
