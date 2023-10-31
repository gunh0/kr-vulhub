# Jupyter Notebook 접근 권한이 없는 취약성

Jupyter Notebook(이전에는 IPython notebook으로 알려짐)은 40개 이상의 프로그래밍 언어를 실행할 수 있도록 지원하는 대화형 노트북입니다.

관리자가 Jupyter Notebook에 대한 암호를 설정하지 않은 경우 방문객이 콘솔을 만들고 임의의 Python 코드 및 명령을 실행할 수 있는 무단 액세스 취약성이 발생합니다.

## 환경 운행

실행 테스트 환경:

```
docker compose up -d
```

실행 후, `http://your-ip:8888`을 방문하면 주피터 노트의 웹 관리 인터페이스가 나타나며, 비밀번호는 요구되지 않습니다.

## 취약성 되풀이

new -> terminal을 선택하면 콘솔을 만들 수 있습니다:

![image](https://github.com/ggugga/jupyter/assets/113493380/cf96557f-282b-415c-b277-841b7cf635d1)


임의의 명령을 직접 실행합니다.

![image](https://github.com/ggugga/jupyter/assets/113493380/d2ec9af0-3731-46e2-a0d0-e69cb14bc9da)

