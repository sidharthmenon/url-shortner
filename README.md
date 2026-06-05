## Development

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

```
sail up
```



## Deployment

build the docker for the project

```
docker build -t my-laravel-app .
```

in the deployment server create the following files

### nginx.conf

```
server {
    listen 80;
    server_name _;
    root /var/www/public;

    index index.php index.html index.htm;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Dockerfile

```
# New Nginx image to copy assets
FROM nginx:latest as nginx
COPY --from=my-laravel-app /var/www/public /var/www/public
COPY nginx.conf /etc/nginx/conf.d/default.conf
```

### docker-compose.yml

```
version: '3.8'

services:
  nginx:
    build:
      context: .
      dockerfile: Dockerfile
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - laravel

  app:
    image: my-laravel-app
    restart: unless-stopped
    networks:
      - laravel
    volumes:
      - .env:/var/www/.env
    depends_on:
      - db
      - redis
      - queue
      - scheduler

  queue:
    image: my-laravel-app  # Use the same Laravel app image
    restart: unless-stopped
    networks:
      - laravel
    depends_on:
      - redis
    command: ["php", "artisan", "queue:work"]  

  scheduler:
    image: my-laravel-app  # Use the same Laravel app image
    restart: unless-stopped
    networks:
      - laravel
    command: ["sh", "-c", "while true; do php artisan schedule:work; sleep 60; done"]  # Run scheduler in a loop

  redis:
    image: redis:alpine
    restart: unless-stopped
    networks:
      - laravel

  db:
    image: postgres:15
    restart: unless-stopped
    networks:
      - laravel
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_USER: ${DB_USERNAME}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    volumes:
      - db-data:/var/lib/postgresql/data

volumes:
  db-data:

networks:
  laravel:
    driver: bridge

```

### .env

add env file as required.
