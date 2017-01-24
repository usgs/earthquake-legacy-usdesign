## Docker file to build app as container

FROM usgs/hazdev-base-images:php
MAINTAINER "Edward Hunter" <ehunter@usgs.gov>
LABEL dockerfile_version="v0.1.3"

# install JRE (headless) and Cairo
RUN yum install -y \
		cairo-devel \
		java-1.7.0-openjdk-headless \
		&& \
	yum clean all && \
	pecl channel-update pecl.php.net && \
	printf "\n" | pecl install channel://pecl.php.net/cairo-0.3.2 && \
	printf "; Enable Cairo extension module\nextension=cairo.so" > /etc/php.d/cairo.ini


# copy application (ignores set in .dockerignore)
COPY html/. /var/www/html/

WORKDIR /var/www/html/
EXPOSE 8110
