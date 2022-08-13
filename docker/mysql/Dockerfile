FROM mysql:8.0.30

ENV MYSQL_DATABASE josmardb
ENV MYSQL_ROOT_PASSWORD root_password
ENV MYSQL_USER devuser
ENV MYSQL_PASSWORD 1234

RUN sudo mysql -u root -p josmardb < /docker-entrypoint-initdb.d/init.sql
