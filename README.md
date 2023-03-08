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

## How it works
TBD

## Installation
### Development Environment
it includes compiling and hot-reloading for development
```
cp api/.env.dev.example api/.env.dev

// then =>

docker-compose -f docker-compose.yml -f docker-compose.dev.yml up --build
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
* [ ] Roles Module
* [ ] Users Module
* [ ] Setup Scheduler
* [ ] Setup Queue/Workers
* [ ] Setup Horizon
* [ ] Social Login
* [ ] Settings Module
* [ ] Realtime Notifications Module
* [ ] Realtime Chat Module

## Contributing
Contributions are **welcome** and will be fully **credited**.
