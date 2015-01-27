<?php
require_once __DIR__ . '/../../../bootstrap.php';

if (!Box::get()->sentry()->getUser()) {
	echo 'Who are you?';
	return;
}

umask(0);

Box::get()->script()->manager();
