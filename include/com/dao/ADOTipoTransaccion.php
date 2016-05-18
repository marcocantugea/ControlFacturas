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
 * Description of ADOTipoTransaccion
 *
 * @author MarcoCantu
 */
class ADOTipoTransaccion {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    public function AddNew($TipoTransaccionObj){
        if(!empty($TipoTransaccionObj)){
           $this->mysqlconector->OpenConnection();
           $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->descripcion);
           $active=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->active);
           /*$sql="insert into t_catalogo_tipo_transaccion(descripcion,active)values('$descipcion',$active)";
           if($this->debug){
                echo '<br/>'. $sql;
           }
           $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            */
           $sqlobj= new SqlQueryBuilder("insert");
           $sqlobj->setTable("t_catalogo_tipo_transaccion");
           $sqlobj->addColumn("descripcion");
           $sqlobj->addValue($descipcion);
           $sqlobj->addColumn("active");
           $sqlobj->addValue($active);
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           $TipoTransaccionObj->idctrans=  $this->getlastid();
        }
    }
    
    public function UpdateTipoTrans($TipoTransaccionObj){
        if(!empty($TipoTransaccionObj)){
            $this->mysqlconector->OpenConnection();
            $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->descripcion);
            $active=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->active);
            $idctrans=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->idctrans);
            
            $sqlobj= new SqlQueryBuilder("update");
            $sqlobj->setTable("t_catalogo_tipo_transaccion");
            $sqlobj->addColumn("descripcion");
            $sqlobj->addValue($descipcion);
            $sqlobj->setWhere("idctrans=$idctrans");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
            $this->mysqlconector->CloseDataBase();
            
            unset($sqlobj);
            unset($result);
        }
    }
    
    public function DeleteTipoTrans($TipoTransaccionObj){
        if(!empty($TipoTransaccionObj)){
            $this->mysqlconector->OpenConnection();
            $descipcion= mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->descripcion);
            $active=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->active);
            $idctrans=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransaccionObj->idctrans);
            
            $sqlobj= new SqlQueryBuilder("delete");
            $sqlobj->setTable("t_catalogo_tipo_transaccion");
            $sqlobj->setWhere("idctrans=$idctrans");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
            $this->mysqlconector->CloseDataBase();
            
            unset($sqlobj);
            unset($result);
        }
    }
    
    public function GetAllTipoTrans($ListTipoTrans){
        if(!empty($ListTipoTrans)){
            $this->mysqlconector->OpenConnection();
            
            $sqlobj= new SqlQueryBuilder("select");
            $sqlobj->setTable("t_catalogo_tipo_transaccion");
            $sqlobj->addColumn("idctrans");
            $sqlobj->addColumn("descripcion");
            $sqlobj->addColumn("active");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $tipotrans= new TipoTransaccion();
                    $tipotrans->idctrans=$row['idctrans'];
                    $tipotrans->descripcion=$row['descripcion'];
                    $tipotrans->active=$row['active'];
                    $ListTipoTrans->addItem($tipotrans);
                    unset($tipotrans);
                }
            }            
            $this->mysqlconector->CloseDataBase();
            unset($result);
            unset($sqlobj);
        }
    }
    
    public function GetTipoTransByID($TipoTransObj){
        if(!empty($TipoTransObj)){
            $this->mysqlconector->OpenConnection();
            $idctrans=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransObj->idctrans);
            
            $sqlobj= new SqlQueryBuilder("select");
            $sqlobj->setTable("t_catalogo_tipo_transaccion");
            $sqlobj->addColumn("idctrans");
            $sqlobj->addColumn("descripcion");
            $sqlobj->addColumn("active");
            $sqlobj->setWhere("idctrans=$idctrans");
            
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $TipoTransObj->idctrans=$row['idctrans'];
                    $TipoTransObj->descripcion=$row['descripcion'];
                    $TipoTransObj->active=$row['active'];
                }
            }            
            $this->mysqlconector->CloseDataBase();
            unset($result);
            unset($sqlobj);
        }
    }
    
    public function GetTipoTransByQuery($ListTipoTrans,$SQLBuilderObj){
        if(!empty($ListTipoTrans)){
            $this->mysqlconector->OpenConnection();
            
            $SQLBuilderObj->setTable("t_catalogo_tipo_transaccion");
            $SQLBuilderObj->addColumn("idctrans");
            $SQLBuilderObj->addColumn("descripcion");
            $SQLBuilderObj->addColumn("active");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($SQLBuilderObj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $tipotrans= new TipoTransaccion();
                    $tipotrans->idctrans=$row['idctrans'];
                    $tipotrans->descripcion=$row['descripcion'];
                    $tipotrans->active=$row['active'];
                    $ListTipoTrans->addItem($tipotrans);
                    unset($tipotrans);
                }
            }            
            $this->mysqlconector->CloseDataBase();
            unset($result);
            unset($sqlobj);
        }
    }
    
    public function __destruct() {
        unset($this->mysqlconector);
        unset($this->debug);
    }
    
    public function Activate($TipoTransObj){
        if(!empty($TipoTransObj)){
            $this->mysqlconector->OpenConnection();
            $idctrans=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransObj->idctrans);
            
            $sqlobj= new SqlQueryBuilder("update");
            $sqlobj->setTable("t_catalogo_tipo_transaccion");
            $sqlobj->addColumn("active");
            $sqlobj->addValue(1);
            $sqlobj->setWhere("idctrans=$idctrans");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
            
            $this->mysqlconector->CloseDataBase();
            unset($result);
            unset($sqlobj);
        }
    }
    public function Deactivate($TipoTransObj){
        if(!empty($TipoTransObj)){
            $this->mysqlconector->OpenConnection();
            $idctrans=mysqli_real_escape_string($this->mysqlconector->conn,$TipoTransObj->idctrans);
            
            $sqlobj= new SqlQueryBuilder("update");
            $sqlobj->setTable("t_catalogo_tipo_transaccion");
            $sqlobj->addColumn("active");
            $sqlobj->addValue(0);
            $sqlobj->setWhere("idctrans=$idctrans");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
            
            $this->mysqlconector->CloseDataBase();
            unset($result);
            unset($sqlobj);
        }
    }
    
    public function getlastid(){
        $id =0;
        $this->mysqlconector->OpenConnection();
        $sql="select max(idctrans) as lastid from t_catalogo_tipo_transaccion";
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
