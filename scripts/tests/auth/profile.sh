#!/bin/bash

# Load common functions
source "$(dirname "$0")/../utils.sh"

# Test JWT Authentication - Get Profile
print_section "JWT Authentication - Profile Test"

# Check if access token exists
if [ ! -f "/tmp/access_token.txt" ]; then
  echo "❌ Access token not found. Please run login.sh first."
  exit 1
fi

ACCESS_TOKEN=$(cat /tmp/access_token.txt)

echo "Testing profile endpoint with JWT token..."
echo "Token: ${ACCESS_TOKEN:0:50}..."

# Make profile request
RESPONSE=$(curl_json -X GET "$BASE_URL/api/auth/profile" \
  -H "Authorization: Bearer $ACCESS_TOKEN")

echo "Response:"
echo "$RESPONSE" | jq '.'

# Check if profile was retrieved successfully
if echo "$RESPONSE" | jq -e '.user' > /dev/null; then
  echo "✅ Profile retrieved successfully!"
  
  # Display user info
  USER_EMAIL=$(echo "$RESPONSE" | jq -r '.user.email')
  USER_ROLE=$(echo "$RESPONSE" | jq -r '.user.role')
  USER_NAME=$(echo "$RESPONSE" | jq -r '.user.full_name')
  
  echo "User Email: $USER_EMAIL"
  echo "User Role: $USER_ROLE"
  echo "User Name: $USER_NAME"
else
  echo "❌ Profile retrieval failed!"
  exit 1
fi
