<?php
/**
 * Created by PhpStorm.
 * User: amitty
 * Date: 5/23/2018
 * Time: 5:10 PM
 */

namespace app\Classes;


class MyHHApi
{
  public $config;
  public $vacancies;

  public function __construct($config)
  {
    $this->config = $config;
  }

  public function getVacancies()
  {
    return $this->vacancies;
  }

  public function run()
  {
    $url = $this->generateUrl();
    $curlResponse = Helper::curlRun($url);
    if ($curlResponse['items'])// Если curl отработал без ошибок и получил вакансии
    {
      foreach ($curlResponse['items'] as $item) // Вытаскиваем все найденные вакансии и отдаём
      {
        $this->vacancies[] = [
            'name'            => $item['name'],
            'requirement'     => $item['snippet']['requirement'],
            'responsibility'  => $item['snippet']['responsibility'],
            'url'             => $item['alternate_url']
        ];
      }
      return true;
    }
    // иначе вакансий нет
    $this->vacancies = false;
  }
  /*
   * Строим запрос на основе конфига
   */
  public function generateUrl()
  {
    $url = 'https://api.hh.ru/vacancies?' . http_build_query([
            'area'            => $this->config['hh-area'],
            'specialization'  => $this->config['hh-specialization'],
            'period'          => $this->config['hh-period'],
            'per_page'        => 100
        ]);
    return $url;
  }


}