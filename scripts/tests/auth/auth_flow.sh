#!/bin/bash

# Load common functions
source "$(dirname "$0")/../utils.sh"

# Test Complete JWT Authentication Flow
print_section "JWT Authentication - Complete Flow Test"

echo "Testing complete authentication flow..."

# Step 1: Login
echo "Step 1: Login"
bash "$(dirname "$0")/login.sh"
if [ $? -ne 0 ]; then
  echo "❌ Login step failed!"
  exit 1
fi

echo ""

# Step 2: Get Profile
echo "Step 2: Get Profile"
bash "$(dirname "$0")/profile.sh"
if [ $? -ne 0 ]; then
  echo "❌ Profile step failed!"
  exit 1
fi

echo ""

# Step 3: Refresh Token
echo "Step 3: Refresh Token"
bash "$(dirname "$0")/refresh.sh"
if [ $? -ne 0 ]; then
  echo "❌ Refresh step failed!"
  exit 1
fi

echo ""

# Step 4: Get Profile Again (with new token)
echo "Step 4: Get Profile Again (with new token)"
bash "$(dirname "$0")/profile.sh"
if [ $? -ne 0 ]; then
  echo "❌ Profile step failed!"
  exit 1
fi

echo ""

# Step 5: Logout
echo "Step 5: Logout"
bash "$(dirname "$0")/logout.sh"
if [ $? -ne 0 ]; then
  echo "❌ Logout step failed!"
  exit 1
fi

echo ""
echo "✅ Complete authentication flow test passed!"
