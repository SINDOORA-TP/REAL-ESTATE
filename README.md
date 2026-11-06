# Magaram Real Estate

A royal black and antique-gold web portal and executive management platform for ultra-luxury residential, penthouses, villas, and commercial real estate.

---

## 1. Quick Start

### Option A: Running with XAMPP (Recommended)
1. Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.
2. Open your web browser and visit:
   ```
   http://localhost/real/install.php
   ```
   *(Or if placed in `htdocs/magaram`, visit `http://localhost/magaram/install.php`)*
3. Click **"Install & Seed Database"**. This will automatically create the `magaram_db` database, tables, seed sample properties, and set up the default admin.
4. Default Executive Admin Credentials:
   - **Username:** `admin`
   - **Password:** `admin123`
   - **Login URL:** `http://localhost/real/admin/login.php`

### Option B: PHP Built-in Server
You can also run locally in seconds using PowerShell:
```powershell
cd "c:\Users\LENOVO\OneDrive\Documents\real"
php -S localhost:8000
```
Then visit `http://localhost:8000` in your browser.

---

## 2. Architecture & Tech Stack

| Layer | Implementation |
|---|---|
| **Palette** | Royal Obsidian (`#080808`), Dark Surface (`#141414`), Antique Gold (`#C6A15B`), Shimmer Gold (`#E5C98B`), Cream Text (`#F5F1E8`) |
| **Typography** | `Playfair Display` (Headings) + `Inter` (Body text) |
| **Frontend** | HTML5, CSS3, Vanilla JS (zero external heavy frameworks) |
| **Sparkle FX** | Bespoke Canvas Particle Engine (`assets/js/sparkle.js`) |
| **Backend** | PHP 8.x with PDO (Prepared statements everywhere) |
| **Database** | MySQL / MariaDB (`database.sql`) |
| **Security** | CSRF tokens, honeypot traps, session rate-limiting, file upload MIME verification, `.htaccess` PHP execution disabling in `/uploads` |

---

## 3. Sparkle Effect Specification

1. **Hero Gold Dust:** A `<canvas>` particle layer above the hero photo and below the headline. It maintains 60–80 floating particles drifting upward, twinkling through opacity and scale, using colors between `#C6A15B` and `#E5C98B`.
2. **Four-Point Star Glints:** Randomly flashing 4-pointed micro stars.
3. **Gold Text Shimmer:** Moving light sweep across `"Luxury Living"` using `background-clip: text` with animated `background-position`.
4. **Button Shine:** Diagonal light streak sweep across `"Book Site Visit"` on hover.
5. **Card Sparkle:** Soft gold glow and particle hover response on property cards.
6. **Cursor Trail:** Fading gold spark trail following desktop mouse movement.
7. **Performance & Accessibility:**
   - Automatically pauses particle loop when browser tab is hidden using the Page Visibility API.
   - Reduces particle count by 50% on mobile devices.
   - Respects `prefers-reduced-motion` and disables particle canvas and shimmer animations for accessibility.

---

## 4. Key Pages & Admin Modules

- **Public Frontend:**
  - `index.php`: Hero with sparkle canvas, headline, CTA buttons, search bar, stats row, featured properties, about preview, why us, amenities, gallery with lightbox, testimonials, Google Maps, and enquiry form.
  - `properties.php`: Filter catalog by category, location, price, and status with pagination.
  - `property.php`: Individual estate view with gallery, specs, architectural overview, amenities, and pre-filled inquiry / booking forms.
  - `about.php`: Heritage and architectural philosophy.
  - `services.php`: Luxury brokerage, investment advisory, commercial acquisitions.
  - `contact.php`: Direct concierge form, office address, opening hours, Google Maps embed.
  - `brochure.php`: Executive architectural prospectus delivery with print/PDF support.

- **Admin Management Suite (`/admin`):**
  - `login.php` / `logout.php`: Secure authentication with `password_hash()` and session regeneration.
  - `dashboard.php`: Live counters for total properties, available units, leads, and recent inquiries.
  - `properties/`: Full CRUD (Add, Edit, Delete, multiple image upload, cover photo selection, amenities mapping).
  - `enquiries.php`: Status filtering (New / Contacted / Completed), internal notes, and **Export to CSV**.
  - `gallery.php`: Upload and manage architectural photo gallery.
  - `settings.php`: Edit brand name, contact details, Google Map embed URL, hero copy, and toggle or edit the 4 stats counters.

---

## 5. Answers to Section 11 Checklist

1. **Brand Styling:** Configured as **MAGARAM REAL ESTATE** with royal monogram emblem.
2. **Contact Details & Address:** Initialized with customizable defaults in Jubilee Hills, Hyderabad; easily updated anytime via Admin Settings (`/admin/settings.php`).
3. **Sample Properties:** Loaded with 6 ultra-luxury architectural landmarks (Penthouses, Villas, Commercial Spire, Land Parcels) clearly tagged with `[Sample Data]` badges as requested.
4. **Stats & Testimonials:** Both feature an instant one-click toggle in the Admin Settings to show or hide them until real figures and client approvals are available.

