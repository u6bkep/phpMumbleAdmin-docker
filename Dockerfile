#by u6bkep (https://github.com/u6bkep/phpMumbleAdmin-docker)

FROM ubuntu:22.04

COPY zeroc.gpg /etc/apt/keyrings

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Americas/Detroit

RUN echo  "deb [signed-by=/etc/apt/keyrings/zeroc.gpg] https://download.zeroc.com/ice/3.7/ubuntu22.04 stable main" > /etc/apt/sources.list.d/zeroc-ice-3.7.list && \
	apt-get -y update && \
	apt-get -y install ca-certificates apache2 && \
	apt-get -y update && \
	apt-get -y install php-zeroc-ice libapache2-mod-php && \
	rm /var/www/html/index.html && \
	rm -rf /var/lib/apt/lists/*

COPY phpMumbleAdmin /var/www/html
COPY Murmur-1.4.0.php /var/www/html/slicesPhp
RUN chmod 777 /var/www/html/program/cache/sessions/ /var/www/html/cache/ /var/www/html/config/ /var/www/html/logs/ /var/www/html/program/cache/
VOLUME [ "/var/www/html" ]
EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
