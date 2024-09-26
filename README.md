## Запуск проекта
1. Запустите  и  создайте  контейнеры  с  помощью  docker-compose:
   
    ``````
    sudo docker compose up --build

2. Создайте базу данных:
 
    ``````
   sudo docker exec -it mysql2 mysql -u root -p"root" -e "CREATE DATABASE tages;"

*  mysql2  -  имя  контейнера  MySQL.
    *  root  -  имя  пользователя.
    *  root  -  пароль  пользователя  root.
    *  tages  -  имя  базы  данных.

3. Импортируйте  данные  в  базу  данных:

    ``````
   sudo docker exec mysql2 mysql -u root -p"root" tages < mysql-init/db_backup.sql

*  mysql2  -  имя  контейнера  MySQL.
    *  root  -  имя  пользователя.
    *  root  -  пароль  пользователя  root.
    *  tages  -  имя  базы  данных.
    *  mysql-init/db_backup.sql  -  путь  к  файлу  с  данными  для  импорта.

4. Перейти на:
   
   ``````
   http://localhost:80