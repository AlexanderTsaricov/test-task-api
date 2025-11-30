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

## БД доступы:
```
DB_CONNECTION=mysql
DB_HOST=141.8.192.182
DB_PORT=3306
DB_DATABASE=f1198795_test
DB_USERNAME=f1198795_test
DB_PASSWORD="f1198795_test"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_DRIVER=file
```