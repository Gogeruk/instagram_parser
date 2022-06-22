1.
docker-compose up -d --build

2.
docker exec -it <php container> sh

3.
composer install

4.
cp .env.example .env

5.
bin/console doctrine:migrations:migrate

6.
bin/console app:parse
