<?php

/*
    MÃ NGUỒN VIẾT BỞI NGUYỄN THÀNH - CYBERLUX.VN
    Vui Lòng Không Xóa Để Tôn Trọng Tác Giả!
    Liên Hệ: 
     + Facebook: https://web.facebook.com/iamthanh.xDeveloper/
     + Zalo: 0764780360
*/

$routes = [
    '/' => 'public/client/index.php',
    '/auth/login' => 'public/client/auth/login.php',
    '/auth/register' => 'public/client/auth/register.php',
    '/auth/forgot' => 'public/client/auth/forgot.php',
    '/auth/reset' => 'public/client/auth/reset.php',
    '/deposit' => 'public/client/deposit.php'
];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$get = [];

$pathParts = explode('/', trim($path, '/'));
$basePath = '';
$foundRoute = false;

for($i = 0; $i < count($pathParts); $i++){
    if($i > 0){
        $basePath .= '/';
    }
    $basePath .= $pathParts[$i];
    
    if(array_key_exists('/' . $basePath, $routes)){
        $foundRoute = true;
        $routePath = '/' . $basePath;
        
        for($j = $i + 1; $j < count($pathParts); $j++){
            $get[] = $pathParts[$j];
        }
        
        break;
    }
}

if($foundRoute){
    # Xử Lí Ajaxs
    if(strpos($routePath, '/ajax/') === 0 || strpos($routePath, '/cron/') === 0){   
        if(strpos($routePath, '/ajax/admin/') === 0){
            # Check Điều Kiện Ajax Admin
        } else {
            header('Content-Type: application/json');
            include $routes[$routePath]; 
        }
    } else 
    
    # Xử Lí Logic Admin 
    if(strpos($routePath, '/admin/') === 0){   
        # Check Điều Kiện Admin
    } else {
        include('public/client/layouts/header.php');
        include $routes[$routePath];
        include('public/client/layouts/footer.php');
    }
} else {
    include('public/404.php');
}
?>
