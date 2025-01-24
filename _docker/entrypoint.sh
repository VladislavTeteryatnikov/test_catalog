#!/bin/sh

# Ожидаем, пока база данных будет доступна
echo "Ожидание запуска базы данных..."
/usr/local/bin/wait-for-it db:3306 --timeout=30 --strict -- echo "База данных доступна!"

# Запускаем миграции
echo "Запускаем миграции..."
php artisan migrate --force

# Запускаем Supervisor
echo "Запускаем Supervisor..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf

