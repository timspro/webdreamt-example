<?php
use Base\Post as BasePost;
use Propel\Runtime\Connection\ConnectionInterface;

class Post extends BasePost {

	function preSave(ConnectionInterface $con = null) {
		$this->setUsersId(Box::get()->sentry()->getUser()->getId());
		return parent::preSave($con);
	}

}
