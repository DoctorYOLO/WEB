<?php

include("handler.class.php");

const LOGIN         =   "drYOLO";
const PASSWORD      =   "testpassword";

$text   =   "test message";

error_reporting( E_ERROR );

$manager = new handler(LOGIN, PASSWORD);
$firstNewsPage = $manager->getFirstNewsPage();
$manager->sendMessage($firstNewsPage, $text);