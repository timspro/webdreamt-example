<?php
require_once __DIR__ . '/../bootstrap.php';
use Propel\Runtime\ActiveQuery\Criteria;
use WebDreamt\Component;
use WebDreamt\Component\Wrapper;
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Group;
use WebDreamt\Component\Wrapper\Panel;

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

$formPost = new Form('post', null, "method='POST'");
$formPost->deny()->allow('id');
$formPostComment = new Form('post_comment');
$formComment = new Form('comment');

$formPostComment->link('comment_id', $formComment);
$formPost->addExtraColumn('extra')->link('extra', $formPostComment, 'post_id');
$panelComment = new Panel($formPost);
$panelComment->setTitle('Leave a comment...');

$dataPost = new Data('post', null, 'div', 'post');
$dataPost->setDataClass('post')->hide('users_id')->setDateTimeFormat('n/d/y')
		->reorder([0 => 'title', 1 => 'created_at', 2 => 'html']);
$page->content->addExtraComponent($panelComment);

$dataComment = new Data('comment', new Component('td'), 'tr');
$dataComment->setDataClass('comment')->hide('created_at');
$dataPostComment = new Data('post_comment', null, 'div', 'padding');
$dataPostComment->link('comment_id', new Wrapper(new Wrapper($dataComment, 'table'), 'div', 'comment'));
$dataPostComments = new Group($dataPostComment, 'div', 'comments');
$dataPost->addExtraColumn('comments')->link('comments', $dataPostComments, 'post_id');
$panelPost = new Panel($dataPost);
$panelPost->setTitle(null);

$page->content->setOnNullInput('There are no posts.');
$page->content->setDisplayComponent($panelPost);

echo $page->render($content);
