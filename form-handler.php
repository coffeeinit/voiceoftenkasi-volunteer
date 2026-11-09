<?php
    // Enable error reporting for debugging (remove in production)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require __DIR__ . '/vendor/autoload.php';

    use setasign\fpdf\Fpdf;
    use Dompdf\Dompdf;

// Database configuration
$dbFile = 'volunteer_database.db';

    // Ensure volunteer_photos directory exists
    function ensurePhotoDir() {
        $uploadDir = 'volunteer_photos';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    }
function initDatabase() {
    global $dbFile;

    try {
        // Ensure photo directory if needed
        ensurePhotoDir();

        $pdo = new PDO("sqlite:$dbFile");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create volunteers table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS volunteers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                registration_id TEXT UNIQUE NOT NULL,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                dob TEXT,
                email TEXT,
                blood_group TEXT NOT NULL,
                contact_no TEXT NOT NULL,
                whatsapp_no TEXT NOT NULL,
                qualification TEXT NOT NULL,
                occupation TEXT NOT NULL,
                address1 TEXT NOT NULL,
                address2 TEXT,
                city TEXT NOT NULL,
                state TEXT NOT NULL,
                zip TEXT NOT NULL,
                donate TEXT NOT NULL,
                days TEXT NOT NULL,
                interests TEXT NOT NULL,
                motto TEXT NOT NULL,
                travel_km TEXT,
                preferred_locations TEXT,
                instagram TEXT,
                facebook TEXT,
                twitter TEXT,
                linkedin TEXT,
                social_repost TEXT,
                photo TEXT,
                submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                status TEXT DEFAULT 'active',
                created_at DATETIME,
                updated_at DATETIME
            )
        ");

        // Ensure otp_sessions table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS otp_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                phone TEXT NOT NULL,
                otp TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                attempts INTEGER DEFAULT 0,
                verified INTEGER DEFAULT 0
            )
        ");

        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}


// Sanitize input
function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map('sanitizeInput', $data);
        }
        return htmlspecialchars(strip_tags(trim((string)$data)));
}

