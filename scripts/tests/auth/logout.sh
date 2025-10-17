#!/bin/bash

# Load common functions
source "$(dirname "$0")/../utils.sh"

# Test JWT Authentication - Logout
print_section "JWT Authentication - Logout Test"

echo "Testing logout endpoint..."

# Make logout request
RESPONSE=$(curl_json -X POST "$BASE_URL/api/auth/logout")

echo "Response:"
echo "$RESPONSE" | jq '.'

# Check if logout was successful
if echo "$RESPONSE" | jq -e '.message' > /dev/null; then
  MESSAGE=$(echo "$RESPONSE" | jq -r '.message')
  echo "✅ Logout successful: $MESSAGE"
  
  # Clean up saved tokens
  rm -f /tmp/access_token.txt /tmp/refresh_token.txt
  echo "✅ Tokens cleaned up"
else
  echo "❌ Logout failed!"
  exit 1
fi
