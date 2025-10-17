#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

print_section "ASSIGNMENTS: LIST ALL"
curl_json GET "/api/assignments" | jq .
