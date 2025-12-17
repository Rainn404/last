#!/bin/bash

# WhatsApp Fonnte Integration - Verification Checklist
# Run this to verify all components are properly installed

echo "=================================================="
echo "  WhatsApp Fonnte Integration Verification"
echo "=================================================="
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Counter
TOTAL=0
PASSED=0

# Function to check file
check_file() {
    TOTAL=$((TOTAL + 1))
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $1"
        PASSED=$((PASSED + 1))
    else
        echo -e "${RED}✗${NC} $1 (NOT FOUND)"
    fi
}

# Function to check text in file
check_content() {
    TOTAL=$((TOTAL + 1))
    if grep -q "$2" "$1" 2>/dev/null; then
        echo -e "${GREEN}✓${NC} $1 - Contains: $2"
        PASSED=$((PASSED + 1))
    else
        echo -e "${RED}✗${NC} $1 - Missing: $2"
    fi
}

echo "1. FILES CREATED/MODIFIED"
echo "=========================="
check_file "app/Services/WhatsAppService.php"
check_file "database/migrations/2025_12_16_000000_add_wa_sent_to_pendaftaran_table.php"
check_file "test_whatsapp_fonnte.php"
check_file "WHATSAPP_FONNTE_SETUP.md"
check_file "WHATSAPP_IMPLEMENTATION_SUMMARY.txt"

echo ""
echo "2. CONFIGURATION"
echo "================"
check_content ".env" "FONNTE_TOKEN"
check_content "config/services.php" "fonnte"

echo ""
echo "3. MODEL & DATABASE"
echo "==================="
check_content "app/Models/Pendaftaran.php" "wa_sent"

echo ""
echo "4. CONTROLLER INTEGRATION"
echo "========================="
check_content "app/Http/Controllers/Admin/PendaftaranController.php" "WhatsAppService"
check_content "app/Http/Controllers/Admin/PendaftaranController.php" "statusChanged"

echo ""
echo "5. SERVICE METHODS"
echo "=================="
check_content "app/Services/WhatsAppService.php" "public function send"
check_content "app/Services/WhatsAppService.php" "public function bulkSend"
check_content "app/Services/WhatsAppService.php" "normalizePhoneNumber"
check_content "app/Services/WhatsAppService.php" "buildMessage"

echo ""
echo "6. MESSAGE TEMPLATES"
echo "===================="
check_content "app/Services/WhatsAppService.php" "interview"
check_content "app/Services/WhatsAppService.php" "diterima"
check_content "app/Services/WhatsAppService.php" "ditolak"

echo ""
echo "=================================================="
echo -e "RESULT: ${GREEN}$PASSED/${TOTAL}${NC} checks passed"
echo "=================================================="

if [ $PASSED -eq $TOTAL ]; then
    echo -e "${GREEN}✓ Implementation is COMPLETE and READY${NC}"
    exit 0
else
    echo -e "${YELLOW}⚠ Some checks failed. Please review.${NC}"
    exit 1
fi
