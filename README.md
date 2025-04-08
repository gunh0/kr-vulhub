## 1. 환경 설정

이 취약점은 MYSQL 및 MariaDB에서 비밀번호 비교 과정에서 발생하는 문제로, 잘못된 비밀번호로도 로그인할 수 있는 문제를 유발한다.

## 1.1. Docker 환경 설정

1. **Docker Compose 사용**: docker-compose.yml 파일을 사용하여 MySQL 5.5.23 환경을 구성한다.
2. **MySQL 5.5.23 버전**: 이 버전에서만 해당 취약점이 발생한다.
3. MySQL root 계정 비밀번호는 123456으로 설정된다.
4. **포트번호**: MySQL은 3307 포트를 사용하며, Docker Compose로 컨테이너를 실행한다.

![image.png](attachment:331ffb2e-f3e9-4aa5-80bb-e6e285c81a4c:image.png)

## 1.2. MySQL 클라이언트 접속

컨테이너가 실행되면 MySQL 클라이언트를 사용하여 root 계정으로 접속한다.

![image.png](attachment:28cf78c5-e2da-4c5d-8af1-3351def0f6d3:image.png)

## 2. 취약점 재현

CVE-2012-2122 취약점은 잘못된 비밀번호를 입력했을 때 적은 확률로 로그인에 성공하는 문제다. 이 문제를 재현하기 위해 잘못된 비밀번호를 사용하여 반복 로그인 시도를 한다.

## 2.1. 반복 로그인 시도

잘못된 비밀번호를 사용하여 1000번 로그인 시도를 반복한다. 

![image.png](attachment:563282bb-b11c-49e0-834c-b951d92bbd12:image.png)

![image.png](attachment:87206296-73a2-478c-a09f-d4848e488d64:image.png)

![image.png](attachment:e8160b0d-8961-4eeb-b08f-d0d7d6ae0b6c:image.png)

로그인이 성공한 것을 볼 수 있다.
