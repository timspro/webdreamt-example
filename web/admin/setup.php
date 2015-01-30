<?php
use WebDreamt\Server;

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

Box::get()->server()->allow('default', 'comment', Server::ACT_CREATE, ['id', 'comment', 'name']);
Box::get()->server()->allow('default', 'post_comment', Server::ACT_CREATE);
$all = ['comment', 'post_comment', 'post', 'post_tag', 'tag'];
Box::get()->server()->allow('admin', $all, [Server::ACT_CREATE, Server::ACT_UPDATE, Server::ACT_DELETE]);
