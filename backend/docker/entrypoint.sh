#!/bin/sh
set -e

cd /var/www/html

# ---------------------------------------------------------------------------
# 1. Disk persistent (Render: mountPath = /var/www/html/persistent)
#    Jika env PERSISTENT_MOUNT di-set, database & storage dipindah ke sana
#    agar data bertahan antar-deployment.
# ---------------------------------------------------------------------------
if [ -n "${PERSISTENT_MOUNT:-}" ] && [ -d "$PERSISTENT_MOUNT" ]; then
    echo "[entrypoint] Menggunakan disk persistent: $PERSISTENT_MOUNT"
    mkdir -p "$PERSISTENT_MOUNT/database" "$PERSISTENT_MOUNT/storage/app/public" \
             "$PERSISTENT_MOUNT/storage/app/private" "$PERSISTENT_MOUNT/storage/app/dmls"

    # Salin database lama ke disk persistent (transisi pertama saja).
    if [ ! -s "$PERSISTENT_MOUNT/database/database.sqlite" ] && [ -f /var/www/html/database/database.sqlite ]; then
        cp /var/www/html/database/database.sqlite "$PERSISTENT_MOUNT/database/database.sqlite"
    fi

    # Salin file upload lama bila storage persistent masih kosong.
    if [ -z "$(ls -A "$PERSISTENT_MOUNT/storage/app" 2>/dev/null)" ]; then
        cp -rn /var/www/html/storage/app/. "$PERSISTENT_MOUNT/storage/app/" 2>/dev/null || true
    fi

    # Hanya database + folder upload yang di-symlink agar bertahan antar-deployment.
    # storage/framework (cache/session/view) & storage/logs tetap di kontainer
    # karena memang dibangun ulang tiap boot.
    for target in storage/app database/database.sqlite; do
        dest="$PERSISTENT_MOUNT/$target"
        src="/var/www/html/$target"
        if [ -e "$src" ] && [ ! -L "$src" ]; then rm -rf "$src"; fi
        if [ ! -e "$src" ]; then
            mkdir -p "$(dirname "$src")"
            ln -s "$dest" "$src"
        fi
    done

    export DB_DATABASE="${DB_DATABASE:-$PERSISTENT_MOUNT/database/database.sqlite}"
fi

# ---------------------------------------------------------------------------
# 2. Struktur direktori wajib
# ---------------------------------------------------------------------------
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    storage/app/private \
    database \
    bootstrap/cache

[ -f "${DB_DATABASE:-database/database.sqlite}" ] || touch "${DB_DATABASE:-database/database.sqlite}"

chown -R www-data:www-data database storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwx database storage bootstrap/cache 2>/dev/null || true

# ---------------------------------------------------------------------------
# 3. Bersihkan cache build-time
# ---------------------------------------------------------------------------
php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

# ---------------------------------------------------------------------------
# 4. Migrasi (+ sekali seed saat database kosong)
# ---------------------------------------------------------------------------
php artisan migrate --force --no-interaction

if [ "${SEED_ON_EMPTY:-false}" = "true" ]; then
    USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tr -dc '0-9' || echo "1")
    if [ "${USER_COUNT:-1}" = "0" ]; then
        echo "[entrypoint] Database kosong -> menjalankan seeder..."
        php artisan db:seed --force --no-interaction || true
    fi
fi

# ---------------------------------------------------------------------------
# 5. Symlink storage publik (gambar berita)
# ---------------------------------------------------------------------------
php artisan storage:link --force --no-interaction || true

# ---------------------------------------------------------------------------
# 6. Optimasi produksi (terakhir: membaca environment Render)
# ---------------------------------------------------------------------------
php artisan optimize --no-interaction || true

exec "$@"
