#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

user_id=${1:-2}
title=${2:-"Test notify"}
message=${3:-"Change room"}

print_section "NOTIFY: CREATE for user=${user_id}"
payload=$(jq -n --argjson uid "${user_id}" --arg t "${title}" --arg m "${message}" '{user_id:$uid,title:$t,message:$m}')
curl_json POST /api/notifications "${payload}" | jq .


