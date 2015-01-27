<?php

class Box extends \WebDreamt\Box {

	function __construct() {
		parent::__construct();
		$this->DatabaseUsername = 'root';
		$this->DatabasePassword = '';
		$this->DatabaseName = 'webdreamt_blog';
		$this->DatabaseHost = 'localhost';
		$this->enable();
	}

	function javascript() {
		$js = '<script src="' . $this->root() . '/dist/ckeditor/ckeditor.js"></script>' .
				'<script src="' . $this->root() . '/dist/client/build.js"></script>';
		return $js;
	}

	function css() {
		return '<link href="' . $this->root() . '/dist/client/build.min.css" rel="stylesheet">';
	}

}
