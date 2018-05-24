<?php
/**
 * Created by PhpStorm.
 * User: amitty
 * Date: 5/22/2018
 * Time: 3:36 PM
 */
require '../vendor/autoload.php';
require 'Command/ParseVacanciesCommand.php';
require 'Classes/Helper.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use app\Command\ParseVacanciesCommand;

$config =  parse_ini_file('config.ini');


$application = new Application();
$command = new ParseVacanciesCommand($config);
$application->add($command);

$application->setDefaultCommand($command->getName(), true);

//$application->setDefaultCommand($command, true);
$application->run();
