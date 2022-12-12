## Installation (non-docker ver.)

### Built

Create `.env` file.

Run commands

```bash
npm update --legacy-peer-deps
composer install
php artisan optimize
php artisan storage:link
php artisan migrate:install
php artisan migrate
```

### Run

```shell
php artisan serve
npm run dev
```


## Installation (docker)


### Built

Change port in `docker-compose.yml` if you want or conflict in local.

Create `.env` file.
> `DB_HOST=mysql` base on docker-compose mysql container

```bash
docker compose pull
docker compose build
```

Docker run image to install composer, replace 
* `c:/git/laravel-react-crawler` with project root
  * Can run variable with current folder, but might be different at different os.
* `laravel-react-crawler_app`
  * Default image name can also be different at different os.
  * Check by `docker image ls`

Run commands

```angular2html
docker run --rm -v c:/git/laravel-react-crawler:/application --tty laravel-react-crawler_app composer install
docker run --rm -v c:/git/laravel-react-crawler:/application --tty laravel-react-crawler_app npm install --legacy-peer-deps
docker-compose up -d
docker compose exec app php artisan key:generate
docker compose exec app php artisan optimize
docker compose exec app php artisan migrate:install
docker compose exec app php artisan migrate
```
