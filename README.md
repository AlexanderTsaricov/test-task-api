# Тестовое задание
## Что выполнено
Реализован проект на базе laravel. 
Добавлены модели:
1. Income
2. Order
3. Sale
4. Stock

Добавлены таблицы в БД:
1. incomes
2. orders
3. sales
4. stocks

Добавлены сервисы:
1. Сервис ApiClient. Служит для загрузки данных из Api.
Методы:
Приватный метод send. Служит для непосредственного запроса к базе данных.
Публичные методы:
getSales - получает данные sales из апи 
getOrders - получает данные orders из апи 
getIncomes - получает данные incomes из апи 
getStocks - получает данные stocks из апи 

Добавлены консольные команды:
import:data

Занимаетс импортом из апи данных через сервис ApiClient и заполняет соответствующие таблицы. 
Пример использования:  php artisan import:data sales 2025-11-20 2025-11-30 100,<br>
где 2025-11-20 - дата с которой начинается выборка данных
2025-11-30 - дата по которую заканчивается выборка данных
100 - строк данных за один запрос

Загружены данные всех четырех таблиц по датам с 2025-11-20 по 2025-11-30

## БД доступы (пример .env):
```
DB_CONNECTION=mysql
DB_HOST=data-base
DB_PORT=33061
DB_DATABASE=db
DB_USERNAME=odin
DB_PASSWORD=odin
DB_ROOT_PASSWORD=iGdrAssil*&^

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_DRIVER=file
```

## Развертывание приложения

Для развертывания приложения прежде воспользуйтесь командой:
```
php artisan make:docker
```
Данная команда сгенерирует docker-compose.yml и Dockerfile. 
Все пароли берутся из вашего .env файла. Пример паролей указан выше.

После генерации файлов воспользуйтесь командой:
```
docker-compose up -d
```
Перед запуском docker-compose up -d убедитесь, что старые контейнеры удалены, чтобы избежать ошибок сборки!
Чтобы удалить старые контейнеры воспользуйтесь командой:
```
docker-compose down --rmi all --volumes --remove-orphans
```

## Запуск расписания автообновления
Для запуска расписания автообновления добавьте в системный cron:
```
* * * * * docker compose exec app php artisan schedule:run >> /var/www/project/storage/logs/scheduler.log 2>&1
```