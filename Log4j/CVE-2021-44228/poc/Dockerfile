# Stage 1: compile Exploit.java
FROM eclipse-temurin:8-jdk AS build
WORKDIR /build
COPY Exploit.java .
RUN javac Exploit.java

# Stage 2: serve compiled class over HTTP
FROM python:3.11-alpine
WORKDIR /www
COPY --from=build /build/Exploit.class .
EXPOSE 8000
CMD ["python3", "-m", "http.server", "8000"]
