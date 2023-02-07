<?php
/*
	File: dbConnectionMssqlSCF.php
	Description: required to open dpmt (www.dpmt.org.br) MSSQL SCF database connection
	Author: Paulo Fonseca Júnior
	Create date: 2011-05-13
	Require:	/lib/php/globals/globals.php
				/lib/php/dbConnection/DbMSSQL.php
    			/lib/php/dbConnection/DbMySql.php

	Usage: 	require_once("/lib/connection/dbConnectionMssqlSCF.php");
*/

require_once($_SERVER['DOCUMENT_ROOT']. "/dpmt/portal/incs/uteis/DbMSSQL.php");
$connectionMssql = new DbMSSQL();
?>
