<?php
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Data\Form\InputSelect;
use WebDreamt\Component\Wrapper\Panel;

require_once __DIR__ . '/../bootstrap.php';

if (!Box::get()->sentry()->getUser()) {
	echo 'Who are you?';
	return;
}

$names = Box::get()->db()->query('SELECT id, name FROM tag')->fetchAll(PDO::FETCH_KEY_PAIR);
$formTag = new InputSelect('tag', 'name', $names);
$formTag->setLabelable(false);

$formPostTag = new Form('post_tag');
$formPostTag->setMultiple(true)->link('tag_id', $formTag);

$formPost = new Form('post', null, 'method="POST"');
$formPost->setLabels(['html' => 'HTML']);
$formPost->deny('users_id')->addExtraColumn('tags')->link('tags', $formPost->getLabelComponent());
$formPost->link('tags', $formPostTag, 'post_id');

$page = new Template();
$formPanel = new Panel($formPost);
$formPanel->setTitle('New Post');
$page->content->setDisplayComponent($formPanel);
echo $page->render();
