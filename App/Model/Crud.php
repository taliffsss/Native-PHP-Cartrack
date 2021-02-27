<?php
namespace Cartrack\Model;

use Cartrack\Core\Model;

class Crud extends Model {
	
	function __construct()
	{
		parent::__construct();
		
		$this->table = 'api_crud';

		$this->primaryKey = 'id';
	}
}

?>