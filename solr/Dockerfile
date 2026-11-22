FROM solr:8.1.1
USER root

RUN cp /opt/solr/dist/solr-dataimporthandler-*.jar \
    /opt/solr/server/solr-webapp/webapp/WEB-INF/lib
#dataimporthandler 관련파일 실제 작동할수있는 디렉토리로 이동

# 볼륨 경로는 재실행시 이미지대로 불러와지지 않을수도있어 볼륨이 아닌 경로로 설정. 
#최초실행은 문제없으나 한번 실행후 재실행시 오류 발생가능하며 이를 해결하기위해 경로 변경
ENV SOLR_HOME=/opt/solr/server/solr

RUN mkdir -p /opt/solr/server/solr/myCore/conf && chown -R solr:solr /opt/solr/server/solr/myCore
#myCore 코어 디렉토리 생성

USER solr

COPY --chown=solr:solr solrconfig.xml /opt/solr/server/solr/myCore/conf/
COPY --chown=solr:solr managed-schema /opt/solr/server/solr/myCore/conf/
#설정파일 복사

RUN echo "name=myCore" > /opt/solr/server/solr/myCore/core.properties
