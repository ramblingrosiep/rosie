<?php
/**
 *
 * Class Loader
 * @author noto
 *
 */

class ClassLoader {
	/**
	 *
	 * variable for storing directories in array
	 * @var unknown_type
	 */

	private $dirs = array();


	/**
	 *
	 * Constructor
	 */
	public function __construct($classDir) {
		spl_autoload_register(array($this, 'loader'));
		$this->registerDir($classDir);
	}


	/**
	 *
	 * Register directories
	 * @param string $dir
	 */
	public function registerDir($dir){
		if( is_array($dir) ) {
			array_splice( $this->dirs, count($this->dirs), 0, $dir );
		} else {
			$this->dirs[] = $dir;
		}
	}


	/**
	 *
	 * Callback
	 * @param string $classname
	 */
	public function loader($classname){

		foreach ($this->dirs as $dir) {

			$file = $dir . '/' . $classname . '.php';
			if(is_readable($file)){
				require $file;
				return;
			}
		}
	}
}