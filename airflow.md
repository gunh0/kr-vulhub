# Apache Airflow Command Injection in Example Dag (CVE-2020-11978)
# Apache Airflow 예제 DAG에서의 명령 주입 취약점 (CVE-2020-11978)
[中文版本(Chinese version)](README.zh-cn.md)
[중국어 버전(Chinese version)](README.zh-cn.md)
Apache Airflow is an open source, distributed task scheduling framework. In the version prior to 1.10.10, there is a command injection vulnerability in the example DAG `example_trigger_target_dag`, which caused attackers to execute arbitrary commands in the worker process.
Apache Airflow는 오픈 소스이며, 분산 작업 예약 프레임워크이다. 버전 1.10.10 이전에는 'example_trigger_target_dag'라는 예제 DAG에서 명령 주입 취약점이 있었는데 이로 인해 공격자가 작업자 프로세스에서 임의의 명령을 실행할 수 있었다.

Since there are many components to be started, it may be a bit stuck. Please prepare more than 2G of memory for the use of the virtual machine.
시작되는데 많은 구성 요소가 있기에 지연될 수 있다. 가상 머신을 사용하기 위해서 2GB 이상의 메모리를 준비하라.

References:
참조:
- <https://lists.apache.org/thread/cn57zwylxsnzjyjztwqxpmly0x9q5ljx>
- <https://github.com/pberba/CVE-2020-11978>

## Vulnerability Environment
## 취약점 환경
Execute the following commands to start airflow 1.10.10:
Airflow 1.10.10을 실행하기 위해 다음의 명령어들을 실행하라.
```bash
```bash
#Initialize the database
#데이터베이스 초기화
docker compose run airflow-init

#Start service
#서비스 시작
docker compose up -d
```

## Exploit
## 익스플로잇

Visit `http://your-ip:8080` to see the airflow management terminal, and turn on the `example_trigger_target_dag` flag:
Airflow 관리 터미널을 확인하기 위해 'http://당신의-ip:8000'을 방문하고 'example_trigger_target_dag' 플래그 활성화.

![](1.png)

Click the "triger" button on the right, then input the configuration JSON with the crafted payload `{"message":"'\";touch /tmp/airflow_dag_success;#"}`:
우측에 있는 "triger" 버튼을 누르고 조작된 페이로드`{"message":"'\";touch /tmp/airflow_dag_success;#"}`:를 포함한 JSON 설정을 입력하라.

![](2.png)

Wait a few seconds to see the execution of "success":
"success" 실행을 위해 잠시 기다려라.

![](3.png)

Go to the CeleryWorker container to see the result, `touch /tmp/airflow_dag_success` has been successfully executed:
CeleryWorekr 컨테이너로 이동하여 `touch /tmp/airflow_dag_success'가 성공적으로 실행됨을 확인하라.

```
docker compose exec airflow-worker ls -l /tmp
```

![](4.png)
