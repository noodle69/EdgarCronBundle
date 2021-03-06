# EdgarCronBundle

## Extend

This bundle offers possibility to transform Command into Cron or simply create Cron like a Command

### Command class

```php
namespace ...;

use Edgar\Cron\Cron\AbstractCron;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends AbstractCron
{
    protected function configure()
    {
        $this
            ->setName('your:cron:foo')
            ->addArgument('foo', InputArgument::REQUIRED, 'foo argument')
            ->setDescription('Your cron foo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('start foo');
        $output->writeln('foo argument : ' . $this->getArgument($input, 'foo'));
        $output->writeln('end foo');
    }
}
```

* AbstractCron: replace "Command" or "ContainerWareCommand" with this abstract class
* getArgument: replace "$input->getArgument(...)" with "$this->getArgument(...)"

You can override "CronAbstract" properties to define customer cron expression by default "* * * * *" :
* minute
* hour
* dayOfMonth
* month
* dayOfWeek

### Declare your command (cron) as a service

```yaml
# Resources/config/services.yml
parameters:
    your.cron.foo.class: Your\Bundle\Command\FooCommand
    
services:
    your.cron.foo:
        class: %your.cron.foo.class%
        tags:
            - { name: edgar.cron, alias: foo, expression: "* * * * *", priority: 200, arguments: "foo:fooarg" }
```

* Your command (cron) is tagged with "edgar.cron".
* You should define an alias
* priority is not mandatory
* expression is not mandatory
* expression format is : m h d dm dw
* arguments is not mandatory
* arguments format is : arg1:value1 arg2:value2 ...

