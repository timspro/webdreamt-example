<?php
use Base\PostTag as BasePostTag;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'post_tag' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PostTag extends BasePostTag {

	function preInsert(ConnectionInterface $con = null) {
		$tag = $this->getTag();
		if ($tag === null || $tag->getName() === '') {
			return false;
		}
		return parent::preInsert($con);
	}

}
