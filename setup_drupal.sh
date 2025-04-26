#!/bin/bash

# 서버 준비될 때까지 대기
echo "[*] Drupal 서버가 준비될 때까지 대기 중..."
until curl -s http://localhost:8080/core/install.php | grep -q "Drupal"; do
    sleep 2
done

echo "[*] Drupal 서버가 올라왔습니다. 설치 진행합니다."

# 자동 설치 요청 보내기
curl -s -X POST http://localhost:8080/core/install.php?profile=standard \
    -H "Content-Type: application/x-www-form-urlencoded" \
    --data "db_type=mysql&db_host=mysql&db_port=3306&db_name=drupal&db_user=drupal&db_pass=drupal&langcode=en&site_name=testsite&account_name=admin&account_pass=admin1234&account_mail=admin@example.com&update_status_module[1]=1&update_status_module[2]=1&op=Save+and+continue"

echo "[+] Drupal 설치 완료되었습니다."