<?php
use Base\Comment as BaseComment;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'comment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Comment extends BaseComment {

	function preInsert(ConnectionInterface $con = null) {
		$this->setName(htmlspecialchars($this->getName()));
		$this->setComment(htmlspecialchars($this->getComment()));
		return parent::preInsert($con);
	}

}
