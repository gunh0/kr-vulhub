FROM tomcat:9.0.30

# AJP 커넥터 활성화
RUN sed -i \
    -e 's|<!--\(.*AJP/1.3.*\)-->|<Connector port="8009" protocol="AJP/1.3" redirectPort="8443" address="0.0.0.0" />|' \
    /usr/local/tomcat/conf/server.xml

EXPOSE 8080 8009

# 기본 ROOT 웹앱 복구
RUN cp -r /usr/local/tomcat/webapps.dist/ROOT /usr/local/tomcat/webapps/

CMD ["catalina.sh", "run"]

