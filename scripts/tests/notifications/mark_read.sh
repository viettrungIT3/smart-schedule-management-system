#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

id=${1:?"usage: mark_read.sh <notification_id>"}

print_section "NOTIFY: MARK READ id=${id}"
curl_json POST "/api/notifications/${id}/read" | jq .


