# Drupal Cross-Site Scripting by File Upload (CVE-2019-6341)
> 화이트햇 스쿨 3기 - 전소현


### 요약
- Drupal은 2000년도에 등장한 PHP 기반 콘텐츠 관리 프레임워크
- 파일 모듈이나 서브 시스템을 통해 악성 파일을 업로드함으로써 XSS 취약점 발생
<br/><br/> 
### 환경 구성 및 실행 
- docker compose up -d 를 실행하여 테스트 환경을 실행
  ![image](https://github.com/user-attachments/assets/837f0f9a-2850-475e-8751-93eda254a3db)
- your-ip:8000 에 접속하여 drupal 설치
  ![image](https://github.com/user-attachments/assets/4dc6c6f1-f373-4b6d-94ce-3a66b634438e)
- php blog-poc.php your-ip 8080 을 실행하여 파일 업로드
  ![image](https://github.com/user-attachments/assets/35062bcd-4fe1-4e1e-9e95-52aabe690161)
- your-ip:8080/sites/default/files/pictures/YYYY-MM 에 접속하면 XSS 취약점 발생
<br/><br/>  
### 결과
![image](https://github.com/user-attachments/assets/b776e8b7-760d-4e94-8ce4-bb2f50eaa870)
<br/><br/>  
### 정리
File upload 공격은 서버에 파일이 업로드 되는 기능을 이용하여, 업로드 된 파일을 웹 서버에 요청할 때 발생하는 취약점이다. 따라서, 실행 권한을 제거하거나 MIME 타입 검증, 확장자 필터링 및 파일 크기 제한 등의 보안 조치를 취해야 한다.


[my github url](https://github.com/wwwesh/whitehat.git)
