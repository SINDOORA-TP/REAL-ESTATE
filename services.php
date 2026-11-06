<?php
/**
 * Magaram Real Estate - Services
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings();
$db = get_db();

$services = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC");
        $services = $stmt->fetchAll();
    } catch (Exception $e) {
        $services = [];
    }
}

if (empty($services)) {
    $services = [
        [
            'title' => 'Luxury Residential Brokerage',
            'description' => 'Exclusive representation for high-net-worth individuals acquiring premier penthouses, villas, and sprawling estates. Complete off-market access and private escrow negotiation.',
            'icon' => 'home'
        ],
        [
            'title' => 'Signature Commercial Spaces',
            'description' => 'Advising corporations and private investors on trophy office towers, premium retail flagships, and high-yield commercial assets across major financial hubs.',
            'icon' => 'building'
        ],
        [
            'title' => 'Private Investment Advisory',
            'description' => 'Strategic real estate portfolio planning, capital appreciation modeling, acquisition vetting, and tax-efficient asset structuring.',
            'icon' => 'trending-up'
        ],
        [
            'title' => 'Bespoke Architectural Project Management',
            'description' => 'Turnkey supervision from concept blueprints and structural compliance to master craft interior finishing for bespoke private residences.',
            'icon' => 'compass'
        ]
    ];
}

$customTitle = 'Bespoke Services | Magaram Real Estate';
$customDesc = 'Discover Magaram Real Estate advisory services: luxury residential acquisition, signature commercial spaces, and private wealth investment.';
require_once __DIR__ . '/includes/header.php';
?>

<div style="padding-top: 130px; background: var(--bg-surface); padding-bottom: 50px; border-bottom: 1px solid var(--border-subtle);">
    <div class="container text-center">
        <span class="section-tag">Bespoke Practice</span>
        <h1 style="font-size: 3.2rem; margin-bottom: 14px;">Advisory & Private Practice</h1>
        <p style="color: var(--text-muted); max-width: 680px; margin: 0 auto; font-size: 1.1rem;">
            A comprehensive suite of bespoke real estate services tailored for private clients, institutions, and family offices.
        </p>
    </div>
</div>

<div class="container section-padding">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; margin-bottom: 70px;">
        <?php foreach ($services as $srv): ?>
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 40px; border-radius: 8px; transition: all var(--transition-smooth); position: relative;">
                <div style="color: var(--gold-bright); font-size: 2rem; margin-bottom: 20px;">&#9670;</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 16px; color: var(--gold-bright);"><?= e($srv['title']); ?></h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 0.95rem; margin-bottom: 24px;">
                    <?= nl2br(e($srv['description'])); ?>
                </p>
                <button type="button" class="btn btn-outline btn-sm" data-open-modal="modal-site-visit">
                    Consult Private Advisor &rarr;
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Banner -->
    <div style="background: linear-gradient(135deg, #181512 0%, #101010 100%); border: 1px solid var(--border-gold); padding: 60px; border-radius: 8px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.8);">
        <span class="section-tag">Confidential Advisory</span>
        <h2 style="font-size: 2.4rem; margin-bottom: 16px;">Seeking an Unlisted Landmark?</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 30px auto; font-size: 1.05rem;">
            Our private office manages several off-market transactions each quarter that will never appear in public listings.
        </p>
        <button type="button" class="btn btn-primary" data-open-modal="modal-site-visit">
            Schedule Private Consultation
        </button>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

