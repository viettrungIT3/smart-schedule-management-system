#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

if [ $# -ne 1 ]; then
    echo "Usage: $0 <teacher_id>"
    exit 1
fi

TEACHER_ID=$1
print_section "ASSIGNMENTS: LIST BY TEACHER $TEACHER_ID"
curl_json GET "/api/assignments/teacher/$TEACHER_ID" | jq .
