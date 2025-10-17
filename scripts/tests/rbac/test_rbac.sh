#!/bin/bash

# RBAC Test Script
# Test Role-Based Access Control functionality

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

echo "🔐 Testing RBAC (Role-Based Access Control)"
echo "=========================================="

# Test 1: Admin Login and RBAC Info
echo ""
echo "1. Testing Admin Login with RBAC Info..."
ADMIN_RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=admin@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")

echo "Admin Login Response:"
echo "$ADMIN_RESPONSE" | jq '.' 2>/dev/null || echo "$ADMIN_RESPONSE"

# Extract admin token
ADMIN_TOKEN=$(echo "$ADMIN_RESPONSE" | jq -r '.tokens.access_token' 2>/dev/null)

if [ -n "$ADMIN_TOKEN" ]; then
    echo "✅ Admin token extracted: ${ADMIN_TOKEN:0:20}..."
    
    # Test Admin Profile with RBAC
    echo ""
    echo "2. Testing Admin Profile with RBAC Info..."
    ADMIN_PROFILE=$(curl -s -X GET "$BASE_URL/api/auth/profile" \
      -H "Authorization: Bearer $ADMIN_TOKEN")
    
    echo "Admin Profile with RBAC:"
    echo "$ADMIN_PROFILE" | jq '.' 2>/dev/null || echo "$ADMIN_PROFILE"
else
    echo "❌ Failed to extract admin token"
fi

# Test 2: Teacher Login and RBAC Info
echo ""
echo "3. Testing Teacher Login with RBAC Info..."
TEACHER_RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=teacher.a@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")

echo "Teacher Login Response:"
echo "$TEACHER_RESPONSE" | jq '.' 2>/dev/null || echo "$TEACHER_RESPONSE"

# Extract teacher token
TEACHER_TOKEN=$(echo "$TEACHER_RESPONSE" | jq -r '.tokens.access_token' 2>/dev/null)

if [ -n "$TEACHER_TOKEN" ]; then
    echo "✅ Teacher token extracted: ${TEACHER_TOKEN:0:20}..."
    
    # Test Teacher Profile with RBAC
    echo ""
    echo "4. Testing Teacher Profile with RBAC Info..."
    TEACHER_PROFILE=$(curl -s -X GET "$BASE_URL/api/auth/profile" \
      -H "Authorization: Bearer $TEACHER_TOKEN")
    
    echo "Teacher Profile with RBAC:"
    echo "$TEACHER_PROFILE" | jq '.' 2>/dev/null || echo "$TEACHER_PROFILE"
else
    echo "❌ Failed to extract teacher token"
fi

# Test 3: Student Login and RBAC Info
echo ""
echo "5. Testing Student Login with RBAC Info..."
STUDENT_RESPONSE=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -d "email=student1@example.com" \
  -d "password=admin123" \
  -d "auth_type=jwt" \
  -H "Content-Type: application/x-www-form-urlencoded")

echo "Student Login Response:"
echo "$STUDENT_RESPONSE" | jq '.' 2>/dev/null || echo "$STUDENT_RESPONSE"

# Extract student token
STUDENT_TOKEN=$(echo "$STUDENT_RESPONSE" | jq -r '.tokens.access_token' 2>/dev/null)

if [ -n "$STUDENT_TOKEN" ]; then
    echo "✅ Student token extracted: ${STUDENT_TOKEN:0:20}..."
    
    # Test Student Profile with RBAC
    echo ""
    echo "6. Testing Student Profile with RBAC Info..."
    STUDENT_PROFILE=$(curl -s -X GET "$BASE_URL/api/auth/profile" \
      -H "Authorization: Bearer $STUDENT_TOKEN")
    
    echo "Student Profile with RBAC:"
    echo "$STUDENT_PROFILE" | jq '.' 2>/dev/null || echo "$STUDENT_PROFILE"
else
    echo "❌ Failed to extract student token"
fi

echo ""
echo "🎉 RBAC Testing Completed!"
echo "=========================="
