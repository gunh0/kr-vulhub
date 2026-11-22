FROM httpd:2.4.49

# mod_cgid 활성화 (RCE 재현 조건)
RUN sed -i \
    -e 's/^#LoadModule cgid_module modules\/mod_cgid.so/LoadModule cgid_module modules\/mod_cgid.so/' \
    /usr/local/apache2/conf/httpd.conf

# CVE-2021-41773의 공식 문서화된 전제 조건: 루트 디렉터리에
# "require all denied"가 적용되어 있지 않아야 경로 순회가 성공한다.
# (Apache 공식 보안 권고문 명시 사항)
RUN sed -i \
    -e 's/Require all denied/Require all granted/' \
    /usr/local/apache2/conf/httpd.conf

COPY cgi-bin/test.cgi /usr/local/apache2/cgi-bin/test.cgi
RUN chmod +x /usr/local/apache2/cgi-bin/test.cgi

EXPOSE 80
