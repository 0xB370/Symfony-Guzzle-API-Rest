# Symfony-API-Rest

API Rest made using Symfony 5 and PostgreSQL, tested using Guzzle.

To run the app locally, ensure that your system has:

- Symfony 5

- PostgreSQL

- Required drivers (such as pdo_pgsql - for linux install run _apt-get install php-pgsql_ - )

## Preparing the environment

- After clone or download, located in the project's directory run _composer install_.

- Set the database credentials as an environment variable named DATABASE_URL.

_E.g. command for linux:_

		export DATABASE_URL='postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=12&charset=utf8' 

**IMPORTANT!** In linux the export command doesn't export the variable to another terminal session. So if you export it in a terminal, you use it on the same terminal.

- Start PostgreSQL service.

- Run: 

		php bin/console doctrine:database:create

		bin/console make:migration

		bin/console doctrine:migrations:migrate

		symfony server:start

- Open your browser at _localhost:8000_ to verify that there's no errors.

- Hit the endpoints declared in TaxonomiaController.php and ProductoController.php.
