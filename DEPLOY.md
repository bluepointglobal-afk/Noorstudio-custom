# Deploying NoorStudio to AWS EC2

A complete, copy-paste guide to host this theme on a single EC2 instance with a
real database, HTTPS, and a git-based workflow so you can keep customising it.

> **Stack:** Ubuntu 24.04 LTS · nginx · PHP 8.3-FPM · MariaDB · WordPress ·
> the `noorstudio` theme pulled from this repo.
> A one-shot script (`deploy/install-wordpress.sh`) does Parts B–E for you;
> the manual steps below explain what it does so you can customise or debug.

There are two ways through this doc:
- **Fast path:** Part A (launch EC2) → run `deploy/install-wordpress.sh` → Part F (DNS + HTTPS).
- **Manual path:** follow every part by hand.

---

## Architecture choice (read once)

| | Recommended for you | When to upgrade |
|---|---|---|
| **Database** | MariaDB **on the same EC2 instance** (simplest, cheapest) | Move to **Amazon RDS** when you need backups/failover/scale |
| **Instance** | `t3.small` (2 GB RAM) | `t3.medium`+ under real traffic |
| **Images/CDN** | local disk | **S3 + CloudFront** (or a caching plugin) as traffic grows |

A marketing site like this runs comfortably on one `t3.small`. Don't over-build day one.

---

## Part A — Launch the EC2 instance

1. **EC2 → Launch instance.**
   - **AMI:** Ubuntu Server 24.04 LTS (64-bit x86).
   - **Type:** `t3.small` (2 GB RAM). `t2.micro` (free tier) works but is tight for WordPress.
   - **Key pair:** create/download one (`noorstudio.pem`) — this is your SSH login.
   - **Storage:** 20–30 GB gp3.
2. **Security group** — inbound rules:
   - SSH (22) → **My IP** only.
   - HTTP (80) → Anywhere.
   - HTTPS (443) → Anywhere.
3. **Allocate an Elastic IP** (EC2 → Elastic IPs → Allocate → Associate with the
   instance). This keeps your public IP stable across reboots — required before
   you point a domain at it.
4. **SSH in:**
   ```bash
   chmod 400 noorstudio.pem
   ssh -i noorstudio.pem ubuntu@<ELASTIC_IP>
   ```

---

## Part B — Install the LEMP stack

```bash
sudo apt update && sudo apt -y upgrade
sudo apt -y install nginx mariadb-server \
  php8.3-fpm php8.3-mysql php8.3-curl php8.3-gd php8.3-mbstring \
  php8.3-xml php8.3-zip php8.3-intl php8.3-imagick \
  git unzip curl

sudo systemctl enable --now nginx php8.3-fpm mariadb
```

Lock down MariaDB and create the database:

```bash
sudo mysql_secure_installation     # set a root password, answer "Y" to the rest

sudo mysql -u root -p <<'SQL'
CREATE DATABASE noorstudio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'noor'@'localhost' IDENTIFIED BY 'CHANGE_ME_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON noorstudio.* TO 'noor'@'localhost';
FLUSH PRIVILEGES;
SQL
```

---

## Part C — Install WordPress core + WP-CLI

```bash
# WP-CLI (makes everything below scriptable)
curl -sSL -o /tmp/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
sudo install -m 0755 /tmp/wp /usr/local/bin/wp

# Document root
sudo mkdir -p /var/www/noorstudio
sudo chown -R $USER:www-data /var/www/noorstudio
cd /var/www/noorstudio

wp core download
wp config create --dbname=noorstudio --dbuser=noor --dbpass='CHANGE_ME_STRONG_PASSWORD' --dbhost=localhost
wp core install \
  --url="https://YOUR_DOMAIN" \
  --title="NoorStudio" \
  --admin_user="admin" \
  --admin_password="CHANGE_ME_ADMIN_PASSWORD" \
  --admin_email="you@example.com" \
  --skip-email
```

---

## Part D — Install **this theme** from the repo

Clone the repo once, then symlink its theme folder into WordPress. This is the
key to easy customisation: edit on GitHub (or locally), `git pull` on the
server, and the live site updates — no re-uploading.

```bash
# Clone the repo (use a deploy key or PAT for a private repo)
sudo git clone --branch claude/lucid-galileo-1errx8 \
  https://github.com/bluepointglobal-afk/Noorstudio-custom.git /opt/noorstudio-src

# Symlink the theme into WordPress
sudo ln -sfn /opt/noorstudio-src/wp-content/themes/noorstudio \
  /var/www/noorstudio/wp-content/themes/noorstudio

# Activate it
cd /var/www/noorstudio
wp theme activate noorstudio
```

### Add the real artwork
The design bundle's images aren't in the repo. Copy `img-01.png … img-15.*`
(and optionally `og-image.jpg`) into the theme's image folder:

```bash
# from your laptop, where the design bundle lives:
scp -i noorstudio.pem ./assets/*.{png,jpg} \
  ubuntu@<ELASTIC_IP>:/opt/noorstudio-src/wp-content/themes/noorstudio/assets/images/
```

Until they're present the theme renders branded placeholders, so nothing breaks.

### Permissions
```bash
sudo chown -R www-data:www-data /var/www/noorstudio /opt/noorstudio-src
sudo find /var/www/noorstudio -type d -exec chmod 755 {} \;
sudo find /var/www/noorstudio -type f -exec chmod 644 {} \;
```

---

## Part E — nginx + permalinks + pages

