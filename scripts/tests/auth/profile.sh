#!/bin/bash

# Load common functions
source "$(dirname "$0")/../utils.sh"

# Test JWT Authentication - Get Profile
print_section "JWT Authentication - Profile Test"

# Load TOKEN from config.env
CONFIG_FILE="$(dirname "$0")/../config.env"
if [ -f "$CONFIG_FILE" ]; then
  source "$CONFIG_FILE"
else
  echo "❌ Config file not found: $CONFIG_FILE"
  exit 1
fi

# Check if TOKEN exists
if [ -z "$TOKEN" ]; then
  echo "❌ No TOKEN found in config.env. Please run login.sh first."
  exit 1
fi

echo "Testing profile endpoint with JWT token..."
echo "Token: ${TOKEN:0:50}..."

# Make profile request directly with curl
RESPONSE=$(curl -s -X GET "$BASE_URL/api/auth/profile" \
  -H "Authorization: Bearer $TOKEN")

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
