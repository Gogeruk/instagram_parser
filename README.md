
1.
docker-compose up -d --build

2.
docker exec -it php_container_id sh

3.
cp .env.example .env

4.
composer install

5.
bin/console doctrine:migrations:migrate

6.
bin/console app:parse

bin/console app:parse your_usernames