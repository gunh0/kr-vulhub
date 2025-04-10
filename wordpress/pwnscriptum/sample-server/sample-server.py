import http.server
import socketserver

port = 4000

with socketserver.TCPServer(("", port), http.server.SimpleHTTPRequestHandler) as httpd:
	print(f"Starting server at port {port}...")
	httpd.serve_forever()
