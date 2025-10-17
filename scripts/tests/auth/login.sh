#!/bin/bash

# Load common functions
source "$(dirname "$0")/../utils.sh"

# Test JWT Authentication Login
print_section "JWT Authentication - Login Test"

# Test data
EMAIL="test@example.com"
PASSWORD="admin123"

echo "Testing login with valid credentials..."
echo "Email: $EMAIL"
echo "Password: $PASSWORD"

# Make login request
RESPONSE=$(curl_json -X POST "$BASE_URL/api/auth/login" \
  -d "email=$EMAIL" \
  -d "password=$PASSWORD" \
  -d "auth_type=jwt")

echo "Response:"
echo "$RESPONSE" | jq '.'

# Check if login was successful
if echo "$RESPONSE" | jq -e '.tokens.access_token' > /dev/null; then
  echo "✅ Login successful!"
  
  # Extract tokens
  ACCESS_TOKEN=$(echo "$RESPONSE" | jq -r '.tokens.access_token')
  REFRESH_TOKEN=$(echo "$RESPONSE" | jq -r '.tokens.refresh_token')
  
  echo "Access Token: ${ACCESS_TOKEN:0:50}..."
  echo "Refresh Token: ${REFRESH_TOKEN:0:50}..."
  
  # Save tokens to config.env
  CONFIG_FILE="$(dirname "$0")/../config.env"
  sed -i.bak "s/^TOKEN=.*/TOKEN=$ACCESS_TOKEN/" "$CONFIG_FILE"
  
  # Also save refresh token to a separate file for refresh tests
  echo "$REFRESH_TOKEN" > /tmp/refresh_token.txt
  
  echo "✅ Tokens saved to config.env and refresh token file"
else
  echo "❌ Login failed!"
  exit 1
fi
