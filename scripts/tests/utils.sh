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
  
  if [[ -n "${data}" ]]; then
    if [[ -n "${TOKEN}" ]]; then
      curl -sS -X "${method}" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -H "Authorization: Bearer ${TOKEN}" \
        -d "${data}" \
        "${BASE_URL}${path}"
    else
      curl -sS -X "${method}" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -d "${data}" \
        "${BASE_URL}${path}"
    fi
  else
    if [[ -n "${TOKEN}" ]]; then
      curl -sS -X "${method}" -H "Authorization: Bearer ${TOKEN}" "${BASE_URL}${path}"
    else
      curl -sS -X "${method}" "${BASE_URL}${path}"
    fi
  fi
}

print_section() { echo -e "\n===== $1 ====="; }


