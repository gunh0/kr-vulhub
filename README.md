# Korean Vulhub (한글판)

![logo](./README.assets/logo.svg)

취약한 도커 환경을 구축하여, 이해도를 높이고, 실습을 통해 보안 기술을 익히는 것을 목표로 합니다.

[Vulhub](https://github.com/vulhub/vulhub) (<https://vulhub.org/>) 을 참고하여, 다양한 컨테이너 기반의 취약한 환경을 구축합니다.

<br/>

### Table of Contents

- **ActiveMQ** — Java 기반 오픈소스 메시지 브로커
    - [CVE-2016-3088](./ActiveMQ/CVE-2016-3088/README.md) — ActiveMQ fileserver 임의 파일 쓰기 → RCE
        - Contributor: [@Roronoawjd](https://github.com/Roronoawjd) | Risk Score: 9.8 (Reproducibility: 75%)

- **CouchDB** — Erlang 기반 오픈소스 문서 지향 NoSQL 데이터베이스
    - [CVE-2017-12635](./CouchDB/CVE-2017-12635/README.md) — CouchDB JSON 파서 불일치를 이용한 원격 권한 상승
        - Contributor: [@jason1343](https://github.com/jason1343) | Risk Score: 9.8 (Reproducibility: 70%)

- **Django** — Python 기반 웹 프레임워크
    - [CVE-2021-35042](./Django/CVE-2021-35042/README.md) — QuerySet.order_by() SQL Injection
        - Contributor: [@sj1226m](https://github.com/sj1226m) | Risk Score: 7.5 (Reproducibility: 70%)
    - [CVE-2022-34265](./Django/CVE-2022-34265/README.ko-kr.md) — Trunc()/Extract() SQL Injection
        - Contributor: [@woohyun212](https://github.com/woohyun212) | Risk Score: 9.8 (Reproducibility: 85%)
    - [CVE-2022-34265 (2)](./Django/CVE-2022-34265_2/README.md) — Trunc()/Extract() SQL Injection
        - Contributor: [@KMINGON](https://github.com/KMINGON) | Risk Score: 9.8 (Reproducibility: 80%)

- **Express** — Node.js 웹 프레임워크
    - [CVE-2024-29041](./Express/CVE-2024-29041/README.md) — Express 오픈 리다이렉트 취약점
        - Contributor: [@j93es](https://github.com/j93es) | Risk Score: 6.1 (Reproducibility: 75%)

- **Elfinder** — PHP 기반 웹 파일 관리자
    - [CVE-2021-32682](./Elfinder/CVE-2021-32682/README.md) — ZIP 인수 삽입을 통한 원격 코드 실행
        - Contributor: [@Tjdmin1](https://github.com/Tjdmin1) | Risk Score: 9.8 (Reproducibility: 75%)

- **Flask** — Python 경량 웹 프레임워크
    - [SSTI](./Flask/SSTI/README.md) — Server Side Template Injection
        - Contributor: [@positiveWand](https://github.com/positiveWand) | Risk Score: 9.0 (Reproducibility: 75%)

- **Gradio** — Python 기반 ML 모델 웹 인터페이스 라이브러리
    - [CVE-2023-51449](./Gradio/CVE-2023-51449/README.md) — /file 엔드포인트 디렉터리 트래버설
        - Contributor: [@annseojin](https://github.com/annseojin) | Risk Score: 7.5 (Reproducibility: 80%)

- **GeoServer** — Java 기반 오픈소스 공간 데이터 서버
    - [CVE-2023-25157](./GeoServer/CVE-2023-25157/README.md) — GeoServer OGC 필터 SQL 인젝션
        - Contributor: [@djadydwls0720](https://github.com/djadydwls0720) | Risk Score: 9.8 (Reproducibility: 65%)
    - [CVE-2023-25157 (2)](./GeoServer/CVE-2023-25157_2/README.md) — GeoServer OGC 필터 SQL 인젝션
        - Contributor: [@moooooji](https://github.com/moooooji) | Risk Score: 9.8 (Reproducibility: 60%)

- **HugeGraph** — Apache 기반 오픈소스 그래프 데이터베이스
    - [CVE-2024-43441](./HugeGraph/CVE-2024-43441/README.md) — JWT 비밀 키 하드코딩으로 인한 인증 우회
        - Contributor: [@HanTul](https://github.com/HanTul) | Risk Score: 9.8 (Reproducibility: 85%)

- **Librsvg** — GNOME SVG 렌더링 라이브러리
    - [CVE-2023-38633](./Librsvg/CVE-2023-38633/README.md) — librsvg xi:include 디렉터리 탐색 파일 읽기
        - Contributor: [@EL55](https://github.com/EL55) | Risk Score: 7.5 (Reproducibility: 80%)

- **Libssh** — SSHv2 프로토콜 C 라이브러리
    - [CVE-2018-10933](./Libssh/CVE-2018-10933/README.md) — libssh 서버 state machine 인증 우회
        - Contributor: [@hhtboy](https://github.com/hhtboy) | Risk Score: 9.8 (Reproducibility: 75%)

- **MongoExpress** — MongoDB 웹 기반 관리 인터페이스
    - [CVE-2019-10758](./MongoExpress/CVE-2019-10758/README.md) — mongo-express 원격 코드 실행
        - Contributor: [@ilohas0021](https://github.com/ilohas0021) | Risk Score: 9.8 (Reproducibility: 80%)

- **MySQL** — 관계형 데이터베이스
    - [CVE-2012-2122](./MySQL/CVE-2012-2122/README.md) — MySQL Authentication Bypass
        - Contributor: [@baethwjd2](https://github.com/baethwjd2) | Risk Score: 7.0 (Reproducibility: 70%)

- **Next.js** — React 기반 풀스택 웹 프레임워크
    - [CVE-2025-29927](./Next.js/CVE-2025-29927/README.md) — Next.js 미들웨어 인가 우회
        - Contributor: [@idealinsane](https://github.com/idealinsane) | Risk Score: 9.1 (Reproducibility: 85%)

- **Nginx** — 고성능 웹 서버 / 리버스 프록시
    - [CVE-2017-7529](./Nginx/CVE-2017-7529/README.md) — Nginx Integer Overflow Vulnerability
        - Contributor: [@c0dep1ayer](https://github.com/c0dep1ayer) | Risk Score: 7.5 (Reproducibility: 75%)

- **Node** — JavaScript 런타임 환경
    - [CVE-2017-14849](./Node/CVE-2017-14849/README.md) — Node.js path.normalize() 디렉터리 탐색 취약점
        - Contributor: [@ssongk](https://github.com/ssongk) | Risk Score: 7.5 (Reproducibility: 75%)
    - [CVE-2017-14849 (2)](./Node/CVE-2017-14849_2/README.md) — Node.js path.normalize() 디렉터리 탐색 취약점
        - Contributor: [@junwonheo](https://github.com/junwonheo) | Risk Score: 7.5 (Reproducibility: 65%)

- **PHP** — 서버 사이드 스크립트 언어
    - [CVE-2012-1823](./PHP/CVE-2012-1823/README.md) — php-cgi 인자 주입을 통한 원격 코드 실행
        - Contributor: [@kty121](https://github.com/kty121) | Risk Score: 9.8 (Reproducibility: 80%)

- **Python** — Python 런타임 환경
    - [CVE-2017-8291](./Python/CVE-2017-8291/README.md) — PIL(Pillow) GhostScript EPS 처리 RCE
        - Contributor: [@wjdgnsdl213](https://github.com/wjdgnsdl213) | Risk Score: 9.8 (Reproducibility: 75%)
    - [CVE-2023-24329](./Python/CVE-2023-24329/README.md) — urllib.parse URL 블록리스트 우회 및 SSRF
        - Contributor: [@Opal1031](https://github.com/Opal1031) | Risk Score: 7.5 (Reproducibility: 90%)

- **Redis** — 인메모리 키-값 데이터베이스
    - [CVE-2022-0543](./Redis/CVE-2022-0543/README.md) — Lua 샌드박스 탈출을 통한 원격 코드 실행
        - Contributor: [@yeo0n](https://github.com/yeo0n) | Risk Score: 10.0 (Reproducibility: 65%)

- **Spring** — Java 엔터프라이즈 웹 프레임워크
    - [CVE-2022-22963](./Spring/CVE-2022-22963/README.md) — Spring Cloud Function SpEL 코드 주입
        - Contributor: [@foskingson](https://github.com/foskingson) | Risk Score: 9.8 (Reproducibility: 75%)
    - [CVE-2022-22965](./Spring/CVE-2022-22965/README.md) — Spring Framework RCE via Data Binding (Spring4Shell)
        - Contributor: [@ddddabi](https://github.com/ddddabi) | Risk Score: 9.8 (Reproducibility: 70%)
    - [CVE-2022-22978](./Spring/CVE-2022-22978/README.md) — Spring Security Authorization Bypass in RegexRequestMatcher
        - Contributor: [@sub0810](https://github.com/sub0810) | Risk Score: 9.8 (Reproducibility: 80%)

- **Struts2** — Java 기반 MVC 웹 프레임워크
    - [CVE-2018-11776](./Struts2/CVE-2018-11776/README.md) — Struts2 S2-057 URL 매핑 OGNL 표현식 주입 RCE
        - Contributor: [@ye11oc4t](https://github.com/ye11oc4t) | Risk Score: 8.1 (Reproducibility: 80%)
    - [CVE-2019-0230](./Struts2/CVE-2019-0230/README.md) — Struts2 S2-059 OGNL 표현식 주입 RCE
        - Contributor: [@hy30nq](https://github.com/hy30nq) | Risk Score: 9.8 (Reproducibility: 80%)

- **Tiki Wiki** — PHP 기반 오픈소스 CMS / Wiki
    - [CVE-2020-15906](./TikiWiki/CVE-2020-15906/README.md) — TikiWiki CMS Authentication Bypass → RCE
        - Contributor: [@haijun9](https://github.com/haijun9) | Risk Score: 8.8 (Reproducibility: 60%)

- **Tomcat** — Java 기반 오픈소스 웹 애플리케이션 서버
    - [CVE-2020-1938](./Tomcat/CVE-2020-1938/README.md) — Apache Tomcat AJP 파일 읽기 (Ghostcat)
        - Contributor: [@mythofsummer](https://github.com/mythofsummer) | Risk Score: 9.8 (Reproducibility: 70%)

<br/>

### Report Evaluation

각 보고서는 취약점 자체의 위험도와 Report Reliability를 분리해 평가합니다. Docker 환경과 제출된 PoC를 재검증한 뒤 기록합니다.

- Reproducibility: 제출된 환경과 PoC를 그대로 따랐을 때 재현 가능한 정도를 0%에서 100%로 표현합니다. 환경 구성, 취약 조건, 재현 절차, PoC 코드, 실행 결과, 대응 방안의 명확성을 기준으로 평가합니다.
- Risk Score: 인증 필요 여부, 원격 악용 가능성, 영향 범위, PoC 및 Docker 환경에서 확인되는 실제 동작을 기준으로 CVSS처럼 0.0에서 10.0 사이로 평가합니다.
