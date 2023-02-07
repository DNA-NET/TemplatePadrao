<?php
/*
	Copyright (c) 2010, 2011 Conselho Regional de Contabilidade do Paran� - http://www.hc.org.br

	Class: DbMSSQL
	Version: current (in progress)
	Improvements from 1.0 stable:	1) Standarization: inserted '_' character in front of parameters
									2) Implemented methods: query, fetch_assoc and num_rows
									3) fetch_assoc method changed to trim all values to bypass freetds bug that returns /b instead empty
	Standard: PHP Version 5.1.6
	Description: Object abstract to create db connection to Microsoft SQL Server and manipulate it with your data managment language (SQL)
	Author: Paulo Fonseca J�nior
	Create date: 2010-10-18
	Require: php 5.1.6 or higher with MSSQL support

	Usage:	<?php
			require_once(PHP_LIB_PATH. "dbConnection/DbMSSQL.php");
			$dbConnection = new DbMSSQL("domain.com", "database1", "username", "password")
			?>
*/

class mssql {
	private $connectionId; /* stores object connection id */

	/*
		Method: __construct
		Description: DbMSSQL constructor - creates a db connection and selects database
		Author: Paulo Fonseca J�nior
		Create date: 2010-10-18

		Usage:	<?php
				require_once("/lib/php/dbConnection/DbMSSQL.php");
				$dbConnection = new DbMSSQL("domain.com", "database1", "username", "password")
				?>
	*/
	function __construct()
	{
		try {
			/* CONEXÃO INICIAL do Banco de Dados */
			$serverName = "172.16.1.34";
			$uid = "dna";
			$pwd = "Y34bRx2020#$!fg";
			$connectionInfo = array(
				"UID"=>$uid,
				"PWD"=>$pwd, 
				"Database"=>"SCF"
			);
			$this->connectionId = sqlsrv_connect($serverName, $connectionInfo);
		} catch (Exception $e) {
			echo "ERRO: " .$e->getMessage();
			$this->connectionId = false;
		}
	}

	/*
		Method: id
		Description: Gets db connection id
		Author: Paulo Fonseca J�nior
		Create date: 2010-11-09

		Usage:	<?php
				require_once("/lib/php/dbConnection/DbMSSQL.php");
				$dbConnection = new DbMSSQL("domain.com", "database1", "username", "password")
				$query = mssql_query("SELECT * FROM TABLE1", $dbConnection->id());
				?>
	*/
	function id	()
	{
		try {
			return $this->connectionId;
		} catch (Exception $e) {
			echo "ERRO: " .$e->getMessage();
			return NULL;
		}
	}

	/*
		Method: fetch_assoc
		Description: Database fetch_assoc
		Author: Paulo Fonseca J�nior
		Create date: 2011-04-20

		Usage:	<?php
				require_once("/lib/php/dbConnection/DbMySql.php");
				$dbConnection = new DbMySql("domain.com", "database1", "username", "password")
				$result = $dbConnection->query("SELECT * FROM TABLE1");
				$row = $dbConnection->fetch_assoc($result);
				?>
	*/
	function fetch_assoc	($_queryResult	// query result data
							)
	{
		try {
			$r = sqlsrv_fetch_array($_queryResult, 2);
			
			// trim all values to bypass freetds bug that returns /b instead empty
			if (!empty($r))
				foreach($r as $key => $value)
					$r[$key] = trim($value);
			return $r;
		} catch (Exception $e) {
			echo "ERRO: " .$e->getMessage();
			return NULL;
		}
	}

	/*
		Method: query
		Description: Database query
		Author: Paulo Fonseca J�nior
		Create date: 2011-04-20

		Usage:	<?php
				require_once("/lib/php/dbConnection/DbMySql.php");
				$dbConnection = new DbMySql("domain.com", "database1", "username", "password")
				$result = $dbConnection->query("SELECT * FROM TABLE1");
				?>
	*/
	function query	($_query	// query string
					)
	{
		try {
			return sqlsrv_query($this->connectionId, $_query);
		} catch (Exception $e) {
			echo "ERRO: " .$e->getMessage();
			return NULL;
		}
	}

	/*
		Method: num_rows
		Description: Database num_rows
		Author: Paulo Fonseca J�nior
		Create date: 2011-04-20

		Usage:	<?php
				require_once("/lib/php/dbConnection/DbMySql.php");
				$dbConnection = new DbMySql("domain.com", "database1", "username", "password")
				$result = $dbConnection->query("SELECT * FROM TABLE1");
				$totalRows = $dbConnection->num_rows($result);
				?>
	*/
	function num_rows($_queryResult	// query result data
						)
	{
		try {
			return sqlsrv_num_rows($_queryResult);
		} catch (Exception $e) {
			echo "ERRO: " .$e->getMessage();
			return NULL;
		}
	}
}
?>
