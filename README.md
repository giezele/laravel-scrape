# Requirements
- Stable version of [Docker](https://docs.docker.com/engine/install/)
- Compatible version of [Docker Compose](https://docs.docker.com/compose/install/#install-compose)

# How To Launch

### For first time only !
- `git clone https://github.com/giezele/laravel-scrape.git`
- `cd laravel-scrape`
- `docker compose up -d --build`
- `docker compose exec phpmyadmin chmod 777 /sessions`
- `docker compose exec php bash`
- `chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache`
- `chmod -R 775 /var/www/storage /var/www/bootstrap/cache`
- `composer setup`
- fill in DB credentials in .env
- `php artisan migrate`
- `php artisan queue:work`

### From the second time onwards
- `docker compose up -d`
- `docker compose exec php bash`
- `php artisan queue:work`

***
### Testing with Postman
##### POST /api/jobs:

URL: `http://localhost/api/jobs`  
Method: `POST`  
Body (JSON):
```
{
"urls": ["http://example.com", "http://example.org"],
"selectors": ".example-class"
}
```
##### GET /api/jobs/{id}:

URL: `http://localhost/api/jobs/{id}`  
Method: `GET`  
##### DELETE /api/jobs/{id}:

URL: `http://localhost/api/jobs/{id}`  
Method: `DELETE`
