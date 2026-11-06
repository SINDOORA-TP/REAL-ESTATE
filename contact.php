<?php
/**
 * Magaram Real Estate - Contact & Concierge
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings();

$customTitle = 'Contact Concierge | Magaram Real Estate';
$customDesc = 'Get in touch with Magaram Real Estate for private appointments, property evaluations, and off-market viewings.';
require_once __DIR__ . '/includes/header.php';
?>

<div style="padding-top: 130px; background: var(--bg-surface); padding-bottom: 50px; border-bottom: 1px solid var(--border-subtle);">
    <div class="container text-center">
        <span class="section-tag">Direct Access</span>
        <h1 style="font-size: 3.2rem; margin-bottom: 14px;">The Private Concierge</h1>
        <p style="color: var(--text-muted); max-width: 680px; margin: 0 auto; font-size: 1.1rem;">
            Whether inquiring regarding an acquisition, scheduling a discreet private viewing, or seeking advisory on a trophy asset.
        </p>
    </div>
</div>

<div class="container section-padding">
    <div class="contact-grid" style="margin-bottom: 70px;">
        <!-- Contact Information & Opening Hours -->
        <div>
            <span class="section-tag">Executive Chambers</span>
            <h2 style="font-size: 2.2rem; margin-bottom: 24px;">Connect with Leadership</h2>
            
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 32px; margin-bottom: 30px;">
                <div class="info-item">
                    <span class="info-icon">&#9906;</span>
                    <div>
                        <strong style="color: var(--gold-bright);">Office Headquarters:</strong>
                        <p style="color: var(--text-muted); margin-top: 4px;"><?= e($settings['office_address']); ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">&#9742;</span>
                    <div>
                        <strong style="color: var(--gold-bright);">Direct Phone:</strong>
                        <p style="margin-top: 4px;">
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" style="color: var(--text-cream); font-weight: 600;">
                                <?= e($settings['contact_phone']); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-icon">&#9993;</span>
                    <div>
                        <strong style="color: var(--gold-bright);">Confidential Email:</strong>
                        <p style="margin-top: 4px;">
                            <a href="mailto:<?= e($settings['contact_email']); ?>" style="color: var(--text-cream);">
                                <?= e($settings['contact_email']); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="info-item" style="margin-bottom: 0;">
                    <span class="info-icon">&#9200;</span>
                    <div>
                        <strong style="color: var(--gold-bright);">Private Consultation Hours:</strong>
                        <p style="color: var(--text-muted); margin-top: 4px;">Monday – Saturday: 10:00 AM – 7:00 PM IST<br>Sunday: By Private Executive Appointment Only</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enquiry Form -->
        <div class="contact-info-card">
            <h3 style="font-size: 1.6rem; margin-bottom: 16px; color: var(--gold-bright);">
                Submit a Private Inquiry
            </h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">
                All submissions are encrypted and routed directly to our Managing Director's desk.
            </p>

            <form class="luxury-form ajax-enquiry-form" action="<?= BASE_URL; ?>/api/enquiry.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
                <input type="hidden" name="type" value="Enquiry">
                <input type="text" name="website_url_hp" class="hp-field" tabindex="-1" autocomplete="off">

                <div class="form-row">
                    <div class="form-group">
                        <label for="c-name">Full Name *</label>
                        <input type="text" id="c-name" name="name" class="form-control" placeholder="Vikramaditya Rao" required>
                    </div>
                    <div class="form-group">
                        <label for="c-phone">Phone Number *</label>
                        <input type="tel" id="c-phone" name="phone" class="form-control" placeholder="+91 98765 00000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="c-email">Email Address *</label>
                    <input type="email" id="c-email" name="email" class="form-control" placeholder="client@domain.com" required>
                </div>

                <div class="form-group">
                    <label for="c-interest">Area of Interest</label>
                    <select id="c-interest" name="category" class="form-control">
                        <option value="Residential">Ultra-Luxury Residential / Penthouse</option>
                        <option value="Villa">Signature Villa Estate</option>
                        <option value="Commercial">Commercial / Corporate Headquarters</option>
                        <option value="Advisory">Investment Portfolio Advisory</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="c-message">Confidential Notes / Requirements</label>
                    <textarea id="c-message" name="message" class="form-control" rows="4" placeholder="Detail your parameters, preferred timeline, and communication preferences..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    Send Confidential Inquiry
                </button>
            </form>
        </div>
    </div>

    <!-- Map Location -->
    <div style="border-radius: 8px; overflow: hidden; border: 1px solid var(--border-gold); height: 420px; box-shadow: 0 15px 40px rgba(0,0,0,0.8);">
        <iframe 
            src="<?= e($settings['map_embed_url']); ?>" 
            title="Magaram Real Estate Office Map" 
            style="width: 100%; height: 100%; border: 0; filter: invert(90%) hue-rotate(180deg) contrast(120%);"
            loading="lazy" 
            allowfullscreen>
        </iframe>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

