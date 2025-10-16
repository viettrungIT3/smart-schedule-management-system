#!/usr/bin/env bash
set -euo pipefail
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "${SCRIPT_DIR}/../utils.sh"

schedule_id=${1:-1}

print_section "ATTENDANCE: LIST BY SCHEDULE ${schedule_id}"
curl_json GET "/api/attendance/schedule/${schedule_id}" | jq .

print_section "ATTENDANCE: MARK (demo)"
payload='[{"student_id":4,"status":"present"},{"student_id":5,"status":"absent","note":"sick"}]'
curl_json POST "/api/attendance/schedule/${schedule_id}" "${payload}" | jq .


