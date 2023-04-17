
# Product Management

A small application using Laravel 10, Vuejs 3 + Vue-Router 4 + Vuex 4, and Ant Design stack in managing products based on user specifications. Products are under product categories and are restricted to user ange range. There are categories of products that are restricted to age 10 - 30.

## Project

Clone repository from this link: `https://github.com/bmaraon/product-management`.


## Configurations

To run this project, please refer to this environment variables.
#### .env
```
APP_NAME=Laravel
APP_ENV="Product Management"
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:9001
VITE_BASE_URL=http://localhost:9001

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_management
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```
#### vite.config.js
```
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```


## Installation
- Open Git Bash or any terminal
- Access SQL server then create database
```
mysql -uroot -p
create database product_management charset=utf8mb4;
```
- Go to project root directory
- Install dependencies
```bash
composer install
...

npm install
...
```
- Generate app key
```
php artisan key:generate
```
- Migration and seeder (remove seeder if not necessary)
```
php artisan migrate --seed
```


    
## Running the application

Backend
```bash
php atisan serve --port=9001
```

Frontend
```bash
npm run dev
```
or
```bash
npm run production
```
## Default credentials
Regular user
```bash
email: admin@user.com
password: password
```
Restricted user
```bash
email: restricted@user.com
password: password
```
