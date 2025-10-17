#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

class_id=${1:-1}
teacher_id=${2:-2}
weekday=${3:-1}

print_section "SCHEDULES: SEARCH c=${class_id} t=${teacher_id} w=${weekday}"
curl_json GET "/api/schedules/search?class_id=${class_id}&teacher_id=${teacher_id}&weekday=${weekday}&page=1&per_page=5" | jq .


