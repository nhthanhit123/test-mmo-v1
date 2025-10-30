<?php

/*
    MÃ NGUỒN VIẾT BỞI NGUYỄN THÀNH - CYBERLUX.VN
    Vui Lòng Không Xóa Để Tôn Trọng Tác Giả!
    Liên Hệ: 
     + Facebook: https://web.facebook.com/iamthanh.xDeveloper/
     + Zalo: 0764780360
*/


$nify = mysqli_connect(DBHOST, DBUSER, DBNAME, DBPASS) or die('Vui Lòng Kết Nối Database!');
$nify->set_charset("utf8");
mysqli_query($nify, "SET time_zone = '+07:00'");

function echoNify($name){
    global $nify;
    $query = $nify->query("SELECT * FROM `settings` WHERE `name` = '$name'")->fetch_assoc();
    return $query['value'];
}

function checkReconnect(&$conn) {
    if (!mysqli_ping($conn)) {
        mysqli_close($conn); 
        $conn = mysqli_connect(DBHOST, DBUSER, DBNAME, DBPASS) or die('Vui Lòng Kết Nối Database!');
    }
}

function sendTelegram($message){
    global $botToken, $chatId, $proxyString;

    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded'
        ],
        CURLOPT_TIMEOUT => 10,
    ]);

    if($proxyString){
        $parts = explode(':', $proxyString);
        if(count($parts) >= 2){
            $proxyIpPort = $parts[0] . ':' . $parts[1];
            curl_setopt($ch, CURLOPT_PROXY, $proxyIpPort);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); // SOCKS5

            if(isset($parts[2]) && isset($parts[3])){
                $proxyAuth = $parts[2] . ':' . $parts[3];
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
            }
        }
    }

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if($result === false){
        error_log("Telegram send failed: $error");
        return false;
    }

    return true;
}

function AntiXss($input){
    if(is_array($input)){
        return array_map('AntiXss', $input);
    }
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Remove potentially dangerous characters
    $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
    return $input;
}

function x($input){
    global $nify;
    $input = AntiXss($input);
    $input = mysqli_real_escape_string($nify, $input);
    // Additional SQL injection prevention
    $input = preg_replace('/(union|select|insert|update|delete|drop|create|alter|exec|script|javascript|vbscript|onload|onerror|onclick)/i', '', $input);
    return $input;
}

function redirect($url){
    return('<meta http-equiv="refresh" content="0;url='.$url.'">');
}

function isLogin(){
    if(isset($_COOKIE['remember_token'])){
        $token = AntiXss($_COOKIE['remember_token']);
        global $nify;
        $check = $nify->query("SELECT `id` FROM `users` WHERE `remember_token` = '$token' AND `status` = 'active'");
        if($check->num_rows > 0){
            return true;
        } else {
            // Clear invalid token
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
            return false;
        }
    } else {
        return false;
    }
}

function ToTime($time){
    return date('d/m/Y - h:i:s', $time);
}

function StatusVPS($status){
    switch($status){
        case 'pending':
        case '0':
            return '<span class="badge bg-info"> Đang Tạo </span>';
        case 'active':
            return '<span class="badge bg-success"> Hoạt Động </span>';
        case 'suspended':
            return '<span class="badge bg-danger" tooltip="Trạng thái tạm khóa có thể là VPS đang được backup hoặc bị Admin khóa (Nếu bạn nghĩ bị khóa hãy liên hệ hỗ trợ)."> Tạm Khóa </span>';
        case 'unactive':
            return '<span class="badge bg-danger"> Đã Tắt </span>';
        case 'expired':
            return '<span class="badge bg-warning"> Chờ Gia Hạn </span>';
        case 'endtime':
            return '<span class="badge bg-danger"> Hết Hạn </span>';
        case 'cancelled':
            return '<span class="badge bg-dark" tooltip="Có thể hệ thống đã hết IP, đã hoàn tiền, nếu có thắc mắc khác hãy liên hệ hỗ trợ."> Đã Hủy </span>';
        case '5':
            return '<span class="badge bg-danger"> Không Thanh Toán </span>';
        default:
            return '<span class="badge badge-dark badge bg-danger"> '.$status ?? 'Không Xác Định'.' </span>';
    }
}

