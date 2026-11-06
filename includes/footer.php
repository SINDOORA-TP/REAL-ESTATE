<?php
/**
 * Magaram Real Estate - Global Footer & Modal Components
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$settings = get_all_settings();
$csrfToken = generate_csrf_token();
?>

<!-- Global Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-col">
                <a href="<?= BASE_URL; ?>/index.php" class="brand-logo" style="margin-bottom: 18px;">
                    <img src="<?= BASE_URL; ?>/assets/img/magaram-logo.svg" alt="Magaram Crest" width="36" height="36">
                    <div class="brand-title">
                        MAGARAM
                        <span class="brand-sub">REAL ESTATE</span>
                    </div>
                </a>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">
                    Curating architectural masterworks and signature estates for those who command distinction.
                </p>
                <div style="font-size: 0.8rem; color: var(--gold-primary);">
                    Excellence &bull; Discretion &bull; Legacy
                </div>
            </div>

            <!-- Portfolio Links -->
            <div class="footer-col">
                <h4>Portfolio</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/properties.php?category=Apartment">Penthouses & Luxury Suites</a></li>
                    <li><a href="<?= BASE_URL; ?>/properties.php?category=Villa">Signature Garden Villas</a></li>
                    <li><a href="<?= BASE_URL; ?>/properties.php?category=Commercial">Grade-A Commercial</a></li>
                    <li><a href="<?= BASE_URL; ?>/properties.php?category=Plot">Private Land Parcels</a></li>
                    <li><a href="<?= BASE_URL; ?>/properties.php?status=Upcoming">Upcoming Landmarks</a></li>
                </ul>
            </div>

            <!-- Company Navigation -->
            <div class="footer-col">
                <h4>The House</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/about.php">About Magaram</a></li>
                    <li><a href="<?= BASE_URL; ?>/services.php">Bespoke Advisory</a></li>
                    <li><a href="<?= BASE_URL; ?>/contact.php">Private Concierge</a></li>
                    <li><a href="<?= BASE_URL; ?>/admin/login.php">Executive Portal</a></li>
                </ul>
            </div>

            <!-- Concierge & Office Contact -->
            <div class="footer-col">
                <h4>Private Concierge</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;">
                    <?= e($settings['office_address']); ?>
                </p>
                <p style="margin-bottom: 6px;">
                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" style="color: var(--gold-bright); font-weight: 600;">
                        <?= e($settings['contact_phone']); ?>
                    </a>
                </p>
                <p>
                    <a href="mailto:<?= e($settings['contact_email']); ?>" style="color: var(--text-muted); font-size: 0.88rem;">
                        <?= e($settings['contact_email']); ?>
                    </a>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; <?= date('Y'); ?> <?= e($settings['site_name']); ?>. All Rights Reserved.
                <?php if (!empty($settings['stats_note'])): ?>
                    <span style="display: block; font-size: 0.72rem; color: #888; margin-top: 4px;">
                        Note: <?= e($settings['stats_note']); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div style="display: flex; gap: 20px;">
                <a href="<?= BASE_URL; ?>/admin/login.php" style="color: var(--text-muted); font-size: 0.8rem;">Admin Login</a>
            </div>
        </div>
    </div>
</footer>

<!-- ---------------- Book Site Visit Modal ---------------- -->
<div class="modal-backdrop" id="modal-site-visit" role="dialog" aria-modal="true" aria-labelledby="modal-visit-title">
    <div class="modal-dialog">
        <button type="button" class="modal-close" aria-label="Close dialog">&times;</button>
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="section-tag">Private Appointment</span>
            <h3 id="modal-visit-title" style="font-size: 1.8rem;">Book a Site Visit</h3>
            <p class="modal-property-name" style="display: none; color: var(--gold-bright); font-size: 0.95rem; margin-top: 6px;"></p>
        </div>

        <form class="luxury-form ajax-enquiry-form" action="<?= BASE_URL; ?>/api/enquiry.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
            <input type="hidden" name="type" value="Site Visit">
            <input type="hidden" name="property_id" value="">
            
            <!-- Honeypot Bot Trap -->
            <input type="text" name="website_url_hp" class="hp-field" tabindex="-1" autocomplete="off">

            <div class="form-row">
                <div class="form-group">
                    <label for="visit-name">Full Name *</label>
                    <input type="text" id="visit-name" name="name" class="form-control" placeholder="e.g. Vikramaditya Rao" required>
                </div>
                <div class="form-group">
                    <label for="visit-phone">Direct Phone *</label>
                    <input type="tel" id="visit-phone" name="phone" class="form-control" placeholder="+91 98765 00000" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="visit-email">Email Address *</label>
                    <input type="email" id="visit-email" name="email" class="form-control" placeholder="name@domain.com" required>
                </div>
                <div class="form-group">
                    <label for="visit-date">Preferred Date</label>
                    <input type="date" id="visit-date" name="preferred_date" class="form-control" min="<?= date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="visit-notes">Bespoke Requirements / Notes</label>
                <textarea id="visit-notes" name="message" class="form-control" rows="3" placeholder="Tell us your preferences (e.g., private elevator, skyline view, executive timing)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                Confirm Private Viewing Request
            </button>
        </form>
    </div>
</div>

<!-- ---------------- Get Brochure Modal ---------------- -->
<div class="modal-backdrop" id="modal-brochure" role="dialog" aria-modal="true" aria-labelledby="modal-brochure-title">
    <div class="modal-dialog">
        <button type="button" class="modal-close" aria-label="Close dialog">&times;</button>
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="section-tag">Confidential Portfolio</span>
            <h3 id="modal-brochure-title" style="font-size: 1.8rem;">Download Brochure</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 6px;">
                Receive the complete architectural prospectus with high-resolution floor plans and specifications.
            </p>
        </div>

        <form class="luxury-form ajax-enquiry-form" action="<?= BASE_URL; ?>/api/enquiry.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
            <input type="hidden" name="type" value="Brochure">
            <input type="hidden" name="property_id" value="">
            
            <!-- Honeypot Bot Trap -->
            <input type="text" name="website_url_hp" class="hp-field" tabindex="-1" autocomplete="off">

            <div class="form-group">
                <label for="brochure-name">Full Name *</label>
                <input type="text" id="brochure-name" name="name" class="form-control" placeholder="e.g. Ananya Singhania" required>
            </div>

            <div class="form-group">
                <label for="brochure-phone">Phone Number *</label>
                <input type="tel" id="brochure-phone" name="phone" class="form-control" placeholder="+91 98765 00000" required>
            </div>

            <div class="form-group">
                <label for="brochure-email">Email Address (To Receive PDF) *</label>
                <input type="email" id="brochure-email" name="email" class="form-control" placeholder="client@company.com" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                Access Architectural Brochure
            </button>
        </form>
    </div>
</div>

<!-- JavaScript Core -->
<script src="<?= BASE_URL; ?>/assets/js/main.js"></script>
<script src="<?= BASE_URL; ?>/assets/js/sparkle.js"></script>
</body>
</html>

