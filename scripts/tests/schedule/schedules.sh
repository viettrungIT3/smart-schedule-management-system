#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

print_section "SCHEDULES: SEARCH"
curl_json GET "/api/schedules/search?class_id=1&teacher_id=2&weekday=1&page=1&per_page=5" | jq .
