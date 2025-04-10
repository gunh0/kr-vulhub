# alpine:3.19.1
ARG REPO=eclipse-temurin:17-jre-alpine
ARG CRUSHFTP_VERSION=10.8.0_4
ARG SOURCE=netlah/crushftp-source:$CRUSHFTP_VERSION
FROM $REPO AS base

FROM $SOURCE AS src

FROM base as build
ARG STARTUP=startup.sh
ADD $STARTUP /src/var/opt/startup.sh
ADD service.sh /src/var/opt/service.sh
WORKDIR /src/tmp
COPY --from=src src/crushftp.zip .
ARG CRUSHFTP_VERSION=10.8.0_4
RUN date '+%F %T %Z'>>__builddate.txt \
  && echo "CrushFTP Version = $CRUSHFTP_VERSION">>__version.txt \
  && echo "Build Date Time  = `cat __builddate.txt`">>__version.txt \
  && echo "Java             = $JAVA_VERSION">>__version.txt

FROM base AS deps
RUN apk upgrade --no-cache \
  && apk --no-cache add bash bash-completion bash-doc ca-certificates curl wget \
  && update-ca-certificates

FROM deps as final
COPY --from=build /src /
WORKDIR /var/opt
RUN ln -s /var/opt/service.sh /usr/sbin/service \
  && chmod +x service.sh startup.sh
ENTRYPOINT [ "/bin/bash", "-c", "/var/opt/startup.sh" ]

ARG CRUSHFTP_VERSION=10.8.0_4
ENV ADMIN_USER=crushadmin \
  ADMIN_PASSWORD= \
  WEB_PROTOCOL=http \
  WEB_PORT=8080\
  SOURCE_ZIP=/tmp/crushftp.zip \
  CRUSHFTP_VERSION=$CRUSHFTP_VERSION

HEALTHCHECK --interval=1m --timeout=3s \
  CMD curl --silent --fail ${WEB_PROTOCOL}://localhost:${WEB_PORT}/favicon.ico -H 'Connection: close' > /dev/null || exit 1

VOLUME [ "/var/opt/crushftp" ]
# remove range of Passive FTP 20000-20100 from declare
EXPOSE 21 2222 443 8080 9090
LABEL org.opencontainers.image.authors="https://github.com/NetLah" \
  org.opencontainers.image.url="https://hub.docker.com/r/netlah/crushftp" \
  org.opencontainers.image.source="https://github.com/NetLah/docker-crushftp" \
  org.opencontainers.image.description="CrushFTP 10 server on Alpine Linux 3.19 and OpenJDK 17." \
  org.opencontainers.image.version="$CRUSHFTP_VERSION"\
  version="$CRUSHFTP_VERSION"
