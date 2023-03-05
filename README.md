<p align="center">
  <img src="https://laravelvuespa.com/preview-dark.png" width="400" />
</p>

# Laravel & Vuexy SPA Starter Kit
[![](https://img.shields.io/badge/vue.js-v2.6-04C690.svg)](https://vuejs.org/)
[![](https://img.shields.io/badge/Laravel-v10.0-ff2e21.svg)](https://laravel.com)
![Test PHP](https://github.com/fumeapp/laranuxt/workflows/Test%20PHP/badge.svg)
[![Lint PHP](https://github.com/fumeapp/laranuxt/actions/workflows/lint-php.yml/badge.svg)](https://github.com/fumeapp/laranuxt/actions/workflows/lint-php.yml)

## Technology
- Laravel 10
- Vue 2.6
- [Vuexy Theme](https://themeforest.net/item/vuexy-vuejs-html-laravel-admin-dashboard-template/23328599)
- Sanctum (session)
- Fortify
- Docker & Docker Compose
- Nginx
- Mysql
- Redis
- i18n
- Vuex

## How it works
TBD

## Installation
### Development Environment
it includes compiling and hot-reloading for development
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up --build
```

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


### Roadmap
* [x] Laravel, Sunctum, and Fortify installations
* [x] Vue & vuex installations
* [x] Setup Vuexy Template
* [x] Login
* [ ] Forget & Reset Password
* [ ] Setup Laravel Permission
* [ ] Users Module
* [ ] Roles Module
* [ ] Update Profile

