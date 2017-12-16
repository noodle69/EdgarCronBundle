# EdgarCronBundle

Cron Bundle scheduler 

[![Downloads](https://img.shields.io/packagist/dt/edgar/cron-bundle.svg?style=flat-square)](https://packagist.org/packages/edgar/cron-bundle)
[![Latest release](https://img.shields.io/github/release/noodle69/EdgarCronBundle.svg?style=flat-square)](https://github.com/noodle69/EdgarCronBundle/releases)
[![License](https://img.shields.io/packagist/l/edgar/cron-bundle.svg?style=flat-square)](LICENSE)

## Description

This bundle offer a command that you should use as a cronjob :

```cmd
* * * * * cd <your_project_root> && php bin/console edgar:crons:run
```

This command will list all commands extending "AbstractCron" class and defined as service tagged with "edgar.cron".

You can define specific cron expression for each command as cron and prioritize them.

## Documentation

[Installation](docs/INSTALL.md)
[Usage](docs/USAGE.md)
