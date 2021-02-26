FROM nginx:alpine
# Run apt-get update
RUN apk update && apk add sudo
RUN apk add git 
RUN /bin/sh -c "apk add --no-cache bash"

COPY ./App /var/www

COPY ./nginx/site.conf.template /etc/nginx/conf.d/site.conf.template

COPY ./nginx/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod 0775 /usr/local/bin/docker-entrypoint.sh
RUN chmod 775 /var/www/logs

EXPOSE 8081

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]