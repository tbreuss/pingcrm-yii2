# Ping CRM on Yii 2

A Yii 2 demo application to illustrate how [Inertia.js](https://inertiajs.com) works.

With Inertia you are able to build single-page apps using classic server-side routing and controllers, without building an API. 

This application is a port of the original [Ping CRM written in Laravel](https://github.com/inertiajs/pingcrm) and based on the [Yii 2 Basic Project Template](https://github.com/yiisoft/yii2-app-basic). 

![](screenshot.png)

## Demo

<https://pingcrm-yii2.tebe.ch>

## Installation

Clone the repo locally:

```sh
git clone https://github.com/tbreuss/pingcrm-yii2 pingcrm-yii2
cd pingcrm-yii2
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
npm run css-dev
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php yii migrate
```

Run database seeder:

```sh
php yii db/seed
```

Run the dev server (the output will give the address):

```sh
php yii serve
```

You're ready to go! Visit Ping CRM in your browser, and login with:

- **Username:** johndoe@example.com
- **Password:** secret

## Running tests

To run the Ping CRM tests, run:

```
(to be done)
```

## Requirements

- PHP >= 5.6.0
- Node.js & NPM
- SQLite

## Credits

- Original work by Jonathan Reinink (@reinink) and contributors
- Port to Yii 2 by Thomas Breuss (@tbreuss)
