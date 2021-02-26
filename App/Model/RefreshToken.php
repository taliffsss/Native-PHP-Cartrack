<?php
namespace Cartrack\Model;

use Cartrack\Core\Model;

class RefreshToken extends Model {
	
	function __construct()
	{
		parent::__construct();
		$this->table = 'refresh_token';
	}
}

?>