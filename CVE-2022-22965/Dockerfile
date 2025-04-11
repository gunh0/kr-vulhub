FROM maven:3.8.4-eclipse-temurin-17 as builder
ARG MVN_ARGS
ADD ./src/ /app/src/
ADD ./pom-core.xml /app/pom.xml
WORKDIR /app
RUN --mount=type=cache,target=/root/.m2,rw mvn --batch-mode clean package ${MVN_ARGS}

FROM lunasec/tomcat-9.0.59-jdk11
COPY --from=builder /app/target/*.war /usr/local/tomcat/webapps/
EXPOSE 8080
CMD ["catalina.sh", "run"]