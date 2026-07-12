package com.example;

import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpServer;
import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;

import java.io.OutputStream;
import java.net.InetSocketAddress;

/**
 * CVE-2021-44228 (Log4Shell) 취약 재현용 최소 HTTP 서버.
 * 클라이언트가 보낸 X-Api-Version 헤더 값을 그대로 log4j로 로깅한다.
 * log4j-core 2.14.1 (취약 버전)을 사용한다.
 */
public class VulnerableApp {

    private static final Logger logger = LogManager.getLogger(VulnerableApp.class);

    public static void main(String[] args) throws Exception {
        HttpServer server = HttpServer.create(new InetSocketAddress(8080), 0);
        server.createContext("/", new RootHandler());
        server.setExecutor(null);
        System.out.println("[VulnerableApp] listening on :8080 (log4j 2.14.1)");
        server.start();
    }

    static class RootHandler implements HttpHandler {
        @Override
        public void handle(HttpExchange exchange) throws java.io.IOException {
            String apiVersion = exchange.getRequestHeaders().getFirst("X-Api-Version");
            if (apiVersion == null) {
                apiVersion = "unknown";
            }

            // 취약 지점: 사용자 제어 값을 검증 없이 로깅
            logger.info("Incoming request - X-Api-Version: {}", apiVersion);

            String response = "OK\n";
            exchange.sendResponseHeaders(200, response.getBytes().length);
            OutputStream os = exchange.getResponseBody();
            os.write(response.getBytes());
            os.close();
        }
    }
}
