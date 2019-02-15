<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

abstract class Controller {
	protected static $_Model;
	protected static $_Router;
	protected static $_View;
	protected $columns = "*";
	
	// Constructor saves router
	public function __construct($_Router = null) {
		self::$_Router = $_Router ?? Application::getRouter();
	}
	
	// Default access levels
	protected static $_Authors = [ROLE_AUTHOR,ROLE_EDITOR,ROLE_PUBLISHER,ROLE_MANAGER,ROLE_ADMINISTRATOR];
	protected static $_Editors = [ROLE_EDITOR,ROLE_PUBLISHER,ROLE_MANAGER,ROLE_ADMINISTRATOR];
	protected static $_Publishers = [ROLE_PUBLISHER,ROLE_MANAGER,ROLE_ADMINISTRATOR];
	protected static $_Managers = [ROLE_MANAGER,ROLE_ADMINISTRATOR];
	protected static $_Administrators = [ROLE_ADMINISTRATOR];
	
	// Returns true if the model includes an access property
	private function containsAccessProperty() {
		return $this->columns == "*" || !(strpos($this->columns,'accesslevel') === false);
	}
	
	// Returns true if the model includes a status property
	private function containsStatusProperty() {
		return $this->columns == "*" || !(strpos($this->columns,'status') === false);
	}
	
	// Returns router
	public function getRouter() {
		return self::$_Router;
	}
	
	// Returns filter for list permission
	public function requireListPermission() {
		$filter = [];
		if($this->containsAccessProperty())
			if(Session::isRegistered())
				$filter[] = '`accesslevel` IN ('.ACCESS_PUBLIC.','.ACCESS_REGISTERED.')';
			else
				$filter[] = '`accesslevel` IN ('.ACCESS_PUBLIC.','.ACCESS_GUEST.')';
		if($this->containsStatusProperty())
			$filter[] = "`status`=".STATUS_PUBLISHED;
		return implode(' AND ',$filter) ?? '1';
	}
	
	// Returns filter for view permission
	private function requireViewPermission() {
		$filter = [];
		if($this->containsAccessProperty())
			if(Session::isRegistered())
				$filter[] = '`accesslevel` IN ('.ACCESS_PUBLIC.','.ACCESS_REGISTERED.','.ACCESS_PRIVATE.')';
			else
				$filter[] = '`accesslevel` IN ('.ACCESS_PUBLIC.','.ACCESS_GUEST.','.ACCESS_PRIVATE.')';
		if($this->containsStatusProperty())
			$filter[] = "`status`=".STATUS_PUBLISHED;
		return implode(' AND ',$filter) ?? '1';
	}
	
