<?php
// Get templates from database
$category = isset($_GET['category']) ? x($_GET['category']) : '';
$search = isset($_GET['search']) ? x($_GET['search']) : '';

$whereClause = "WHERE `status` = 'active'";
if(!empty($category)){
    $whereClause .= " AND `category` = '$category'";
}
if(!empty($search)){
    $whereClause .= " AND (`name` LIKE '%$search%' OR `description` LIKE '%$search%')";
}

$templates = $nify->query("SELECT * FROM `website_templates` $whereClause ORDER BY `created_at` DESC");
$categories = $nify->query("SELECT DISTINCT `category` FROM `website_templates` WHERE `status` = 'active' ORDER BY `category`");
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Kho Giao Diện</h3>
                        <div class="nk-block-des text-soft">
                            <p>Chọn giao diện website phù hợp với nhu cầu của bạn</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                <em class="icon ni ni-more-v"></em>
                            </a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right">
                                                <em class="icon ni ni-search"></em>
                                            </div>
                                            <input type="text" class="form-control" id="search-templates" placeholder="Tìm kiếm giao diện..." value="<?= htmlspecialchars($search) ?>">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white" data-bs-toggle="dropdown">
                                                Danh mục
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <ul class="link-list-opt no-bdr">
                                                    <li>
                                                        <a href="?category=">
                                                            <span>Tất cả</span>
                                                        </a>
                                                    </li>
                                                    <?php while($cat = $categories->fetch_assoc()): ?>
                                                    <li>
                                                        <a href="?category=<?= $cat['category'] ?>">
                                                            <span><?= ucfirst($cat['category']) ?></span>
                                                        </a>
                                                    </li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <?php if($templates->num_rows > 0): ?>
                <div class="row g-gs">
                    <?php while($template = $templates->fetch_assoc()): ?>
                    <div class="col-xxl-4 col-lg-4 col-sm-6">
                        <div class="card product-card">
                            <div class="product-thumb">
                                <a href="/w-generate/<?= $template['id'] ?>">
                                    <img class="card-img-top" src="<?= $template['thumbnail'] ?: 'https://via.placeholder.com/400x300/4a90e2/ffffff?text=' . urlencode($template['name']) ?>" alt="<?= htmlspecialchars($template['name']) ?>" style="height: 15rem; object-fit: cover;">
                                </a>
                                
                                <ul class="product-badges">
                                    <?php if($template['created_at'] > date('Y-m-d H:i:s', strtotime('-7 days'))): ?>
                                    <li>
                                        <span class="badge bg-success">Mới</span>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <span class="badge bg-info"><?= ucfirst($template['category']) ?></span>
                                    </li>
                                </ul>

                                <ul class="product-actions">
                                    <div class="bg-primary" style="display: flex;">
                                        <li>
                                            <a href="/w-generate/<?= $template['id'] ?>" title="Thuê ngay">
                                                <em class="icon ni ni-cart text-white"></em>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= $template['demo_url'] ?>" target="_blank" title="Xem demo">
                                                <em class="icon ni ni-eye text-white"></em>
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </div>

                            <div class="card-inner text-center">
                                <ul class="product-tags">
                                    <li>
                                        <span><em class="ni ni-tag mr-2"></em><?= ucfirst($template['category']) ?></span>
                                    </li>
                                </ul>

                                <h5 class="product-title">
                                    <a href="/w-generate/<?= $template['id'] ?>"><?= htmlspecialchars($template['name']) ?></a>
                                </h5>

                                <p class="product-description text-muted small">
                                    <?= htmlspecialchars(substr($template['description'], 0, 100)) ?>...
                                </p>

                                <div class="product-price text-primary h5">
                                    <?= number_format($template['price'], 0, ',', '.') ?>đ
                                    <small class="text-muted">/tháng</small>
                                </div>

                                <?php if($template['features']): ?>
                                <div class="product-features mt-2">
                                    <?php 
                                    $features = json_decode($template['features'], true);
                                    if(is_array($features) && count($features) > 0):
                                    ?>
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        <?php foreach(array_slice($features, 0, 3) as $feature): ?>
                                        <span class="badge bg-light text-dark small"><?= htmlspecialchars($feature) ?></span>
                                        <?php endforeach; ?>
                                        <?php if(count($features) > 3): ?>
                                        <span class="badge bg-light text-dark small">+<?= count($features) - 3 ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <em class="icon ni ni-folder-empty" style="font-size: 4rem; color: #dee2e6;"></em>
                    <h5 class="mt-3">Không tìm thấy giao diện nào</h5>
                    <p class="text-muted">Vui lòng thử lại với từ khóa khác hoặc chọn danh mục khác.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-templates');
    if(searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            
            searchTimeout = setTimeout(function() {
                const currentUrl = new URL(window.location);
                if(searchTerm) {
                    currentUrl.searchParams.set('search', searchTerm);
                } else {
                    currentUrl.searchParams.delete('search');
                }
                window.location.href = currentUrl.toString();
            }, 500);
        });
    }
});
</script>