# aaxis

In CLI run:
```
composer install

yarn install

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load

php bin/console lexik:jwt:generate-keypair

php bin/console php bin/console security:hash-password
```
and introduce a new password from 'admin' user. Then copy the password hash generated and paste in USER_PASSWORD env variable.

Later, in CLI run:
```
yarn run dev

symfony server:start -d
```

Let's get started!
