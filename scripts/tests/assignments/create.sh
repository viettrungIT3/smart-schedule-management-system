#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

if [ $# -ne 3 ]; then
    echo "Usage: $0 <class_id> <teacher_id> <subject_id>"
    exit 1
fi

CLASS_ID=$1
TEACHER_ID=$2
SUBJECT_ID=$3

print_section "ASSIGNMENTS: CREATE"
curl_json POST "/api/assignments" \
    -d "{\"class_id\": $CLASS_ID, \"teacher_id\": $TEACHER_ID, \"subject_id\": $SUBJECT_ID, \"periods_per_week\": 2}" | jq .
