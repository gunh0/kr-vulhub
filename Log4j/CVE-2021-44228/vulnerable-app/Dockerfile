# Stage 1: build
FROM maven:3.8.6-eclipse-temurin-8 AS build
WORKDIR /app
COPY pom.xml .
COPY src ./src
RUN mvn -q -B clean package

# Stage 2: run
FROM eclipse-temurin:8-jre
WORKDIR /app
COPY --from=build /app/target/vulnerable-app.jar app.jar
EXPOSE 8080
ENTRYPOINT ["java", "-jar", "app.jar"]
