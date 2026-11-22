FROM httpd:2.4.49

# 1. RCE 연계를 위해 cgid 및 cgi 모듈 활성화 (mpm_event 멀티스레드 환경 대응)
RUN sed -i 's/#LoadModule cgid_module/LoadModule cgid_module/g' /usr/local/apache2/conf/httpd.conf
RUN sed -i 's/#LoadModule cgi_module/LoadModule cgi_module/g' /usr/local/apache2/conf/httpd.conf

# 2. Path Traversal이 정상 작동하도록 기본 파일 시스템 권한을 granted로 전면 개방
RUN sed -i 's/Require all denied/Require all granted/g' /usr/local/apache2/conf/httpd.conf