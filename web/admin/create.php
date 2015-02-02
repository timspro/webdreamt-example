<?php
use WebDreamt\Component\Icon;
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Data\Form\InputSelect;
use WebDreamt\Component\Wrapper\Group;
use WebDreamt\Component\Wrapper\Panel;
use WebDreamt\Store;

require_once __DIR__ . '/../../bootstrap.php';

if (!Box::get()->sentry()->getUser()) {
	echo 'Who are you?';
	return;
}

$data = Data::getObjectFromUrl();
if (!($data instanceof Post)) {
	$data = null;
}

$store = new Store();

$store->set('edit_post_tags', function() use ($store) {
	$tag = new Form('tag');
	$tag->denyLabels();
	$postTag = new Form('post_tag');
	$postTag->link('tag_id', $tag)->addIcon(new Icon(Icon::TYPE_DELETE), '', true);
	$postTags = new Group($postTag);
	return $postTags;
});

$store->set('create_post_tag', function() use ($store) {
	$names = Box::get()->db()->query('SELECT id, name FROM tag')->fetchAll(PDO::FETCH_KEY_PAIR);
	$tag = new InputSelect('tag', 'name', $names);
	$postTag = new Form('post_tag');
	$postTag->link('tag_id', $tag)->multiple();
	return $postTag;
});

//Create the post.
$post = new Form('post');
$post->setLabels(['html' => 'Content'])->setHtmlClass(['html' => 'ckeditor']);
$post->deny('users_id')->addExtraColumn('tags')->link('tags', $store->get('edit_post_tags'), 'post_id');
$post->addExtraColumn('add_tag')->link('add_tag', $store->get('create_post_tag'), 'post_id')
		->denyLabels('add_tag');

$panel = new Panel($post);
$panel->setTitle('New Post');

$page = new Template();
$page->content->setDisplayComponent($panel);
echo $page->render($data);
