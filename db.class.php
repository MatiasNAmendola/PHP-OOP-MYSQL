<?php

	class Database {

		// Variables for MYSQL Database
		private $db_host = 'localhost';
		private $db_user = '';
		private $db_pass = '';
		private $db_name = '';

		// Misc variables for other functions
		private $dbconn = 0;
		private $con = false;
		private $result = array();

		// Connect to the database.
		public function connect() {
			if(!$this->con) {
				$this->dbconn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
				if($this->dbconn) {
					$this->con = true;
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}

		// Disconnect from the database.
		public function disconnect() {
			if($this->con) {
				if(mysqli_close()) {
					$this->con = false;
					return true;
				} else {
					return false;
				}
			}
		}

		// Function to check if the table exits
		private function tableExists($table) {
			$tablesAvailable = mysqli_query($this->dbconn,'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
			if($tablesAvailable) {
				if(mysqli_num_rows($tablesAvailable) == 1) {
					return true;
				} else {
					array_push($this->result, $table." does not exist in the database.");
					return false;
				}
			}
		}

		// SELECT query
		public function select($table, $rows = '*', $where = null, $order = null) {
			$query = 'SELECT '.$rows.' FROM '.$table;

			if($where != null) {
				$query .= ' WHERE '.$where;
			}

			if($order != null) {
				$quer .= ' ORDER '.$order;
			}

			if($this->tableExists($table)) {
				$query_fin = mysqli_query($this->dbconn,$query);
				if($query_fin) {
					$this->numResults = mysqli_num_rows($query_fin);

					for($i = 0; $i < $this->numResults; $i++) {
						$mysql_array = mysqli_fetch_array($query_fin);
						$key = array_keys($mysql_array);

						for($j = 0; $j < count($key); $j++) {
							if(!is_int($key[$j])) {
								if(mysqli_num_rows($query_fin) > 1) {
									$this->result[$i][$key[$j]] = $mysql_array[$key[$j]];
								} else if(mysqli_num_rows($query_fin) < 1) {
									$this->result = null;
								} else {
									$this->result[$key[$j]] = $mysql_array[$key[$j]];
								}
							}
						}

					}

					// Success
					return true;

				} else {
					array_push($this->result, mysqli_error());
					return false;
				}

			} else {
				return false; // Table does not exist.
			}

		}

		// INSERT query
		public function insert($table, $params=array()) {

			if($this->tableExists($table)) {

				$sql = 'INSERT INTO '.$table.' ('.implode(',',array_keys($params)).') VALUES ("' . implode('", "', $params) . '")';

				$query = mysqli_query($this->dbconn, $sql);

				if($query) {
					array_push($this->result, mysqli_insert_id($this->dbconn));
					return true;
				} else {
					array_push($this->result, mysqli_error($this->dbconn));
					return false;
				}

			} else {
				return false; // Table does not exist.
			}

		}

		// DELETE QUERY
		public function delete($table, $where = null) {

			if($this->tableExists($table)) {
				if($where == null) {
					$sql = 'DELETE FROM '.$table;
				} else {
					$sql = 'DELETE FROM '.$table.' WHERE '.$where;
				}

				$query = mysqli_query($this->dbconn, $sql);

				if($query) {
					array_push($this->result, mysqli_affected_rows($this->dbconn));
					return true; // Success
				} else {
					array_push($this->result, mysqli_error($this->dbconn));
					return false; // Fail
				}

			} else {
				return false; // Fail
			}

		}

		// UPDATE QUERY
		public function update($table, $params=array(), $where) {

			if($this->tableExists($table)) {

				$args = array();

				foreach($params as $field=>$value) {
					$args[] = $field.'="'.$value.'"';
				}

				$sql = 'UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$where;

				$query = mysqli_query($this->dbconn, $sql);

				if($query) {
					array_push($this->result, mysqli_affected_rows($this->dbconn));
					return true; // Fail
				} else {
					array_push($this->result, mysqli_error($this->dbconn));
					return false; // Fail
				}

			} else {
				return false; // Table doesn't exist
			}

		}

		// Any SQL Query
		public function query($sql) {
			$query = mysqli_query($this->dbconn, $sql);
			if($query) {
				$this->numResults = mysqli_num_rows($query);

				for($i = 0; $i < $this->numResults; $i++) {
					$mysql_array = mysqli_fetch_array($query);
					$key = array_keys($mysql_array);

					for($j = 0; $j < count($key); $j++) {
						if(!is_int($key[$j])) {
							if(mysqli_num_rows($query) > 1) {
								$this->result[$i][$key[$j]] = $mysql_array[$key[$j]];
							} else if(mysqli_num_rows($query) < 1) {
								$this->result = null;
							} else {
								$this->result[$key[$j]] = $mysql_array[$key[$j]];
							}
						}
					}

				}

				return true; // Success

			} else {
				array_push($this->result, mysqli_error());
				return false;
			}
		}

		// Get the result of the queries
		public function getResults() {
			$val = $this->result;
			$this->result = array();
			return $val;
		}

	}

?>
