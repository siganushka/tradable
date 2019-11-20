
# How to install ?

### clone && configuration

```
$ git clone https://github.com/siganushka/tradable.git
$ cd ./tradable
$ cp .env .env.local

```

> configuration ``.env.local``

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

### install dependencies

```
$ composer install
```

### create database && table && fixtures

```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
$ php bin/console doctrine:fixtures:load
```

### install && compress front-end dependencies

```
$ yarn install
$ yarn encore production
```

### unit test

```
$ php bin/phpunit --debug
```
