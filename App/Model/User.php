<?php
namespace Cartrack\Model;

use Cartrack\Core\Model;

class User extends Model {
	
	function __construct()
	{
		parent::__construct();
		
		$this->deletedAt = 'deleted_at';
		$this->table = 'users';
	}
}

?>