FROM ubuntu:20.04
ENV DEBIAN_FRONTEND=noninteractive

# 필수 패키지 및 아파치 웹 서버 설치
RUN apt-get update && apt-get install -y wget build-essential apache2 && apt-get clean

# 취약점이 존재하는 순수 GNU Bash 4.3 소스코드 다운로드 및 컴파일
WORKDIR /usr/src
RUN wget http://ftp.gnu.org/gnu/bash/bash-4.3.tar.gz && \
    tar xzf bash-4.3.tar.gz && \
    cd bash-4.3 && \
    ./configure && \
    make && \
    make install

# 시스템의 기본 쉘을 방금 컴파일한 취약한 Bash로 교체
RUN mv /bin/bash /bin/bash.bak && ln -s /usr/local/bin/bash /bin/bash

# [핵심 수정] 우분투 아파치 환경에 맞는 cgi 및 cgid 모듈 동시 활성화
RUN a2enmod cgi cgid

# [핵심 수정] 기본 설정과 충돌 없는 독자적인 /var/www/shock 경로 생성 및 스크립트 배치
RUN mkdir -p /var/www/shock
RUN echo '#!/bin/bash\necho "Content-type: text/html"\necho ""\necho "<h1>Shellshock Vulnerable Lab</h1>"' > /var/www/shock/victim.cgi
RUN chmod +x /var/www/shock/victim.cgi

# [핵심 수정] 아파치 메인 설정 파일에 우리만의 /shock/ 라우팅 규칙과 실행 권한을 강제로 주입
RUN echo "ScriptAlias /shock/ /var/www/shock/" >> /etc/apache2/apache2.conf
RUN echo "<Directory /var/www/shock/>" >> /etc/apache2/apache2.conf
RUN echo "    Options +ExecCGI" >> /etc/apache2/apache2.conf
RUN echo "    AddHandler cgi-script .cgi" >> /etc/apache2/apache2.conf
RUN echo "    Require all granted" >> /etc/apache2/apache2.conf
RUN echo "</Directory>" >> /etc/apache2/apache2.conf

EXPOSE 80
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]