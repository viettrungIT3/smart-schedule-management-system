#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

print_section "USERS: CREATE (demo)"
payload='{"full_name":"Demo User","email":"demo-'"$(date +%s)"'@example.com","password":"P@ssw0rd!","role":"student","status":"active"}'
curl_json POST /api/users "${payload}" | jq .


