<?php

/*
 * Copyright (C) 2016 MarcoCantu
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Description of MysqlConnector
 *
 * @author MarcoCantu
 */
class MysqlConnector {
    private $servername;
    private $username;
    private $password;
    private $database;
    public $conn;
    
    
    public function __construct() {
        $settings=new Config();
        $this->username=$settings->username;
        $this->password=$settings->password;
        $this->database=$settings->database;
        $this->servername=$settings->servername;
    }
    
    public function OpenConnection(){
        $this->conn= new mysqli($this->servername, $this->username,  $this->password,  $this->database);
        if($this->conn->connect_error){
            die("Connection failed: " . mysqli_connect_error());
        }
    }
    
    public function CloseDataBase(){
        try{
            $this->conn->close();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
}
