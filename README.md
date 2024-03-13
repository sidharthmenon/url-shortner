# URL Shortner
A lightweight and simple url shortner based on Nginx and Laravel

- Nginx is used to serve html pages that redirects to the required urls.
- Laravel is used to generate the html pages required for nginx to serve.
- URL are **not** directly read from database on each request.
- It is as fast as serving static html files from nginx.

## Run
1. Clone repository
2. ```php artisan migrate```
3. ```php artisan db:seed --class=PermissionSeeder```
4. ```php artisan db:seed --class=UserSeeder```

## Nginx config for accessing short URLs

```sh
server {
       listen 80;
       listen [::]:80;

       server_name url.local;

       root /home/<path_to_directory>/storage/app/public/urls;
       index index.html;

       location / {
               try_files $uri $uri/ =404;
       }
}
```
