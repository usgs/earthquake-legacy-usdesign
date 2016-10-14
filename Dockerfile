## Docker file to build app as container

FROM php:5.6-apache
MAINTAINER "Edward Hunter" <ehunter@usgs.gov>
LABEL dockerfile_version="v0.1.1"

# install JRE (headless)
RUN apt-key update -y && \
    apt-get update -y && \
    apt-get install -y --no-install-recommends \
        openjdk-7-jre-headless && \
    apt-get clean

# configure Cairo
RUN apt-get install -y libcairo2-dev && \
    pecl install channel://pecl.php.net/cairo-0.3.2 && \
    echo 'extension=cairo.so' > /usr/local/etc/php/conf.d/cairo.ini

# copy application (ignores set in .dockerignore)
COPY . /hazdev-project

WORKDIR /hazdev-project
EXPOSE 8110
