# OpenSSL 心脏出血漏洞 CVE-2014-0160

<br/>

## 漏洞描述

在OpenSSL1.0.1版本中存在严重漏洞（CVE-2014-0160），此次漏洞问题存在于ssl/dl_both.c文件中。OpenSSL Heartbleed模块存在一个BUG，当攻击者构造一个特殊的数据包，满足用户心跳包中无法提供足够多的数据，会导致memcpy把SSLv3记录之后的数据直接输出，该漏洞导致攻击者可以远程读取存在漏洞版本的openssl服务器内存中长大64K的数据。

心脏出血是一个内存漏洞，攻击者利用这个漏洞可以服务到目标进程内存信息，如其他人的Cookie等敏感信息。

参考链接：

- https://heartbleed.com/
- https://filippo.io/Heartbleed

<br/>

## 环境搭建

Vulhub运行如下命令启动一个使用了OpenSSL 1.0.1c的Nginx服务器：

```
docker-compose up -d
```

环境启动后，访问`https://your-ip`即可查看到hello页面。

<br/>

## 漏洞复现

通过Nmap进行漏洞检测：

```shell
nmap -sV -p 443 --script ssl-heartbleed.nse your-ip
```

Python运行[ssltest.py](https://github.com/vulhub/vulhub/blob/master/openssl/heartbleed/ssltest.py)，拿到敏感数据（可能包含Cookie）。

<br/>
