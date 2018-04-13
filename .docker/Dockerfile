FROM drupal:7

WORKDIR /var/www/html

RUN set -ex; \
  apt-get update; \
  apt-get install -y --no-install-recommends git ruby ruby-dev;

# https://stackoverflow.com/questions/39269285/compass-provision-failing-with-docker-compose-up
RUN /bin/bash -l -c "gem install --no-rdoc --no-ri sass -v 3.4.22"
RUN /bin/bash -l -c "gem install --no-rdoc --no-ri compass"

RUN rm -rf sites/all \
  && cd sites \
  && git clone https://github.com/ua-snap/people-directory.git \
  && mv people-directory all \
  && cd all \
  && git submodule init && git submodule update

RUN cd sites/all/themes/directory \
  && compass compile

COPY .docker/settings.php sites/default

RUN chown -R www-data:www-data sites modules themes

# vim:set ft=dockerfile:
