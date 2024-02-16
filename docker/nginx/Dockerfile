FROM nginx:latest

RUN rm /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

COPY ./api/public /var/www/public
