ARG FROM_IMAGE=usgs/httpd-php:latest

FROM ${FROM_IMAGE}
LABEL maintainer="Eric Martinez<emartinez@usgs.gov>" \
      dockerfile_version="v0.1.6"

# install JRE (headless) and Cairo
RUN yum install -y \
    php55w-devel \
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
