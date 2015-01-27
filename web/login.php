<?php
require_once __DIR__ . '/../bootstrap.php';
use WebDreamt\Component;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Page;
use WebDreamt\Component\Wrapper\Panel;

$error = '';
if (isset($_POST['1:email']) && isset($_POST['1:password'])) {
	$sentry = Box::get()->sentry();
	try {
		$user = $sentry->findUserByCredentials([
			'email' => $_POST['1:email'],
			'password' => $_POST['1:password']
		]);
	} catch (Exception $e) {
		$error = 'Could not find a user with that email and password.';
	}

	if ($error === '') {
		$sentry->login($user, true);
	}
}

if (Box::get()->sentry()->getUser()) {
	header("Location: index.php");
	die();
}

$form = new Form('users', null, 'method="POST"');
$form->addExtraComponent(new Component('div', null, 'style="margin-bottom:10px"', $error), false);
$form->deny()->allow('email', 'password')->setHtmlType([
	'email' => Form::HTML_TEXT,
	'password' => Form::HTML_PASSWORD
]);

$panel = new Panel($form, null, 'style="margin:50px"');
$panel->setTitle('Login');

$page = new Template();
$page->content->setDisplayComponent($panel);
echo $page->render();
