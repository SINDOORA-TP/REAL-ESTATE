<?php
/**
 * Magaram Real Estate - Executive Architectural Brochure Delivery
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$propertyId = !empty($_GET['property_id']) ? (int)$_GET['property_id'] : null;
$property = null;

if ($propertyId) {
    $db = get_db();
    if ($db) {
        $stmt = $db->prepare("SELECT * FROM properties WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $propertyId]);
        $property = $stmt->fetch();
    }
    if (!$property) {
        $fallbacks = get_fallback_properties();
        foreach ($fallbacks as $fb) {
            if ($fb['id'] == $propertyId) {
                $property = $fb;
                break;
            }
        }
    }
}

// Check if an uploaded PDF exists
$pdfFile = BROCHURE_UPLOAD_DIR . DIRECTORY_SEPARATOR . ($propertyId ? "property_{$propertyId}.pdf" : "magaram_portfolio.pdf");
if (file_exists($pdfFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($pdfFile) . '"');
    readfile($pdfFile);
    exit;
}

// Otherwise render the executive digital architectural prospectus
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Architectural Prospectus | Magaram Real Estate</title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">
    <style>
        body {
            background-color: #0b0b0b;
            padding: 40px 20px;
        }
        .prospectus-card {
            max-width: 860px;
            margin: 0 auto;
            background: #141414;
            border: 2px solid var(--border-gold);
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.9);
            position: relative;
        }
        .prospectus-card::after {
            content: '';
            position: absolute;
            inset: 12px;
            border: 1px solid rgba(229, 201, 139, 0.25);
            pointer-events: none;
        }
        @media print {
            body { background: #fff; color: #000; padding: 0; }
            .prospectus-card { border: 1px solid #000; box-shadow: none; padding: 20px; background: #fff; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="prospectus-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-gold); padding-bottom: 24px; margin-bottom: 36px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <img src="<?= BASE_URL; ?>/assets/img/magaram-logo.svg" alt="Magaram Crest" width="46" height="46">
                <div>
                    <h2 style="font-size: 1.6rem; letter-spacing: 0.08em; margin: 0;">MAGARAM</h2>
                    <span style="font-size: 0.7rem; letter-spacing: 0.25em; color: var(--gold-bright);">CONFIDENTIAL PROSPECTUS</span>
                </div>
            </div>
            <button onclick="window.print()" class="btn btn-outline btn-sm btn-print">Print / Save as PDF</button>
        </div>

        <span class="section-tag">Executive Summary</span>
        <h1 style="font-size: 2.4rem; margin-bottom: 16px; color: var(--gold-bright);">
            <?= e($property ? $property['title'] : 'Signature Landmark Portfolio'); ?>
        </h1>
        <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 30px;">
            <?= e($property ? $property['location'] : 'Exclusive Residences, Penthouses & Trophy Commercial Assets'); ?>
        </p>

        <?php if ($property): ?>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; background: #1a1a1a; padding: 20px; border: 1px solid var(--border-subtle); margin-bottom: 30px; text-align: center;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Valuation</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #fff;"><?= e($property['price_display'] ?? format_inr((float)$property['price'])); ?></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Super Built Area</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #fff;"><?= number_format($property['area_sqft']); ?> sq.ft</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Suites</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #fff;"><?= $property['bedrooms']; ?> Bedrooms</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gold-bright); text-transform: uppercase;">Status</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #4ade80;"><?= e($property['status']); ?></div>
                </div>
            </div>

            <h3 style="font-size: 1.3rem; margin-bottom: 12px; color: var(--text-cream);">Architectural Overview</h3>
            <p style="line-height: 1.8; color: var(--text-muted); margin-bottom: 30px;">
                <?= nl2br(e($property['description'])); ?>
            </p>
        <?php else: ?>
            <p style="line-height: 1.8; color: var(--text-muted); margin-bottom: 30px;">
                Welcome to Magaram Real Estate's curated collection. Every landmark within this portfolio represents uncompromising construction quality, Grade-A structural engineering, and private bespoke luxury.
            </p>
        <?php endif; ?>

        <div style="border-top: 1px solid var(--border-subtle); padding-top: 24px; display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted);">
            <div>Private Concierge: +91 98765 43210</div>
            <div>contact@magaramrealestate.com</div>
        </div>
    </div>
</body>
</html>

