# CVE-2023-36664: Ghostscript Remote Code Execution

## 취약점 요약

**CVE ID:** CVE-2023-36664  
**제품:** Ghostscript (< 10.01.2)  
**취약점 유형:** Remote Code Execution (RCE)  

### 개요

본 취약점(CVE-2023-36664)은 Ghostscript가 파이프 장치(%pipe% 또는 | 접두사)에 대한 경로 권한 검증을 미흡하게 처리하여 발생하는 임의 코드 실행(RCE) 취약점임.

공격자에 의해 악의적으로 조작된 문서 파일(PS/EPS)을 시스템이 파싱하거나 처리하는 과정에서 사용자 인가 없이 임의의 시스템 명령어가 실행될 수 있음.

### [개념 설명] Ghostscript와 파이프(Pipe)의 이해

#### 1. Ghostscript란 무엇인가?.

주요 역할: 본질적으로 텍스트로 이루어진 그래픽 좌표 코드(예: "100 200 위치에 선을 그어라")를 읽어서, 우리가 눈으로 볼 수 있는 모니터 화면이나 프린터 출력용 이미지 파일로 변환해 주는 역할을 한다.

#### 2. 파이프(Pipe)란 무엇인가?

파이프는 한 애플리케이션의 출력을 다른 애플리케이션의 입력으로 연결해 소프트웨어 간에 서로 통신할 수 있도록 돕는 기능이다. 명령에서는 | 기호로 나타낸다.

예시: cat /etc/hosts | grep localhost

- cat 명령어가 뽑아낸 텍스트 전체 데이터가 파이프(|)를 타고 grep 명령어의 입력값으로 바로 전달되면서 "localhost"가 포함된 행만 필터링하게 된다.


---

## 취약점 이해

### 취약점의 근본 원인

Ghostscript는 기본적으로 안전 모드(-dSAFER)가 켜져 있으면 시스템의 민감한 파일에 접근하거나 외부 명령어를 함부로 실행하지 못하도록 파일 경로를 엄격하게 검사한다.

문제점: 공격자가 파일 경로 자리에 일반적인 파일명이 아니라 파이프 장치 접두사(%pipe% 또는 |)를 정밀하게 조작해서 밀어 넣었을 때, Ghostscript 내부의 권한 검증 로직이 이 접두사를 제대로 인지하지 못하고 "안전한 경로" 혹은 "검증 대상이 아닌 것"으로 오인하여 통과시켜 버린다.

결과: 원래는 걸러졌어야 할 위험한 명령어가 검증 루프를 허무하게 우회하게 되는 것이다.

### 취약점의 실행 메커니즘

악성 파일 주입: 공격자가 포스트스크립트(.ps/.eps) 파일 내부에 (%pipe%악성명령어) (모드) file /DCTDecode filter 같은 형태로 코드를 심어둔다.

파싱 및 오인: Ghostscript가 이 파일을 읽어 처리하는 과정에서 권한 검증 단계(문지기)를 에러 없이 통과한다.

OS Shell로 전달: 검증을 통과한 명령어 스트림이 파이프 장치 기능에 의해 리눅스의 sh나 윈도우의 cmd 같은 운영체제 내부 쉘로 그대로 전달되어 백엔드 권한으로 실행된다.

### 취약 코드 살펴보기

패치된 코드를 보면 다음 두 개의 `.c` 파일이 수정된 것을 확인할 수 있다.
 
1. `base/gpmisc.c`
2. `base/gslibctx.c`

여기서 `gpmisc.c`을 보며 취약점을 이해하면 된다.
 
![png2](png2.png)
 
이 취약점의 핵심은 `%pipe%`와 같은 특수 경로를 일반 파일 경로와 제대로 구분하지 못하고, 그대로 일반 경로로 인식해버린다는 데 있다.

## 경로 정제 함수: `gp_file_name_reduce`
 
