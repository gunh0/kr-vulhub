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

<!-- ![Untitled](https://prod-files-secure.s3.us-west-2.amazonaws.com/b0d2f5ac-6821-4326-b143-ea7b7bbfe725/63477c1c-882b-4644-8464-bd64cb131f1d/Untitled.png) -->

<!-- ![Untitled](https://prod-files-secure.s3.us-west-2.amazonaws.com/b0d2f5ac-6821-4326-b143-ea7b7bbfe725/b31f20ce-62e9-461d-9ffe-a1266784b808/Untitled.png) -->

<br>

### 결과

`() { foo; }; echo Content-Type: text/plain; echo; /usr/bin/id` 페이로드를 User-Agent header값에 넣으면 `/usr/bin/id` 가 실행된 결과가 응답값으로 오는 것을 확인할 수 있다.

<!-- ![Untitled](https://prod-files-secure.s3.us-west-2.amazonaws.com/b0d2f5ac-6821-4326-b143-ea7b7bbfe725/2ff1a9d3-55db-483e-9045-cba60913f375/Untitled.png) -->

패치된 버전의 주소로 공격을 할 경우 페이로드가 통하지 않는다.

<!-- ![Untitled](https://prod-files-secure.s3.us-west-2.amazonaws.com/b0d2f5ac-6821-4326-b143-ea7b7bbfe725/17ae7442-0a99-4793-8e87-9b67e828104b/Untitled.png) -->

<br>

### 정리

- CVE-2014-6271는 위험도가 10 만점으로 측정될 만큼 많은 곳에 큰 영향을 끼친 위험한 취약점이다.
- docker-compose.yml에 CVE-2014-6271에 취약한 버전의 주소와 패치된 버전의 주소를 함께 제공해 쉽게 해당 취약점을 실습할 수 있도록 한다.