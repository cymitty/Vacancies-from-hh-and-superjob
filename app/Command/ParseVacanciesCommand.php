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
    //Строим запрос на основе конфига
    $url = 'https://api.hh.ru/vacancies?' . http_build_query([
        'area'            => $this->config['hh-area'],
        'specialization'  => $this->config['hh-specialization'],
        'period'          => $this->config['hh-period'],
        'per_page'        => 100
        ]);
    $curl = curl_init($url);
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => false,// для работы с https
        CURLOPT_USERAGENT => "MyCustomSearch",// обязателен любой user-agent для api.hh.ru
        CURLOPT_RETURNTRANSFER => true// it will return the result on success, false on failure.
    ));
    $curlResponse = json_decode(curl_exec($curl), true);
    curl_close($curl);
    $vacancies = [];
    foreach ($curlResponse['items'] as $item)
    {
      $vacancies[] = [
          'name'            => $item['name'],
          'requirement'     => $item['snippet']['requirement'],
          'responsibility'  => $item['snippet']['responsibility'],
          'url'             => $item['alternate_url']
      ];
    }

    if (Helper::generateFile($vacancies))
    {
      $output->writeln('Файл был создан, найдено ' . count($vacancies) . ' вакансий');
    }


//    $outputLog = [];
//    $outputText = "";
//    exec('ping ya.ru', $outputLog);
//    foreach ($outputLog as $line)
//    {
//      $outputLog .= $line;
//      $outputText .= $line . PHP_EOL;
//    }
//
//    if ( $this->createFileWithResult($outputText) )
//    {
//      $output->writeln('file was created.');
//      $output->writeln($outputLog);
//    }


  }


}