이를 확인하기 위해 파일 경로를 정제하는 함수를 살펴볼 필요가 있다. `gpmisc.c`에서 경로를 다듬는 역할을 하는 함수는 `gp_file_name_reduce(...)`이며, 이 함수는 내부적으로 `gp_file_name_combine()`을 호출하여 그 결과를 반환한다.
 
```c
gp_file_name_reduce(const char *fname, uint flen, char *buffer, uint *blen) {
    return gp_file_name_combine(fname, flen, fname + flen, 0, false, buffer, blen);
}
```
 
`gp_file_name_combine()`은 이름 그대로, 전달받은 파일 경로에서 `./`, `//`와 같은 불필요한 상대 경로 표현을 제거해주는 함수다.
 
문제는 여기서 발생한다. 만약 이 함수에 일반적인 파일 경로가 아니라 `%pipe%`처럼 명령어 실행을 유도하는 특수 문자열이 입력되면, 함수는 이를 정상적인 경로 패턴으로 인식하지 못한 채 **아무런 처리 없이 원본 문자열을 그대로 반환**한다.
 
## 검증 로직의 부재: `gp_validate_path_len`
 
![png1](png1.png)
 
`gp_validate_path_len(...)`은 내부적으로 `gp_file_name_reduce()`를 호출해 경로를 검증하는 함수인데, 이 과정에서 입력값이 일반 파일 경로인지 `%pipe%`와 같은 명령 실행 구문인지를 구분하는 별도의 검증 로직이 존재하지 않는다.
 
결과적으로 `%pipe%`가 포함된 문자열이 아무런 필터링 없이 그대로 통과하게 되며, 이것이 이번 취약점의 근본 원인이다.

따라서 `%pipe%touch /tmp/pwned`와 같은 문자열이 파일 경로로 전달되면, 사용자는 단순히 이미지 파일을 렌더링하려 했을 뿐인데도 실제로는 touch /tmp/pwned 명령이 실행되어 /tmp/pwned 파일이 생성되는 결과로 이어진다.

### 아키텍처

```
┌─────────────────────────────────┐
│  Docker Container            │
│  (Ubuntu 22.04 + Ghostscript)   │
│                                 │
│  /home/test/               ← Working directory
│  ├── poc.py               ← PoC 생성 스크립트
│  |                              |
│  └── /var/www/html/config.php   ← 민감 정보 파일
│                                 │
│  User: test (비 root)           │
│  Ghostscript: 10.01.1 (취약)    │
└─────────────────────────────────┘
```
여기서 root 계정이 아닌 일반 계정을 선택한 이유는, 실제 운영 서버는 보안상 root 계정을 직접 사용하지 않는 것이 일반적이기 때문이다. 따라서 실제 서버가 공격받는 상황을 최대한 가깝게 재현하기 위해 일반 계정 기준으로 환경을 구성하였다.

### Docker Compose 설정

```yaml
version: '3.8'

services:
  gs-lab:
    build: .
    container_name: cve_lab
    network_mode: "host"
    environment:
      - DISPLAY=${DISPLAY}
    volumes:
      - /tmp/.X11-unix:/tmp/.X11-unix:ro
      - ./poc.py:/home/test/poc.py
    stdin_open: true
    tty: true
```
#### envirment
- `DISPLAY=${DISPLAY}`: 호스트의 X11 디스플레이 서버에 컨테이너 내 GUI 애플리케이션이 접근할 수 있도록 환경 변수를 전달한다.
#### volumes 
- `/tmp/.X11-unix:/tmp/.X11-unix:ro`: 호스트의 X11 디스플레이 서버와 컨테이너 간 통신을 위한 Unix 소켓을 마운트한다.

- `./poc.py:/home/test/poc.py`: 호스트의 로컬 PoC스크립트를 컨테이너 실행 환경으로 마운트한다.
### Dockerfile

