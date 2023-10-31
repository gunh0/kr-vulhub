# OpenSSL Heartbleed (CVE-2014-0160)

**Contributors**
-   [김민지@hzuiw33](https://github.com/hzuiw33)

<br/>

### 요약

OpenSSL 1.0에서버전 1에는 심각한 취약성(CVE-2014-0160)이 있다. 이 취약성 문제는 ssl/dl_both.c 파일에 존재한다. OpenSSL Heartbleed 모듈에는 BUG가 있다. 공격자가 사용자의 하트비트 패킷을 만족시키기 위해 특수 패킷을 구성하면. memcpy가 SSLv3를 로깅하고 데이터를 직접 출력하게 된다. 이 취약성을 통해 공격자는 64K의 openSSL 서버 메모리를 읽을 수 있다.

Heartbleed은 공격자가 다른 사람의 쿠키와 같은 민감한 정보를 대상 프로세스의 메모리 정보로 서비스할 수 있는 메모리 취약성이다.

<br/>

## 취약점 환경 구성

다음 명령어를 실행하여 OpenSSL을 실행하는 컨테이너를 빌드하고 시작한다:

```
docker compose build
docker compose up -d
```

<br/>

## 취약점 재현

- Nmap을 이용하여 OpenSSL의 취약성을 탐지한다.
- python 스크립트로 작성된 코드인 ssltest.py을 실행하여 중요한 데이터를 가져온다. (cookies도 포함될 수 있다.)

```
python3 ssltest.py your-ip
```

<br/>

## 참고 링크

- https://github.com/Threekiii/Vulhub-Reproduce/blob/master/OpenSSL%20%E5%BF%83%E8%84%8F%E5%87%BA%E8%A1%80%E6%BC%8F%E6%B4%9E%20CVE-2014-0160.md
- https://guleum-zone.tistory.com/88
- https://heartbleed.com/
- https://filippo.io/Heartbleed
