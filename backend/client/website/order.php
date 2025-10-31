<?php
header('Content-Type: application/json; charset=utf-8');

if(!isLogin()){
    echo json_encode(['status' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if(!$data || !isset($data['template_id']) || !isset($data['package_type'])){
    echo json_encode(['status' => false, 'message' => 'Dữ liệu không hợp lệ!']);
    exit;
}

$templateId = (int)$data['template_id'];
$packageType = x($data['package_type']);
$domain = isset($data['domain']) ? x($data['domain']) : '';
$dotId = isset($data['dot_id']) ? (int)$data['dot_id'] : 0;

// Validate package type
if(!in_array($packageType, ['monthly', 'quarterly', 'yearly'])){
    echo json_encode(['status' => false, 'message' => 'Gói thuê không hợp lệ!']);
    exit;
}

// Check template exists
$template = $nify->query("SELECT * FROM `website_templates` WHERE `id` = '$templateId' AND `status` = 'active'")->fetch_assoc();
if(!$template){
    echo json_encode(['status' => false, 'message' => 'Giao diện không tồn tại!']);
    exit;
}

// Calculate price
$price = $template['price'];
switch($packageType){
    case 'quarterly':
        $price = $price * 3 * 0.9; // 10% discount
        break;
    case 'yearly':
        $price = $price * 12 * 0.8; // 20% discount
        break;
}

// Add domain price if selected
if($dotId > 0){
    $dot = $nify->query("SELECT * FROM `dots` WHERE `id` = '$dotId' AND `status` = 'active'")->fetch_assoc();
    if($dot){
        $price += $dot['price'];
        $domain .= $dot['extension'];
    }
}

// Check user balance
if($getUser['balance'] < $price){
    echo json_encode(['status' => false, 'message' => 'Số dư không đủ! Vui lòng nạp thêm tiền.']);
    exit;
}

// Calculate expiry date
$expiresAt = date('Y-m-d H:i:s');
switch($packageType){
    case 'monthly':
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 month'));
        break;
    case 'quarterly':
        $expiresAt = date('Y-m-d H:i:s', strtotime('+3 months'));
        break;
    case 'yearly':
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));
        break;
}

// Create order
$nify->begin_transaction();

try {
    // Deduct balance
    $nify->query("UPDATE `users` SET `balance` = `balance` - '$price' WHERE `id` = '".$getUser['id']."'");
    
    // Create website order
    $nify->query("INSERT INTO `website_orders` (`user_id`, `template_id`, `domain`, `dot_id`, `package_type`, `price`, `status`, `expires_at`) VALUES ('".$getUser['id']."', '$templateId', '$domain', '$dotId', '$packageType', '$price', 'pending', '$expiresAt')");
    
    if($nify->affected_rows > 0){
        $nify->commit();
        echo json_encode(['status' => true, 'message' => 'Đặt hàng thành công!']);
    } else {
        $nify->rollback();
        echo json_encode(['status' => false, 'message' => 'Tạo đơn hàng thất bại!']);
    }
} catch (Exception $e) {
    $nify->rollback();
    echo json_encode(['status' => false, 'message' => 'Có lỗi xảy ra!']);
}
?>