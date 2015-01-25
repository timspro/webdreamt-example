<?php
require_once __DIR__ . '/../bootstrap.php';

Box::get()->sentry()->logout();

header("Location: index.php");
die();
