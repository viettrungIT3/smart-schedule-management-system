#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

user_id=${1:-2}

print_section "NOTIFY: CREATE"
curl_json POST /api/notifications "{\"user_id\":${user_id},\"title\":\"Test notify\",\"message\":\"Change room\"}" | jq .

print_section "NOTIFY: LIST BY USER ${user_id}"
curl_json GET "/api/notifications/user/${user_id}" | jq .