// Validate phone number
function validatePhone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle form submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    header('Content-Type: application/json');
    try {
        $pdo = initDatabase();
            // If updating
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
                $fields = [
                    'first_name', 'last_name', 'email', 'blood_group', 'contact_no', 'whatsapp_no',
                    'qualification', 'occupation', 'address1', 'address2', 'city', 'state', 'zip',
                    'donate', 'days', 'interests', 'motto', 'status', 'travel_km', 'preferred_locations',
                    'instagram', 'facebook', 'twitter', 'linkedin', 'social_repost', 'dob'
                ];
                $set = [];
                $params = [];
                foreach ($fields as $f) {
                    if (isset($_POST[$f])) {
                        $set[] = "$f = ?";
                        $value = $_POST[$f];
                        // Handle array fields
                        if (in_array($f, ['days', 'interests', 'preferred_locations']) && is_array($value)) {
                            $value = implode(', ', array_map('sanitizeInput', $value));
                        } else {
                            $value = sanitizeInput($value);
                        }
                        $params[] = $value;
                    }
                }
                // Handle photo upload if present
                $uploadedFile = null;
                if (isset($_FILES['volunteer_photo']) && $_FILES['volunteer_photo']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['volunteer_photo'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    if (!in_array($mimeType, $allowedTypes)) {
                        throw new Exception('Invalid file type. Only JPEG, PNG, and GIF are allowed');
                    }
                    $maxSize = 5 * 1024 * 1024;
                    if ($file['size'] > $maxSize) {
                        throw new Exception('File size too large. Maximum 5MB allowed');
                    }
                    $uploadDir = 'volunteer_photos/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $newFileName = 'volunteer_' . ($_POST['contact_no'] ?? $id) . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $newFileName;
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        throw new Exception('Failed to upload file');
                    }
                    $uploadedFile = $newFileName;
                    $set[] = "photo = ?";
                    $params[] = $uploadedFile;
                }
                $set[] = "updated_at = ?";
                $params[] = date('Y-m-d H:i:s');
                $params[] = $id;
                $sql = "UPDATE volunteers SET ".implode(", ", $set)." WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute($params);
                // Fetch registration_id for this volunteer
                $stmtReg = $pdo->prepare('SELECT registration_id FROM volunteers WHERE id = ?');
                $stmtReg->execute([$id]);
                $registration_id = $stmtReg->fetchColumn() ?: '';
                echo json_encode(['success' => $result, 'message' => 'Volunteer updated successfully', 'registration_id' => $registration_id, 'uploaded_file' => $uploadedFile]);
                exit;
            }
        // Validate required fields
        $requiredFields = [
            'first_name', 'last_name', 'blood_group', 'contact_no', 
            'whatsapp_no', 'qualification', 'occupation', 'address1', 
            'city', 'state', 'zip', 'donate', 'motto'
        ];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Required field '$field' is missing");
            }
        }
        // Validate checkboxes
        if (empty($_POST['days']) || !is_array($_POST['days'])) {
            throw new Exception('Please select at least one available day');
        }
        if (empty($_POST['interests']) || !is_array($_POST['interests'])) {
            throw new Exception('Please select at least one area of interest');
        }
        if (empty($_POST['consent']) || empty($_POST['terms'])) {
            throw new Exception('Please accept the consent and terms');
        }
        // Sanitize input data
        $data = [
            'first_name' => sanitizeInput($_POST['first_name']),
            'last_name' => sanitizeInput($_POST['last_name']),
            'email' => sanitizeInput($_POST['email']),
            'blood_group' => sanitizeInput($_POST['blood_group']),
            'contact_no' => sanitizeInput($_POST['contact_no']),
            'whatsapp_no' => sanitizeInput($_POST['whatsapp_no']),
            'qualification' => sanitizeInput($_POST['qualification']),
            'occupation' => sanitizeInput($_POST['occupation']),
            'address1' => sanitizeInput($_POST['address1']),
            'address2' => sanitizeInput($_POST['address2']),
            'city' => sanitizeInput($_POST['city']),
            'state' => sanitizeInput($_POST['state']),
            'zip' => sanitizeInput($_POST['zip']),
            'donate' => sanitizeInput($_POST['donate']),
            'days' => implode(', ', array_map('sanitizeInput', $_POST['days'])),
            'interests' => implode(', ', array_map('sanitizeInput', $_POST['interests'])),
            'motto' => sanitizeInput($_POST['motto']),
                'travel_km' => sanitizeInput($_POST['travel_km'] ?? ''),
                'preferred_locations' => isset($_POST['preferred_locations']) ? (is_array($_POST['preferred_locations']) ? implode(', ', array_map('sanitizeInput', $_POST['preferred_locations'])) : sanitizeInput($_POST['preferred_locations'])) : '',
                'registration_id' => 'VOT' . date('Ymd') . '_' . uniqid(),
                'dob' => sanitizeInput($_POST['dob'] ?? ''),
        ];
        // Validate phone numbers
        if (!validatePhone($data['contact_no'])) {
            throw new Exception('Invalid contact number format');
        }
        if (!validatePhone($data['whatsapp_no'])) {
            throw new Exception('Invalid WhatsApp number format');
        }
        // Validate email if provided
        if (!empty($data['email']) && !validateEmail($data['email'])) {
            throw new Exception('Invalid email format');
        }
        // Validate blood group
        $validBloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        if (!in_array($data['blood_group'], $validBloodGroups)) {
            throw new Exception('Invalid blood group');
        }
        // Check for duplicate mobile number
        $checkDuplicate = $pdo->prepare("SELECT COUNT(*) FROM volunteers WHERE contact_no = ? OR whatsapp_no = ?");
        $checkDuplicate->execute([$data['contact_no'], $data['whatsapp_no']]);
        if ($checkDuplicate->fetchColumn() > 0) {
            throw new Exception('User already exists. If you have issues, please contact us or try logging in.');
        }
        // Handle file upload
        $uploadedFile = null;
        if (isset($_FILES['volunteer_photo']) && $_FILES['volunteer_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['volunteer_photo'];
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPEG, PNG, and GIF are allowed');
            }
            // Validate file size (max 5MB)
            $maxSize = 5 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                throw new Exception('File size too large. Maximum 5MB allowed');
            }
            // Create upload directory
            $uploadDir = 'volunteer_photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            // Generate filename
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'volunteer_' . $data['contact_no'] . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Failed to upload file');
            }
            $uploadedFile = $newFileName;
            $data['photo'] = $uploadedFile;
        }
            // In POST handler, set created_at and updated_at
            $now = date('Y-m-d H:i:s');
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
        // Insert into database
        $sql = "INSERT INTO volunteers (
                registration_id, first_name, last_name, dob, email, blood_group, 
            contact_no, whatsapp_no, qualification, occupation, address1, 
                address2, city, state, zip, donate, days, interests, motto, travel_km, preferred_locations, photo, created_at, updated_at
        ) VALUES (
                :registration_id, :first_name, :last_name, :dob, :email, :blood_group,
            :contact_no, :whatsapp_no, :qualification, :occupation, :address1,
                :address2, :city, :state, :zip, :donate, :days, :interests, :motto, :travel_km, :preferred_locations, :photo, :created_at, :updated_at
        )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Volunteer registration submitted successfully!',
            'registration_id' => $data['registration_id'],
            'uploaded_file' => $uploadedFile
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// API endpoints for AJAX requests (GET)
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    try {
        $pdo = initDatabase();
        switch ($_GET['action']) {
                case 'get_all_volunteers':
                $stmt = $pdo->query("SELECT * FROM volunteers ORDER BY submission_date DESC");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;
                case 'get':
                    $id = $_GET['id'] ?? '';
                    if (empty($id)) throw new Exception('ID required');
                    $stmt = $pdo->prepare("SELECT * FROM volunteers WHERE id = ?");
                    $stmt->execute([$id]);
                    $volunteer = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$volunteer) throw new Exception('Volunteer not found');
                    echo json_encode($volunteer);
                    break;
            case 'search':
                $mobile = $_GET['mobile'] ?? '';
                if (empty($mobile)) {
                    throw new Exception('Mobile number required');
                }
                $stmt = $pdo->prepare("SELECT * FROM volunteers WHERE contact_no LIKE ? OR whatsapp_no LIKE ?");
                $stmt->execute(["%$mobile%", "%$mobile%"]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($results);
                break;
            case 'get_stats':
                $stats = [
                    'total_volunteers' => $pdo->query("SELECT COUNT(*) FROM volunteers")->fetchColumn(),
                    'blood_donors' => $pdo->query("SELECT COUNT(*) FROM volunteers WHERE donate = 'Yes'")->fetchColumn(),
                    'recent_registrations' => $pdo->query("SELECT COUNT(*) FROM volunteers WHERE submission_date >= date('now', '-30 days')")->fetchColumn()
                ];
                // Blood group distribution
                $bloodGroups = $pdo->query("SELECT blood_group, COUNT(*) as count FROM volunteers GROUP BY blood_group")->fetchAll(PDO::FETCH_ASSOC);
                $stats['blood_groups'] = [];
                foreach ($bloodGroups as $bg) {
                    $stats['blood_groups'][$bg['blood_group']] = $bg['count'];
                }
                // City distribution
                $cities = $pdo->query("SELECT city, COUNT(*) as count FROM volunteers GROUP BY city ORDER BY count DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
                $stats['top_cities'] = [];
                foreach ($cities as $city) {
                    $stats['top_cities'][$city['city']] = $city['count'];
                }
                echo json_encode($stats);
                break;
            case 'delete':
                $id = $_GET['id'] ?? '';
                if (empty($id)) {
                    throw new Exception('ID required');
                }
                // Get photo filename before deletion
                $stmt = $pdo->prepare("SELECT photo FROM volunteers WHERE id = ?");
                $stmt->execute([$id]);
                $photo = $stmt->fetchColumn();
                // Delete from database
                $stmt = $pdo->prepare("DELETE FROM volunteers WHERE id = ?");
                $result = $stmt->execute([$id]);
                // Delete photo file if exists
                if ($photo && file_exists('volunteer_photos/' . $photo)) {
                    unlink('volunteer_photos/' . $photo);
                }
                echo json_encode(['success' => $result, 'message' => 'Volunteer deleted successfully']);
                break;
            case 'update_status':
                $id = $_GET['id'] ?? '';
                $status = $_GET['status'] ?? '';
                if (empty($id) || empty($status)) {
                    throw new Exception('ID and status required');
                }
                $stmt = $pdo->prepare("UPDATE volunteers SET status = ? WHERE id = ?");
                $result = $stmt->execute([$status, $id]);
                echo json_encode(['success' => $result, 'message' => 'Status updated successfully']);
                break;
            case 'deactivate':
                    $id = $_GET['id'] ?? '';
                    if (empty($id)) {
                        throw new Exception('ID required');
                    }
                    $stmt = $pdo->prepare("UPDATE volunteers SET status = 'inactive', updated_at = ? WHERE id = ?");
                    $result = $stmt->execute([date('Y-m-d H:i:s'), $id]);
                    echo json_encode(['success' => $result, 'message' => 'Volunteer deactivated successfully']);
                    break;
            case 'activate':
                    $id = $_GET['id'] ?? '';
                    if (empty($id)) {
                        throw new Exception('ID required');
                    }
                    $stmt = $pdo->prepare("UPDATE volunteers SET status = 'active', updated_at = ? WHERE id = ?");
                    $result = $stmt->execute([date('Y-m-d H:i:s'), $id]);
                    echo json_encode(['success' => $result, 'message' => 'Volunteer activated successfully']);
                    break;
            case 'update_volunteer':
                    $id = $_POST['id'] ?? '';
                    if (empty($id)) throw new Exception('ID required');
                    $fields = [
                        'first_name', 'last_name', 'email', 'blood_group', 'contact_no', 'whatsapp_no',
                        'qualification', 'occupation', 'address1', 'address2', 'city', 'state', 'zip',
                        'donate', 'days', 'interests', 'motto', 'status', 'travel_km', 'preferred_locations',
                        'dob',
                    ];
                    $set = [];
                    $params = [];
                    foreach ($fields as $f) {
                        if (isset($_POST[$f])) {
                            $set[] = "$f = ?";
                            $value = $_POST[$f];
                            // Handle array fields
                            if (in_array($f, ['days', 'interests', 'preferred_locations']) && is_array($value)) {
                                $value = implode(', ', array_map('sanitizeInput', $value));
                            } else {
                                $value = sanitizeInput($value);
                            }
                            $params[] = $value;
                        }
                    }
                    $set[] = "updated_at = ?";
                    $params[] = date('Y-m-d H:i:s');
                    $params[] = $id;
                    $sql = "UPDATE volunteers SET ".implode(", ", $set)." WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute($params);
                    echo json_encode(['success' => $result, 'message' => 'Volunteer updated successfully']);
                    break;
            case 'export_all_csv':
                    $stmt = $pdo->query("SELECT * FROM volunteers ORDER BY submission_date DESC");
                    $volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="votf_volunteer.csv"');
                    $out = fopen('php://output', 'w');
                    if (!empty($volunteers)) {
                        $headers = array_keys($volunteers[0]);
                        fputcsv($out, $headers);
                        foreach ($volunteers as $row) {
                            fputcsv($out, $row);
                        }
                    }
                    fclose($out);
                    break;
            case 'download_pdf_volunteer':
                        if (isset($_GET['ref'])) {
                            $ref = $_GET['ref'];
                            $stmt = $pdo->prepare("SELECT * FROM volunteers WHERE registration_id = :ref");
                            $stmt->bindValue(':ref', $ref, PDO::PARAM_STR);
                            $stmt->execute();
                            $volunteer = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if (!$volunteer) {
                                header('Content-Type: text/plain');
                                echo 'Reference not found.';
                                exit;
                            }
                            
                            // Use local logo and embed as base64
                            $logoPath = __DIR__ . '/logo.png';
                            $logoData = '';
                            if (file_exists($logoPath)) {
                                $logoData = base64_encode(file_get_contents($logoPath));
                            }

                       
                            // Signature image logic (use base64 data URI like logo)
                            $signaturePath = __DIR__ . '/signature.jpg';
                            $signatureData = '';
                            $signatureImg = '';
                            if (file_exists($signaturePath)) {
                                $signatureData = base64_encode(file_get_contents($signaturePath));
                                $signatureImg = '<img src="data:image/png;base64,' . $signatureData . '" alt="Signature" style="width:50px;max-width:100%;left:10px;height:auto;display:block;margin-bottom:4px;">';
                            }
                            
                         
                            // Generate QR code for authenticity
                            $qrBase64 = '';
                            try {
                                if (class_exists('Endroid\\QrCode\\QrCode')) {
                                    $qrData = 'https://volunteer.voiceoftenkasi.org/form-handler.php?profile=' . urlencode($volunteer['registration_id']);
                                    $qrCode = new Endroid\QrCode\QrCode($qrData);
                                    $qrCode->setSize(120);
                                    $qrCode->setMargin(2);
                                    if (class_exists('Endroid\\QrCode\\Writer\\PngWriter')) {
                                        $writer = new Endroid\QrCode\Writer\PngWriter();
                                        $qrImage = $writer->write($qrCode)->getString();
                                    } else {
                                        $qrImage = $qrCode->writeString();
                                    }
                                    $qrBase64 = base64_encode($qrImage);
                                }
                            } catch (Exception $e) { 
                                $qrBase64 = ''; 
                            }
                            // Improved HTML for ID card PDF
                            $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>VOTF ID_CARD' . htmlspecialchars($volunteer['registration_id']) . '</title><style>
                            @page { size: A4; margin: 15mm; }
                            body { margin: 0; padding: 0; font-family: "Inter", "Segoe UI", "Roboto", Arial, sans-serif; }
                            .card-container { display: table; width: 100%; text-align: center; }
                            .card-row { display: table-row; }
                            .card-cell { display: table-cell; width: 50%; vertical-align: top; padding: 10mm; }
                            .idcard { width: 64mm; height: 100mm; margin: 0 auto; background: #fff; border: 2px solid #3b5bdb; border-radius: 8px; overflow: hidden; position: relative; box-sizing: border-box; }
                            .front, .back { width: 64mm; height: 100mm; padding: 4mm; box-sizing: border-box; position: relative; text-align: center; }
                            .logo { max-width: 110px; max-height: 110px; margin: 0 auto 4mm auto; display: block; }
                            .logo-large { max-width: 100px; max-height: 130px; margin: 0 auto 8mm auto; display: block; }
                            .watermark-id { position: absolute; left: 0; top: 38%; width: 100%; text-align: center; font-size: 32px; color: rgba(59,91,219,0.08); font-weight: bold; letter-spacing: 2px; transform: rotate(-20deg); z-index: 0; pointer-events: none; user-select: none; }
.photo { width: 28mm;    height: 28mm; border-radius: 4px;      border: 2px solid #3b5bdb;   background: #f8f9fa;     display: block;     margin: 0 auto 2mm auto; object-fit: cover;      object-position: center;  }
.photo-frame {
    width: 28mm;
    height: 28mm;
    overflow: hidden;
    border: 2px solid #3b5bdb;
    border-radius: 4px;
    margin: 0 auto 2mm auto;
}

.photo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
                            .initials { width: 28mm; height: 28mm; border-radius: 4px; background: #e0e7ff; color: #3b5bdb; text-align: center; font-size: 22px; font-weight: bold; border: 2px solid #3b5bdb; margin: 0 auto 2mm auto; display: flex; align-items: center; justify-content: center; }
                            .volunteer-title { text-align: center; font-size: 13px; font-weight: bold; color: #3b5bdb; margin: 0 0 2mm 0; letter-spacing: 1px; }
                            .info-table { width: 100%; border-collapse: collapse; margin-top: 2mm; }
                            .info-table td { font-size: 9px; padding: 1.5px 0; }
                            .info-table .label { color: #3b5bdb; font-weight: bold; text-align: left; padding-right: 6px; width: 38%; }
                            .info-table .value { color: #222; font-weight: normal; text-align: right; width: 62%; }
                            .site { text-align: center; font-size: 13px; color: #3b5bdb; font-weight: bold; margin: 0 0 2mm 0; }
                            .socials { text-align: center; margin: 6mm 0 0 0; font-size: 9.5px; }
                            .socials .label { color: #3b5bdb; font-weight: bold; margin-right: 2mm; }
                            .socials .username { color: #222; font-weight: normal; margin-right: 4mm; }
                            .socials .insta { color: #e1306c; font-weight: bold; font-size: 10.5px; }
                            .verified { text-align: center; font-size: 10px; color: #27ae60; font-weight: bold; position: absolute; left: 0; right: 0; bottom: 7mm; z-index: 2; }
                            .gen-date { text-align: center; font-size: 8px; color: #888; position: absolute; left: 0; right: 0; bottom: 3.5mm; z-index: 2; }
                            .qr { width: 36mm; height: 36mm; display: block; margin: 0 auto 6mm auto; border-radius: 3px; border: 1.5px solid #e0e7ff; background: #fff; }
                            </style></head><body>';
                            $html .= '<div class="card-container"><div class="card-row">';
                            // FRONT SIDE
                            $html .= '<div class="card-cell"><div class="idcard front">';
                            $html .= '<div class="watermark-id">' . htmlspecialchars($volunteer['registration_id']) . '</div>';
                            if ($logoData) {
                                $html .= '<img src="data:image/png;base64,' . $logoData . '" class="logo" style="width:120px;max-width:60%;display:block;margin:0 auto 4mm auto;" alt="Logo">';
                            }
                            $html .= '<div class="volunteer-title">VOLUNTEER</div>';
                           if (!empty($volunteer['photo']) && file_exists('volunteer_photos/' . $volunteer['photo'])) {
    $photoData = base64_encode(file_get_contents('volunteer_photos/' . $volunteer['photo']));
    $html .= '<div class="photo-frame"><img src="data:image/jpeg;base64,' . $photoData . '" class="photo" alt="Photo"></div>';
} else {
    $initials = strtoupper(substr($volunteer['first_name'],0,1) . substr($volunteer['last_name'],0,1));
    $html .= '<div class="initials">' . $initials . '</div>';
}
                            $html .= '<table class="info-table">';
                            $html .= '<tr><td class="label">Name</td><td class="value">' . htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']) . '</td></tr>';
                            $html .= '<tr><td class="label">DOB</td><td class="value">' . htmlspecialchars($volunteer['dob'] ?: 'N/A') . '</td></tr>';
                            $html .= '<tr><td class="label">Blood</td><td class="value">' . htmlspecialchars($volunteer['blood_group']) . '</td></tr>';
                            $html .= '<tr><td class="label">City</td><td class="value">' . htmlspecialchars($volunteer['city']) . '</td></tr>';
                            $html .= '<tr><td class="label">Mobile</td><td class="value">' . htmlspecialchars($volunteer['contact_no']) . '</td></tr>';
                            $html .= '<tr><td class="label">Status</td><td class="value">' . htmlspecialchars(ucfirst($volunteer['status'])) . '</td></tr>';
                            $html .= '</table>';
                            $html .= '<div style="position:absolute;right:8mm;bottom:3mm;text-align:right;z-index:2;display:flex;flex-direction:column;align-items:flex-end;">'
                                . $signatureImg .
                                '<span style="font-family:cursive,\'Brush Script MT\',\'Segoe Script\',sans-serif;font-size:10px;font-style:italic;color:#222;display:block;margin-bottom:2px;">Ananthan Ayyasamy</span>'
                                .'<span style="font-size:9px;color:#3b5bdb;font-weight:bold;display:block;">Founder</span>'
                            .'</div>';

                            $html .= '</div></div>';
                            // BACK SIDE
                            $html .= '<div class="card-cell"><div class="idcard back" >';
                            if ($logoData) {
                                $html .= '<img src="data:image/png;base64,' . $logoData . '" class="logo" style="width:120px;max-width:60%;display:block;margin:0 auto 4mm auto;" alt="Logo">';
                            }
                            if ($qrBase64) {
                                $html .= '<img src="data:image/png;base64,' . $qrBase64 . '" class="qr" alt="QR Code" style="margin:0 auto 2mm auto;">';
                                $html .= '<div style="text-align:center;font-size:12px;font-weight:bold;color:#3b5bdb;margin:1mm 0 1mm 0;">' . htmlspecialchars($volunteer['registration_id']) . '</div>';
                                $html .= '<div style="text-align:center;font-size:10px;color:#27ae60;font-weight:bold;margin:2mm 0 1mm 0;">Digitally Generated</div>';
                                $html .= '<div style="text-align:center;font-size:9px;color:#888;margin:0 0 2mm 0;">Generated: ' . date('d M Y') . '</div>';
                            }
                           
                            $html .= '<div class="site" style="margin:2mm 0 2mm 0;">voiceoftenkasi.org</div>';
                            $html .= '<div style="left:0;right:0;bottom:2mm;text-align:center;font-size:9px;color:#3b5bdb;z-index:10;line-height:1.5;">
  <span>If found, Please inform <b>Voice of Tenkasi Foundation</b>.</span><br/>
  <span style="display:block;margin-top:1.5mm;font-size:6px;color:#222;">
    6/281A, Bharathi Thenvadal St, Viswanathaperi, <br/>
    Sivagiri Tk, Tenkasi Dt, 627757<br/>
    87782 00402<br/>
   info@voiceoftenkasi.org
  </span>
</div>';
                            $html .= '</div>';
                            $html .= '</div></div>';
                            $html .= '</div></div>';
                            $html .= '</body></html>';
                            
                            // Generate PDF
                            $dompdf = new Dompdf();
                            $options = new \Dompdf\Options();
                            $options->set('isRemoteEnabled', true);
                            $options->set('defaultFont', 'Arial');
                            $dompdf->setOptions($options);
                            $dompdf->loadHtml($html);
                            $dompdf->setPaper('A4', 'portrait');
                            $dompdf->render();
                            $dompdf->stream('Volunteer_ID_Card_' . $volunteer['registration_id'] . '.pdf', ["Attachment" => false]);
                            exit;
                        }
                        break;
                    case 'login':
                    $mobile = $_GET['mobile'] ?? '';
                    if (empty($mobile)) {
                        throw new Exception('Mobile number required');
                    }
                    $stmt = $pdo->prepare("SELECT registration_id FROM volunteers WHERE contact_no = ? OR whatsapp_no = ? LIMIT 1");
                    $stmt->execute([$mobile, $mobile]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result && $result['registration_id']) {
                        echo json_encode(['registration_id' => $result['registration_id']]);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'Mobile number not found.']);
                    }
                    break;
        }
    } catch (Exception $e) {
        http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
    }
    exit;
}

    // Public profile view: form-handler.php?profile=REGISTRATION_ID
    if (isset($_GET['profile']) && !isset($_GET['action'])) {
        $regid = $_GET['profile'];
        header('Content-Type: text/html; charset=utf-8');
        try {
            $pdo = initDatabase();
            $stmt = $pdo->prepare("SELECT * FROM volunteers WHERE registration_id = ?");
            $stmt->execute([$regid]);
            $v = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$v) {
                echo '<!DOCTYPE html><html><head><title>Profile Not Found</title><meta name="viewport" content="width=device-width,initial-scale=1"><style>body{font-family:sans-serif;background:#f8fafd;text-align:center;padding:40px;}h1{color:#e74c3c;}</style></head><body><h1>Profile Not Found</h1><p>No volunteer found for this registration ID.</p></body></html>';
                exit;
            }
            // Logo at the top of the card
                        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Volunteer Profile - Voice of Tenkasi</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"><style>body{font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;margin:0;padding:0;display:flex;align-items:center;justify-content:center;} .container{background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(59,91,219,0.13);padding:0 0 64px 0;max-width:410px;width:100%;text-align:center;position:relative;overflow:hidden;} .profile-header{background:linear-gradient(135deg,#3b5bdb 0%,#764ba2 100%);padding:28px 0 18px 0;} .profile-photo{width:92px;height:92px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(59,91,219,0.10);background:#f8fafd;object-fit:cover;margin-bottom:10px;} .profile-initials{width:92px;height:92px;border-radius:50%;background:#e0e7ff;color:#3b5bdb;display:flex;align-items:center;justify-content:center;font-size:2.2em;font-weight:700;margin:0 auto 10px auto;border:3px solid #fff;} .profile-name{color:#fff;font-size:1.45em;font-weight:700;letter-spacing:0.5px;} .profile-meta{color:#e0e7ff;font-size:1em;margin-top:2px;} .section-title{font-size:1.08em;font-weight:700;color:#3b5bdb;margin:18px 0 10px 0;text-align:left;letter-spacing:0.2px;} .profile-section{padding:0 22px 0 22px;text-align:left;} .profile-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:13px;font-size:1.04em;} .profile-row i{color:#3b5bdb;min-width:22px;text-align:center;font-size:1.1em;margin-top:2px;} .profile-label{font-weight:600;color:#3b5bdb;min-width:110px;display:inline-block;} .profile-value{color:#22223b;font-weight:500;flex:1;} .profile-badge{display:inline-block;padding:2px 12px;border-radius:12px;font-weight:600;font-size:13px;vertical-align:middle;background:#27ae60;color:#fff;margin-left:6px;} .sticky-actions{position:fixed;left:0;bottom:0;width:100vw;max-width:410px;margin:0 auto;background:rgba(255,255,255,0.98);box-shadow:0 -2px 16px rgba(59,91,219,0.10);padding:14px 0 10px 0;display:flex;gap:12px;justify-content:center;z-index:100;} .action-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border:none;border-radius:8px;font-size:1.08em;font-weight:600;cursor:pointer;transition:background 0.2s;text-decoration:none;} .action-btn.download{background:#27ae60;color:#fff;} .action-btn.copy{background:#3b5bdb;color:#fff;} .action-btn:active{transform:scale(0.97);} @media(max-width:500px){.container{padding:0 0 64px 0;max-width:98vw;} .sticky-actions{max-width:98vw;}} </style></head><body><div class="container">';
            echo '<div style="text-align:center;margin:0 0 10px 0;"><img src="logo.png" alt="Voice of Tenkasi Logo" style="max-width:200px;width:100%;margin:0 auto;display:inline-block;" /></div>';
            echo '<div class="container">';
            echo '<div class="profile-header">';
            if ($v['photo']) {
                echo "<img src='volunteer_photos/".htmlspecialchars($v['photo'])."' alt='Photo' class='profile-photo'>";
            } else {
                $initials = strtoupper(substr($v['first_name'],0,1).substr($v['last_name'],0,1));
                echo "<div class='profile-initials'>$initials</div>";
            }
            echo '<div class="profile-name">'.htmlspecialchars($v['first_name'].' '.$v['last_name']).'</div>';
            echo '<div class="profile-meta">Reg. ID: <b>'.htmlspecialchars($v['registration_id']).'</b></div>';
            echo '</div>';
            echo '<div class="profile-section">';
            // Personal Section
            echo '<div class="section-title"><i class="fa-solid fa-user"></i> Personal</div>';
            echo '<div class="profile-row"><i class="fa-solid fa-user-check"></i><span class="profile-label">Status</span><span class="profile-value">'.ucfirst($v['status']??'active').($v['status']==='active'?'<span class="profile-badge">Active</span>':'').'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-droplet"></i><span class="profile-label">Blood Group</span><span class="profile-value">'.htmlspecialchars($v['blood_group']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-city"></i><span class="profile-label">City</span><span class="profile-value">'.htmlspecialchars($v['city']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-graduation-cap"></i><span class="profile-label">Qualification</span><span class="profile-value">'.htmlspecialchars($v['qualification']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-briefcase"></i><span class="profile-label">Occupation</span><span class="profile-value">'.htmlspecialchars($v['occupation']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-cake-candles"></i><span class="profile-label">DOB</span><span class="profile-value">'.htmlspecialchars($v['dob'] ?: 'N/A').($v['dob'] ? ' (' . calculateAge($v['dob']) . ' yrs)' : '').'</span></div>';
            // Contact Section
            echo '<div class="section-title"><i class="fa-solid fa-address-book"></i> Contact</div>';
            echo '<div class="profile-row"><i class="fa-solid fa-phone"></i><span class="profile-label">Mobile</span><span class="profile-value">'.htmlspecialchars($v['contact_no']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-brands fa-whatsapp"></i><span class="profile-label">WhatsApp</span><span class="profile-value">'.htmlspecialchars($v['whatsapp_no']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-envelope"></i><span class="profile-label">Email</span><span class="profile-value">'.htmlspecialchars($v['email']?:'N/A').'</span></div>';
            // Volunteer Details Section
            echo '<div class="section-title"><i class="fa-solid fa-hand-holding-heart"></i> Volunteer Details</div>';
            echo '<div class="profile-row"><i class="fa-solid fa-calendar-plus"></i><span class="profile-label">Registered On</span><span class="profile-value">'.htmlspecialchars($v['created_at']?substr($v['created_at'],0,10):'').'</span></div>';
            // For Days Available
            $daysValue = '';
            if (isset($v['days'])) {
                if (is_array($v['days'])) {
                    $daysValue = '→';
                } else if (trim($v['days']) !== '') {
                    $daysValue = htmlspecialchars($v['days']);
                }
            }
            echo '<div class="profile-row"><i class="fa-solid fa-calendar-days"></i><span class="profile-label">Days Available</span><span class="profile-value">'.$daysValue.'</span></div>';
            // For Interests
            $interestsValue = '';
            if (isset($v['interests'])) {
                if (is_array($v['interests'])) {
                    $interestsValue = '→';
                } else if (trim($v['interests']) !== '') {
                    $interestsValue = htmlspecialchars($v['interests']);
                }
            }
            echo '<div class="profile-row"><i class="fa-solid fa-heart"></i><span class="profile-label">Interests</span><span class="profile-value">'.$interestsValue.'</span></div>';
            // For Preferred Locations
            $locationsValue = '';
            if (isset($v['preferred_locations'])) {
                if (is_array($v['preferred_locations'])) {
                    $locationsValue = '→';
                } else if (trim($v['preferred_locations']) !== '') {
                    $locationsValue = htmlspecialchars($v['preferred_locations']);
                } else {
                    $locationsValue = 'N/A';
                }
            } else {
                $locationsValue = 'N/A';
            }
            echo '<div class="profile-row"><i class="fa-solid fa-location-dot"></i><span class="profile-label">Preferred Locations</span><span class="profile-value">'.$locationsValue.'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-road"></i><span class="profile-label">Travel KM</span><span class="profile-value">'.htmlspecialchars($v['travel_km']?:'Not specified').'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-lightbulb"></i><span class="profile-label">Motivation</span><span class="profile-value">'.htmlspecialchars($v['motto']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-hand-holding-medical"></i><span class="profile-label">Donate Blood</span><span class="profile-value">'.htmlspecialchars($v['donate']).'</span></div>';
            echo '<div class="profile-row"><i class="fa-solid fa-share-nodes"></i><span class="profile-label">Social Media Work</span><span class="profile-value">'.htmlspecialchars($v['social_repost'] ?? 'N/A').'</span></div>';
            // Social Section
            echo '<div class="section-title"><i class="fa-solid fa-hashtag"></i> Social</div>';
            echo '<div class="profile-row"><i class="fa-brands fa-instagram"></i><span class="profile-label">Instagram</span><span class="profile-value">'.htmlspecialchars($v['instagram'] ?? 'N/A').'</span></div>';
            echo '<div class="profile-row"><i class="fa-brands fa-facebook"></i><span class="profile-label">Facebook</span><span class="profile-value">'.htmlspecialchars($v['facebook'] ?? 'N/A').'</span></div>';
            echo '<div class="profile-row"><i class="fa-brands fa-twitter"></i><span class="profile-label">Twitter</span><span class="profile-value">'.htmlspecialchars($v['twitter'] ?? 'N/A').'</span></div>';
            echo '<div class="profile-row"><i class="fa-brands fa-linkedin"></i><span class="profile-label">LinkedIn</span><span class="profile-value">'.htmlspecialchars($v['linkedin'] ?? 'N/A').'</span></div>';
            echo '</div>';
            // Action icons at the end of the card
            $profileUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?profile=' . urlencode($v['registration_id']);
            echo '<div style="margin:28px 0 0 0;display:flex;gap:18px;justify-content:center;">';
            echo '<a href="form-handler.php?action=download_pdf_volunteer&ref='.urlencode($v['registration_id']).'" target="_blank" class="action-btn download" title="Download PDF" style="background:#27ae60;color:#fff;"><i class="fa-solid fa-file-pdf"></i></a>';
            echo '<button class="action-btn copy" title="Copy Profile Link" style="background:#3b5bdb;color:#fff;" onclick="copyProfileLink()"><i class="fa-solid fa-link"></i></button>';
            echo '</div>';
            echo '<script>function copyProfileLink(){navigator.clipboard.writeText("'.htmlspecialchars($profileUrl).'").then(function(){alert("Profile link copied!");},function(){alert("Failed to copy link.");});}</script>';
            echo '</div></body></html>';
            exit;
        } catch (Exception $e) {
            echo '<!DOCTYPE html><html><head><title>Error</title><meta name="viewport" content="width=device-width,initial-scale=1"><style>body{font-family:sans-serif;background:#f8fafd;text-align:center;padding:40px;}h1{color:#e74c3c;}</style></head><body><h1>Error</h1><p>'.htmlspecialchars($e->getMessage()).'</p></body></html>';
            exit;
        }
    }

    // Add a PHP function to calculate age from DOB (supports both DD/MM/YYYY and YYYY-MM-DD):
    function calculateAge($dobStr) {
        if (!$dobStr) return '';
        // Support both formats
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dobStr)) {
            // YYYY-MM-DD
            list($year, $month, $day) = explode('-', $dobStr);
        } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dobStr)) {
            // DD/MM/YYYY
            list($day, $month, $year) = explode('/', $dobStr);
        } else {
            return '';
        }
        $dob = mktime(0, 0, 0, (int)$month, (int)$day, (int)$year);
        if (!$dob) return '';
        $now = time();
        $age = date('Y', $now) - $year;
        if (date('md', $now) < sprintf('%02d%02d', $month, $day)) {
            $age--;
        }
        return $age;
    }
