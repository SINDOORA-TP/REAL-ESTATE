<?php
/**
 * Magaram Real Estate - Enquiry & Lead Capture API
 */
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// 1. Verify CSRF Token
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Security token invalid or expired. Please refresh the page and try again.']);
    exit;
}

// 2. Honeypot check (must be empty; bots often auto-fill it)
if (!empty($_POST['website_url_hp'])) {
    // Silently fail for bots
    echo json_encode(['success' => true, 'message' => 'Thank you for your enquiry.']);
    exit;
}

// 3. Rate limiting check (max 5 submissions per 60s per user)
if (!check_rate_limit('enquiry_submit', 5, 60)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many submissions. Please wait a minute before submitting again.']);
    exit;
}

// 4. Sanitize and Validate Input
$name = clean_string($_POST['name'] ?? '');
$phone = clean_string($_POST['phone'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$message = clean_string($_POST['message'] ?? '');
$type = clean_string($_POST['type'] ?? 'Enquiry');
$propertyId = !empty($_POST['property_id']) ? (int)$_POST['property_id'] : null;
$preferredDate = !empty($_POST['preferred_date']) ? clean_string($_POST['preferred_date']) : null;

if (empty($name) || strlen($name) < 2) {
    echo json_encode(['success' => false, 'message' => 'Please provide your full name.']);
    exit;
}

if (empty($phone) || strlen($phone) < 7) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid phone number.']);
    exit;
}

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

// Allowed enquiry types
$allowedTypes = ['Enquiry', 'Site Visit', 'Brochure'];
if (!in_array($type, $allowedTypes)) {
    $type = 'Enquiry';
}

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

// 5. Store in Database (if available)
$db = get_db();
$savedId = null;

if ($db) {
    try {
        $stmt = $db->prepare("
            INSERT INTO enquiries 
            (property_id, name, phone, email, message, type, preferred_date, status, ip_address, created_at)
            VALUES 
            (:property_id, :name, :phone, :email, :message, :type, :preferred_date, 'New', :ip_address, NOW())
        ");
        $stmt->execute([
            'property_id'    => $propertyId,
            'name'           => $name,
            'phone'          => $phone,
            'email'          => $email,
            'message'        => $message,
            'type'           => $type,
            'preferred_date' => $preferredDate,
            'ip_address'     => $ipAddress
        ]);
        $savedId = $db->lastInsertId();
    } catch (Exception $e) {
        error_log("Database error in enquiry.php: " . $e->getMessage());
    }
}

// 6. Send Email Notification to Admin
$subject = "New " . $type . " Request from " . $name . " | Magaram Real Estate";
$body = "
    <h2>New " . e($type) . " Request</h2>
    <p><strong>Name:</strong> " . e($name) . "</p>
    <p><strong>Phone:</strong> " . e($phone) . "</p>
    <p><strong>Email:</strong> " . e($email) . "</p>
    " . ($propertyId ? "<p><strong>Property ID:</strong> " . $propertyId . "</p>" : "") . "
    " . ($preferredDate ? "<p><strong>Preferred Date:</strong> " . e($preferredDate) . "</p>" : "") . "
    <p><strong>Message / Notes:</strong> " . nl2br(e($message)) . "</p>
    <hr>
    <p><small>IP Address: " . e($ipAddress) . " | Submitted: " . date('d M Y, h:i A') . "</small></p>
";
@send_admin_notification($subject, $body);

// 7. Formulate Response
$response = [
    'success' => true,
    'message' => $type === 'Site Visit' 
        ? 'Your private appointment request has been scheduled. Our concierge team will reach out to you shortly to confirm timings.'
        : ($type === 'Brochure'
            ? 'Thank you. Your architectural brochure is ready for download.'
            : 'Thank you for contacting Magaram. Our executive representative will connect with you promptly.')
];

if ($type === 'Brochure') {
    $response['download_url'] = BASE_URL . '/brochure.php' . ($propertyId ? '?property_id=' . $propertyId : '');
}

echo json_encode($response);

