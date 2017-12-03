# EdgarCronBundle

Cron Bundle scheduler 

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1b9063ff-aa66-4fd6-b1fc-08fbec3797a0/mini.png)](https://insight.sensiolabs.com/projects/1b9063ff-aa66-4fd6-b1fc-08fbec3797a0)
[![Downloads](https://img.shields.io/packagist/dt/edgar/ez-cron-bundle.svg?style=flat-square)](https://packagist.org/packages/edgar/ez-cron-bundle)
[![Latest release](https://img.shields.io/github/release/noodle69/EdgarCronBundle.svg?style=flat-square)](https://github.com/noodle69/EdgarCronBundle/releases)
[![License](https://img.shields.io/packagist/l/edgar/ez-cron-bundle.svg?style=flat-square)](LICENSE)

## Description

This bundle offer a command that you should use as a cronjob :

```cmd
* * * * * cd <your_project_root> && php app/console smile:crons:run
```

This command will list all commands extending "CronAbstract" class and defined as service tagged with "smile.cron".

You can define specific cron expression for each command as cron and prioritize them.

## Documentation

[Installation](docs/INSTALL.md)
[Usage](docs/USAGE.md)
