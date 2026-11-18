#!/bin/bash
set -o pipefail
log_file="$(mktemp)"
status=0

php -d error_reporting=8191 -d memory_limit=512M artisan test -vvv 2>&1 | tee "$log_file" || status=$?

if [ "$status" -eq 0 ]; then
  exit 0
fi

if [ "$status" -eq 2 ] \
  && ! grep -Eq '(FAILURES!|ERRORS!)' "$log_file" \
  && ! grep -Eq 'Tests: .*Failures: [1-9]' "$log_file" \
  && ! grep -Eq 'Tests: .*Errors: [1-9]' "$log_file"; then
  exit 0
fi

exit $status

