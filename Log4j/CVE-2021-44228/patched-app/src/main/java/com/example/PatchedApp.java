package com.example;

import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpServer;
import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;

import java.io.OutputStream;
import java.net.InetSocketAddress;

/**
 * CVE-2021-44228 (Log4Shell) 차등 비교용 패치 버전 HTTP 서버.
 * vulnerable-app과 완전히 동일한 로직이지만, log4j-core 2.17.1(패치 버전)을 사용한다.
 * 동일한 공격 요청을 받았을 때 RCE가 재현되지 않음을 증명하는 대조군 역할을 한다.
 */
public class PatchedApp {

    private static final Logger logger = LogManager.getLogger(PatchedApp.class);

    public static void main(String[] args) throws Exception {
        HttpServer server = HttpServer.create(new InetSocketAddress(8080), 0);
        server.createContext("/", new RootHandler());
        server.setExecutor(null);
        System.out.println("[PatchedApp] listening on :8080 (log4j 2.17.1 (patched))");
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
