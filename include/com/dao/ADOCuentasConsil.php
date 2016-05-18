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
 * Description of ADOCuentasConsil
 *
 * @author MarcoCantu
 */
class ADOCuentasConsil {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    public function __destruct() {
        unset($this->mysqlconector);
        unset($this->debug);
    }
    
    public function AddNew($CuentaConcilObj){
        if(!empty($CuentaConcilObj)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->descripcion);
           $cuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->cuenta);

           $sqlobj= new SqlQueryBuilder("insert");
           $sqlobj->setTable("t_cuentas_conciliacion");
           $sqlobj->addColumn("descripcion");
           $sqlobj->addValue($descipcion);
           $sqlobj->addColumn("cuenta");
           $sqlobj->addValue($cuenta);
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           $CuentaConcilObj->idcuenta=  $this->getlastid();
           
           
        }
    }
    
    public function UpdateInfo($CuentaConcilObj){
        if(!empty($CuentaConcilObj)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->descripcion);
           $cuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->cuenta);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->idcuenta);

           $sqlobj= new SqlQueryBuilder("update");
           $sqlobj->setTable("t_cuentas_conciliacion");
           $sqlobj->addColumn("descripcion");
           $sqlobj->addValue($descipcion);
           $sqlobj->addColumn("cuenta");
           $sqlobj->addValue($cuenta);
           $sqlobj->setWhere("idcuenta=$idcuenta");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           $CuentaConcilObj->idcuenta=  $this->getlastid();
        }
    }

    public function DeleteInfo($CuentaConcilObj){
        if(!empty($CuentaConcilObj)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->descripcion);
           $cuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->cuenta);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->idcuenta);

           $sqlobj= new SqlQueryBuilder("delete");
           $sqlobj->setTable("t_cuentas_conciliacion");
           $sqlobj->setWhere("idcuenta=$idcuenta");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           $CuentaConcilObj->idcuenta=  $this->getlastid();
        }
    }
    
    public function GetAllCuentas($ListCuentaConcil){
        if(!empty($ListCuentaConcil)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->descripcion);
           $cuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->cuenta);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->idcuenta);
           
           $sqlobj= new SqlQueryBuilder("select");
           $sqlobj->setTable("t_cuentas_conciliacion");
           $sqlobj->addColumn("descripcion");
           $sqlobj->addColumn("cuenta");
           $sqlobj->addColumn("idcuenta");
           
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $cuenta = new CuentaConcilObj();
                    $cuenta->idcuenta=$row['idcuenta'];
                    $cuenta->descripcion=$row['descripcion'];
                    $cuenta->cuenta=$row['cuenta'];
                    $ListCuentaConcil->addItem($cuenta);
                }
            }
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           
        }
    }
    public function GetCuentaByID($CuentaConcilObj){
        if(!empty($CuentaConcilObj)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->descripcion);
           $cuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->cuenta);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->idcuenta);
           
           $sqlobj= new SqlQueryBuilder("select");
           $sqlobj->setTable("t_cuentas_conciliacion");
           $sqlobj->addColumn("descripcion");
           $sqlobj->addColumn("cuenta");
           $sqlobj->addColumn("idcuenta");
           $sqlobj->setWhere("idcuenta=$idcuenta");
           
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $CuentaConcilObj->idcuenta=$row['idcuenta'];
                    $CuentaConcilObj->descripcion=$row['descripcion'];
                    $CuentaConcilObj->cuenta=$row['cuenta'];
                }
            }
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           
        }
    }
    public function GetCuentaByQuery($ListCuentaConcil,$SQLBuilderObj){
        if(!empty($ListCuentaConcil)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->descripcion);
           $cuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->cuenta);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$CuentaConcilObj->idcuenta);
           
           $SQLBuilderObj->setTable("t_cuentas_conciliacion");
           $SQLBuilderObj->addColumn("descripcion");
           $SQLBuilderObj->addColumn("cuenta");
           $SQLBuilderObj->addColumn("idcuenta");
           
           if($this->debug){
                echo '<br/>'. $SQLBuilderObj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($SQLBuilderObj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $cuenta = new CuentaConcilObj();
                    $cuenta->idcuenta=$row['idcuenta'];
                    $cuenta->descripcion=$row['descripcion'];
                    $cuenta->cuenta=$row['cuenta'];
                    $ListCuentaConcil->addItem($cuenta);
                }
            }
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           
        }
    }
    
    public function getlastid(){
        $id =0;
        $this->mysqlconector->OpenConnection();
        $sql="select max(idcuenta) as lastid from t_cuentas_conciliacion";
        $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()) {
                $id=$row['lastid'];
            }
        }
        $this->mysqlconector->CloseDataBase();
        unset($result);
        return $id;
        
    }
    
}
