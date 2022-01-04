<?php

use Apsl\Mvc\Database\Connection;
use Apsl\Mvc\Mailer\SmtpMailer;


$services = [];

$services['mailer'] = new SmtpMailer(require 'config/mail.php');
$services['database'] = new Connection(require 'config/database.php');

return $services;
