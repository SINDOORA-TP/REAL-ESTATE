<?php
/**
 * Magaram Real Estate - Global Header Component
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$settings = get_all_settings();
$csrfToken = generate_csrf_token();
$pageTitle = isset($customTitle) ? $customTitle . ' | ' . APP_NAME : APP_NAME . ' | ' . ($settings['tagline'] ?? APP_TAGLINE);
$pageDesc = isset($customDesc) ? $customDesc : ($settings['hero_subtext'] ?? 'Bespoke residences, signature penthouses, and prime commercial estates crafted with architectural precision.');
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= e($pageTitle); ?></title>
    <meta name="description" content="<?= e($pageDesc); ?>">
    
    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($pageTitle); ?>">
    <meta property="og:description" content="<?= e($pageDesc); ?>">
    <meta property="og:image" content="<?= BASE_URL; ?>/assets/img/hero-bg.svg">
    <meta property="og:url" content="<?= BASE_URL . e($_SERVER['REQUEST_URI'] ?? '/'); ?>">

    <!-- Preconnect Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">

    <!-- Structured Data (Schema.org RealEstateAgent) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "RealEstateAgent",
      "name": "<?= e($settings['site_name']); ?>",
      "image": "<?= BASE_URL; ?>/assets/img/magaram-logo.svg",
      "telephone": "<?= e($settings['contact_phone']); ?>",
      "email": "<?= e($settings['contact_email']); ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= e($settings['office_address']); ?>"
      },
      "priceRange": "₹₹₹₹"
    }
    </script>
</head>
<body>

<!-- Global Royal Navigation -->
<header class="site-header" id="main-header">
    <div class="container nav-container">
        <!-- Brand Logo & Monogram -->
        <a href="<?= BASE_URL; ?>/index.php" class="brand-logo" aria-label="Magaram Real Estate Home">
            <img src="<?= BASE_URL; ?>/assets/img/magaram-logo.svg" alt="Magaram Crest" width="38" height="38">
            <div class="brand-title">
                MAGARAM
                <span class="brand-sub">REAL ESTATE</span>
            </div>
        </a>

        <!-- Desktop & Mobile Navigation Links -->
        <nav class="nav-menu" id="primary-navigation">
            <a href="<?= BASE_URL; ?>/index.php" class="nav-link <?= ($currentPage === 'index' || $currentPage === '') ? 'active' : ''; ?>">Home</a>
            <a href="<?= BASE_URL; ?>/about.php" class="nav-link <?= ($currentPage === 'about') ? 'active' : ''; ?>">About</a>
            <a href="<?= BASE_URL; ?>/properties.php" class="nav-link <?= ($currentPage === 'properties' || $currentPage === 'property') ? 'active' : ''; ?>">Properties</a>
            <a href="<?= BASE_URL; ?>/services.php" class="nav-link <?= ($currentPage === 'services') ? 'active' : ''; ?>">Services</a>
            <a href="<?= BASE_URL; ?>/contact.php" class="nav-link <?= ($currentPage === 'contact') ? 'active' : ''; ?>">Contact</a>
        </nav>

        <!-- Right Header Actions -->
        <div class="nav-actions">
            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="nav-link phone-link" style="font-size: 0.85rem; color: var(--gold-bright);">
                <?= e($settings['contact_phone']); ?>
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-open-modal="modal-site-visit">
                Book Site Visit
            </button>
            <button class="menu-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false">
                &#9776;
            </button>
        </div>
    </div>
</header>
