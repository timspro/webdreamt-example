<?php
require_once __DIR__ . '/../vendor/autoload.php';

umask(0);

CustomBox::get()->script()->manager();
