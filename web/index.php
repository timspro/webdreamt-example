<?php
require_once __DIR__ . '/../bootstrap.php';
use Propel\Runtime\ActiveQuery\Criteria;
use WebDreamt\Component\Custom;
use WebDreamt\Component\Icon;
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Group;
use WebDreamt\Component\Wrapper\Modal;
use WebDreamt\Component\Wrapper\Panel;
use WebDreamt\Store;

$root = Box::get()->root();

//So, this is a fairly straightforward layout. There is just a side bar on the left
//and the page with content.
$page = new Template();

//Get the data.
if (isset($_GET['title'])) {
	$title = str_replace('-', ' ', $_GET['title']);
	$postQuery = PostQuery::create()->filterByTitle($title);
} else {
	$postQuery = PostQuery::create()->orderByCreatedAt(Criteria::DESC);
}

$data = $postQuery->leftJoin('Post.PostComment')->with('PostComment')
				->leftJoin('PostComment.Comment')->with('Comment')
				->orderBy('Comment.CreatedAt', Criteria::DESC)->find();
if (count($data) !== 0) {
	$data = $data[0];
} else {
	$data = null;
}

$store = new Store();
$store->set('data', function() use ($data) {
	return $data;
});

$store->set('form', function () use ($store) {
	$custom = new Custom(function($input) use ($store) {
		if (!Box::get()->sentry()->getUser()) {
			$time = $store->get('data')->getCreatedAt()->getTimestamp();
			if ((time() - $time) / 3600 > 24 * 21) {
				return '';
			}
		}
		$post = new Form('post');
		$post->deny()->allow('id');
		$post->addExtraColumn('extra')->link('extra', $store->get('form_post_comment'), 'post_id')
				->denyLabels('extra');
		$panel = new Panel($post);
		$panel->setTitle('Leave a comment...');

		return $panel->render($input);
	});

	return $custom;
});

$store->set('form_post_comment', function() {
	$comment = new Form('comment');
	$comment->setDefaultValues(['name' => 'Anonymous']);

	$postComment = new Form('post_comment');
	$postComment->link('comment_id', $comment);
	return $postComment;
});

$store->set('post', function() use ($store, $root) {
	$post = new Data('post', null, 'div', 'post');
	$post->setDataClass('post')->hide('users_id')->setDateTimeFormat('n/d/y')
			->reorder(['title', 'created_at', 'html']);
	$post->addExtraColumn('comments')->link('comments', $store->get('post_comments'), 'post_id');

	$icon = new Icon(Icon::TYPE_EDIT);
	$icon->setGroups('admin');
	$post->addIcon($icon, "$root/admin/create.php");

	$icon = new Icon(Icon::TYPE_DELETE);
	$icon->setGroups('admin');
	$post->addIcon($icon, '');

	$panel = new Panel($post);
	$panel->setTitle(null);
	return $panel;
});

$store->set('post_comments', function() use ($root) {
	$comment = new Data('comment', null, 'div', 'comment');
	$comment->setDataClass('comment')->hide()->show('comment');
	$comment->addExtraComponent(new Custom(function ($input) {
		$name = $input->getName();
		$date = $input->getCreatedAt();
		if ($date !== null) {
			$date = $date->format(Data::$DefaultDateTimeFormat);
		}
		return " - $name, $date";
	}, true, 'div', 'comment-author'));

	$icon = new Icon(Icon::TYPE_EDIT);
	$icon->setGroups('admin');
	$comment->addIcon($icon, "$root/modal.php", true);

	$icon = new Icon(Icon::TYPE_DELETE);
	$icon->setGroups('admin');
	$comment->addIcon($icon, '');

	$postComment = new Data('post_comment');
	$postComment->link('comment_id', $comment);
	$postComments = new Group($postComment, 'div', 'comments');
	return $postComments;
});

$page->content->setOnNullInput('There is no post with that title.');
$page->content->setDisplayComponent($store->get('post'));
$page->content->addExtraComponent($store->get('form'));

echo $page->render($data);
