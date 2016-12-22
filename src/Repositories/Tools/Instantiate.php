<?php

namespace Arch\Repositories\Tools;

trait Instantiate {

	private static $instance = false;
	// get instance of class
	// who call this function directly
	public static function getInstance () {

		if ( !self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}