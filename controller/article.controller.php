<?php
/**
 * @application    Cubo CMS
 * @type           Controller
 * @class          ArticleController
 * @version        2.0.4
 * @date           2019-03-05
 * @author         Dan Barto
 * @copyright      Copyright (c) 2019 Cubo CMS; see COPYRIGHT.md
 * @license        MIT License; see LICENSE.md
 */
namespace Cubo;

class ArticleController extends Controller {
	protected $columns = "*";
	
	public function view() {
		if($result = parent::view()) {
			$this->_Model::visit($this->getRouter()->getName());
		}
		return $result;
	}
}
?>