	public function all() {
		$model = __CUBO__.'\\'.self::getRouter()->getController();
		try {
			if(class_exists($model)) {
				self::$_Model = new $model;
				$_Data = self::$_Model::getAll($this->columns,$this->requireListPermission());
				if($_Data) {
					return $this->render($_Data);
				} else {
					// No items returned, must be empty data set
					$model = self::getRouter()->getController();
					throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'severity'=>2,'response'=>405,'message'=>"Model '{$model}' returned no data"]);
				}
			} else {
				$model = self::getRouter()->getController();
				throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'severity'=>1,'response'=>405,'message'=>"Model '{$model}' does not exist"]);
			}
		} catch(Error $_Error) {
			$_Error->showMessage();
		}
		return false;
	}
	
	// Default method redirects to view
	public function default() {
		return $this->view();
	}
	
	// Call view with requested format
	protected function render($_Data) {
		$view = __CUBO__.'\\'.self::getRouter()->getController().'view';
		$format = self::getRouter()->getFormat();
		if(class_exists($view)) {
			if(method_exists($view,$format)) {
				// Send retrieved data to view and return output
				self::$_View = new $view;
				return self::$_View->$format($_Data);
			} else {
				// Method does not exist for this view
				$view = self::getRouter()->getController();
				throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'line'=>__LINE__,'file'=>__FILE__,'severity'=>1,'response'=>405,'message'=>"View '{$view}' does not have the method '{$format}' defined"]);
			}
		} else {
			// View not found
			$view = self::getRouter()->getController();
			throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'line'=>__LINE__,'file'=>__FILE__,'severity'=>1,'response'=>405,'message'=>"View '{$view}' does not exist"]);
		}
		return false;
	}
	
	public function view() {
		$model = __CUBO__.'\\'.self::getRouter()->getController();
		try {
			if(class_exists($model)) {
				self::$_Model = new $model;
				$_Data = self::$_Model::get(self::getRouter()->getName(),$this->columns,$this->requireViewPermission());
				if($_Data) {
					return $this->render($_Data);
				} else {
					// Could not retrieve item, check again to see if it exists
					$result = self::$_Model::get(self::getRouter()->getName(),$this->columns);
					if($result) {
						// The item is found; determine if it is published
						if(isset($result->status) && $result->status == STATUS_PUBLISHED) {
							// The item is published; visitor does not have access
							if(Session::isGuest()) {
								// No user is logged in; redirect to login page
								$model = ucfirst(self::getRouter()->getController());
								$name = self::getRouter()->getName();
								Session::setMessage(array('alert'=>'info','icon'=>'exclamation','message'=>"{$model} '{$name}' requires user access"));
								Session::set('login_redirect',Configuration::getParameter('uri'));
								Router::redirect('/user/login',403);
							} else {
								// User is logged in, so does not have required permissions
								$model = ucfirst(self::getRouter()->getController());
								$name = self::getRouter()->getName();
								throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'line'=>__LINE__,'file'=>__FILE__,'severity'=>3,'response'=>405,'message'=>"User does not have access to {$model} '{$name}'"]);
								//Session::setMessage(array('alert'=>'error','icon'=>'exclamation','text'=>"This user has no access to {$this->class}"));
								//Session::set('login_redirect',Application::getParam('uri'));
								//Router::redirect('/user?noaccess',403);
							}
						} else {
							// The item is not published
							$model = ucfirst(self::getRouter()->getController());
							$name = self::getRouter()->getName();
							throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'line'=>__LINE__,'file'=>__FILE__,'severity'=>2,'response'=>405,'message'=>"{$model} '{$name}' is no longer available"]);
						}
					} else {
						// The item really does not exist
						$model = ucfirst(self::getRouter()->getController());
						$name = self::getRouter()->getName();
						throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'line'=>__LINE__,'file'=>__FILE__,'severity'=>2,'response'=>405,'message'=>"{$model} '{$name}' does not exist"]);
					}
				}
			} else {
				$model = self::getRouter()->getController();
				throw new Error(['class'=>__CLASS__,'method'=>__METHOD__,'line'=>__LINE__,'file'=>__FILE__,'severity'=>1,'response'=>405,'message'=>"Model '{$model}' does not exist"]);
			}
		} catch(Error $_Error) {
			$_Error->showMessage();
		}
		return false;
	}
	
	// Special method: create
	public function create() {
	}
	
	// Special method: edit
	public function edit() {
	}
	
	// Special method: trash
	public function trash() {
	}
	
	// Returns true if current user has permitted role to create an item
	public function canCreate() {
		return in_array(Session::getRole(),self::$_authors);
	}
	
	// Returns true if current user does not have permitted role to create an item
	public function cannotCreate() {
		return !$this->canCreate($author);
	}
	
	// Returns true if current user is the author or has permitted role to edit an item
	public function canEdit($author = 0) {
		return in_array(Session::getRole(),self::$_Editors) || Session::getUser() == $author;
	}
	
	// Returns true if current user is not the author and does not have permitted role to edit an item
	public function cannotEdit($author = 0) {
		return !$this->canEdit($author);
	}
	
	// Returns true if current user is the author or has permitted role to publish an item
	public function canManage() {
		return in_array(Session::getRole(),self::$_Managers);
	}
	
	// Returns true if current user is not the author and does not have permitted role to publish an item
	public function cannotManage() {
		return !$this->canManage();
	}
	
	// Returns true if current user is the author or has permitted role to publish an item
	public function canPublish() {
		return in_array(Session::getRole(),self::$_Publishers);
	}
	
	// Returns true if current user is not the author and does not have permitted role to publish an item
	public function cannotPublish() {
		return !$this->canPublish();
	}
	
	// Returns true (for the moment)
	public function canRead() {
		return true;
	}
	
	// Returns false (for the moment)
	public function cannotRead() {
		return !$this->canRead();
	}
}
?>