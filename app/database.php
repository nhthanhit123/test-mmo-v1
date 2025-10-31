<?php
if(session_status() === PHP_SESSION_NONE) session_start();

define("DBHOST", "LOCALHOST");
define("DBNAME", "arownmqdn9q_datasbe");
define("DBUSER", "arownmqdn9q_datasbe");
define("DBPASS", "arownmqdn9q_datasbe");

# Cài Đặt Website
# Thêm Các Config Tĩnh Nếu Muốn

date_default_timezone_set('Asia/Ho_Chi_Minh');
// header_remove('Set-Cookie');
include('ini.php');
?>