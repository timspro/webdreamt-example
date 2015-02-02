<?php
require_once __DIR__ . '/../bootstrap.php';
use WebDreamt\Component\Wrapper\Data;
use WebDreamt\Component\Wrapper\Data\Form;
use WebDreamt\Component\Wrapper\Modal;

if (isset($_GET['action']) && $_GET['action'] === 'update') {
	$editable = Data::getObjectFromUrl();
} else {
	$editable = null;
}

$modals = [
	'comment' => Comment::class,
	'tag' => Tag::class
];

foreach ($modals as $table => $class) {
	$modal = new Modal(new Form($table));
	$modal->setCssClassCallback(function($input) use ($class) {
		return $input instanceof $class ? 'wd-modal-show' : '';
	});
	$modal->setGroups('admin');
	if ($editable instanceof $class) {
		echo $modal->render($editable);
		exit();
	}
}

