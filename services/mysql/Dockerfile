#FROM mariadb:10.3.22
FROM mysql:5.7

LABEL maintainer="Danilo Diogenes <zehdanilo@gmail.com>"

RUN ln -snf /usr/share/zoneinfo/America/Fortaleza /etc/localtime && echo America/Fortaleza > /etc/timezone && chown -R mysql:root /var/lib/mysql/

COPY my.cnf /etc/mysql/conf.d/my.cnf

RUN chmod 0444 /etc/mysql/conf.d/my.cnf

CMD [ "mysqld" ]

EXPOSE 3306