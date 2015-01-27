<?php
require_once __DIR__ . '/../../bootstrap.php';
use WebDreamt\Component;

$page = new Template();
$page->content->setDisplayComponent(new Component('iframe', 'iframe-fill', 'src="script/authorization.php"'
		. ' frameBorder="0"'));
$page->content->appendHtml('style="padding:0"');
echo $page->render();
