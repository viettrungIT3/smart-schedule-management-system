#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

if [ $# -ne 1 ]; then
    echo "Usage: $0 <class_id>"
    exit 1
fi

CLASS_ID=$1
print_section "ASSIGNMENTS: LIST BY CLASS $CLASS_ID"
curl_json GET "/api/assignments/class/$CLASS_ID" | jq .