Copy the provided server block, edit the domain, enable it:

```bash
sudo cp /opt/noorstudio-src/deploy/nginx-noorstudio.conf /etc/nginx/sites-available/noorstudio
sudo sed -i 's/YOUR_DOMAIN/example.com/g' /etc/nginx/sites-available/noorstudio
sudo ln -sfn /etc/nginx/sites-available/noorstudio /etc/nginx/sites-enabled/noorstudio
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

Pretty permalinks + the supporting pages from the SEO sitemap:

```bash
cd /var/www/noorstudio
wp rewrite structure '/%postname%/' --hard

# Create the pages the nav/footer link to (so they don't 404)
for slug in features how-it-works templates pricing islamic-childrens-books \
  kdp-childrens-book-creator personalized-childrens-books success-stories \
  about contact blog affiliate privacy-policy terms-of-service cookie-policy; do
  wp post create --post_type=page --post_status=publish \
    --post_title="$(echo $slug | sed 's/-/ /g')" --post_name="$slug" \
    --post_content="<p>Placeholder — add the strategy copy for this page.</p>"
done
```

Visit `http://YOUR_DOMAIN` — the homepage should render (front-page.php takes
priority regardless of the Reading settings).

---

## Part F — Domain + HTTPS (Let's Encrypt)

1. **DNS:** in Route 53 (or your registrar) create an **A record** for your
   domain → the **Elastic IP**. Add a second A record for `www`.
2. **TLS certificate** (free, auto-renewing):
   ```bash
   sudo apt -y install certbot python3-certbot-nginx
   sudo certbot --nginx -d example.com -d www.example.com
   ```
   Certbot rewrites the nginx block to listen on 443 and redirect 80→443.
3. **Tell WordPress it's HTTPS** (so canonical/OG URLs are correct):
   ```bash
   cd /var/www/noorstudio
   wp option update home    'https://example.com'
   wp option update siteurl 'https://example.com'
   ```

That's it — you're live with a valid certificate and the SEO tags pointing at
the real domain.

---

## Customising the theme

Everything is plain PHP/CSS — no build step, no page builder.

### 1. Content (the fast 90%)
All homepage copy lives in **one file**: `wp-content/themes/noorstudio/inc/content.php`.
Tidy PHP arrays for the FAQ, pricing, books, illustration styles, and
testimonials. Edit text there and the page **and** the schema update together
(the FAQ answers and FAQPage JSON-LD come from the same array).

### 2. Section markup & layout
`wp-content/themes/noorstudio/template-parts/home/*.php` — one file per section
(hero, styles, shelf, how, testimonials, pricing, faq, final). Edit headings,
order, or HTML here. Design tokens (colours, spacing, shadows) and all CSS are
in `style.css`.

### 3. SEO (meta + schema)
`wp-content/themes/noorstudio/inc/seo.php` — meta title/description, Open Graph,
and the JSON-LD. If you install **Yoast** or **Rank Math**, the theme
automatically steps back from the head meta to avoid duplicates but keeps
emitting the SoftwareApplication/FAQPage schema.

### 4. Things you change in wp-admin (no code)
- **Logo:** Appearance → Customize → Site Identity (or drop `img-01.png` in `assets/images/`).
- **Menus:** Appearance → Menus → assign to the `primary` / `footer_*` locations
  to override the built-in fallback links.
- **Inner-page content:** edit the pages you created in Part E.

### Recommended git workflow
```
edit locally / on GitHub  →  commit & push
on the server:  cd /opt/noorstudio-src && sudo git pull && sudo systemctl reload php8.3-fpm
```
Because the theme is a **symlink** to the cloned repo, a `git pull` is the whole
deploy. For zero-risk experiments, work on a branch and only pull it on the
server when you're happy.

> **Updating WordPress itself** never touches your theme — your customisations
> live in this repo, not in core, so a child theme isn't necessary here.

---

## Operations checklist

- **Backups:** `wp db export` on a cron + snapshot the EBS volume (AWS Backup).
  For the DB only: `mysqldump noorstudio | gzip > backup-$(date +%F).sql.gz`.
- **Caching:** add a page-cache plugin (e.g. WP Super Cache) — pairs well with
  the theme's already-light JS/CSS and helps the Core Web Vitals targets.
- **Updates:** `wp core update`, `wp plugin update --all` periodically.
- **Security:** keep port 22 restricted to your IP, install `fail2ban`,
  consider AWS WAF / CloudFront in front if you get bot traffic.
- **Email:** WordPress can't send mail from EC2 reliably — use an SMTP plugin
  with Amazon SES or another provider for contact-form / trial emails.
- **Logs:** nginx `/var/log/nginx/`, PHP-FPM `/var/log/php8.3-fpm.log`,
  WordPress debug via `define('WP_DEBUG', true)` in `wp-config.php` (dev only).

---

## Cost ballpark (us-east-1, on-demand)

| Item | ~Monthly |
|---|---|
| `t3.small` 24/7 | ~$15 |
| 30 GB gp3 | ~$2.40 |
| Elastic IP (while attached) | free |
| Route 53 hosted zone | $0.50 |
| Data transfer (light) | a few $ |

Reserved/Savings Plans cut the instance cost ~40–60% if you commit. **Amazon
Lightsail** ($5–$10 fixed/mo, WordPress blueprint included) is a simpler
alternative if you don't need raw EC2 control — the theme install steps (Part D)
are identical there.
