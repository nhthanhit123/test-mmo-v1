<?php
// Get user's website orders
$status = isset($_GET['status']) ? x($_GET['status']) : '';

$whereClause = "WHERE `user_id` = '".$getUser['id']."'";
if(!empty($status)){
    $whereClause .= " AND `status` = '$status'";
}

$orders = $nify->query("SELECT wo.*, wt.name as template_name, wt.category, d.extension as domain_extension 
                       FROM `website_orders` wo 
                       LEFT JOIN `website_templates` wt ON wo.template_id = wt.id 
                       LEFT JOIN `dots` d ON wo.dot_id = d.id 
                       $whereClause 
                       ORDER BY wo.created_at DESC");

// Get statistics
$totalOrders = $nify->query("SELECT COUNT(*) as count FROM `website_orders` WHERE `user_id` = '".$getUser['id']."'")->fetch_assoc()['count'];
$activeOrders = $nify->query("SELECT COUNT(*) as count FROM `website_orders` WHERE `user_id` = '".$getUser['id']."' AND `status` = 'active'")->fetch_assoc()['count'];
$pendingOrders = $nify->query("SELECT COUNT(*) as count FROM `website_orders` WHERE `user_id` = '".$getUser['id']."' AND `status` = 'pending'")->fetch_assoc()['count'];
$expiredOrders = $nify->query("SELECT COUNT(*) as count FROM `website_orders` WHERE `user_id` = '".$getUser['id']."' AND `status` = 'expired'")->fetch_assoc()['count'];
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Trang Web Của Tôi</h3>
                        <div class="nk-block-des text-soft">
                            <p>Bạn có tổng <?= $totalOrders ?> dịch vụ website</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                <em class="icon ni ni-menu-alt-r"></em>
                            </a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-bs-toggle="dropdown">
                                                <em class="d-none d-sm-inline icon ni ni-filter-alt"></em>
                                                <span>Lọc theo trạng thái</span>
                                                <em class="dd-indc icon ni ni-chevron-right"></em>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <ul class="link-list-opt no-bdr">
                                                    <li>
                                                        <a href="?status=">
                                                            <span>Tất cả</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="?status=pending">
                                                            <span>Chờ xử lý</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="?status=active">
                                                            <span>Đang hoạt động</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="?status=expired">
                                                            <span>Đã hết hạn</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-block-tools-opt d-none d-sm-block">
                                        <a href="/kho-giao-dien" class="btn btn-primary">
                                            <em class="icon ni ni-plus"></em>
                                            <span>Thuê Website Mới</span>
                                        </a>
                                    </li>
                                </ul>
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
                                        <div class="text-soft">Tổng Dịch Vụ</div>
                                        <div class="h2 fw-bold mt-1"><?= $totalOrders ?></div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-primary text-white">
                                            <em class="icon ni ni-layers"></em>
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
                                        <div class="text-soft">Đang Hoạt Động</div>
                                        <div class="h2 fw-bold mt-1"><?= $activeOrders ?></div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-success text-white">
                                            <em class="icon ni ni-check-circle"></em>
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
                                        <div class="text-soft">Chờ Xử Lý</div>
                                        <div class="h2 fw-bold mt-1"><?= $pendingOrders ?></div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-warning text-white">
                                            <em class="icon ni ni-clock"></em>
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
                                        <div class="text-soft">Đã Hết Hạn</div>
                                        <div class="h2 fw-bold mt-1"><?= $expiredOrders ?></div>
                                    </div>
                                    <div class="nk-cb1">
                                        <div class="nk-cb1-icon bg-danger text-white">
                                            <em class="icon ni ni-alert-circle"></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="nk-block">
                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner p-0">
                            <table class="nk-tb-list nk-tb-ulist">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col">
                                            <span class="sub-text">Giao diện</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-xxl">
                                            <span class="sub-text">Tên miền</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-lg">
                                            <span class="sub-text">Gói thuê</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-lg">
                                            <span class="sub-text">Giá</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-xxl">
                                            <span class="sub-text">Trạng thái</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md">
                                            <span class="sub-text">Ngày hết hạn</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-mb">
                                            <span class="sub-text">Ngày tạo</span>
                                        </th>
                                        <th class="nk-tb-col nk-tb-col-tools text-end">
                                            <span class="sub-text">Thao tác</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($orders->num_rows > 0): ?>
                                        <?php while($order = $orders->fetch_assoc()): ?>
                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col">
                                                <div class="project-title">
                                                    <div class="user-avatar sq bg-primary">
                                                        <span><?= strtoupper(substr($order['template_name'], 0, 2)) ?></span>
                                                    </div>
                                                    <div class="project-info">
                                                        <h6 class="title"><?= htmlspecialchars($order['template_name']) ?></h6>
                                                        <span class="badge bg-light text-dark small"><?= ucfirst($order['category']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-xxl">
                                                <span class="fw-medium">
                                                    <?= $order['domain'] ?: 'Chưa cấu hình' ?>
                                                </span>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span class="badge bg-info">
                                                    <?php
                                                    switch($order['package_type']){
                                                        case 'monthly': echo '1 Tháng'; break;
                                                        case 'quarterly': echo '3 Tháng'; break;
                                                        case 'yearly': echo '12 Tháng'; break;
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span class="text-primary fw-bold"><?= number_format($order['price'], 0, ',', '.') ?>đ</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-xxl">
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch($order['status']){
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        $statusText = 'Chờ xử lý';
                                                        break;
                                                    case 'active':
                                                        $statusClass = 'bg-success';
                                                        $statusText = 'Đang hoạt động';
                                                        break;
                                                    case 'expired':
                                                        $statusClass = 'bg-danger';
                                                        $statusText = 'Đã hết hạn';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-dark';
                                                        $statusText = 'Đã hủy';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="text-muted">
                                                    <?= $order['expires_at'] ? date('d/m/Y', strtotime($order['expires_at'])) : 'N/A' ?>
                                                </span>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="text-muted"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-bs-toggle="dropdown">
                                                                <em class="icon ni ni-more-h"></em>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li>
                                                                        <a href="#" onclick="viewOrderDetails(<?= $order['id'] ?>)">
                                                                            <em class="icon ni ni-eye"></em>
                                                                            <span>Xem chi tiết</span>
                                                                        </a>
                                                                    </li>
                                                                    <?php if($order['status'] == 'pending'): ?>
                                                                    <li>
                                                                        <a href="#" onclick="cancelOrder(<?= $order['id'] ?>)">
                                                                            <em class="icon ni ni-cross"></em>
                                                                            <span>Hủy đơn</span>
                                                                        </a>
                                                                    </li>
                                                                    <?php endif; ?>
                                                                    <?php if($order['status'] == 'active'): ?>
                                                                    <li>
                                                                        <a href="#" onclick="renewOrder(<?= $order['id'] ?>)">
                                                                            <em class="icon ni ni-refresh"></em>
                                                                            <span>Gia hạn</span>
                                                                        </a>
                                                                    </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr class="nk-tb-item">
                                            <td colspan="8" class="text-center py-5">
                                                <em class="icon ni ni-folder-empty" style="font-size: 3rem; color: #dee2e6;"></em>
                                                <h5 class="mt-3">Bạn chưa có dịch vụ nào</h5>
                                                <p class="text-muted">
                                                    <a href="/kho-giao-dien" class="btn btn-primary btn-sm mt-2">
                                                        <em class="icon ni ni-plus me-1"></em>Thuê website ngay
                                                    </a>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewOrderDetails(orderId) {
    // Implement view order details modal
    alert('Xem chi tiết đơn hàng #' + orderId);
}

function cancelOrder(orderId) {
    if(confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        fetch('/ajaxs/client/website/cancel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: orderId,
                csrf_token: document.querySelector('input[name="csrf_token"]').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                alert('Hủy đơn hàng thành công!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra! Vui lòng thử lại.');
        });
    }
}

function renewOrder(orderId) {
    // Implement renew order functionality
    alert('Gia hạn dịch vụ #' + orderId);
}
</script>

<?php csrf_field(); ?>