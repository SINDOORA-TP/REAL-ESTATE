<?php
/**
 * Magaram Real Estate - Live Search & Filter API Endpoint
 */
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$category = clean_string($_GET['category'] ?? '');
$location = clean_string($_GET['location'] ?? '');
$maxPrice = !empty($_GET['budget']) ? (float)$_GET['budget'] : 0;
$keyword = clean_string($_GET['q'] ?? '');

$db = get_db();
$results = [];

if ($db) {
    try {
        $sql = "
            SELECT p.*, 
                   COALESCE(pi.image_path, 'assets/img/hero-bg.svg') AS cover_image
            FROM properties p
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_cover = 1
            WHERE 1=1
        ";
        $params = [];

        if (!empty($category)) {
            $sql .= " AND p.category = :category";
            $params['category'] = $category;
        }

        if (!empty($location)) {
            $sql .= " AND p.location LIKE :location";
            $params['location'] = '%' . $location . '%';
        }

        if ($maxPrice > 0) {
            $sql .= " AND p.price <= :price";
            $params['price'] = $maxPrice;
        }

        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE :kw OR p.description LIKE :kw OR p.location LIKE :kw)";
            $params['kw'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC LIMIT 24";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
    } catch (Exception $e) {
        $results = [];
    }
} else {
    // Search within fallback properties
    $fallbacks = get_fallback_properties();
    $results = array_filter($fallbacks, function($item) use ($category, $location, $maxPrice, $keyword) {
        if (!empty($category) && strcasecmp($item['category'], $category) !== 0) return false;
        if (!empty($location) && stripos($item['location'], $location) === false) return false;
        if ($maxPrice > 0 && $item['price'] > $maxPrice) return false;
        if (!empty($keyword) && stripos($item['title'] . ' ' . $item['description'], $keyword) === false) return false;
        return true;
    });
    $results = array_values($results);
}

echo json_encode([
    'success' => true,
    'count' => count($results),
    'data' => $results
]);

