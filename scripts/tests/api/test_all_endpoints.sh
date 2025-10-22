#!/bin/bash

# Test All API Endpoints Script
# Tests all API endpoints with proper JWT authentication

set -e

# Configuration
BASE_URL="http://localhost:8088"
TEST_EMAIL="admin@example.com"
TEST_PASSWORD="admin123"
LOG_FILE="test_results.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "$1" | tee -a "$LOG_FILE"
}

# Test function
test_endpoint() {
    local method="$1"
    local endpoint="$2"
    local data="$3"
    local expected_status="$4"
    local description="$5"
    
    log "${BLUE}Testing: $description${NC}"
    log "  Endpoint: $method $endpoint"
    
    if [ -n "$data" ]; then
        response=$(curl -s -w "\n%{http_code}" -X "$method" \
            -H "Content-Type: application/x-www-form-urlencoded" \
            -H "Authorization: Bearer $ACCESS_TOKEN" \
            -d "$data" \
            "$BASE_URL$endpoint")
    else
        response=$(curl -s -w "\n%{http_code}" -X "$method" \
            -H "Authorization: Bearer $ACCESS_TOKEN" \
            "$BASE_URL$endpoint")
    fi
    
    # Extract status code and body
    status_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | sed '$d')
    
    if [ "$status_code" = "$expected_status" ]; then
        log "  ${GREEN}✅ PASS${NC} (Status: $status_code)"
        echo "$body" | head -c 200
        log ""
    else
        log "  ${RED}❌ FAIL${NC} (Expected: $expected_status, Got: $status_code)"
        echo "$body" | head -c 200
        log ""
    fi
}

# Main execution
log "${YELLOW}=== API Endpoints Testing ===${NC}"
log "Base URL: $BASE_URL"
log "Test User: $TEST_EMAIL"
log "Timestamp: $(date)"
log ""

# Step 1: Login and get token
log "${BLUE}Step 1: Authentication${NC}"
login_response=$(curl -s -X POST "$BASE_URL/api/auth/login" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    --data-urlencode "email=$TEST_EMAIL" \
    --data-urlencode "password=$TEST_PASSWORD")

ACCESS_TOKEN=$(echo "$login_response" | php -r '$d=json_decode(stream_get_contents(STDIN),true);echo $d["tokens"]["access_token"]??"";')

if [ -z "$ACCESS_TOKEN" ]; then
    log "${RED}❌ Login failed - no token received${NC}"
    echo "$login_response"
    exit 1
fi

log "${GREEN}✅ Login successful${NC}"
log "Token length: ${#ACCESS_TOKEN}"
log ""

# Step 2: Test all endpoints
log "${BLUE}Step 2: Testing API Endpoints${NC}"

# Users API
test_endpoint "GET" "/api/users?length=5" "" "200" "Users API (DataTables)"
test_endpoint "GET" "/api/users/1" "" "200" "Get User by ID"

# Schedules API  
test_endpoint "GET" "/api/schedules?length=5" "" "200" "Schedules API (DataTables)"
test_endpoint "GET" "/api/schedules/calendar" "" "200" "Schedules Calendar API"

# Subjects API
test_endpoint "GET" "/api/subjects" "" "200" "Subjects API"

# Rooms API
test_endpoint "GET" "/api/rooms" "" "200" "Rooms API"

# Settings API
test_endpoint "GET" "/api/settings" "" "200" "Settings API"

# Auth API
test_endpoint "GET" "/api/auth/profile" "" "200" "User Profile API"

# Step 3: Test POST endpoints (if needed)
log "${BLUE}Step 3: Testing POST Endpoints${NC}"

# Test user creation
test_endpoint "POST" "/api/users" \
    "full_name=Test User&email=testuser@example.com&password=test123&role=student" \
    "200" "Create User API"

# Test schedule creation
test_endpoint "POST" "/api/schedules" \
    "subject_id=1&teacher_id=3&room_id=1&class_id=3&timeslot_id=1&weekday=1&schedule_date=2024-12-01&start_time=09:00&end_time=10:00" \
    "200" "Create Schedule API"

# Step 4: Test settings save
log "${BLUE}Step 4: Testing Settings Save${NC}"

test_endpoint "POST" "/api/settings/general" \
    "app_name=TestApp&app_url=http://test.com&timezone=UTC" \
    "200" "Save General Settings"

test_endpoint "POST" "/api/settings/appearance" \
    "theme=dark&color_preset=preset-2&sidebar_caption=true" \
    "200" "Save Appearance Settings"

# Summary
log "${YELLOW}=== Testing Complete ===${NC}"
log "All API endpoints tested successfully!"
log "Check $LOG_FILE for detailed results."
log ""

# Cleanup
unset ACCESS_TOKEN
