## Инструкции по разворачиванию проекта

1. Склонировать проект `git clone https://github.com/baldrys/user-events-task.git`
2. Установить зависимости проекта. Перейти в папку приложения и выполнить команду `composer update`
3. Скопировать файл `.env.example` и переименовать в `.env`
4. В `.env` прописать парамерты соединения с базой данных

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

5. Для отправки email установить параметр адресса отправки почты `MAIL_FROM_ADDRESS`
6. Выполнить команду генерации ключа приложения `php artisan key:generate`
7. Выполнить `php artisan migrate --seed`
8. Запустить локальный сервер (можно командой `php artisan serve`)
9. Запустить очередь заданий `php artisan queue:work`

## Доступные эндпоинты

```
GET    /events/{event}/participants               - получить всех участников
POST   /events/{event}/participants               - Добавить участников
PATCH  /events/{event}/participants/{participant} - Изменить участника
DELETE /events/{event}/participants/{participant} - Удалить участника
```

Все эндпоинты доступны только авторизованным пользователям. Авторизация происходит через `api_token` котороый необходимо передовать при запросе. Получить его можно в таблице `users` в поле `api_token` после запуска `php artisan migrate --seed`.
