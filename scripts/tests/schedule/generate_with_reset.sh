#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

print_section "SCHEDULES: GENERATE with reset=1"
curl_json POST "/api/schedules/generate?reset=1" | jq .


