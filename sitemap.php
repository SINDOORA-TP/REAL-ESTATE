<?php
/**
 * Magaram Real Estate - Dynamic XML Sitemap Generator
 */
header('Content-Type: application/xml; charset=UTF-8');

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = get_db();
$properties = [];

if ($db) {
    try {
        $stmt = $db->query("SELECT slug, updated_at FROM properties WHERE status != 'Sold' ORDER BY id DESC");
        $properties = $stmt->fetchAll();
    } catch (Exception $e) {}
}

if (empty($properties)) {
    $fallbacks = get_fallback_properties();
    foreach ($fallbacks as $fb) {
        $properties[] = [
            'slug' => $fb['slug'],
            'updated_at' => date('Y-m-d')
        ];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= BASE_URL; ?>/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= BASE_URL; ?>/properties.php</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= BASE_URL; ?>/about.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?= BASE_URL; ?>/services.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= BASE_URL; ?>/contact.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php foreach ($properties as $p): ?>
    <url>
        <loc><?= BASE_URL; ?>/property.php?slug=<?= urlencode($p['slug']); ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($p['updated_at'] ?? 'now')); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <?php endforeach; ?>
</urlset>

