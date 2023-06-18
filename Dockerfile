FROM ubuntu:22.04

COPY zeroc.gpg /etc/apt/keyrings

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Americas/Detroit

RUN echo  "deb [signed-by=/etc/apt/keyrings/zeroc.gpg] https://download.zeroc.com/ice/3.7/ubuntu22.04 stable main" > /etc/apt/sources.list.d/zeroc-ice-3.7.list && \
	apt -y update && \
	apt -y install ca-certificates apache2 && \
	apt -y update && \
	apt -y install php-zeroc-ice libapache2-mod-php nano && \
	rm /var/www/html/index.html
	

COPY phpMumbleAdmin /var/www/html
RUN chmod 777 /var/www/html/program/cache/sessions/ /var/www/html/cache/ /var/www/html/config/ /var/www/html/logs/ /var/www/html/program/cache/

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