function statusCpanel($status){
    if($status == '0'){
        return '<span class="badge badge-info badge bg-info"> Chờ Xử Lí </span>';
    } else if($status == '1'){
        return '<span class="badge badge-success badge bg-success"> Hoạt Động </span>';
    } else if($status == '2'){
        return '<span class="badge badge-warning badge bg-warning"> Chờ Gia Hạn </span>';
    } else if($status == '3'){
        return '<span class="badge badge-danger badge bg-danger"> Hết Hạn </span>';
    } else if($status == '4'){
        return '<span class="badge badge-danger badge bg-danger"> Bị Khóa </span>';
    } else if($status == '5'){
        return '<span class="badge badge-danger badge bg-danger"> Không Thanh Toán </span>';
    } else if($status == '6'){
        return '<span class="badge badge-dark badge bg-dark"> Hết Hạn Dùng Thử </span>';
    } else {
        return '<span class="badge badge-dark badge bg-danger"> '.$status ?? 'Không Xác Định'.' </span>';
    }
}

function StatusCard($status){
     if($status == '0'){
        return '<div class="text-primary"> Đang Kiểm Tra </div>';
    } else if($status == '1'){
        return '<div class="text-success"> Thẻ Đúng </div>';
    } else if($status == '2'){
        return '<div class="text-danger"> Thẻ Sai </div>';
    } else {
        return '<div class="text-danger"> Không Xác Định </div>';
    }
}

function smtpSend($receiverEmail, $receiverName, $subject, $content, $bccEmail){
    include 'smtp/class.smtp.php';
    include 'smtp/PHPMailerAutoload.php';
    include 'smtp/class.phpmailer.php';

    $mail = new PHPMailer();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = "html"; 
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = echoNify('SMTP_USERNAME'); 
    $mail->Password = echoNify('SMTP_PASSWORD');
    $mail->SMTPSecure = 'tls';
    $mail->protocol = 'mail';
    $mail->Port = 587;
    $mail->setFrom(echoNify('SMTP_USERNAME'), $bccEmail);
    $mail->addAddress($receiverEmail, $receiverName);
    $mail->addReplyTo(echoNify('SMTP_PASSWORD'), $bccEmail);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->CharSet = 'UTF-8';
    
    try{
        $send = $mail->send();
        return $send;
    } catch(Exception $e){
        return $mail->ErrorInfo;
    }
}

function cURLTo($url){
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if(curl_errno($ch)){
        return curl_errno($ch);
    } else {
        return $response;
    }
    
     curl_close($ch);
}

function isAdmin($level){
    if(isLogin()){
        if($level == 'admin'){
            return true;
        } else {
            return false;
        }
    } else {
            return false;
    }
}

function TruTienDichVu($time, $amount, $hsd){
    $ngayMua = $time; 
    $ngayHienTai = time(); 
    $soNgay = floor(($ngayHienTai - $ngayMua) / (60 * 60 * 24));

    $giaDichVu = $amount; 
    $tienDaDung = floor($soNgay * ($giaDichVu / $hsd));
    
    $tienConLai = max(0, $giaDichVu - $tienDaDung);
    return $tienConLai;
}

# Get User
if(isLogin()){
    $token = AntiXss($_COOKIE['remember_token']);
    $getUser = mysqli_query($nify, "SELECT * FROM `users` WHERE `remember_token` = '$token' AND `status` = 'active'")->fetch_assoc();
    if(!$getUser || $getUser['remember_token'] !== $token){
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        exit('Phiên Đăng Nhập Không Hợp Lệ.');
    }

    $nify->query("UPDATE `users` SET `ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '".$getUser['id']."'");
} else {
    $getUser = null;
}

# Enhanced Security Functions
function generateSecureToken($length = 32){
    return bin2hex(random_bytes($length));
}

function validateCSRF($token){
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitizeFilename($filename){
    $filename = AntiXss($filename);
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    return $filename;
}

function validateEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

function validatePhone($phone){
    return preg_match('/^[0-9]{10,11}$/', $phone);
}

function logSecurity($event, $details = ''){
    $logEntry = date('Y-m-d H:i:s') . " - IP: " . $_SERVER['REMOTE_ADDR'] . " - Event: " . $event . " - Details: " . $details . "\n";
    file_put_contents(__DIR__ . '/../security.log', $logEntry, FILE_APPEND | LOCK_EX);
}

# Rate Limiting
function checkRateLimit($key, $limit = 5, $window = 300){
    $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($key);
    $current = time();
    
    if(file_exists($cacheFile)){
        $data = json_decode(file_get_contents($cacheFile), true);
        if($data['reset_time'] < $current){
            $data = ['count' => 1, 'reset_time' => $current + $window];
        } else {
            $data['count']++;
            if($data['count'] > $limit){
                return false;
            }
        }
    } else {
        $data = ['count' => 1, 'reset_time' => $current + $window];
    }
    
    file_put_contents($cacheFile, json_encode($data));
    return true;
}

# Session Security
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

session_regenerate_id(true);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

# Set security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

# Generate CSRF token if not exists
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = generateSecureToken();
}
?>