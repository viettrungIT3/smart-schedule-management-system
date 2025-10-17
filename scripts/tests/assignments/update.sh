#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

if [ $# -ne 2 ]; then
    echo "Usage: $0 <assignment_id> <periods_per_week>"
    exit 1
fi

ASSIGNMENT_ID=$1
PERIODS=$2

print_section "ASSIGNMENTS: UPDATE $ASSIGNMENT_ID"
curl_json PUT "/api/assignments/$ASSIGNMENT_ID" \
    -d "{\"periods_per_week\": $PERIODS}" | jq .
