#!/bin/bash

# Simple test script for JWT authentication
BASE_URL="http://localhost:8088"

echo "Testing JWT Authentication..."

# Test login
echo "1. Testing login..."
RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=test@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")

echo "Login Response:"
echo "$RESPONSE" | jq '.'

# Extract token
ACCESS_TOKEN=$(echo "$RESPONSE" | jq -r '.tokens.access_token')
echo "Access Token: ${ACCESS_TOKEN:0:50}..."

# Test profile
echo "2. Testing profile..."
PROFILE_RESPONSE=$(curl -s -X GET "$BASE_URL/api/auth/profile" \
  -H "Authorization: Bearer $ACCESS_TOKEN")

echo "Profile Response:"
echo "$PROFILE_RESPONSE" | jq '.'

echo "✅ Test completed!"
