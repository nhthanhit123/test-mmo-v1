<?php
if(isLogin()){
    echo redirect('/');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = x($_POST['username']);
    $email = x($_POST['email']);
    $password = x($_POST['password']);
    $confirm_password = x($_POST['confirm_password']);
    $phone = x($_POST['phone']);
    
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)){
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    } elseif(strlen($username) < 3 || strlen($username) > 50){
        $error = 'Tên đăng nhập phải từ 3-50 ký tự!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Email không hợp lệ!';
    } elseif(strlen($password) < 6){
        $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } elseif($password !== $confirm_password){
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif(!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)){
        $error = 'Số điện thoại không hợp lệ!';
    } else {
        $checkUsername = $nify->query("SELECT `id` FROM `users` WHERE `username` = '$username'")->num_rows;
        $checkEmail = $nify->query("SELECT `id` FROM `users` WHERE `email` = '$email'")->num_rows;
        
        if($checkUsername > 0){
            $error = 'Tên đăng nhập đã tồn tại!';
        } elseif($checkEmail > 0){
            $error = 'Email đã được sử dụng!';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $nify->query("INSERT INTO `users` (`username`, `email`, `password`, `phone`, `ip`, `created_at`) VALUES ('$username', '$email', '$hashedPassword', '$phone', '".$_SERVER['REMOTE_ADDR']."', '".time()."')");
            
            if($nify->affected_rows > 0){
                $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
                echo redirect('/auth/login?success=' . urlencode($success));
                exit();
            } else {
                $error = 'Đăng ký thất bại! Vui lòng thử lại.';
            }
        }
    }
}
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Đăng Ký</h3>
                        <div class="nk-block-des text-soft">
                            <p>Tạo tài khoản mới để bắt đầu</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="nk-block">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="card card-bordered">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Đăng Ký Tài Khoản Mới</h5>
                                        <div class="nk-block-des">
                                            <p>Tạo tài khoản để trải nghiệm dịch vụ của chúng tôi</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if(isset($error)): ?>
                                <div class="alert alert-danger alert-icon">
                                    <em class="icon ni ni-cross-circle"></em>
                                    <strong>Thất bại!</strong> <?= $error ?>
                                </div>
                                <?php endif; ?>
                                
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="form-label" for="username">Tên đăng nhập</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nhập tên đăng nhập (3-50 ký tự)" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email</label>
                                        <div class="form-control-wrap">
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Nhập email của bạn" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="phone">Số điện thoại</label>
                                        <div class="form-control-wrap">
                                            <input type="tel" class="form-control form-control-lg" id="phone" name="phone" placeholder="Nhập số điện thoại (không bắt buộc)" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="password">Mật khẩu</label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="confirm_password">Xác nhận mật khẩu</label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="confirm_password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="custom-control custom-control-xs custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="agree" name="agree" required>
                                            <label class="custom-control-label" for="agree">Tôi đồng ý với <a href="#" target="_blank">điều khoản sử dụng</a></label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Đăng Ký</button>
                                    </div>
                                </form>
                                
                                <div class="form-note-s2 text-center pt-4">
                                    <p>Đã có tài khoản? <a href="/auth/login">Đăng nhập ngay</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passcodeSwitches = document.querySelectorAll('.passcode-switch');
    
    passcodeSwitches.forEach(function(switchElement) {
        switchElement.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            
            if(targetInput) {
                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);
                
                const iconShow = this.querySelector('.icon-show');
                const iconHide = this.querySelector('.icon-hide');
                
                if(type === 'text') {
                    iconShow.style.display = 'none';
                    iconHide.style.display = 'block';
                } else {
                    iconShow.style.display = 'block';
                    iconHide.style.display = 'none';
                }
            }
        });
    });
});
</script>