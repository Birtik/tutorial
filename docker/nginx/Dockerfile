FROM nginx:1.18

COPY nginx.conf /etc/nginx/conf.d/default.conf.template

CMD /bin/bash -c "envsubst '\$CONTAINER_APP_NAME' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf && nginx -g 'daemon off;'"
