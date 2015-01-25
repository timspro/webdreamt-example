<?php
require_once __DIR__ . '/../bootstrap.php';
use Propel\Runtime\ActiveQuery\Criteria;
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;

//So, this is a fairly straightforward layout. There is just a side bar on the left
//and the page with content.
$page = new Template();

$content = null;
if (isset($_GET['title'])) {
	$title = str_replace('-', ' ', $_GET['title']);
	$posts = PostQuery::create()->filterByTitle($title)->find();
	if (count($posts) !== 0) {
		$content = $posts[0];
	}
}

if (!$content) {
	$posts = PostQuery::create()->orderByCreatedAt(Criteria::DESC)->find();
	if (count($posts) !== 0) {
		$content = $posts[0];
	}
}

$formPost = new Form('post');
$formPost->deny()->allow('id');
$formPostComment = new Form('post_comment');
$formComment = new Form('comment');

$formPostComment->link('comment_id', $formComment);
$formPost->addExtraColumn('extra')->link('extra', $formPostComment, 'post_id');

$dataPost = new Data('post');
$dataPost->setDataClass('post')->hide('users_id')->setDateTimeFormat('n/d/y')
		->reorder([0 => 'title', 1 => 'created_at', 2 => 'html']);
$dataPost->addExtraComponent($formPost);

$dataComment = new Data('comment');
$dataPostComment = new Data('post_comment');
$dataPostComment->link('comment_id', $dataComment);
$dataPost->addExtraColumn('comments')->link('comments', new Group($dataPostComment), 'post_id');

$page->content->setOnNullInput('There are no posts.');
$page->content->setDisplayComponent($dataPost);

echo $page->render($content);
