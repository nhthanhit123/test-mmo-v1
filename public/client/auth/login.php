<?php
if(isLogin()){
    echo redirect('/');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = x($_POST['username']);
    $password = x($_POST['password']);
    $remember = isset($_POST['remember']) ? 1 : 0;
    
    if(empty($username) || empty($password)){
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    } else {
        $checkUser = $nify->query("SELECT * FROM `users` WHERE `username` = '$username' OR `email` = '$username' AND `status` = 'active'");
        if($checkUser->num_rows == 0){
            $error = 'Tài khoản không tồn tại hoặc đã bị khóa!';
        } else {
            $user = $checkUser->fetch_assoc();
            if(password_verify($password, $user['password'])){
                $token = md5($user['id'] . time() . rand(1000, 9999));
                $expires = $remember ? time() + (30 * 24 * 60 * 60) : time() + (24 * 60 * 60);
                
                setcookie('remember_token', $token, $expires, '/', '', false, true);
                $nify->query("UPDATE `users` SET `remember_token` = '$token', `ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '".$user['id']."'");
                
                echo redirect('/');
                exit();
            } else {
                $error = 'Mật khẩu không chính xác!';
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
                        <h3 class="nk-block-title page-title">Đăng Nhập</h3>
                        <div class="nk-block-des text-soft">
                            <p>Đăng nhập vào tài khoản của bạn</p>
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
                                        <h5 class="nk-block-title">Chào Mừng Trở Lại!</h5>
                                        <div class="nk-block-des">
                                            <p>Đăng nhập để truy cập vào tài khoản của bạn</p>
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
                                        <label class="form-label" for="username">Tên đăng nhập hoặc Email</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nhập tên đăng nhập hoặc email" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="password">Mật khẩu</label>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Nhập mật khẩu" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="custom-control custom-control-xs custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="remember" name="remember" <?= isset($_POST['remember']) ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="remember">Ghi nhớ đăng nhập</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Đăng Nhập</button>
                                    </div>
                                </form>
                                
                                <div class="form-note-s2 text-center pt-4">
                                    <p>Chưa có tài khoản? <a href="/auth/register">Đăng ký ngay</a></p>
                                    <p><a href="/auth/forgot">Quên mật khẩu?</a></p>
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
    const passcodeSwitch = document.querySelector('.passcode-switch');
    const passwordInput = document.getElementById('password');
    
    if(passcodeSwitch && passwordInput) {
        passcodeSwitch.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const iconShow = this.querySelector('.icon-show');
            const iconHide = this.querySelector('.icon-hide');
            
            if(type === 'text') {
                iconShow.style.display = 'none';
                iconHide.style.display = 'block';
            } else {
                iconShow.style.display = 'block';
                iconHide.style.display = 'none';
            }
        });
    }
});
</script>