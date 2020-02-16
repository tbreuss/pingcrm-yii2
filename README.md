# Ping CRM on Yii2

A Yii2 demo application to illustrate how [Inertia.js](https://inertiajs.com) works. This is a port of the original [Ping CRM written in Laravel](https://github.com/inertiajs/pingcrm). 

![](screenshot.png)

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
php yii db:seed
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

## Credits

- Original work by Jonathan Reinink (@reinink) and contributors
- Port to Yii2 by Thomas Breuss (@tbreuss)
