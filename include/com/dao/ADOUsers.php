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
 * Description of ADOUsers
 *
 * @author MarcoCantu
 */
class ADOUsers {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    public function AddNewUser($UserObj){
        if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $user=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->user);
            $pass= mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->pass);
            $active=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->active);
            $email= mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->email);
            $sql="insert into t_users(user,pass,active,email) values('$user',md5('$pass'),$active,'$email')";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function UpdateUser($UserObj){
        if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $iduser=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->iduser);
            $user=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->user);
            $active=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->active);
            $email= mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->email);
            $sql="update t_users set user='$user', active=$active, email='$email' where iduser=$iduser";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function getUsuarios($ListUserObj){
        if(!empty($ListUserObj)){
            $this->mysqlconector->OpenConnection();
            $sql="select iduser,user,email,active from t_users;";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $User= new UserObj();
                     $User->iduser=$row['iduser'];
                     $User->user=$row['user'];
                     $User->email=$row['email'];
                     $User->active=$row['active'];
                     $ListUserObj->addItem($User);
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function getUsuarioByID($UserObj){
        if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $iduser=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->iduser);
            $sql="select iduser,user,email,active from t_users where iduser=$iduser;";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $UserObj->iduser=$row['iduser'];
                     $UserObj->user=$row['user'];
                     $UserObj->email=$row['email'];
                     $UserObj->active=$row['active'];
                 }
             }
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function DeleteUser($UserObj){
         if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $iduser= mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->iduser);
            $sql="delete from t_users where iduser=$iduser";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
         }
    }
    
    public function ActivateUser($UserObj){
        if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $iduser=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->iduser);
            $sql="update t_users set active=1 where iduser=$iduser";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function DeactivateUser($UserObj){
        if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $iduser=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->iduser);
            $sql="update t_users set active=0 where iduser=$iduser";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
        }
    }
    
    
    public function Loggin($UserObj){
        if(!empty($UserObj)){
            $this->mysqlconector->OpenConnection();
            $user=  mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->user);
            $password= mysqli_real_escape_string($this->mysqlconector->conn,$UserObj->pass);
            $sql="select iduser,user,email,active from t_users where user='$user' and pass=md5('$password');";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $UserObj->iduser=$row['iduser'];
                     $UserObj->user=$row['user'];
                     $UserObj->email=$row['email'];
                     $UserObj->active=$row['active'];
                 }
             }
            $this->mysqlconector->CloseDataBase();
        }
    }
    
}
