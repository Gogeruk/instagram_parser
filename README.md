
WORK IN PROGRESS


1.
docker-compose up -d --build

2.
docker exec -it php_container_id /bin/bash

3.
cp .env.example .env

4.
composer install

5.
npm install --force
(npm run watch&)

6
bin/console doctrine:migrations:migrate

7
bin/console app:parse
bin/console app:parse your_usernames
