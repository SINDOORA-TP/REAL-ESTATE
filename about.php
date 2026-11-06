<?php
/**
 * Magaram Real Estate - About Us
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings();

$customTitle = 'About Us | Magaram Real Estate Heritage';
$customDesc = 'Discover the ethos and architectural vision behind Magaram Real Estate - curating irreplaceable living spaces for discerning clients.';
require_once __DIR__ . '/includes/header.php';
?>

<div style="padding-top: 130px; background: var(--bg-surface); padding-bottom: 50px; border-bottom: 1px solid var(--border-subtle);">
    <div class="container text-center">
        <span class="section-tag">Our Heritage & Vision</span>
        <h1 style="font-size: 3.2rem; margin-bottom: 14px;">The Magaram Ethos</h1>
        <p style="color: var(--text-muted); max-width: 680px; margin: 0 auto; font-size: 1.1rem;">
            Real estate is not merely a transaction of square feet. It is the physical manifestation of vision, heritage, and distinction.
        </p>
    </div>
</div>

<div class="container section-padding">
    <div class="split-content" style="margin-bottom: 80px;">
        <div>
            <span class="section-tag">Philosophy</span>
            <h2 style="font-size: 2.3rem; margin-bottom: 20px;">Architectural Excellence with Unwavering Discretion</h2>
            <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.8; margin-bottom: 20px;">
                Founded on the premise that prime real estate requires bespoke advisory rather than mass marketing, Magaram operates as a private advisory house catering to industrialists, royal families, global enterprises, and private collectors.
            </p>
            <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.8; margin-bottom: 24px;">
                Our properties undergo strict architectural, environmental, and legal auditing before being presented to our network. Whether acquiring an off-market penthouses overlooking the Hyderabad skyline or master-planning a corporate headquarters, we ensure complete confidentiality at every milestone.
            </p>
            <div style="border-left: 2px solid var(--gold-bright); padding-left: 20px; font-style: italic; color: var(--gold-bright); font-size: 1.05rem;">
                &ldquo;We don't sell real estate. We curate environments where extraordinary lives unfold.&rdquo;
            </div>
        </div>

        <div class="luxury-image-frame">
            <img src="<?= BASE_URL; ?>/assets/img/about-estate.svg" alt="Magaram Architecture Heritage" loading="lazy">
        </div>
    </div>

    <!-- Core Pillars -->
    <div style="text-align: center; margin-bottom: 48px;">
        <span class="section-tag">Our Core Pillars</span>
        <h2 class="section-title">The Foundation of Trust</h2>
    </div>

    <div class="feature-cards-grid">
        <div class="feature-box">
            <div class="feature-icon-wrapper">&#9830;</div>
            <h3 class="feature-title">Absolute Discretion</h3>
            <p class="feature-text">High-profile transactions protected by non-disclosure protocols and private viewing corridors.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon-wrapper">&#9733;</div>
            <h3 class="feature-title">Architectural Scrutiny</h3>
            <p class="feature-text">Every building material, structural load, and finish is evaluated against European luxury benchmarks.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon-wrapper">&#9881;</div>
            <h3 class="feature-title">End-to-End Concierge</h3>
            <p class="feature-text">From bespoke legal conveyance to interior commissioning, our private office oversees the entire journey.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

