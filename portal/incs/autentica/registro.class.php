<?php

/* 
 * The MIT License
 *
 * Copyright 2021 rogers.neves.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class registro {
	/**
	*
	* @author Rogers Neves
	* @name Registro de profissionais no BD SPW
	* @tutorial Classe com métodos de acesso e autenticação a informações de registro profissional
	* Adaptado para uso no Atualiza em 02/2021
	*
	*/
        
	private $conn;
	
	public $adimplente;
	
	public $numero;
	
	public $ativo;
	
	function __construct($numero_registro=''){
	/**
	*
	* @author Rogers Neves
	* @tutorial Instancia conexão 
	* @access public 
	* @return void
	*
	**/
		include_once($_SERVER['DOCUMENT_ROOT'].'/hc/portal/incs/uteis/dbConnectionMssql.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/hc/portal/incs/uteis/mssql.class.php');
                
        // marca atributo do registro
		$this->numero = $numero_registro;
                
		// instancia conexão
		$this->conn = new mssql();
                
		// verifica adimplência e seta o atributo
		$query = "select count(*) as total "
			. "FROM SCF..SFNA01  A "
			. "WHERE DATEADD(DAY, 1, A.[Data Vencimento]) < GETDATE() "
			. "AND A.[Num. Registro] = '$numero_registro'";

		$ret = $this->conn->query($query);

		$row = $this->conn->fetch_assoc($ret);

		if($row['total'] == 0)
			$this->adimplente = true;
		else
			$this->adimplente = false;
		
		
		// verifica se está baixado e seta o atributo
		$query2 = "select count(*) as total "
			. "FROM SCF..SCDA01  A "
			. "WHERE A.[Situacao Cadastral] = 1 "
			. "AND A.[Num. Registro] = '$numero_registro'";

		$ret2 = $this->conn->query($query2);

		$row2 = $this->conn->fetch_assoc($ret2);

		if($row2['total'] == '0')
			$this->ativo = false;
		else
			$this->ativo = true;
                
    }
        
	function autentica($senha_registro=''){
	/**
	*
	* @author Rogers Neves
	* @tutorial Verifica registro e senha do profissional
	* @access public 
	* @return void
	*
	**/
            
		$query = "SELECT A.NOME as nome, A.[Num. Registro] as registro "
				. "FROM SCF..SCDA01 A, SCF..SCDA111 B "
				. "WHERE A.[Num. Registro] = '$this->numero' "
				. "AND B.SENHA = '$senha_registro' "
				. "AND A.[Num. Registro] = B.[NUMREGISTRO]";
				
		$ret = $this->conn->query($query);

		if(sqlsrv_has_rows($ret) > 0){
			return $this->conn->fetch_assoc($ret);
		}
		else
			return false;
						
		
	}
        
	function adimplente($numero_registro=''){
	/**
	*
	* @author Rogers Neves
	* @tutorial Verifica se o registro está adimplente
	* @access public 
	* @return void
	*
	**/
            
		$query = "select count(*) as total "
				. "FROM SCF..SFNA01  A "
				. "WHERE DATEADD(DAY, 1, A.[Data Vencimento]) < GETDATE() "
				. "AND A.[Num. Registro] = '$numero_registro'";

		$ret = $this->conn->query($query);
		
		$row = $this->conn->fetch_assoc($ret);
			
		if($row['total'] == 0)
				$this->adimplente = true;
			else
				$this->adimplente = false;
		
	}
}