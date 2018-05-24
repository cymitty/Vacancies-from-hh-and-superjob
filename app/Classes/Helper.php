<?php
/**
 * Created by PhpStorm.
 * User: amitty
 * Date: 5/24/2018
 * Time: 6:40 PM
 */

namespace app\Classes;


class Helper
{
  public static function generateFile($vacancies)
  {
    $data  = '';// data html файла
    $data .= "<head><meta charset=\"utf-8\"><style>table {    font-family: arial, sans-serif;    border-collapse: collapse;    width: 100%;}td, th {    border: 1px solid #dddddd;    text-align: left;padding: 8px;}tr:nth-child(even) {background-color: #dddddd;}</style></head>";
    $data .= "<table>" . PHP_EOL;
    foreach ($vacancies as $vacancy)
    {
      $data .= "<tr>" . PHP_EOL;
      $data .= "<td>" . $vacancy['name'] . "</td>" . PHP_EOL;
      $data .= "<td>" . $vacancy['requirement'] . "</td>" . PHP_EOL;
      $data .= "<td>" . $vacancy['responsibility'] . "</td>" . PHP_EOL;
      $data .= "<td><a href='" . $vacancy['url'] ."'>" . $vacancy['url'] . "</a></td>" . PHP_EOL;
      $data .= "</tr>" . PHP_EOL;
    }
    $data .= "</table>" . PHP_EOL;
    // Сохраняем файл
    $file = 'ResultFiles/Vacancies' . date("Y-m-d_H-i-s") . '.html';
    if (file_put_contents($file, $data))
    {
      return true;
    }
    return false;
  }
}