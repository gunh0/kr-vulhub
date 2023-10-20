# CVE-2014-6271

#### Contributor

배송현(@bshyuunn)

### 요약

해당 취약점은 bash의 환경변수를 설정할 때 발생한다.

다음과 같이 foo라는 환경 변수를 설정할 때 함수 형태로 선언하게 되면 내부에서 함수로 받아들이게 된다. 여기까지는 크게 문제가 없지만 뒤에 삽입되는 스크립트가 같이 실행되게 된다. 

```
export foo='() { echo hello; }; pwd; ls;'
```

bash가 실행되면 `pwd; ls;` 뒷 부분의 스크립트가 실행되게 된다. 

여기까지 보면 공격자가 해당 취약점을 이용하기 위해서는 쉘을 실행시킬 수 있어야만 하는 것 같지만 Bash CGI에서 사용자의 User-Agent 값을 환경변수에 저장하게 되기 때문에 CGI를 이용한 서버에 모두 취약하게 된다.

<br>

### 환경 구성 및 실행

```bash
docker compose build
docker compose up -d
```

`[http://your-ip/`를](http://your-ip/를) 방문하면 safe.cgi, victim.cgi두개의 하위페이지가 있다. safe.cgi는 최신 버전의 bash로 생성되었고 victim.cgi는 shellshock에 취약한 4.3 버전의 bash로 생성되었다.

![image](https://github.com/bshyuunn/whitehat-school-vulhub/assets/87067974/7dc7ad69-4e32-412d-89b5-c6a308679a1a)
![image](https://github.com/bshyuunn/whitehat-school-vulhub/assets/87067974/b3bac6ea-9724-403a-8e15-8bde22ffddb8)


<br>

### 결과

`() { foo; }; echo Content-Type: text/plain; echo; /usr/bin/id` 페이로드를 User-Agent header값에 넣으면 `/usr/bin/id` 가 실행된 결과가 응답값으로 오는 것을 확인할 수 있다.

![image](https://github.com/bshyuunn/whitehat-school-vulhub/assets/87067974/4fedbf86-a08c-4f44-9c3d-c8aeb016c3fc)

패치된 버전의 주소로 공격을 할 경우 페이로드가 통하지 않는다.
![image](https://github.com/bshyuunn/whitehat-school-vulhub/assets/87067974/3d3835ad-d0b9-48d8-9fda-bb26db861c4b)


<br>

### 정리

- CVE-2014-6271는 위험도가 10 만점으로 측정될 만큼 많은 곳에 큰 영향을 끼친 위험한 취약점이다.
- docker-compose.yml에 CVE-2014-6271에 취약한 버전의 주소와 패치된 버전의 주소를 함께 제공해 쉽게 해당 취약점을 실습할 수 있도록 한다.
