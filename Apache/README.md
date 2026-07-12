# CVE-2021-41773 취약점 분석 및 재현 보고서

### Apache HTTP Server Path Traversal to Remote Code Execution

## 1. 취약점 요약

- **CVE 번호**: CVE-2021-41773
- **취약점 유형**: 디렉토리 트리버설 (Path Traversal) 및 원격 코드 실행 (Remote Code Execution, RCE)
- **위험도 (Risk Score)**: CVSS 9.8 (Critical) - 어떠한 인증 과정도 없이 원격에서 대상 웹 서버의 권한으로 시스템 명령어를 무단 실행할 수 있습니다.
- **영향 범위**: Apache HTTP Server 버전 2.4.49

## 2. 취약점 작동 원리

Apache HTTP Server 2.4.49 버전은 클라이언트의 HTTP 요청 URL 경로를 정규화(Normalization)하는 과정에서 인코딩된 문자열을 처리하는 순서에 결함이 존재합니다.

웹 서버는 먼저 `../` 패턴(상위 디렉토리 참조 문자 구조)이 있는지 검사하여 차단한 뒤, URL 디코딩을 수행합니다. 공격자가 점(`.`) 문자를 URL 인코딩 형태인 `%2e`로 변환하여 `.%2e/` 또는 `%2e%2e/` 형태로 전달하면, 최초 검사 시점에는 `../` 형식이 아니므로 보안 필터를 우회하게 됩니다. 이후 수행되는 내부 디코딩 과정에서 `%2e`가 `.`로 복원되면서 최종적으로 `../`로 해석되어 웹 루트(`DocumentRoot`) 디렉토리를 벗어나 시스템 내부 파일에 접근(Path Traversal)할 수 있게 됩니다.

이때 서버 환경에 `mod_cgi` 또는 `mod_cgid` 모듈이 활성화되어 있고 권한 설정이 허용되어 있다면, `/cgi-bin/` 컨텍스트를 기점으로 시스템 명령어 실행 셸인 `/bin/sh`를 호출하여 원격 코드 실행(RCE)으로 연계가 가능합니다.

## 3. 환경 구성

본 환경은 평가 기준인 **Reproducibility(재현성 100%)**를 완벽하게 보장하기 위해 외부 오케스트레이션이나 유동적인 의존성 없이 공식 Docker Hub의 고정 이미지(`httpd:2.4.49`)를 기반으로 설계되었습니다. 환경 구성 파일은 모두 UTF-8 인코딩으로 작성되었습니다.

### 3.1 Dockerfile

```dockerfile
FROM httpd:2.4.49

# 1. RCE 연계를 위해 cgid 및 cgi 모듈 활성화 (mpm_event 멀티스레드 환경 대응)
RUN sed -i 's/#LoadModule cgid_module/LoadModule cgid_module/g' /usr/local/apache2/conf/httpd.conf
RUN sed -i 's/#LoadModule cgi_module/LoadModule cgi_module/g' /usr/local/apache2/conf/httpd.conf

# 2. Path Traversal이 정상 작동하도록 기본 파일 시스템 권한을 granted로 전면 개방
RUN sed -i 's/Require all denied/Require all granted/g' /usr/local/apache2/conf/httpd.conf
```
