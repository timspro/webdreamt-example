<?php
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Data\Form\InputSelect;
use WebDreamt\Component\Wrapper\Group;
use WebDreamt\Component\Wrapper\Panel;

require_once __DIR__ . '/../../bootstrap.php';

if (!Box::get()->sentry()->getUser()) {
	echo 'Who are you?';
	return;
}

$data = Data::getObjectFromUrl();

//Create the tags for the post.
$names = Box::get()->db()->query('SELECT id, name FROM tag')->fetchAll(PDO::FETCH_KEY_PAIR);
$tag = new InputSelect('tag', 'name', $names);
$tag->setLabelable(false);
$postTag = new Form('post_tag');
$postTag->setMultiple(true)->link('tag_id', $tag);
$postTags = new Group($postTag);

//Create the post.
$post = new Form('post', null, 'method="POST"');
$post->setLabels(['html' => 'HTML']);
$post->deny('users_id')->addExtraColumn('tags')->link('tags', $post->getLabelComponent());
$post->link('tags', $postTags, 'post_id');

$panel = new Panel($post);
$panel->setTitle('New Post');

$page = new Template();
$page->content->setDisplayComponent($panel);
echo $page->render($data);
