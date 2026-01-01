# Korean Vulhub (한글판)

![logo](./README.assets/logo.svg)

취약한 도커 환경을 구축하여, 이해도를 높이고, 실습을 통해 보안 기술을 익히는 것을 목표로 합니다.

[Vulhub](https://github.com/vulhub/vulhub) (<https://vulhub.org/>) 을 참고하여, 다양한 컨테이너 기반의 취약한 환경을 구축합니다.

<br/>

### Table of Contents

| Category | Vulnerability | Description | Contributor | Verification | Report Score | Risk Score |
| --- | --- | --- | --- | --- | --- | --- |
| Django | [CVE-2021-35042](./Django/CVE-2021-35042/README.md) | QuerySet.order_by() SQL Injection | [@sj1226m](https://github.com/sj1226m) | Not revalidated | TBD | TBD |
| Express | [CVE-2024-29041](./Express/CVE-2024-29041/README.md) | Express 오픈 리다이렉트 취약점 | [@j93es](https://github.com/j93es) | Not revalidated | TBD | TBD |
| Flask | [SSTI](./Flask/SSTI/README.md) | Server Side Template Injection | [@positiveWand](https://github.com/positiveWand) | Not revalidated | TBD | TBD |
| MySQL | [CVE-2012-2122](./MySQL/CVE-2012-2122/README.md) | MySQL Authentication Bypass | [@baethwjd2](https://github.com/baethwjd2) | Not revalidated | TBD | TBD |
| Next.js | [CVE-2025-29927](./Next.js/CVE-2025-29927/README.md) | Next.js 미들웨어 인가 우회 | [@idealinsane](https://github.com/idealinsane) | Not revalidated | TBD | TBD |
| Nginx | [CVE-2017-7529](./nginx/CVE-2017-7529/README.md) | Nginx Integer Overflow Vulnerability | [@c0dep1ayer](https://github.com/c0dep1ayer) | Not revalidated | TBD | TBD |
| Spring | [CVE-2022-22963](./Spring/CVE-2022-22963/README.md) | Spring Cloud Function SpEL 코드 주입 | [@foskingson](https://github.com/foskingson) | Not revalidated | TBD | TBD |
| Spring | [CVE-2022-22978](./Spring/CVE-2022-22978/README.md) | Spring Security Authorization Bypass in RegexRequestMatcher | [@sub0810](https://github.com/sub0810) | Not revalidated | TBD | TBD |

<br/>

### Report Evaluation

각 보고서는 취약점 자체의 위험도와 보고서 완성도를 분리해 평가합니다. 점수는 Docker 환경과 제출된 PoC를 재검증한 뒤 0.0에서 10.0 사이의 값으로 기록합니다.

- 보고서 완성도: 환경 구성, 취약 조건, 재현 절차, PoC 코드, 실행 결과, 대응 방안이 명확한지 평가합니다.
- 취약점 위험도: 인증 필요 여부, 원격 악용 가능성, 영향 범위, PoC 및 Docker 환경에서 확인되는 실제 동작을 기준으로 CVSS처럼 0.0에서 10.0 사이로 평가합니다.
