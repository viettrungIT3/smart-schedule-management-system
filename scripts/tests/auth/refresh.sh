#!/bin/bash

# Load common functions
source "$(dirname "$0")/../utils.sh"

# Test JWT Authentication - Refresh Token
print_section "JWT Authentication - Refresh Token Test"

# Check if refresh token exists
if [ ! -f "/tmp/refresh_token.txt" ]; then
  echo "❌ Refresh token not found. Please run login.sh first."
  exit 1
fi

REFRESH_TOKEN=$(cat /tmp/refresh_token.txt)

echo "Testing token refresh..."
echo "Refresh Token: ${REFRESH_TOKEN:0:50}..."

# Make refresh request
RESPONSE=$(curl_json -X POST "$BASE_URL/api/auth/refresh" \
  -d "refresh_token=$REFRESH_TOKEN")

echo "Response:"
echo "$RESPONSE" | jq '.'

# Check if refresh was successful
if echo "$RESPONSE" | jq -e '.tokens.access_token' > /dev/null; then
  echo "✅ Token refresh successful!"
  
  # Extract new tokens
  NEW_ACCESS_TOKEN=$(echo "$RESPONSE" | jq -r '.tokens.access_token')
  NEW_REFRESH_TOKEN=$(echo "$RESPONSE" | jq -r '.tokens.refresh_token')
  
  echo "New Access Token: ${NEW_ACCESS_TOKEN:0:50}..."
  echo "New Refresh Token: ${NEW_REFRESH_TOKEN:0:50}..."
  
  # Update saved tokens
  echo "$NEW_ACCESS_TOKEN" > /tmp/access_token.txt
  echo "$NEW_REFRESH_TOKEN" > /tmp/refresh_token.txt
  
  echo "✅ Tokens updated successfully"
else
  echo "❌ Token refresh failed!"
  exit 1
fi
