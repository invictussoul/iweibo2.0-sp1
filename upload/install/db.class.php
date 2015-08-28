<?php

if(!defined('IN_MB')) {
	exit('Access Denied');
}

class mysqlDb {
	var $version = '';
	var $link = null;
	var $querynum = 0;
	var $debug = array();

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $dbcharset = '') {

		$func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect';
		if(!$this->link = @$func($dbhost, $dbuser, $dbpw, 1)) {
			$this->halt('Can not connect to MySQL server');
		}

		if($this->version() > '4.1') {
			$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
			$serverset .= $this->version() > '5.0.1' ? ($serverset ? ',' : '').'sql_mode=\'\'' : '';
			$serverset && mysql_query("SET $serverset", $this->link);
		}

		$dbname && @mysql_select_db($dbname, $this->link);

	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function fetch_first($sql) {
		return $this->fetch_array($this->query($sql));
	}

	function result_first($sql) {
		return $this->result($this->query($sql));
	}

	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link)) && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		if(DEBUG) {
			$backtrace = $explain = array();
			$lines = debug_backtrace();
			foreach($lines as $row) {
				$backtrace = array('file' => str_replace(array(DIR_ROOT, '\\'), array('./', '/'), $row['file']), 'line' => $row['line']);
				if(strpos($row['file'], 'db.class.php') !== false) {
					continue;
				} elseif($row['function'] == 'query') {
					break;
				}
			}
			if(preg_match("/^select /i", $sql)) {
				$eq = mysql_query("explain $sql");
				if($eq) {
					while($exp = mysql_fetch_assoc($eq)) {
						$explain[] = array(
							'id' => $exp['id'],
							'table' => $exp['table'],
							'type' => $exp['type'],
							'possible_keys' => $exp['possible_keys'],
							'key' => $exp['key'],
							'key_len' => $exp['key_len'],
							'ref' => $exp['ref'],
							'rows' => $exp['rows'],
							'Extra' => $exp['Extra']
						);
					}
				}
			}
			$this->debug[$this->querynum] = array(
				'sql' => trim(preg_replace('/\s*(SELECT|FROM|WHERE|DELETE|UPDATE|SET|INSERT INTO|REPLACE INTO|ORDER BY|GROUP BY|VALUES|LIMIT) /i', ' <b>\\1 </b>', htmlspecialchars($sql))),
				'backtrace' => $backtrace,
				'explain' => $explain
			);
		}
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row = 0) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		if(empty($this->version)) {
			$this->version = mysql_get_server_info($this->link);
		}
		return $this->version;
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') {
		$timestamp = time();
		$dberrno = $this->errno();
		$dberror = $this->error();
		$errlog = $errnos = array();
		if(@$fp = fopen(DIR_DATA.'/log/dberrorlog.php', 'r')) {
			while((!feof($fp)) && count($errlog) < 20) {
				$errline = fgets($fp, 350);
				$log = explode("\t", $errline);
				if(isset($log[1]) && $timestamp - $log[1] < 86400) {
					$errnos[$log[1]] = $log[2];
					$errlog[$log[1]] = $errline;
				}
			}
			fclose($fp);
		}
		if(!in_array($dberrno, $errnos)) {
			$errlog[$timestamp] = "<?PHP exit;?>\t$timestamp\t$dberrno\t$message\t".htmlspecialchars($sql)."\t$dberror\t\n";
			@$fp = fopen(DIR_DATA.'/log/dberrorlog.php', 'w');
			@flock($fp, 2);
			foreach(array_unique($errlog) as $dateline => $errline) {
				@fwrite($fp, $errline);
			}
			@fclose($fp);
		}
	}

}

?>