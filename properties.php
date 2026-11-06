<?php
/**
 * Magaram Real Estate - Properties Catalog & Filters
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings();
$db = get_db();

// Filter parameters
$category = clean_string($_GET['category'] ?? '');
$location = clean_string($_GET['location'] ?? '');
$status = clean_string($_GET['status'] ?? '');
$maxPrice = !empty($_GET['budget']) ? (float)$_GET['budget'] : 0;
$keyword = clean_string($_GET['q'] ?? '');

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 6;
$offset = ($page - 1) * $perPage;

$properties = [];
$totalProperties = 0;

if ($db) {
    try {
        $where = ["1=1"];
        $params = [];

        if (!empty($category)) {
            $where[] = "p.category = :cat";
            $params['cat'] = $category;
        }

        if (!empty($location)) {
            $where[] = "p.location LIKE :loc";
            $params['loc'] = '%' . $location . '%';
        }

        if (!empty($status)) {
            $where[] = "p.status = :stat";
            $params['stat'] = $status;
        }

        if ($maxPrice > 0) {
            $where[] = "p.price <= :price";
            $params['price'] = $maxPrice;
        }

        if (!empty($keyword)) {
            $where[] = "(p.title LIKE :kw OR p.description LIKE :kw OR p.location LIKE :kw)";
            $params['kw'] = '%' . $keyword . '%';
        }

        $whereClause = implode(" AND ", $where);

        // Count total
        $countStmt = $db->prepare("SELECT COUNT(*) FROM properties p WHERE {$whereClause}");
        $countStmt->execute($params);
        $totalProperties = (int)$countStmt->fetchColumn();

        // Fetch paginated
        $sql = "
            SELECT p.*, 
                   COALESCE(pi.image_path, CONCAT('assets/img/property-', p.id, '.svg')) AS cover_image
            FROM properties p
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_cover = 1
            WHERE {$whereClause}
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $properties = $stmt->fetchAll();
    } catch (Exception $e) {
        $properties = [];
    }
}

if (empty($properties) && !$db) {
    // Fallback in-memory filtering
    $all = get_fallback_properties();
    $filtered = array_filter($all, function($item) use ($category, $location, $status, $maxPrice, $keyword) {
        if (!empty($category) && strcasecmp($item['category'], $category) !== 0) return false;
        if (!empty($location) && stripos($item['location'], $location) === false) return false;
        if (!empty($status) && strcasecmp($item['status'], $status) !== 0) return false;
        if ($maxPrice > 0 && $item['price'] > $maxPrice) return false;
        if (!empty($keyword) && stripos($item['title'] . ' ' . $item['description'], $keyword) === false) return false;
        return true;
    });
    $totalProperties = count($filtered);
    $properties = array_slice($filtered, $offset, $perPage);
}

$totalPages = ceil($totalProperties / $perPage);

$customTitle = 'Exclusive Properties | Magaram Real Estate';
$customDesc = 'Browse our hand-picked portfolio of penthouses, luxury villas, and Grade-A commercial spaces in prime locations.';
require_once __DIR__ . '/includes/header.php';
?>

<div style="padding-top: 130px; background: var(--bg-surface); padding-bottom: 40px; border-bottom: 1px solid var(--border-subtle);">
    <div class="container text-center">
        <span class="section-tag">Portfolio Catalog</span>
        <h1 style="font-size: 3rem; margin-bottom: 12px;">Curated Estates</h1>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">
            Explore exceptional residences and commercial assets currently available for private acquisition.
        </p>
    </div>
</div>

<div class="container section-padding">
    <!-- Filter Bar -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-gold); border-radius: 8px; padding: 24px; margin-bottom: 48px;">
        <form method="GET" action="properties.php" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 16px; align-items: flex-end;">
            <div class="search-field">
                <label for="f-kw">Search Keyword</label>
                <input type="text" id="f-kw" name="q" value="<?= e($keyword); ?>" placeholder="e.g. Jubilee, Penthouse">
            </div>

            <div class="search-field">
                <label for="f-cat">Category</label>
                <select id="f-cat" name="category">
                    <option value="">All Categories</option>
                    <option value="Apartment" <?= $category === 'Apartment' ? 'selected' : ''; ?>>Penthouses & Apartments</option>
                    <option value="Villa" <?= $category === 'Villa' ? 'selected' : ''; ?>>Villas</option>
                    <option value="Commercial" <?= $category === 'Commercial' ? 'selected' : ''; ?>>Commercial</option>
                    <option value="Plot" <?= $category === 'Plot' ? 'selected' : ''; ?>>Plots</option>
                </select>
            </div>

            <div class="search-field">
                <label for="f-status">Status</label>
                <select id="f-status" name="status">
                    <option value="">All Statuses</option>
                    <option value="Available" <?= $status === 'Available' ? 'selected' : ''; ?>>Available</option>
                    <option value="Upcoming" <?= $status === 'Upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                    <option value="Sold" <?= $status === 'Sold' ? 'selected' : ''; ?>>Sold</option>
                </select>
            </div>

            <div class="search-field">
                <label for="f-budget">Budget</label>
                <select id="f-budget" name="budget">
                    <option value="">Any Budget</option>
                    <option value="100000000" <?= $maxPrice == 100000000 ? 'selected' : ''; ?>>Up to ₹10 Crore</option>
                    <option value="250000000" <?= $maxPrice == 250000000 ? 'selected' : ''; ?>>Up to ₹25 Crore</option>
                    <option value="500000000" <?= $maxPrice == 500000000 ? 'selected' : ''; ?>>Up to ₹50 Crore</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Filter</button>
                <a href="properties.php" class="btn btn-outline" style="padding: 10px 14px;" title="Reset filters">Reset</a>
            </div>
        </form>
    </div>

    <!-- Properties Count Notice -->
    <div style="margin-bottom: 24px; color: var(--text-muted); font-size: 0.92rem; display: flex; justify-content: space-between; align-items: center;">
        <span>Showing <strong><?= count($properties); ?></strong> of <strong><?= $totalProperties; ?></strong> luxury landmark properties</span>
    </div>

    <!-- Properties Grid -->
    <?php if (empty($properties)): ?>
        <div style="text-align: center; padding: 60px 20px; background: var(--bg-card); border: 1px dashed var(--border-gold); border-radius: 8px;">
            <h3 style="font-size: 1.5rem; margin-bottom: 8px;">No Properties Match Your Selection</h3>
            <p style="color: var(--text-muted); margin-bottom: 20px;">We hold unlisted off-market opportunities that may meet your specifications.</p>
            <a href="properties.php" class="btn btn-outline btn-sm">View All Properties</a>
        </div>
    <?php else: ?>
        <div class="properties-grid">
            <?php foreach ($properties as $prop): ?>
                <?php 
                    $coverImg = !empty($prop['cover_image']) ? $prop['cover_image'] : 'assets/img/property-1.svg';
                    $statusClass = 'status-' . strtolower($prop['status']);
                ?>
                <article class="property-card">
                    <div class="property-image-box">
                        <img src="<?= BASE_URL . '/' . e($coverImg); ?>" alt="<?= e($prop['title']); ?>" loading="lazy">
                        <span class="property-badge"><?= e($prop['category']); ?></span>
                        <span class="property-status-badge <?= $statusClass; ?>"><?= e($prop['status']); ?></span>
                    </div>

                    <div class="property-body">
                        <div class="property-location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <?= e($prop['location']); ?>
                        </div>

                        <h3 class="property-title">
                            <a href="<?= BASE_URL; ?>/property.php?slug=<?= e($prop['slug']); ?>">
                                <?= e($prop['title']); ?>
                            </a>
                        </h3>

                        <p class="property-description-preview">
                            <?= e($prop['description']); ?>
                        </p>

                        <div class="property-specs-row">
                            <?php if ($prop['bedrooms'] > 0): ?>
                                <div class="spec-item">
                                    <strong><?= $prop['bedrooms']; ?></strong> Beds
                                </div>
                            <?php endif; ?>
                            <?php if ($prop['bathrooms'] > 0): ?>
                                <div class="spec-item">
                                    <strong><?= $prop['bathrooms']; ?></strong> Baths
                                </div>
                            <?php endif; ?>
                            <div class="spec-item">
                                <strong><?= number_format($prop['area_sqft']); ?></strong> sq.ft
                            </div>
                        </div>

                        <div class="property-footer">
                            <div class="property-price">
                                <?= e($prop['price_display'] ?? format_inr((float)$prop['price'])); ?>
                            </div>
                            <a href="<?= BASE_URL; ?>/property.php?slug=<?= e($prop['slug']); ?>" class="btn btn-outline btn-sm">
                                View Estate
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 50px;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php 
                        $queryParams = $_GET;
                        $queryParams['page'] = $i;
                        $pageUrl = 'properties.php?' . http_build_query($queryParams);
                    ?>
                    <a href="<?= $pageUrl; ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 8px 16px;">
                        <?= $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

