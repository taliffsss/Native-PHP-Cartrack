<?php
namespace Cartrack\Core;

/**
 * @package Database Class
 * @author  Mark Anthony Naluz <anthony.naluz15@gmail.com>
 * @copyright Jul 2018 <Mark Anthony Naluz>
 */

use Cartrack\Libraries\Database;

class Model {

	protected $db;

	protected $primaryKey;

	protected $table;

	protected $deletedAt;
	
	function __construct()
	{
		$this->db = Database::getIntance();
	}

	/**
	 * Insert Data
	 * @param array $data
	 * @return obj
	 */
	public function insert($data)
	{
		$this->db->insert($this->table, $data);

		$id = $this->db->InsertId();

		return $this->fetch([$this->primaryKey => $id]);

	}

	/**
	 * Get Single Data with where clause
	 * @param string $attr
	 * @param string Int $param
	 * @return obj
	 */
	public function fetch(array $where)
	{

		try {

			$whereClause = NULL;

	        foreach ($where as $k => $v) {
	            $whereClause .= "$k = :$k AND ";
	        }

	        $whereClause = rtrim($whereClause,' AND ');

			$this->db->query("SELECT * FROM {$this->table} WHERE {$whereClause}");

			foreach ($where as $k => $v) {
	            $this->db->bind(":$k", $v);
	        }

			$row = $this->db->single();

			return $row;

		} catch (Exception $e) {

			$e->getMessage();

		}

	}

	/**
	 * Get all Data
	 * @return array obj
	 */
	public function fetchall()
	{

		try {

			$this->db->query("SELECT * FROM {$this->table}");

			$this->db->execute();

			$row = $this->db->resultset();

			return $row;

		} catch (Exception $e) {

			$e->getMessage();

		}

	}

	/**
	 * Count all data
	 * @return int
	 */
	public function count_all()
	{

		try {

			$this->db->query("SELECT * FROM {$this->table}");

			$this->db->execute();

			$row = $this->db->rowCount();

			return $row;

		} catch (Exception $e) {

			$e->getMessage();

		}

	}

	public function _delete(array $where, $soft = true)
	{
		if ($soft) {
			$whereClause = [];
			foreach ($where as $k => $v) {
				$whereClause[$v[0]] = $v[2];
			}

			$data = [
				$this->deletedAt => Helper::datetime_ms()
			];
			return $this->update($data, $whereClause);
		}

		return $this->db->delete($this->table, $where);
	}

	public function update(array $data, array $where)
	{
		return $this->db->update($this->table, $data, $where);
	}
}

?>