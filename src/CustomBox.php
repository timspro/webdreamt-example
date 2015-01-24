<?php
use WebDreamt\Box;

class CustomBox extends Box {

	function __construct() {
		parent::__construct();
		$this->DatabaseUsername = 'root';
		$this->DatabasePassword = '';
		$this->DatabaseName = 'blog';
		$this->DatabaseHost = 'localhost';
	}

	function header($css = true, $title = '', $custom = null) {
		parent::header($css, $title, $custom);
	}

}
