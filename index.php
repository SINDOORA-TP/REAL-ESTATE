<?php
/**
 * Magaram Real Estate - Homepage
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings();
$db = get_db();

// Fetch Featured Properties
$featuredProperties = [];
if ($db) {
    try {
        $stmt = $db->query("
            SELECT p.*, 
                   COALESCE(pi.image_path, CONCAT('assets/img/property-', p.id, '.svg')) AS cover_image
            FROM properties p
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_cover = 1
            WHERE p.is_featured = 1
            ORDER BY p.id ASC
            LIMIT 6
        ");
        $featuredProperties = $stmt->fetchAll();
    } catch (Exception $e) {
        $featuredProperties = [];
    }
}

if (empty($featuredProperties)) {
    $featuredProperties = get_fallback_properties();
}

// Fetch Testimonials
$testimonials = [];
if (!empty($settings['show_testimonials'])) {
    if ($db) {
        try {
            $stmt = $db->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY id ASC LIMIT 3");
            $testimonials = $stmt->fetchAll();
        } catch (Exception $e) {
            $testimonials = [];
        }
    }
    if (empty($testimonials)) {
        $testimonials = [
            [
                'name' => 'Vikramaditya Rao',
                'designation' => 'Founder, Apex Capital [Sample]',
                'text' => 'Magaram delivered an experience beyond what traditional real estate firms offer. The level of privacy, architectural insight, and seamless transaction for our Jubilee Hills villa was unmatched.',
                'rating' => 5,
                'is_sample' => 1
            ],
            [
                'name' => 'Dr. Aisha Merchant',
                'designation' => 'Chief Neurosurgeon [Sample]',
                'text' => 'From private viewings to closing paperwork, every single detail was handled with precision and dignity. Truly the gold standard of luxury real estate advisory.',
                'rating' => 5,
                'is_sample' => 1
            ],
            [
                'name' => 'Rajeshwari Devi',
                'designation' => 'Industrialist & Art Collector [Sample]',
                'text' => 'Finding a property that accommodated our curated gallery and private grounds seemed impossible until Magaram brought us their off-market collection.',
                'rating' => 5,
                'is_sample' => 1
            ]
        ];
    }
}

// Gallery items
$galleryItems = [
    ['title' => 'Penthouse Sky Deck', 'img' => 'assets/img/property-1.svg', 'cat' => 'Exterior'],
    ['title' => 'Contemporary Stone Villa', 'img' => 'assets/img/property-2.svg', 'cat' => 'Architecture'],
    ['title' => 'Commercial Glass Spire', 'img' => 'assets/img/property-3.svg', 'cat' => 'Commercial'],
    ['title' => 'Boutique Residence Atrium', 'img' => 'assets/img/property-4.svg', 'cat' => 'Interior'],
    ['title' => 'Green Valley Grounds', 'img' => 'assets/img/property-5.svg', 'cat' => 'Estate'],
    ['title' => 'Opus Executive Complex', 'img' => 'assets/img/property-6.svg', 'cat' => 'Commercial']
];

$customTitle = $settings['site_name'] . ' | Curating Extraordinary Living Spaces';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ---------------- 1. Hero Section ---------------- -->
<section class="hero-section" style="background-image: url('<?= BASE_URL; ?>/assets/img/hero-bg.svg');">
    <div class="hero-overlay"></div>
    
    <!-- Canvas Gold Dust Sparkle Particle Layer -->
    <canvas id="hero-sparkle-canvas"></canvas>

    <div class="container hero-content">
        <!-- Location Pill -->
        <div class="location-pill">
            <span class="pill-dot"></span>
            JUBILEE HILLS, HYDERABAD
        </div>

        <!-- Two-Tone Headline -->
        <h1 class="hero-headline">
            <span class="headline-cream"><?= e($settings['hero_headline_1'] ?? 'Experience'); ?></span>
            <span class="gold-shimmer-text headline-gold"><?= e($settings['hero_headline_2'] ?? 'Luxury Living'); ?></span>
        </h1>

        <!-- Subtext -->
        <p class="hero-subtext">
            <?= e($settings['hero_subtext'] ?? 'Bespoke residences, signature penthouses, and prime commercial estates crafted with architectural precision.'); ?>
        </p>

        <!-- CTA Buttons -->
        <div class="hero-buttons">
            <button type="button" class="btn btn-primary" data-open-modal="modal-site-visit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Book Site Visit
            </button>
            <button type="button" class="btn btn-outline" data-open-modal="modal-brochure">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Get Brochure
            </button>
        </div>

        <!-- Hero Search Filter Bar -->
        <div class="hero-search-wrapper">
            <form action="<?= BASE_URL; ?>/properties.php" method="GET" class="hero-search-form">
                <div class="search-field">
                    <label for="search-location">Location</label>
                    <select name="location" id="search-location">
                        <option value="">All Prime Locations</option>
                        <option value="Jubilee Hills">Jubilee Hills</option>
                        <option value="Banjara Hills">Banjara Hills</option>
                        <option value="Hitec City">Hitec City / Financial Dist.</option>
                        <option value="Gachibowli">Gachibowli Outer Ring</option>
                        <option value="Moinabad">Moinabad Valley</option>
                    </select>
                </div>

                <div class="search-field">
                    <label for="search-category">Property Type</label>
                    <select name="category" id="search-category">
                        <option value="">All Categories</option>
                        <option value="Apartment">Penthouses & Suites</option>
                        <option value="Villa">Signature Villas</option>
                        <option value="Commercial">Grade-A Commercial</option>
                        <option value="Plot">Exclusive Plots</option>
                    </select>
                </div>

                <div class="search-field">
                    <label for="search-budget">Budget Tier</label>
                    <select name="budget" id="search-budget">
                        <option value="">Any Budget</option>
                        <option value="100000000">Up to ₹10 Crore</option>
                        <option value="250000000">Up to ₹25 Crore</option>
                        <option value="500000000">Up to ₹50 Crore</option>
                        <option value="1000000000">₹50 Crore & Above</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px; width: 100%;">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ---------------- 2. Stats Row (Real data or marked sample) ---------------- -->
<?php if (!empty($settings['show_stats']) && $settings['show_stats'] == '1'): ?>
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?= e($settings['stat_1_val']); ?></div>
                <div class="stat-label"><?= e($settings['stat_1_lbl']); ?></div>
                <span class="sample-badge">Sample Data</span>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= e($settings['stat_2_val']); ?></div>
                <div class="stat-label"><?= e($settings['stat_2_lbl']); ?></div>
                <span class="sample-badge">Sample Data</span>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= e($settings['stat_3_val']); ?></div>
                <div class="stat-label"><?= e($settings['stat_3_lbl']); ?></div>
                <span class="sample-badge">Sample Data</span>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= e($settings['stat_4_val']); ?></div>
                <div class="stat-label"><?= e($settings['stat_4_lbl']); ?></div>
                <span class="sample-badge">Sample Data</span>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ---------------- 3. Featured Properties ---------------- -->
<section class="section-padding" id="properties">
    <div class="container">
        <div style="text-align: center;">
            <span class="section-tag">Curated Portfolio</span>
            <h2 class="section-title">Signature Landmarks</h2>
            <p class="section-subtitle">
                Each acquisition in our portfolio represents irreplaceable location, architectural eminence, and uncompromising privacy.
            </p>
        </div>

        <div class="properties-grid">
            <?php foreach ($featuredProperties as $prop): ?>
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

        <div style="text-align: center; margin-top: 50px;">
            <a href="<?= BASE_URL; ?>/properties.php" class="btn btn-outline">
                Explore Complete Portfolio &rarr;
            </a>
        </div>
    </div>
</section>

<!-- ---------------- 4. About Preview ---------------- -->
<section class="section-padding" style="background: var(--bg-surface); border-top: 1px solid var(--border-subtle);" id="about">
    <div class="container">
        <div class="split-content">
            <div class="luxury-image-frame">
                <img src="<?= BASE_URL; ?>/assets/img/about-estate.svg" alt="Magaram Architectural Heritage" loading="lazy">
            </div>

            <div>
                <span class="section-tag">Our Heritage</span>
                <h2 style="font-size: 2.4rem; margin-bottom: 20px;">
                    Where Legacy Meets Architectural Grandeur
                </h2>
                <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.8; margin-bottom: 24px;">
                    <?= nl2br(e($settings['about_preview'])); ?>
                </p>
                <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 32px;">
                    From off-market private penthouses in Jubilee Hills to signature commercial headquarters along the Tech Corridor, our client relationships are anchored in total confidentiality and exacting standards.
                </p>
                <div style="display: flex; gap: 16px;">
                    <a href="<?= BASE_URL; ?>/about.php" class="btn btn-primary">Our Story</a>
                    <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-outline">Consult Concierge</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ---------------- 5. Why Choose Us ---------------- -->
<section class="section-padding">
    <div class="container">
        <div style="text-align: center;">
            <span class="section-tag">The Magaram Standard</span>
            <h2 class="section-title">Why Discerning Clients Choose Magaram</h2>
            <p class="section-subtitle">
                An advisory model structured around discretion, bespoke legal vetting, and unparalleled access.
            </p>
        </div>

        <div class="feature-cards-grid">
            <div class="feature-box">
                <div class="feature-icon-wrapper">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <h3 class="feature-title">Off-Market Exclusivity</h3>
                <p class="feature-text">
                    Over 40% of our premier estate transactions take place entirely off-market, guarding the privacy of buyers and sellers alike.
                </p>
            </div>

            <div class="feature-box">
                <div class="feature-icon-wrapper">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Vetted Legal Clearances</h3>
                <p class="feature-text">
                    Every landmark is subjected to multi-tier title search, structural certification, and RERA compliance before presentation.
                </p>
            </div>

            <div class="feature-box">
                <div class="feature-icon-wrapper">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3 class="feature-title">Turnkey Concierge</h3>
                <p class="feature-text">
                    From bespoke architectural tailoring to property administration and tax advisory, our private office oversees every step.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ---------------- 6. Amenities Showcase ---------------- -->
<section class="section-padding" style="background: var(--bg-surface); border-top: 1px solid var(--border-subtle);" id="amenities">
    <div class="container">
        <div style="text-align: center;">
            <span class="section-tag">Bespoke Living</span>
            <h2 class="section-title">Curated Signature Amenities</h2>
            <p class="section-subtitle">
                Designed to satisfy the expectations of modern royalty.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px;">
            <?php 
                $amenitiesList = [
                    ['Infinity Sky Pools', 'Temperature-regulated horizon pools with skyline panoramic vistas.'],
                    ['Private Biometric Elevators', 'Direct access straight into private penthouse vestibules.'],
                    ['Italian Calacatta Finishes', 'Bookmatched imported marble floors and artisanal millwork.'],
                    ['Smart Home Automation', 'Unified touch and voice control for climate, acoustics, and security.'],
                    ['24/7 Diplomatic Security', 'Multi-tier CCTV perimeter monitoring and discrete concierge.'],
                    ['Staff & Chauffeur Quarters', 'Independent private quarters with dedicated accessways.']
                ];
                foreach ($amenitiesList as $am):
            ?>
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 28px; border-radius: 6px;">
                    <div style="color: var(--gold-bright); font-size: 1.4rem; margin-bottom: 12px;">&#10022;</div>
                    <h4 style="font-size: 1.15rem; margin-bottom: 8px;"><?= $am[0]; ?></h4>
                    <p style="color: var(--text-muted); font-size: 0.88rem;"><?= $am[1]; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ---------------- 7. Gallery with Lightbox ---------------- -->
<section class="section-padding" id="gallery">
    <div class="container">
        <div style="text-align: center;">
            <span class="section-tag">Visual Walkthrough</span>
            <h2 class="section-title">Architectural Gallery</h2>
            <p class="section-subtitle">
                A glimpse inside our landmark residences, architectural facades, and sprawling grounds.
            </p>
        </div>

        <div class="gallery-grid">
            <?php foreach ($galleryItems as $item): ?>
                <div class="gallery-item" data-title="<?= e($item['title']); ?>" data-full="<?= BASE_URL . '/' . e($item['img']); ?>">
                    <img src="<?= BASE_URL . '/' . e($item['img']); ?>" alt="<?= e($item['title']); ?>" loading="lazy">
                    <div class="gallery-overlay">
                        <span style="color: var(--gold-bright); font-size: 1.6rem;">&oplus;</span>
                        <div class="gallery-title"><?= e($item['title']); ?></div>
                        <span style="font-size: 0.75rem; color: var(--gold-primary); text-transform: uppercase;"><?= e($item['cat']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ---------------- 8. Testimonials ---------------- -->
<?php if (!empty($settings['show_testimonials']) && !empty($testimonials)): ?>
<section class="section-padding" style="background: var(--bg-surface); border-top: 1px solid var(--border-subtle);" id="testimonials">
    <div class="container">
        <div style="text-align: center;">
            <span class="section-tag">Client Reflections</span>
            <h2 class="section-title">Voices of Distinction</h2>
            <p class="section-subtitle">
                What leaders of industry, collectors, and legacy families say about their experience with Magaram.
            </p>
        </div>

        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <?= str_repeat('&#9733;', (int)$t['rating']); ?>
                    </div>
                    <p class="testimonial-quote">
                        &ldquo;<?= e($t['text']); ?>&rdquo;
                    </p>
                    <div class="testimonial-author"><?= e($t['name']); ?></div>
                    <div class="testimonial-role"><?= e($t['designation']); ?></div>
                    <?php if (!empty($t['is_sample'])): ?>
                        <span class="sample-badge" style="margin-top: 10px;">Sample Testimonial</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ---------------- 9. Office Location & Contact ---------------- -->
<section class="section-padding contact-section" id="contact">
    <div class="container">
        <div style="text-align: center;">
            <span class="section-tag">Private Appointment</span>
            <h2 class="section-title">Visit Our Executive Chambers</h2>
            <p class="section-subtitle">
                We invite you for an espresso or champagne tasting while we present our off-market portfolio.
            </p>
        </div>

        <div class="contact-grid">
            <!-- Office Details & Direct Form -->
            <div class="contact-info-card">
                <h3 style="font-size: 1.5rem; margin-bottom: 20px; color: var(--gold-bright);">
                    Connect with Our Senior Partner
                </h3>
                
                <form class="luxury-form ajax-enquiry-form" action="<?= BASE_URL; ?>/api/enquiry.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken); ?>">
                    <input type="hidden" name="type" value="Enquiry">
                    
                    <!-- Bot Honeypot -->
                    <input type="text" name="website_url_hp" class="hp-field" tabindex="-1" autocomplete="off">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="home-contact-name">Name *</label>
                            <input type="text" id="home-contact-name" name="name" class="form-control" placeholder="Your Full Name" required>
                        </div>
                        <div class="form-group">
                            <label for="home-contact-phone">Phone *</label>
                            <input type="tel" id="home-contact-phone" name="phone" class="form-control" placeholder="+91 98765 00000" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="home-contact-email">Email Address *</label>
                        <input type="email" id="home-contact-email" name="email" class="form-control" placeholder="your@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="home-contact-msg">Message / Requirements</label>
                        <textarea id="home-contact-msg" name="message" class="form-control" rows="3" placeholder="Specify your desired location, property type, or specific questions..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
                        Request Confidential Consultation
                    </button>
                </form>

                <div style="margin-top: 32px; border-top: 1px solid var(--border-subtle); padding-top: 24px;">
                    <div class="info-item">
                        <span class="info-icon">&#9906;</span>
                        <div>
                            <strong>Headquarters:</strong><br>
                            <span style="color: var(--text-muted); font-size: 0.9rem;"><?= e($settings['office_address']); ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">&#9742;</span>
                        <div>
                            <strong>Direct Concierge:</strong><br>
                            <span style="color: var(--text-muted); font-size: 0.9rem;"><?= e($settings['contact_phone']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="map-container">
                <iframe 
                    src="<?= e($settings['map_embed_url']); ?>" 
                    title="Magaram Real Estate Office Location" 
                    loading="lazy" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

