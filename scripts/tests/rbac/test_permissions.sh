#!/bin/bash

# RBAC Permissions Test Script
# Test specific permissions for different roles

set -e

# Load configuration
SCRIPT_DIR="$(dirname "$0")"
CONFIG_FILE="$SCRIPT_DIR/../config.env"

if [ -f "$CONFIG_FILE" ]; then
    source "$CONFIG_FILE"
else
    echo "ERROR: Config file not found: $CONFIG_FILE"
    exit 1
fi

BASE_URL="${BASE_URL:-http://localhost:8080}"

echo "🔐 Testing RBAC Permissions"
echo "==========================="

# Helper function to test endpoint with token
test_endpoint() {
    local endpoint="$1"
    local method="$2"
    local token="$3"
    local expected_status="$4"
    local description="$5"
    
    echo ""
    echo "Testing: $description"
    echo "Endpoint: $method $endpoint"
    
    if [ "$method" = "GET" ]; then
        response=$(curl -s -w "\n%{http_code}" -X GET "$BASE_URL$endpoint" \
          -H "Authorization: Bearer $token")
    elif [ "$method" = "POST" ]; then
        response=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL$endpoint" \
          -H "Authorization: Bearer $token" \
          -H "Content-Type: application/json" \
          -d '{}')
    fi
    
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n -1)
    
    if [ "$http_code" = "$expected_status" ]; then
        echo "✅ Expected status $expected_status - Got $http_code"
    else
        echo "❌ Expected status $expected_status - Got $http_code"
    fi
    
    echo "Response: $body"
}

# Get tokens for different roles
echo "Getting authentication tokens..."

# Admin token
ADMIN_RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=admin@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")
ADMIN_TOKEN=$(echo "$ADMIN_RESPONSE" | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)

# Teacher token
TEACHER_RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=teacher.a@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")
TEACHER_TOKEN=$(echo "$TEACHER_RESPONSE" | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)

# Student token
STUDENT_RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=student1@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")
STUDENT_TOKEN=$(echo "$STUDENT_RESPONSE" | grep -o '"access_token":"[^"]*"' | cut -d'"' -f4)

echo "✅ Tokens obtained"

# Test Admin permissions (should have access to everything)
echo ""
echo "🔑 Testing ADMIN permissions..."
test_endpoint "/api/schedules" "GET" "$ADMIN_TOKEN" "200" "Admin: View schedules"
test_endpoint "/api/assignments" "GET" "$ADMIN_TOKEN" "200" "Admin: View assignments"
test_endpoint "/api/attendance" "GET" "$ADMIN_TOKEN" "200" "Admin: View attendance"
test_endpoint "/api/notifications" "GET" "$ADMIN_TOKEN" "200" "Admin: View notifications"

# Test Teacher permissions (limited access)
echo ""
echo "👨‍🏫 Testing TEACHER permissions..."
test_endpoint "/api/schedules" "GET" "$TEACHER_TOKEN" "200" "Teacher: View schedules"
test_endpoint "/api/assignments" "GET" "$TEACHER_TOKEN" "200" "Teacher: View assignments"
test_endpoint "/api/attendance" "GET" "$TEACHER_TOKEN" "200" "Teacher: View attendance"
test_endpoint "/api/notifications" "GET" "$TEACHER_TOKEN" "200" "Teacher: View notifications"

# Test Student permissions (read-only access)
echo ""
echo "👨‍🎓 Testing STUDENT permissions..."
test_endpoint "/api/schedules" "GET" "$STUDENT_TOKEN" "200" "Student: View schedules"
test_endpoint "/api/assignments" "GET" "$STUDENT_TOKEN" "200" "Student: View assignments"
test_endpoint "/api/attendance" "GET" "$STUDENT_TOKEN" "200" "Student: View attendance"
test_endpoint "/api/notifications" "GET" "$STUDENT_TOKEN" "200" "Student: View notifications"

# Test unauthorized access (no token)
echo ""
echo "🚫 Testing UNAUTHORIZED access..."
test_endpoint "/api/schedules" "GET" "" "401" "No token: Should be unauthorized"
test_endpoint "/api/assignments" "GET" "" "401" "No token: Should be unauthorized"

# Test invalid token
echo ""
echo "🔒 Testing INVALID token..."
test_endpoint "/api/schedules" "GET" "invalid_token" "401" "Invalid token: Should be unauthorized"

echo ""
echo "🎉 RBAC Permissions Testing Completed!"
echo "======================================"
