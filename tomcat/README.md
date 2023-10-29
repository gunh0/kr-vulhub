# CVE-2020-1938

**Contributors**

-   [김수민(@kimbreathe)](https://github.com/kimbreathe)

<br/>

### 요약

-   CVE-2020-1938은 Apache Tomcat의 AJP 포트가 열려있을 때 발생하는 취약점.
-   Tomcat이 AJP Request를 처리할 때 WAS에서는 org.apache.coyote.ajp.AjpProcessor.java로 데이터를 처리하는데, 이 때 검증 없이 Request를 처리하여 취약점 발생.
-   공격자는 인증 없이 웹 서버의 웹 루트에 존재하는 파일을 읽거나 실행하거나 다운받을 수 있음.

References :
- https://secuhh.tistory.com/24

<br/>

### 환경 구성 및 실행

-   `docker compose up -d` 명령어로 로컬의 Apache Tomcat 9.0.30을 실행.
-   `http://your-ip:8080/`를 방문하면 Tomcat의 예시 페이지를 볼 수 있음. 이 페이지는 AJP 포트인 8009가 열려있음.

<br/>

### 결과


<br/>

### 정리


-  이 취약점은 공격자가 Tomcat 서버에서 실행중인 JAVA 코드에 액세스하여 디렉토리 경로를 조작하거나 원격 코드를 실행하는 등 웹 쉘로 발생할 수 있는 취약점의 위험성과 동일함. 취약점이 패치된 최신 버전으로 업데이트해야하며, 필요한 경우 AJP 포트에 대한 외부 액세스를 제한하는 것이 중요함.
