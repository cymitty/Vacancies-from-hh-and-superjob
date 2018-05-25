<?php
/**
 * Created by PhpStorm.
 * User: amitty
 * Date: 5/22/2018
 * Time: 4:03 PM
 */
namespace app\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use app\Classes\MyHHApi;
use app\Classes\Helper;

class ParseVacanciesCommand extends Command
{
  public $config;

  public function __construct($config, ?string $name = null)
  {
    parent::__construct($name);
    $this->config = $config;
  }

  protected function configure()
  {
    $this
        // имя команды (часть после "bin/console")
        ->setName('vacancies')

        // краткое описание, отображающееся при запуске "php bin/console list"
        ->setDescription('Parse vacancies from hh.ru, superjob.ru')

        // полное описание команды, отображающееся при запуске команды
        // с опцией "--help"
        ->setHelp('This command allows you to creates file with relevant vacancies...')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $vacancies = array();
    $hhAPI = new MyHHApi($this->config);
    $hhAPI->run();
    $vacancies = $hhAPI->getVacancies();

    $superJobAPI = new \SuperjobAPI();
    $superJobAPI->setSecretKey('v1.r079a76ff5c97cf7be4a70fa142569ab6302f57724eaf8d1226af851f3899905925966148.fbbb6bf6988a374e666e1bd4b6cb9508b80183e2');
    $vacanciesSuperJob = $superJobAPI->customGetVacancies([
        "town"        => 782,
        "catalogues"  => 33,
        "period"      => 1,
        "page"        => 0,
        "count"       => 10,

    ]);

    $vacanciesTotal = count($vacancies) + count($vacanciesSuperJob);

    if ($vacancies)
    {
      $file = Helper::generateFile($vacancies, $vacanciesSuperJob);
      $output->writeln('Файл был создан, на сегодня найдено ' . $vacanciesTotal . ' вакансий');
      $output->writeln('Открываю созданный файл...');
      exec(realpath($file));// Открыть созданный файл
    }

  }


}