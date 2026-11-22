#!/bin/sh
set -eu

echo 'CVE-2023-4911 / Looney Tunables'
printf 'architecture : '; uname -m
printf 'runtime user : '; id
printf 'glibc        : '; /usr/bin/ldd --version | sed -n '1p'
printf 'SUID target  : '; ls -l /usr/bin/su
echo 'PoC user     : nobody (uid 65534)'
echo
echo '[*] Running the Qualys minimal crash PoC...'
/lab/poc.sh
