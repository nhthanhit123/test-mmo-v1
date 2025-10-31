<?php
// Get template ID from URL
$templateId = isset($get[0]) ? (int)$get[0] : 0;

if(!$templateId){
    echo redirect('/kho-giao-dien');
    exit();
}

// Get template details
$template = $nify->query("SELECT * FROM `website_templates` WHERE `id` = '$templateId' AND `status` = 'active'")->fetch_assoc();

if(!$template){
    echo redirect('/kho-giao-dien');
    exit();
}

// Get domain extensions
$dots = $nify->query("SELECT * FROM `dots` WHERE `status` = 'active' ORDER BY `price` ASC");

// Calculate package prices
$monthlyPrice = $template['price'];
$quarterlyPrice = $monthlyPrice * 3 * 0.9; // 10% discount
$yearlyPrice = $monthlyPrice * 12 * 0.8; // 20% discount
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between g-3">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Thuê Website</h3>
                        <div class="nk-block-des text-soft">
                            <p>Chọn gói thuê phù hợp với nhu cầu của bạn</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="/kho-giao-dien" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em>
                            <span>Quay lại</span>
                        </a>
                        <a href="/kho-giao-dien" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none">
                            <em class="icon ni ni-arrow-left"></em>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <div class="card">
                    <div class="card-inner">
                        <div class="row pb-5">
                            <div class="col-lg-6">
                                <div class="product-gallery me-xl-1 me-xxl-5">
                                    <div class="slider-init" id="sliderFor">
                                        <div class="slider-item rounded">
                                            <img src="<?= $template['thumbnail'] ?: 'https://via.placeholder.com/600x400/4a90e2/ffffff?text=' . urlencode($template['name']) ?>" class="rounded w-100" alt="<?= htmlspecialchars($template['name']) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="product-info mt-5 me-xxl-5">
                                    <h4 class="product-price text-primary">
                                        <?= number_format($monthlyPrice, 0, ',', '.') ?>đ
                                        <small class="text-muted fs-14px">/tháng</small>
                                    </h4>
                                    <h2 class="product-title"><?= htmlspecialchars($template['name']) ?></h2>
                                    
                                    <div class="product-excrept text-soft">
                                        <p class="lead"><?= htmlspecialchars($template['description']) ?></p>
                                    </div>

                                    <div class="product-meta">
                                        <ul class="d-flex g-3 gx-5">
                                            <li>
                                                <div class="fs-14px text-muted">Danh mục</div>
                                                <div class="fs-16px fw-bold text-secondary"><?= ucfirst($template['category']) ?></div>
                                            </li>
                                            <li>
                                                <div class="fs-14px text-muted">Mã giao diện</div>
                                                <div class="fs-16px fw-bold text-secondary">#<?= str_pad($template['id'], 4, '0', STR_PAD_LEFT) ?></div>
                                            </li>
                                        </ul>
                                    </div>

                                    <?php if($template['features']): ?>
                                    <div class="product-meta">
                                        <h6 class="title">Tính năng nổi bật</h6>
                                        <div class="row g-2">
                                            <?php 
                                            $features = json_decode($template['features'], true);
                                            if(is_array($features)):
                                            ?>
                                            <?php foreach($features as $feature): ?>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <em class="icon ni ni-check-circle-fill text-success me-2"></em>
                                                    <span class="small"><?= htmlspecialchars($feature) ?></span>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="product-meta">
                                        <h6 class="title">Chọn gói thuê</h6>
                                        <ul class="custom-control-group">
                                            <li>
                                                <div class="custom-control custom-radio custom-control-pro no-control checked">
                                                    <input type="radio" class="custom-control-input" name="package_type" id="package_monthly" value="monthly" checked>
                                                    <label class="custom-control-label" for="package_monthly">
                                                        <span class="fw-bold">1 Tháng</span>
                                                        <span class="text-primary d-block"><?= number_format($monthlyPrice, 0, ',', '.') ?>đ</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-radio custom-control-pro no-control">
                                                    <input type="radio" class="custom-control-input" name="package_type" id="package_quarterly" value="quarterly">
                                                    <label class="custom-control-label" for="package_quarterly">
                                                        <span class="fw-bold">3 Tháng</span>
                                                        <span class="text-primary d-block"><?= number_format($quarterlyPrice, 0, ',', '.') ?>đ</span>
                                                        <span class="badge bg-success small">Giảm 10%</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-radio custom-control-pro no-control">
                                                    <input type="radio" class="custom-control-input" name="package_type" id="package_yearly" value="yearly">
                                                    <label class="custom-control-label" for="package_yearly">
                                                        <span class="fw-bold">12 Tháng</span>
                                                        <span class="text-primary d-block"><?= number_format($yearlyPrice, 0, ',', '.') ?>đ</span>
                                                        <span class="badge bg-success small">Giảm 20%</span>
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="product-meta">
                                        <h6 class="title">Tên miền (không bắt buộc)</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="domain_name" placeholder="Nhập tên miền">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-select" id="domain_extension">
                                                        <option value="">-- Chọn đuôi miền --</option>
                                                        <?php while($dot = $dots->fetch_assoc()): ?>
                                                        <option value="<?= $dot['id'] ?>" data-price="<?= $dot['price'] ?>">
                                                            <?= $dot['extension'] ?> (+<?= number_format($dot['price'], 0, ',', '.') ?>đ)
                                                        </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-meta">
                                        <div class="alert alert-info">
                                            <div class="d-flex align-items-center">
                                                <em class="icon ni ni-info-fill me-2"></em>
                                                <div>
                                                    <strong>Tổng thanh toán:</strong>
                                                    <span id="total_price" class="h5 text-primary mb-0"><?= number_format($monthlyPrice, 0, ',', '.') ?>đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-meta">
                                        <ul class="d-flex flex-wrap align-center g-2 pt-1">
                                            <li>
                                                <button class="btn btn-primary" id="btn_order" data-template-id="<?= $template['id'] ?>">
                                                    <em class="icon ni ni-cart me-2"></em>Thuê Ngay
                                                </button>
                                            </li>
                                            <li>
                                                <a href="<?= $template['demo_url'] ?>" target="_blank" class="btn btn-outline-light">
                                                    <em class="icon ni ni-eye me-2"></em>Xem Demo
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="hr border-light">
                        
                        <div class="row g-gs pt-5">
                            <div class="col-lg-12">
                                <div class="product-details entry">
                                    <h3>Chi tiết giao diện</h3>
                                    <p><?= nl2br(htmlspecialchars($template['description'])) ?></p>
                                    
                                    <?php if($template['demo_url']): ?>
                                    <div class="alert alert-success">
                                        <em class="icon ni ni-external me-2"></em>
                                        <strong>Link Demo:</strong> <a href="<?= $template['demo_url'] ?>" target="_blank"><?= htmlspecialchars($template['demo_url']) ?></a>
                                    </div>
                                    <?php endif; ?>
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
    const packageInputs = document.querySelectorAll('input[name="package_type"]');
    const domainExtension = document.getElementById('domain_extension');
    const totalPriceElement = document.getElementById('total_price');
    const btnOrder = document.getElementById('btn_order');
    
    const prices = {
        monthly: <?= $monthlyPrice ?>,
        quarterly: <?= $quarterlyPrice ?>,
        yearly: <?= $yearlyPrice ?>
    };

    function calculateTotal() {
        let selectedPackage = document.querySelector('input[name="package_type"]:checked').value;
        let total = prices[selectedPackage];
        
        let selectedDot = domainExtension.options[domainExtension.selectedIndex];
        if(selectedDot && selectedDot.value) {
            total += parseFloat(selectedDot.dataset.price);
        }
        
        totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    packageInputs.forEach(input => {
        input.addEventListener('change', calculateTotal);
    });

    domainExtension.addEventListener('change', calculateTotal);

    btnOrder.addEventListener('click', function() {
        const templateId = this.dataset.templateId;
        const packageType = document.querySelector('input[name="package_type"]:checked').value;
        const domainName = document.getElementById('domain_name').value.trim();
        const dotId = document.getElementById('domain_extension').value;
        
        if(!isLogin) {
            alert('Vui lòng đăng nhập để thuê website!');
            window.location.href = '/auth/login';
            return;
        }

        const orderData = {
            template_id: templateId,
            package_type: packageType,
            domain: domainName,
            dot_id: dotId,
            csrf_token: document.querySelector('input[name="csrf_token"]').value
        };

        // Show loading
        this.disabled = true;
        this.innerHTML = '<em class="icon ni ni-spinner ni-spin"></em> Đang xử lý...';

        fetch('/ajaxs/client/website/order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                alert('Đặt hàng thành công! Vui lòng thanh toán để kích hoạt dịch vụ.');
                window.location.href = '/manage/web';
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra! Vui lòng thử lại.');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<em class="icon ni ni-cart me-2"></em>Thuê Ngay';
        });
    });
});
</script>

<?php csrf_field(); ?>