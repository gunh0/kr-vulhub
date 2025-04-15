# CVE-2018-17246

> [심수용 (@protruser)](https://github.com/protruser)

<br/>

### 요약

-   Kibana는 Elasticsearch에 저장된 데이터를 시각적으로 분석하고 대시보드로 보여주는 오픈소스 도구
    -   Elasticsearch는 대용량 데이터를 빠르게 검색하고 실시간 분석할 수 있는 오픈소스 검색 엔진
    -   즉, Elasticsearch에 저장된 로그, 매트릭, 이벤트 등의 데이터를 웹을 통해 여러 데이터 지표로 표현할 수 있음
-   접속이 대부분 웹 UI를 통해 이루어지기 떄문에 입력값 검증이 이루어지지 않을 경우 취약점이 발생할 수 있음
-   CVE-2018-17246 취약점은 Apache Hadoop의 YARN 웹 UI에서 발생한 Stored XSS 취약점
    -   YARN은 resource management와 job scheduling/monitoring 기능을 별도의 데몬으로 분리
    -   YARN ResourceManager의 웹 UI에서 사용자가 직접 입력한 값들이 HTML 인코딩 없이 출력됨
-   사용자가 제출한 필드에 악성 스크립트를 삽입하고, 해당 스크립트가 웹 UI에 그대로 출력되면서 스크립트 코드가 실행됨

<br/>
**참고 자료 (References):**

-   <https://nvd.nist.gov/vuln/detail/cve-2018-17246>
-   <https://hadoop.apache.org/docs/current/hadoop-yarn/hadoop-yarn-site/YARN.html>

<br/>

### 환경 구성 및 실행

<br/>

### 결과

![](result.png)

<br/>

### 정리
