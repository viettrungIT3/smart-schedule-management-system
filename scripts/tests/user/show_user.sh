#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

if [ $# -ne 1 ]; then
    echo "Usage: $0 <user_id>"
    exit 1
fi

USER_ID=$1
print_section "USERS: SHOW $USER_ID"
curl_json GET "/api/users/$USER_ID" | jq .
