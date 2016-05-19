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
 * Description of ADOConciliacion
 *
 * @author MarcoCantu
 */
class ADOConciliacion {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    public function __destruct() {
        unset($this->mysqlconector);
        unset($this->debug);
    }
    
    public function AddNew($ConciliacionObj){
        if(!empty($ConciliacionObj)){
           $this->mysqlconector->OpenConnection();
           $idconciliacion= mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idconciliacion);
           $dia=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->dia);
           $concepto=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->concepto);
           $cargo=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->cargo);
           $abono=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->abono);
           $saldo=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->saldo);
           $comentario=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->comentario);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idcuenta);
           $flag=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->flag);
           $mes=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->mes);
           $anio=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->anio);
           
           
           $sqlobj= new SqlQueryBuilder("insert");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->addColumn("dia");$sqlobj->addValue($dia);
           $sqlobj->addColumn("concepto");$sqlobj->addValue($concepto);
           $sqlobj->addColumn("cargo");$sqlobj->addValue($cargo);
           $sqlobj->addColumn("abono");$sqlobj->addValue($abono);
           $sqlobj->addColumn("saldo");$sqlobj->addValue($saldo);
           $sqlobj->addColumn("comentario");$sqlobj->addValue($comentario);
           $sqlobj->addColumn("idcuenta");$sqlobj->addValue($idcuenta);
           $sqlobj->addColumn("flag");$sqlobj->addValue($flag);
           $sqlobj->addColumn("mes");$sqlobj->addValue($mes);
           $sqlobj->addColumn("anio");$sqlobj->addValue($anio);
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
           $ConciliacionObj->idconciliacion=  $this->getlastid();
        }
    }
    public function Update($ConciliacionObj){
        if(!empty($ConciliacionObj)){
           $this->mysqlconector->OpenConnection();
           $idconciliacion= mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idconciliacion);
           $dia=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->dia);
           $concepto=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->concepto);
           $cargo=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->cargo);
           $abono=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->abono);
           $saldo=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->saldo);
           $comentario=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->comentario);
           $idcuenta=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idcuenta);
           $flag=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->flag);
           $mes=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->mes);
           $anio=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->anio);
           $idctrans=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idctrans);
           
           
           $sqlobj= new SqlQueryBuilder("update");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->addColumn("dia");$sqlobj->addValue($dia);
           $sqlobj->addColumn("concepto");$sqlobj->addValue($concepto);
           $sqlobj->addColumn("cargo");$sqlobj->addValue($cargo);
           $sqlobj->addColumn("abono");$sqlobj->addValue($abono);
           $sqlobj->addColumn("saldo");$sqlobj->addValue($saldo);
           $sqlobj->addColumn("comentario");$sqlobj->addValue($comentario);
           $sqlobj->addColumn("idcuenta");$sqlobj->addValue($idcuenta);
           $sqlobj->addColumn("flag");$sqlobj->addValue($flag);
           $sqlobj->addColumn("mes");$sqlobj->addValue($mes);
           $sqlobj->addColumn("anio");$sqlobj->addValue($anio);
           $sqlobj->setWhere("idconciliacion=$idconciliacion");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    public function Delete($ConciliacionObj){
        if(!empty($ConciliacionObj)){
           $this->mysqlconector->OpenConnection();
           $idcuenta=  mysqli_real_escape_string( $this->mysqlconector->conn,$ConciliacionObj->idcuenta);
           $anio=  mysqli_real_escape_string( $this->mysqlconector->conn,$ConciliacionObj->anio);
           $mes=  mysqli_real_escape_string( $this->mysqlconector->conn,$ConciliacionObj->mes);
           
           $sqlobj= new SqlQueryBuilder("delete");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->setWhere("idcuenta=$idcuenta and mes=$mes and anio=$anio");
           
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
           
           $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    public function GetAllConcil($ListConciliacionObj){
        
    }
    public function GetConcilbyID($ListConciliacionObj){
        
    }
    public function GetConcilByQuery($ListConciliacionObj,$SQLQueryBuilder){
      if(!empty($ListConciliacionObj) && !empty($SQLQueryBuilder)){
          $this->mysqlconector->OpenConnection();
           $SQLQueryBuilder->setTable("t_conciliacion");
           $SQLQueryBuilder->addColumn("dia");
           $SQLQueryBuilder->addColumn("concepto");
           $SQLQueryBuilder->addColumn("cargo");
           $SQLQueryBuilder->addColumn("abono");
           $SQLQueryBuilder->addColumn("saldo");
           $SQLQueryBuilder->addColumn("comentario");
           $SQLQueryBuilder->addColumn("idcuenta");
           $SQLQueryBuilder->addColumn("flag");
           $SQLQueryBuilder->addColumn("mes");
           $SQLQueryBuilder->addColumn("anio");
           $SQLQueryBuilder->addColumn("idconciliacion");
           $SQLQueryBuilder->addColumn("idctrans");
           
           
          if($this->debug){
                echo '<br/>'. $SQLQueryBuilder->buildQuery();
            }
            
           $result=  $this->mysqlconector->conn->query($SQLQueryBuilder->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
           if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $conciliacion = new ConciliacionObj();
                    $conciliacion->idcuenta=$row['idcuenta'];
                    $conciliacion->mes=$row['mes'];
                    $conciliacion->anio=$row['anio'];
                    $conciliacion->dia=$row['dia'];
                    $conciliacion->concepto=$row['concepto'];
                    $conciliacion->cargo=$row['cargo'];
                    $conciliacion->abono=$row['abono'];
                    $conciliacion->saldo=$row['saldo'];
                    $conciliacion->comentario=$row['comentario'];
                    $conciliacion->flag=$row['flag'];
                    $conciliacion->idconciliacion=$row['idconciliacion'];
                    $conciliacion->idctrans=$row['idctrans'];
                    $ListConciliacionObj->addItem($conciliacion);
                }
            }
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($SQLQueryBuilder);
      }  
    }
    
    public function SetTransactionId($idconciliacion,$TransactionID){
        if(!empty($TransactionID) && !empty($idconciliacion)){
           $this->mysqlconector->OpenConnection();
           $transid=mysqli_real_escape_string($this->mysqlconector->conn,$TransactionID);
           $idconcil=mysqli_real_escape_string($this->mysqlconector->conn,$idconciliacion);
           $sqlobj= new SqlQueryBuilder("update");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->addColumn("idctrans");$sqlobj->addValue($transid);
           $sqlobj->setWhere("idconciliacion=$idconcil");
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
           $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    
    public function SaveComment($ConciliacionObj){
        if(!empty($ConciliacionObj)){
           $this->mysqlconector->OpenConnection();
           $comentario=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->comentario);
           $idconciliacion=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idconciliacion);
           $sqlobj= new SqlQueryBuilder("update");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->addColumn("comentario");$sqlobj->addValue($comentario);
           $sqlobj->setWhere("idconciliacion=$idconciliacion");
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
           $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    public function MarkFlagTrue($ConciliacionObj){
        if(!empty($ConciliacionObj)){
           $this->mysqlconector->OpenConnection();
           $flag=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->flag);
           $idconciliacion=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idconciliacion);
           $sqlobj= new SqlQueryBuilder("update");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->addColumn("flag");$sqlobj->addValue(1);
           $sqlobj->setWhere("idconciliacion=$idconciliacion");
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
           $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    public function MarkFlagFalse($ConciliacionObj){
        if(!empty($ConciliacionObj)){
           $this->mysqlconector->OpenConnection();
           $flag=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->flag);
           $idconciliacion=mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idconciliacion);
           $sqlobj= new SqlQueryBuilder("update");
           $sqlobj->setTable("t_conciliacion");
           $sqlobj->addColumn("flag");$sqlobj->addValue(0);
           $sqlobj->setWhere("idconciliacion=$idconciliacion");
           if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            
           $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
           $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    
    public function AddReference($RelConcilFactura){
        if(!empty($RelConcilFactura)){
            $this->mysqlconector->OpenConnection();
            $idconcilref=  mysqli_real_escape_string($this->mysqlconector->conn,$RelConcilFactura->idconcilref);
            $idfactura=  mysqli_real_escape_string($this->mysqlconector->conn,$RelConcilFactura->idfactura);
            $comentario=  mysqli_real_escape_string($this->mysqlconector->conn,$RelConcilFactura->comentario);
            $idconciliacion=  mysqli_real_escape_string($this->mysqlconector->conn,$RelConcilFactura->idconciliacion);
            
            $sqlobj= new SqlQueryBuilder("insert");
            $sqlobj->setTable("t_conciliacion_referencias");
            $sqlobj->addColumn("idconciliacion");$sqlobj->addValue($idconciliacion);
            $sqlobj->addColumn("idfactura");$sqlobj->addValue($idfactura);
            $sqlobj->addColumn("comentario");$sqlobj->addValue($comentario);
           
            if($this->debug){
                 echo '<br/>'. $sqlobj->buildQuery();
             }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
            $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    
    public function RemoveReference($RelConcilFactura){
        if(!empty($RelConcilFactura)){
            $this->mysqlconector->OpenConnection();
            $idfactura=  mysqli_real_escape_string($this->mysqlconector->conn,$RelConcilFactura->idfactura);
            $idconciliacion=  mysqli_real_escape_string($this->mysqlconector->conn,$RelConcilFactura->idconciliacion);
            
            $sqlobj= new SqlQueryBuilder("delete");
            $sqlobj->setTable("t_conciliacion_referencias");
            $sqlobj->setWhere("idconciliacion=$idconciliacion and idfactura=$idfactura");
            if($this->debug){
                 echo '<br/>'. $sqlobj->buildQuery();
             }
            
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            
            $this->mysqlconector->CloseDataBase();
           unset($result);
           unset($sqlobj);
        }
    }
    
    public function GetConciliaciones($ListConciliacionObj){
     if(!empty($ListConciliacionObj)){
        $this->mysqlconector->OpenConnection();
        $sql="select distinct idcuenta,anio,mes from t_conciliacion;";
        $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()) {
                $conciliacion = new ConciliacionObj();
                $conciliacion->idcuenta=$row['idcuenta'];
                $conciliacion->mes=$row['mes'];
                $conciliacion->anio=$row['anio'];
                $ListConciliacionObj->addItem($conciliacion);
            }
        }
        $this->mysqlconector->CloseDataBase();
        unset($result);
        unset($conciliacion);
     }   
        
    }

    public function getlastid(){
        $id =0;
        $this->mysqlconector->OpenConnection();
        $sql="select max(idconciliacion) as lastid from t_conciliacion";
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
    
    public function GetReportByTransaction($ListOfReportTransSumObj,$mes,$anio){
        if(!empty($ListOfReportTransSumObj) && !empty($mes) and !empty($anio)){
        $this->mysqlconector->OpenConnection();
        $smes=  mysqli_real_escape_string($this->mysqlconector->conn,$mes);
        $sanio=mysqli_real_escape_string($this->mysqlconector->conn,$anio);
        $sql="select t_catalogo_tipo_transaccion.descripcion, sum(cargo) as cargos,sum(abono) as abonos"
                . " from t_conciliacion "
                . "left join t_catalogo_tipo_transaccion "
                . "on t_conciliacion.idctrans = t_catalogo_tipo_transaccion.idctrans "
                . "where mes=$smes and anio=$sanio "
                . "group by t_catalogo_tipo_transaccion.descripcion,mes,anio;";
        
        if($this->debug){
                 echo '<br/>'. $sql;
             }
        $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()) {
                $Reportobj = new ReportTransSumObj();
                $Reportobj->descripcion=$row['descripcion'];
                $Reportobj->cargos=$row['cargos'];
                $Reportobj->abonos=$row['abonos'];
                $ListOfReportTransSumObj->addItem($Reportobj);
            }
        }
        $this->mysqlconector->CloseDataBase();
        unset($result);
        unset($Reportobj);
     }   
    }
}
