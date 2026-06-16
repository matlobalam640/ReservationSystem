#!/bin/bash
set -euo pipefail
BASE="https://snow-emu-551412.hostingersite.com"
COOKIES="/tmp/admin-cookies.txt"
rm -f "$COOKIES"

curl -s -c "$COOKIES" -b "$COOKIES" "$BASE/login" -o /tmp/login.html
TOKEN=$(grep -oP 'name="_token" value="\K[^"]+' /tmp/login.html | head -1)
curl -s -c "$COOKIES" -b "$COOKIES" -X POST \
  -d "email=admin@hero.ops" \
  -d "password=password" \
  -d "_token=$TOKEN" \
  -o /dev/null -w "login:%{http_code}\n" \
  "$BASE/login"

curl -s -b "$COOKIES" -o /tmp/admin.out \
  -w "admin:%{http_code} size:%{size_download}\n" \
  "$BASE/admin"

head -c 300 /tmp/admin.out
echo
