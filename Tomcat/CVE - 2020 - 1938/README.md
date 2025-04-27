# CVE-2020-1938: Apache Tomcat Ghostcat PoC Report

> 화이트햇 스쿨 3기 18반 김채은  
 
 **GitHub**: https://github.com/chae-chae123/kr-vulhub

---

## 1. 개요

Apache Tomcat은 Java 기반의 서블릿 컨테이너이자 웹 서버로, HTTP뿐 아니라 **AJP (Apache JServ Protocol)** 를 통해 프론트엔드 웹 서버(Apache HTTPD 등)와 통신할 수 있습니다.  
**CVE-2020-1938**(Ghostcat) 취약점은 AJP 커넥터가 외부에 노출된 상태에서 인증 절차 없이 임의 파일을 읽거나 include 할 수 있게 하는 치명적 보안 결함입니다.

---

## 2. 취약점 요약

- **취약점 명**: CVE-2020-1938 (Ghostcat)  
- **취약점 유형**: AJP 프로토콜 원격 파일 읽기/포함(Remote File Read/Include)  
- **영향 버전**:  
  - Tomcat ≤ 9.0.30  
  - Tomcat ≤ 8.5.50  
  - Tomcat ≤ 7.0.99  
- **원인**:  
  - AJP 커넥터(`port=8009`) 요청 파라미터를 적절히 검증하지 않고  
  - `javax.servlet.include.request_uri` 등의 include 속성을 통해 내부 경로를 지정할 수 있음  
- **공격 효과**:  
  - `/WEB-INF/web.xml` 등 웹앱 설정 파일 및 민감 데이터 노출  
  - 파일 업로드 기능이 있을 경우 원격 코드 실행 가능성

---

## 3. 환경 구성

### 3.1 디렉터리 구조
```
Tomcat/CVE-2020-1938/ 
	├── Dockerfile 
	├── docker-compose.yml 
	├── poc.py 
	└── README.md
```
### 3.2 Dockerfile
```dockerfile
FROM tomcat:9.0.30

# 1) 내장된 server.xml 에서 AJP 커넥터 활성화
RUN sed -i \
    -e 's|<!--\(.*AJP/1.3.*\)-->|<Connector port="8009" protocol="AJP/1.3" redirectPort="8443" address="0.0.0.0" />|' \
    /usr/local/tomcat/conf/server.xml

# 2) 기본 웹앱 복구 (ROOT)
RUN cp -r /usr/local/tomcat/webapps.dist/ROOT /usr/local/tomcat/webapps/

# 3) 외부 노출 포트
EXPOSE 8080 8009

CMD ["catalina.sh", "run"]
```
### 3.3 docker-copmpose.yml
``` yaml
services:
  tomcat:
    build: .
    ports:
      - "8080:8080"   # HTTP
      - "8009:8009"   # AJP
```

---

## 4. PoC 진행

### 4.1 컨테이너 빌드·실행

```bash
cd Tomcat/CVE-2020-1938 
docker-compose up --build -d
```
### 4.2 페이지 확인
``` bash
open http://localhost:8080
```
![[Pasted image 20250427160829.png]]
### 4.3 web.xml 읽기 
``` bash
python3 poc.py 127.0.0.1 -p 8009 -f /WEB-INF/web.xml > web.xml
cat web.xml
```
![[Pasted image 20250427160921.png]]

---

## 5. 결론 
poc.py 파일을 통하여 `/WEB-INF/web.xml` 파일을 덤프하였다. 이번 PoC 실습으로 Ghostcat 취약점이 Tomcat 운영환경에서 얼마나 치명적인지 확인 할 수 있었다.
AJP 커넥터 노출만으로도 외부에서 내부 설정 파일을 쉽게 획득할 수 있으며, 획득한 DB 크리덴셜, API 키, 세션 정보등을 기반으로 조직 내부 네트워크 전체가 위험에 노출될 수 있었음을 알 수 있었다.
이에 대한 AJP 커넥터 비활성화, Tomcat 버전 업그레이드 등의 보안 조치가 반드시 필요한것을 알게되었다.

---

### 참고자료(References):
[https://github.com/00theway/Ghostcat-CNVD-2020-10487](https://github.com/00theway/Ghostcat-CNVD-2020-10487)
