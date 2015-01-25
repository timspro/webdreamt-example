<?php
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Panel;

require_once __DIR__ . '/../bootstrap.php';

if (!Box::get()->sentry()->getUser()) {
	echo 'Who are you?';
	return;
}

if (count($_POST) !== 0) {
	Box::get()->server()->batch($_POST);
}

$tags = new Form('post_tag');
$tags->setMultiple(true)->link('tag_id', new Form('tag'));

$form = new Form('post', null, 'method="POST"');
$form->deny('users_id')->addExtraColumn('tags')->link('tags', $form->getLabelComponent());
$form->link('tags', $tags, 'post_id');

$page = new Template();
$panel = new Panel($form);
$page->content->setDisplayComponent($panel);
echo $page->render();
