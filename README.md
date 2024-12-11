## Запуск:

* make init
* cd api
* composer cli doctrine:migrations:migrate
* composer cli doctrine:fixtures:load --purge-with-truncate
* http://avido.app.localhost:8090/app здесь планируется фронт React (сейчас заглушка)
* http://avido.app.localhost:8090/api здесь документация api