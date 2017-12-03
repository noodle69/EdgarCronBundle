# EdgarCronBundle

## Installation

### Get the bundle using composer

Add EdgarCronBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/cron-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Edgar\CronBundle\EdgarCronBundle(),
        // ...
    );
}
```

## Add doctrine ORM support

in yout ezplatform.yml, add

```yaml
doctrine:
    orm:
        auto_mapping: true
```

## Update your SQL schema

```
php bin/console doctrine:schema:update --force
```

## Define global cron job

in your crontab, add

```cmd
* * * * * cd <your_project_root> && php bin/console edgar:crons:run
```

where <your_project_root> is your Symfony root.
