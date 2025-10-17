#!/usr/bin/env bash
set -euo pipefail

# Load config
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]:-}")" && pwd)"
CONFIG_FILE="${SCRIPT_DIR}/config.env"
SAMPLE_FILE="${SCRIPT_DIR}/config.env.sample"

if [[ ! -f "${CONFIG_FILE}" ]]; then
  echo "[INFO] Tạo file cấu hình mẫu: ${SAMPLE_FILE}"
  cat > "${SAMPLE_FILE}" <<'EOF'
# Sao chép file này thành config.env và chỉnh thông số bên dưới
BASE_URL=http://localhost:8088
# Nếu có auth token, điền vào; nếu không có, để trống
TOKEN=
EOF
fi

# shellcheck disable=SC1090
[[ -f "${CONFIG_FILE}" ]] && source "${CONFIG_FILE}"

BASE_URL=${BASE_URL:-http://localhost:8088}
TOKEN=${TOKEN:-}

hdr_auth() {
  if [[ -n "${TOKEN}" ]]; then
    echo "Authorization: Bearer ${TOKEN}"
  fi
}

curl_json() {
  local method="$1" path="$2" data="${3:-}"
  local url="${BASE_URL}${path}"
  
  # Build curl command
  local curl_cmd="curl -sS -X ${method}"
  
  # Add headers
  if [[ -n "${data}" ]]; then
    curl_cmd="${curl_cmd} -H 'Content-Type: application/x-www-form-urlencoded'"
  fi
  
  if [[ -n "${TOKEN}" ]]; then
    curl_cmd="${curl_cmd} -H 'Authorization: Bearer ${TOKEN}'"
  fi
  
  # Add data if provided
  if [[ -n "${data}" ]]; then
    curl_cmd="${curl_cmd} -d '${data}'"
  fi
  
  # Add URL
  curl_cmd="${curl_cmd} '${url}'"
  
  # Execute command
  eval "${curl_cmd}"
}

print_section() { echo -e "\n===== $1 ====="; }


