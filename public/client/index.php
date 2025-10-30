<?php
// Get statistics
$totalUsers = $nify->query("SELECT COUNT(*) as count FROM `users` WHERE `status` = 'active'")->fetch_assoc()['count'];
$totalDeposits = $nify->query("SELECT COUNT(*) as count FROM `deposits` WHERE `status` = 'success'")->fetch_assoc()['count'];
$totalRevenue = $nify->query("SELECT SUM(amount) as total FROM `deposits` WHERE `status` = 'success'")->fetch_assoc()['total'];
$todayRevenue = $nify->query("SELECT SUM(amount) as total FROM `deposits` WHERE `status` = 'success' AND DATE(created_at) = CURDATE()")->fetch_assoc()['total'];

// Get recent deposits
$recentDeposits = $nify->query("SELECT d.*, u.username FROM `deposits` d JOIN `users` u ON d.user_id = u.id WHERE d.status = 'success' ORDER BY d.created_at DESC LIMIT 5");

// Get site settings
$siteName = echoNify('site_name') ?: 'MMO Platform';
$siteDescription = echoNify('site_description') ?: 'Nền tảng MMO uy tín hàng đầu';
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <!-- Hero Section -->
            <div class="nk-block nk-block-lg">
                <div class="bg-primary bg-gradient-dim is-dark rounded-4 p-5">
                    <div class="row align-items-center g-gs">
                        <div class="col-lg-8">
                            <div class="nk-block-text">
                                <h1 class="title fw-bold mb-3">Chào Mừng Đến Với <?= $siteName ?></h1>
                                <p class="lead mb-4"><?= $siteDescription ?> - Nền tảng kiếm tiền online uy tín, an toàn và hiệu quả.</p>
                                <div class="d-flex flex-wrap gap-3">
                                    <?php if(!isLogin()): ?>
                                    <a href="/auth/register" class="btn btn-lg btn-white">
                                        <em class="icon ni ni-user-add me-2"></em>Đăng Ký Ngay
                                    </a>
                                    <a href="/auth/login" class="btn btn-lg btn-outline-light">
                                        <em class="icon ni ni-signin me-2"></em>Đăng Nhập
                                    </a>
                                    <?php else: ?>
                                    <a href="/deposit" class="btn btn-lg btn-white">
                                        <em class="icon ni ni-coins me-2"></em>Nạp Tiền
                                    </a>
                                    <a href="/dashboard" class="btn btn-lg btn-outline-light">
                                        <em class="icon ni ni-dashboard me-2"></em>Dashboard
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="nk-block-img text-center">
                                <div class="nk-block-icon nk-block-icon-lg">
                                    <em class="icon ni ni-growth"></em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="nk-block nk-block-sm">
                <div class="row g-gs">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-inner">
                                <div class="d-flex justify-content-between align-center">
                                    <div>
                                        <div class="text-soft">Tổng Người Dùng</div>
                                        <div class="h2 fw-bold mt-1"><?= number_format($totalUsers) ?></div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-primary text-white">
                                            <em class="icon ni ni-users"></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-inner">
                                <div class="d-flex justify-content-between align-center">
                                    <div>
                                        <div class="text-soft">Giao Dịch Hôm Nay</div>
                                        <div class="h2 fw-bold mt-1"><?= number_format($todayRevenue ?: 0, 0, ',', '.') ?>đ</div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-success text-white">
                                            <em class="icon ni ni-trend-up"></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-inner">
                                <div class="d-flex justify-content-between align-center">
                                    <div>
                                        <div class="text-soft">Tổng Doanh Thu</div>
                                        <div class="h2 fw-bold mt-1"><?= number_format($totalRevenue ?: 0, 0, ',', '.') ?>đ</div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-info text-white">
                                            <em class="icon ni ni-coins"></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-inner">
                                <div class="d-flex justify-content-between align-center">
                                    <div>
                                        <div class="text-soft">Tổng Giao Dịch</div>
                                        <div class="h2 fw-bold mt-1"><?= number_format($totalDeposits) ?></div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-warning text-white">
                                            <em class="icon ni ni-repeat"></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">Tính Năng Nổi Bật</h4>
                        <p>Khám phá các tính năng tuyệt vời của nền tảng chúng tôi</p>
                    </div>
                </div>
                <div class="row g-gs">
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="nk-block-icon nk-block-icon-lg mb-3">
                                    <em class="icon ni ni-shield-check text-primary"></em>
                                </div>
                                <h5 class="title">Bảo Mật Tối Ưu</h5>
                                <p class="text-soft">Hệ thống bảo mật nhiều lớp, mã hóa token MD5, chống XSS và SQL Injection</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="nk-block-icon nk-block-icon-lg mb-3">
                                    <em class="icon ni ni-credit-card text-success"></em>
                                </div>
                                <h5 class="title">Nạp Tiền Đa Dạng</h5>
                                <p class="text-soft">Hỗ trợ nạp tiền qua thẻ cào và chuyển khoản ngân hàng nhanh chóng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="nk-block-icon nk-block-icon-lg mb-3">
                                    <em class="icon ni ni-speed text-info"></em>
                                </div>
                                <h5 class="title">Xử Lý Nhanh</h5>
                                <p class="text-soft">Giao dịch được xử lý tự động, nhanh chóng và chính xác</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="nk-block-icon nk-block-icon-lg mb-3">
                                    <em class="icon ni ni-support text-warning"></em>
                                </div>
                                <h5 class="title">Hỗ Trợ 24/7</h5>
                                <p class="text-soft">Đội ngũ hỗ trợ chuyên nghiệp luôn sẵn sàng giải đáp thắc mắc</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="nk-block-icon nk-block-icon-lg mb-3">
                                    <em class="icon ni ni-growth text-danger"></em>
                                </div>
                                <h5 class="title">Phát Triển Bền Vững</h5>
                                <p class="text-soft">Luôn cập nhật tính năng mới và cải thiện trải nghiệm người dùng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner text-center">
                                <div class="nk-block-icon nk-block-icon-lg mb-3">
                                    <em class="icon ni ni-mobile text-purple"></em>
                                </div>
                                <h5 class="title">Tương Thích Đa Thiết Bị</h5>
                                <p class="text-soft">Giao diện responsive, hoạt động tốt trên mọi thiết bị</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">Giao Dịch Gần Đây</h4>
                        <p>Các giao dịch thành công gần đây trên nền tảng</p>
                    </div>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner p-0">
                        <div class="nk-tb-list nk-tb-flush">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col"><span>Người dùng</span></div>
                                <div class="nk-tb-col"><span>Số tiền</span></div>
                                <div class="nk-tb-col"><span>Phương thức</span></div>
                                <div class="nk-tb-col"><span>Thời gian</span></div>
                            </div>
                            <?php if($recentDeposits->num_rows > 0): ?>
                                <?php while($deposit = $recentDeposits->fetch_assoc()): ?>
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar xs bg-primary">
                                                <span><?= strtoupper(substr($deposit['username'], 0, 1)) ?></span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="tb-lead"><?= htmlspecialchars($deposit['username']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-tb-col">
                                        <span class="tb-lead text-success">+<?= number_format($deposit['amount'], 0, ',', '.') ?>đ</span>
                                    </div>
                                    <div class="nk-tb-col">
                                        <?php if($deposit['type'] == 'card'): ?>
                                            <span class="badge bg-info">Thẻ cào</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Ngân hàng</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="nk-tb-col">
                                        <span class="tb-sub"><?= date('H:i d/m', strtotime($deposit['created_at'])) ?></span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col">
                                        <div class="text-center py-4">
                                            <em class="icon ni ni-info"></em>
                                            <p>Chưa có giao dịch nào.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="nk-block nk-block-lg">
                <div class="bg-dark bg-gradient-dim rounded-4 p-5 text-center">
                    <div class="nk-block-text">
                        <h2 class="title fw-bold mb-3">Bắt Đầu Kiếm Tiền Ngay Hôm Nay!</h2>
                        <p class="lead mb-4">Tham gia cùng hàng ngàn người dùng đang kiếm tiền hiệu quả trên nền tảng của chúng tôi</p>
                        <?php if(!isLogin()): ?>
                        <a href="/auth/register" class="btn btn-lg btn-primary">
                            <em class="icon ni ni-rocket me-2"></em>Đăng Ký Ngay
                        </a>
                        <?php else: ?>
                        <a href="/deposit" class="btn btn-lg btn-primary">
                            <em class="icon ni ni-coins me-2"></em>Nạp Tiền Ngay
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>