```dockerfile
FROM ubuntu:22.04

RUN apt update && \
    apt install -y \
    wget \
    build-essential \
    gedit \
    python3 \
    sudo && \
    rm -rf /var/lib/apt/lists/*


RUN wget https://github.com/ArtifexSoftware/ghostpdl-downloads/releases/download/gs10011/ghostscript-10.01.1.tar.gz && \
    tar -xzf ghostscript-10.01.1.tar.gz

WORKDIR /ghostscript-10.01.1

RUN ./configure && \
    make && \
    make install

RUN mkdir -p /var/www/html && \
    echo "DB_PASSWORD=SuperSecret1234!!!" > /var/www/html/config.php

RUN useradd -m -s /bin/bash test

WORKDIR /home/test
USER test

CMD ["/bin/bash"]
```
#### 기본 apt install

기본적인 poc 환경을 구성하기 위해서는 Ghostscript 취약 버전을 가져오기 위한 wget, tar.gz 압축을 푼 이후 source를 설치하기 위한 build-essential, .ps 실행시 악의적으로 띄울 메모장(gedit) 등이 있다.

#### Ghostscript 10.01.1 설치
 
![png3](png3.png)
![png4](png4.png)
Ghostscript 취약 버전 10.01.1은 git에서 source를 가져와서 설치하였다. `RUN ./configure && \ make && \ make install`는 source 다운받은 폴더에 사용법이 적혀있어 따라서 설치한 것이다.

#### 탈취할 정보
보통 /var/www/html/config.php에는 웹 애플리케이션의 핵심 설정값들이 들어간다. 예를 들면 데이터베이스 이름, 비밀번호
그래서 임의로 DB 비밀번호를 탈취한다고 생각하고 config.php 파일을 생성한다.

#### 일반 계정 생성
root 계정이 아닌 일반 계정 test로 poc를 할 예정이기 때문에 새로운 user를 생성해준다.

---

## 재현 절차

### 1. 환경 구축

```bash
# Docker 이미지 빌드
docker compose -f docker-compose.yml up -d

# 컨테이너 실행
docker exec -it cve_lab bash
```

### 2. PoC 파일 생성

컨테이너 내에서:
![png5](png5.png)

```bash
python3 poc.py -p "gedit /var/www/html/config.php" -m r -f test
```


**생성 결과:**
![png6](png6.png)

입력했던 악성 경로가 .ps 안에 잘 들어가 있는 것을 볼 수 있다.

### 3. Ghostscript로 악의적 파일 처리

```bash
gs -dNOSAFER test.ps
```
![png7](png7.png)
### 4. 결과 확인

gedit가 자동으로 열리며 `/var/www/html/config.php` 내용이 노출된다.

```
DB_PASSWORD=SuperSecret1234!!!
```

---


## 대응 방안

![png8](png8.png)


### 1. 보안 패치 적용 및 업데이트
공식적으로 취약점이 수정된 Ghostscript 10.01.2 이상 최신 버전으로 업데이트를 수행해야 한다.

### 2. 패치 소스코드 분석을 통한 방어 메커니즘 이해
공식 패치에서는 `gp_validate_path_len()` 함수 안에서 `gp_file_name_reduce()`를 호출하기 전에 검증을 하는 로직이 추가되었다.

파이프 특수 문자열 예외 처리 및 차단 강제: 입력 경로의 접두사가 %pipe% 또는 | 기호로 시작하는 경우를 별도로 완벽하게 감지하는 분기문이 추가되었다.


공식 패치 버전: https://github.com/ArtifexSoftware/ghostpdl/commit/5f56c6f6f989816fc9cc671116740acecbed5b6c






## 참고 자료

- **CVE 공식 페이지:** https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2023-36664
- **CVE 이해:** https://www.vicarius.io/vsociety/posts/cve-2023-36664-command-injection-with-ghostscript
- **PoC 재현:** https://github.com/jakabakos/CVE-2023-36664-Ghostscript-command-injection

---

