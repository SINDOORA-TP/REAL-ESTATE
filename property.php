<?php
/**
 * Magaram Real Estate - Property Detail View
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$slug = clean_string($_GET['slug'] ?? '');
$id = !empty($_GET['id']) ? (int)$_GET['id'] : null;

$db = get_db();
$property = null;
$images = [];
$amenities = [];

if ($db && (!empty($slug) || !empty($id))) {
    try {
        if (!empty($slug)) {
            $stmt = $db->prepare("SELECT * FROM properties WHERE slug = :slug LIMIT 1");
            $stmt->execute(['slug' => $slug]);
        } else {
            $stmt = $db->prepare("SELECT * FROM properties WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
        }
        $property = $stmt->fetch();

        if ($property) {
            // Images
            $imgStmt = $db->prepare("SELECT * FROM property_images WHERE property_id = :pid ORDER BY is_cover DESC, sort_order ASC");
            $imgStmt->execute(['pid' => $property['id']]);
            $images = $imgStmt->fetchAll();

            // Amenities
            $amStmt = $db->prepare("
                SELECT a.name, a.icon 
                FROM amenities a 
                JOIN property_amenities pa ON a.id = pa.amenity_id 
                WHERE pa.property_id = :pid
            ");
            $amStmt->execute(['pid' => $property['id']]);
            $amenities = $amStmt->fetchAll();
        }
    } catch (Exception $e) {
        $property = null;
    }
}

// Fallback lookup
if (!$property) {
    $fallbacks = get_fallback_properties();
    foreach ($fallbacks as $fb) {
        if ((!empty($slug) && $fb['slug'] === $slug) || (!empty($id) && $fb['id'] == $id)) {
            $property = $fb;
            $images = [
                ['image_path' => $fb['cover_image'], 'caption' => $fb['title']]
            ];
            $amenities = [
                ['name' => 'Private Elevator', 'icon' => 'elevator'],
                ['name' => 'Smart Automation', 'icon' => 'cpu'],
                ['name' => 'Italian Calacatta Marble', 'icon' => 'gem'],
                ['name' => '24/7 Security Concierge', 'icon' => 'shield'],
                ['name' => 'Infinity Plunge Pool', 'icon' => 'pool'],
                ['name' => 'Covered Multi-car Parking', 'icon' => 'car']
            ];
            break;
        }
    }
}

if (!$property) {
    redirect(BASE_URL . '/properties.php');
}

$customTitle = $property['title'] . ' | Magaram Real Estate';
$customDesc = substr(strip_tags($property['description']), 0, 160);
require_once __DIR__ . '/includes/header.php';
?>

<div style="padding-top: 120px; background: var(--bg-surface); border-bottom: 1px solid var(--border-subtle);">
    <div class="container" style="padding-top: 30px; padding-bottom: 30px;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 20px;">
            <div>
                <div class="location-pill" style="margin-bottom: 12px;">
                    <span class="pill-dot"></span>
                    <?= e($property['location']); ?>
                </div>
                <h1 style="font-size: 2.8rem; margin-bottom: 8px;"><?= e($property['title']); ?></h1>
                <div style="color: var(--text-muted); font-size: 0.95rem;">
                    Category: <strong style="color: var(--gold-bright);"><?= e($property['category']); ?></strong> &bull; Status: <span class="property-status-badge status-<?= strtolower($property['status']); ?>"><?= e($property['status']); ?></span>
                    <?php if (!empty($property['is_sample'])): ?>
                        <span class="sample-badge">Sample Data</span>
                    <?php endif; ?>
                </div>
            </div>

            <div style="text-align: right;">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Valuation</div>
                <div style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--gold-bright); font-weight: 700;">
                    <?= e($property['price_display'] ?? format_inr((float)$property['price'])); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container section-padding">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
        <!-- Left Column: Media & Specifications -->
        <div>
            <!-- Main Hero Image Display -->
            <div style="border-radius: 8px; overflow: hidden; border: 1px solid var(--border-gold); aspect-ratio: 16/10; margin-bottom: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.8);">
                <?php 
                    $mainImage = !empty($images[0]['image_path']) ? $images[0]['image_path'] : 'assets/img/hero-bg.svg';
                ?>
                <img id="main-property-view" src="<?= BASE_URL . '/' . e($mainImage); ?>" alt="<?= e($property['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Specs Grid -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 24px; border-radius: 8px; margin-bottom: 40px; text-align: center;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Super Area</div>
                    <div style="font-size: 1.4rem; font-weight: 700;"><?= number_format($property['area_sqft']); ?> <span style="font-size: 0.85rem; font-weight: 400;">sq.ft</span></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Bedrooms</div>
                    <div style="font-size: 1.4rem; font-weight: 700;"><?= $property['bedrooms'] > 0 ? $property['bedrooms'] : 'N/A'; ?></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Bathrooms</div>
                    <div style="font-size: 1.4rem; font-weight: 700;"><?= $property['bathrooms'] > 0 ? $property['bathrooms'] : 'N/A'; ?></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Availability</div>
                    <div style="font-size: 1.4rem; font-weight: 700; color: #4ade80;"><?= e($property['status']); ?></div>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 48px;">
                <span class="section-tag">Architectural Blueprint</span>
                <h2 style="font-size: 2rem; margin-bottom: 20px;">The Residence Narrative</h2>
                <div style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.8;">
                    <?= nl2br(e($property['description'])); ?>
                </div>
            </div>

            <!-- Amenities -->
            <?php if (!empty($amenities)): ?>
                <div>
                    <span class="section-tag">Bespoke Inclusions</span>
                    <h2 style="font-size: 2rem; margin-bottom: 24px;">Signature Amenities</h2>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <?php foreach ($amenities as $amenity): ?>
                            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 16px 20px; border-radius: 6px; display: flex; align-items: center; gap: 12px;">
                                <span style="color: var(--gold-bright); font-size: 1.2rem;">&#10022;</span>
                                <span style="font-weight: 500;"><?= e($amenity['name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Lead Form & Actions -->
        <div>
            <div style="position: sticky; top: 110px; background: var(--bg-card); border: 1px solid var(--border-gold); border-radius: 8px; padding: 32px; box-shadow: 0 15px 40px rgba(0,0,0,0.7);">
                <span class="section-tag">Direct Representation</span>
                <h3 style="font-size: 1.5rem; margin-bottom: 8px;">Private Viewing Request</h3>
                <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 24px;">
                    Arranged discreetly with our Senior Director.
                </p>

                <form class="luxury-form ajax-enquiry-form" action="<?= BASE_URL; ?>/api/enquiry.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
                    <input type="hidden" name="type" value="Site Visit">
                    <input type="hidden" name="property_id" value="<?= $property['id']; ?>">
                    <input type="text" name="website_url_hp" class="hp-field" tabindex="-1" autocomplete="off">

                    <div class="form-group">
                        <label for="p-name">Full Name *</label>
                        <input type="text" id="p-name" name="name" class="form-control" placeholder="Your Name" required>
                    </div>

                    <div class="form-group">
                        <label for="p-phone">Phone Number *</label>
                        <input type="tel" id="p-phone" name="phone" class="form-control" placeholder="+91 98765 00000" required>
                    </div>

                    <div class="form-group">
                        <label for="p-email">Email Address *</label>
                        <input type="email" id="p-email" name="email" class="form-control" placeholder="client@domain.com" required>
                    </div>

                    <div class="form-group">
                        <label for="p-date">Preferred Viewing Date</label>
                        <input type="date" id="p-date" name="preferred_date" class="form-control" min="<?= date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="p-notes">Special Requests / Requirements</label>
                        <textarea id="p-notes" name="message" class="form-control" rows="2" placeholder="e.g. Helicopter landing, weekend slot, financial documentation..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 6px;">
                        Book Site Visit
                    </button>
                </form>

                <div style="margin-top: 24px; text-align: center;">
                    <button type="button" class="btn btn-outline" style="width: 100%; font-size: 0.82rem;" data-open-modal="modal-brochure" data-property-id="<?= $property['id']; ?>" data-property-title="<?= e($property['title']); ?>">
                        Download Detailed Brochure
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

