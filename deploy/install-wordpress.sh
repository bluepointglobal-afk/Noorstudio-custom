#!/usr/bin/env bash
#
# NoorStudio — one-shot WordPress installer for a fresh Ubuntu 24.04 EC2 box.
# Installs LEMP + WordPress + this theme, wires up nginx and pretty permalinks,
# and creates the supporting pages. Run AFTER Part A (instance launched, SSH'd in).
#
# Usage (edit the vars or pass them as env):
#   sudo DOMAIN=example.com ADMIN_EMAIL=you@example.com \
#        ADMIN_PASS='choose-a-strong-pass' \
#        bash install-wordpress.sh
#
# Re-runnable: skips steps whose result already exists where practical.
# HTTPS is NOT done here — run `certbot --nginx -d $DOMAIN -d www.$DOMAIN`
# afterwards (see DEPLOY.md, Part F), then update siteurl/home to https://.
#
set -euo pipefail

# ---- Config (override via env) ------------------------------------------------
DOMAIN="${DOMAIN:-example.com}"
ADMIN_USER="${ADMIN_USER:-admin}"
ADMIN_PASS="${ADMIN_PASS:-$(openssl rand -base64 12)}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@${DOMAIN}}"
SITE_TITLE="${SITE_TITLE:-NoorStudio}"

DB_NAME="${DB_NAME:-noorstudio}"
DB_USER="${DB_USER:-noor}"
DB_PASS="${DB_PASS:-$(openssl rand -base64 16)}"

WP_DIR="${WP_DIR:-/var/www/noorstudio}"
SRC_DIR="${SRC_DIR:-/opt/noorstudio-src}"
REPO_URL="${REPO_URL:-https://github.com/bluepointglobal-afk/Noorstudio-custom.git}"
REPO_BRANCH="${REPO_BRANCH:-claude/lucid-galileo-1errx8}"
PHP_VER="${PHP_VER:-8.3}"

log() { printf '\n\033[1;32m==> %s\033[0m\n' "$*"; }

if [[ $EUID -ne 0 ]]; then echo "Run with sudo/root." >&2; exit 1; fi

# ---- B. LEMP stack ------------------------------------------------------------
log "Installing nginx, MariaDB, PHP ${PHP_VER}, and tools"
export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get install -y nginx mariadb-server \
  "php${PHP_VER}-fpm" "php${PHP_VER}-mysql" "php${PHP_VER}-curl" "php${PHP_VER}-gd" \
  "php${PHP_VER}-mbstring" "php${PHP_VER}-xml" "php${PHP_VER}-zip" "php${PHP_VER}-intl" \
  "php${PHP_VER}-imagick" git unzip curl openssl
systemctl enable --now nginx "php${PHP_VER}-fpm" mariadb

# ---- DB -----------------------------------------------------------------------
log "Creating database and user"
mysql <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL

# ---- C. WP-CLI + WordPress core ----------------------------------------------
if ! command -v wp >/dev/null 2>&1; then
  log "Installing WP-CLI"
  curl -sSL -o /tmp/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  install -m 0755 /tmp/wp /usr/local/bin/wp
fi
WP="wp --allow-root --path=${WP_DIR}"

log "Downloading and installing WordPress core"
mkdir -p "${WP_DIR}"
if [[ ! -f "${WP_DIR}/wp-load.php" ]]; then
  $WP core download
fi
if [[ ! -f "${WP_DIR}/wp-config.php" ]]; then
  $WP config create --dbname="${DB_NAME}" --dbuser="${DB_USER}" --dbpass="${DB_PASS}" --dbhost=localhost
fi
if ! $WP core is-installed 2>/dev/null; then
  $WP core install --url="http://${DOMAIN}" --title="${SITE_TITLE}" \
    --admin_user="${ADMIN_USER}" --admin_password="${ADMIN_PASS}" \
    --admin_email="${ADMIN_EMAIL}" --skip-email
fi

# ---- D. Theme from this repo --------------------------------------------------
log "Fetching theme from ${REPO_URL} (${REPO_BRANCH})"
if [[ -d "${SRC_DIR}/.git" ]]; then
  git -C "${SRC_DIR}" fetch --depth 1 origin "${REPO_BRANCH}"
  git -C "${SRC_DIR}" checkout "${REPO_BRANCH}"
  git -C "${SRC_DIR}" pull --ff-only origin "${REPO_BRANCH}"
else
  git clone --branch "${REPO_BRANCH}" --depth 1 "${REPO_URL}" "${SRC_DIR}"
fi
ln -sfn "${SRC_DIR}/wp-content/themes/noorstudio" "${WP_DIR}/wp-content/themes/noorstudio"
$WP theme activate noorstudio

# ---- E. Permalinks + supporting pages ----------------------------------------
log "Configuring permalinks and supporting pages"
$WP rewrite structure '/%postname%/' --hard
for slug in features how-it-works templates pricing islamic-childrens-books \
  kdp-childrens-book-creator personalized-childrens-books success-stories \
  about contact blog affiliate privacy-policy terms-of-service cookie-policy; do
  if ! $WP post list --post_type=page --field=post_name | grep -qx "$slug"; then
    title="$(echo "$slug" | sed -E 's/(^|-)([a-z])/\1\u\2/g; s/-/ /g')"
    $WP post create --post_type=page --post_status=publish \
      --post_title="$title" --post_name="$slug" \
      --post_content="<p>Placeholder — add the strategy copy for this page.</p>" >/dev/null
  fi
done

# ---- nginx --------------------------------------------------------------------
log "Configuring nginx"
install -m 0644 "${SRC_DIR}/deploy/nginx-noorstudio.conf" /etc/nginx/sites-available/noorstudio
sed -i "s/YOUR_DOMAIN/${DOMAIN}/g; s/php8.3-fpm.sock/php${PHP_VER}-fpm.sock/g" /etc/nginx/sites-available/noorstudio
ln -sfn /etc/nginx/sites-available/noorstudio /etc/nginx/sites-enabled/noorstudio
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# ---- Permissions --------------------------------------------------------------
log "Setting permissions"
chown -R www-data:www-data "${WP_DIR}" "${SRC_DIR}"
find "${WP_DIR}" -type d -exec chmod 755 {} \;
find "${WP_DIR}" -type f -exec chmod 644 {} \;

# ---- Done ---------------------------------------------------------------------
cat <<DONE

============================================================
 NoorStudio is installed.
   URL:        http://${DOMAIN}/
   Admin:      http://${DOMAIN}/wp-admin/
   Admin user: ${ADMIN_USER}
   Admin pass: ${ADMIN_PASS}
   DB:         ${DB_NAME} / ${DB_USER} / ${DB_PASS}

 Next:
   1. Point ${DOMAIN} (A record) at this box's Elastic IP.
   2. sudo certbot --nginx -d ${DOMAIN} -d www.${DOMAIN}
   3. wp --allow-root --path=${WP_DIR} option update home    'https://${DOMAIN}'
      wp --allow-root --path=${WP_DIR} option update siteurl 'https://${DOMAIN}'
   4. Copy real artwork into:
      ${SRC_DIR}/wp-content/themes/noorstudio/assets/images/
 (Save the credentials above somewhere safe — the random ones aren't recoverable.)
============================================================
DONE
