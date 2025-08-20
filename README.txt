
# WooCommerce Yoga Demo (Header to Footer + bKash/Nagad + Admin Dashboard)

**What you get**
- Custom theme: `monjur-yoga` (Home template with Hero, Yoga Collection, Products, Features, Image-with-Text, Tabs, Banner, Blog, Testimonials, Contact)
- Plugin: `yoga-bkash-nagad-gateway`
  - bKash + Nagad demo gateways (Sandbox-style)
  - Cash-Out checkbox (+৳15) at checkout
  - Orders Dashboard (CSV export, pagination, invoice view + Download PDF via jsPDF)
  - Order status email notification to site admin
  - Settings page for phone and merchant IDs

**How to install (A to Z)**
1. Install **WordPress** + **WooCommerce**.
2. Go to **Appearance → Themes → Add New → Upload Theme** and upload `/wp-content/themes/monjur-yoga` as a ZIP (or copy the folder).
3. Activate **Monjur Yoga** theme. Create a page named **Home**, assign template **Home (Yoga Landing)**, and set it as your static homepage in **Settings → Reading**.
4. Upload/activate plugin **Yoga bKash & Nagad Gateway + Orders Admin** from `/wp-content/plugins/yoga-bkash-nagad-gateway`.
5. In **WooCommerce → Settings → Payments**, enable **bKash (Demo)** and **Nagad (Demo)**.
6. In **Yoga Payments** (admin sidebar), set:
   - Default Phone: `01797850441` (you can change)
   - Merchant IDs (for real integration you will need API credentials)
   - Test Mode (keeps demo behavior).
7. Add products & categories (add category thumbnails for better visuals).
8. Checkout flow:
   - Customer selects **bKash** or **Nagad**.
   - Enters **Phone** and **Transaction ID** (demo field).
   - Optional **Cash-Out** checkbox adds ৳15 fee.
   - On demo, payment is marked *complete* and order placed.
9. **Orders Dashboard**: Admin → **Yoga Payments → Orders Dashboard** for CSV export, pagination and Invoice view (**Download PDF** button).

**Real API integration (notes)**
- Create `includes/api-bkash.php` / `api-nagad.php` to call official APIs (token, create payment, execute, verify). Use settings to store credentials.
- In each gateway's `process_payment`, replace demo completion with verification logic.
- Use webhooks/IPN if provided.

**Safety**
- This build is a demo. For production, add nonce checks, sanitization, and server-side PDF library if desired.

Good luck!
