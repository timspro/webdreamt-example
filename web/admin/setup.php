<?php
require_once __DIR__ . '/../../bootstrap.php';

$sentry = Box::get()->sentry();
$user = $sentry->createUser([
	'email' => 'admin@admin.com',
	'password' => 'password',
		]);
$group = $sentry->createGroup([
	'name' => 'admin'
		]);
$user->attemptActivation($user->getActivationCode());
$user->addGroup($group);
$user->save();

$group = $sentry->createGroup([
	'name' => 'default'
		]);


