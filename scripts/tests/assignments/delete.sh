#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

if [ $# -ne 1 ]; then
    echo "Usage: $0 <assignment_id>"
    exit 1
fi

ASSIGNMENT_ID=$1
print_section "ASSIGNMENTS: DELETE $ASSIGNMENT_ID"
curl_json DELETE "/api/assignments/$ASSIGNMENT_ID" | jq .
