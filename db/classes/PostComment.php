<?php
use Base\PostComment as BasePostComment;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'post_comment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PostComment extends BasePostComment {

	function preInsert(ConnectionInterface $con = null) {
		if (!Box::get()->sentry()->getUser()) {
			$time = $this->getPost()->getCreatedAt()->getTimestamp();
			if ((time() - $time) / 3600 > 24) {
				return false;
			}
		}

		return parent::preInsert($con);
	}

}
