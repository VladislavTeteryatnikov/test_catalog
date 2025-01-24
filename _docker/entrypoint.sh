#!/bin/sh

# Ожидаем, пока база данных будет доступна
echo "Ожидание запуска MySQL..."
/usr/local/bin/wait-for-it db:3306 --timeout=30 --strict -- echo "MySQL запущен"

# Выполняем миграции
echo "Запуск миграций..."
php /var/www/test_catalog/artisan migrate --force

# Запускаем Supervisor
echo "Запуск Supervisor..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
