<p align="center">
  <img src="https://laravelvuespa.com/preview-dark.png" width="400" />
</p>

# Laravel & Vuexy SPA Starter Kit
[![](https://img.shields.io/badge/vue.js-v2.6-04C690.svg)](https://vuejs.org/)
[![](https://img.shields.io/badge/Laravel-v10.0-ff2e21.svg)](https://laravel.com)
![Test PHP](https://github.com/fumeapp/laranuxt/workflows/Test%20PHP/badge.svg)
[![Lint PHP](https://github.com/fumeapp/laranuxt/actions/workflows/lint-php.yml/badge.svg)](https://github.com/fumeapp/laranuxt/actions/workflows/lint-php.yml)

## Technology
- PHP-FPM 8.1
- Laravel 10
- Vue, Vuex, i18n
- [Vuexy Theme](https://themeforest.net/item/vuexy-vuejs-html-laravel-admin-dashboard-template/23328599)
- Sanctum for Authentication (session)
- Fortify
- Docker & Docker Compose
- Nginx
- Mysql
- Redis
- Mailpit (as a test mail driver)
- Redis Queues
- Task Scheduling

## How it works
### Containers
1) **api**: serves the backend app (laravel app)
2) **client**: serves the fronted app (vue app)
3) **webserver**: services static content, storage, and passes traffic to api & client containers (proxy)
4) **mysql**: main database connection
5) **redis**: cache driver / queue connection
6) **mailpit**: SMTP server with a web interface to view all mails (just for dev env)
7) **worker**: runs queue workers & crontab

## Installation
### Development Environment
it includes compiling and hot-reloading for development
```
cp api/.env.dev.example api/.env.dev

// then =>

docker-compose -f docker-compose.yml -f docker-compose.dev.yml up --build

// then =>

// run the migrations
docker exec -it spa-dev-api-1 php artisan migrate --seed
```
- To access the api open http://localhost:8000
- To access the client open http://localhost:3000
- To access the Mailpit open http://localhost:8025

### Staging Environment
Compiles and minifies for staging
```
docker-compose -f docker-compose.yml -f docker-compose.stg.yml up --build
```

### Production Environment
Compiles and minifies for production
```
docker-compose -f docker-compose.yml -f docker-compose.prd.yml up --build
```

## Customize configuration
#### 1) Vue Env [Configuration Reference](https://cli.vuejs.org/config/).


## Roadmap
* [x] Laravel, Sunctum, and Fortify installations
* [x] Vue & vuex installations
* [x] Setup Vuexy Template
* [x] Login
* [x] Forget & Reset Password
* [x] Update Profile Info
* [x] Update Password
* [x] Setup Laravel Permission
* [x] Roles Module
* [x] Users Module
* [x] Setup Scheduler
* [x] Setup Queue/Workers
* [ ] Setup Horizon
* [ ] Settings Module
* [ ] Real Time Notifications Module
* [ ] Real Time Chat Module

## Contributing
Contributions are **welcome** and will be fully **credited**.
