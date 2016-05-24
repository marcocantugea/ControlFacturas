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
 * Description of ADOFacturas
 *
 * @author MarcoCantu
 */
class ADOFacturas {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    public function AddFactura($FacturaObj){
        if(!empty($FacturaObj)){
            $this->mysqlconector->OpenConnection();
            $fecha=  mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->fecha);
            $monto= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->monto);
            $archivoruta=  mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->archivoruta);
            $vencimiento=  mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->vencimiento);
            $montoactual= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->montoactual);
            $idestado= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->idestado);
            $numerofactura= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->numerofactura);
            $numeroorden= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->numeroorden);
            $sql="insert into t_facturas(fecha,monto,archivoruta,vencimiento,montoactual,idestado,numerofactura,numeroorden) values ("
                    ."'$fecha',$monto,'$archivoruta','$vencimiento',$montoactual,0,$numerofactura,$numeroorden)";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function getFacturas($ListOfFacturaObj){
        if(!empty($ListOfFacturaObj)){
            $this->mysqlconector->OpenConnection();
            $sql="select idfactura,date_format(fecha,'%d - %b - %Y') as fecha,monto,archivoruta,date_format(vencimiento,'%d - %b - %Y') as vencimiento,montoactual,idestado,numerofactura,numeroorden,customer_id,customer_name from t_facturas order by numerofactura desc;";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $factura= new FacturaObj();
                     $factura->idfactura=$row['idfactura'];
                     $factura->fecha= $row['fecha'];
                     $factura->monto=$row['monto'];
                     $factura->archivoruta=$row['archivoruta'];
                     $factura->vencimiento=$row['vencimiento'];
                     $factura->montoactual=$row['montoactual'];
                     $factura->idestado=$row['idestado'];
                     $factura->numerofactura=$row['numerofactura'];
                     $factura->numeroorden= $row['numeroorden'];
                     $factura->customer_id=$row['customer_id'];
                     $factura->customer_name=$row['customer_name'];
                     $ListOfFacturaObj->addItem($factura);
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    public function getFacturasById($FacturaObj){
        if(!empty($FacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idfactura=  mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->idfactura);
            $sql="select idfactura,date_format(fecha,'%d - %b - %Y') as fecha,monto,archivoruta,date_format(vencimiento,'%d - %b - %Y') as vencimiento,montoactual,idestado,numerofactura,numeroorden,customer_id,customer_name from t_facturas where idfactura=$idfactura ;";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $FacturaObj->idfactura=$row['idfactura'];
                     $FacturaObj->fecha= $row['fecha'];
                     $FacturaObj->monto=$row['monto'];
                     $FacturaObj->archivoruta=$row['archivoruta'];
                     $FacturaObj->vencimiento=$row['vencimiento'];
                     $FacturaObj->montoactual=$row['montoactual'];
                     $FacturaObj->idestado=$row['idestado'];
                     $FacturaObj->numerofactura=$row['numerofactura'];
                     $FacturaObj->numeroorden= $row['numeroorden'];
                     $FacturaObj->customer_id=$row['customer_id'];
                     $FacturaObj->customer_name=$row['customer_name'];
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function UpdateMontoActual($PagoFacturaObj){
        if(!empty($PagoFacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idfactura=  mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->idfactura);
            $montoactual=  mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->montoactual);
            $sql="update t_facturas set montoactual=$montoactual where idfactura=$idfactura";
            
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            $this->mysqlconector->CloseDataBase();
        }
    }
    public function UpdateEstado($FacturaObj){
        if(!empty($FacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idfactura=  mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->idfactura);
            $idestado=  mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->idestado);
            $sql="update t_facturas set idestado=$idestado where idfactura=$idfactura";
            
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    
    public function GetFacturasByQuery($ListFacturaObj,$SqlQueryBuilder){
        if(!empty($ListFacturaObj) && !empty($SqlQueryBuilder)){
            $this->mysqlconector->OpenConnection();
            $sql=$SqlQueryBuilder->buildQuery();
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $factura= new FacturaObj();
                     $factura->idfactura=$row['idfactura'];
                     $factura->fecha= $row['fecha'];
                     $factura->monto=$row['monto'];
                     $factura->archivoruta=$row['archivoruta'];
                     $factura->vencimiento=$row['vencimiento'];
                     $factura->montoactual=$row['montoactual'];
                     $factura->idestado=$row['idestado'];
                     $factura->numerofactura=$row['numerofactura'];
                     $factura->numeroorden= $row['numeroorden'];
                     $factura->customer_id=$row['customer_id'];
                     $factura->customer_name=$row['customer_name'];
                     $ListFacturaObj->addItem($factura);
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function GetFacturasToConciliate($ListFacturaObj){
        if(!empty($ListFacturaObj)){
            $this->mysqlconector->OpenConnection();
            $sql="select t_facturas.idfactura,date_format(fecha,'%d - %b - %Y') as fecha,numerofactura,monto from t_facturas left join t_conciliacion_referencias on t_conciliacion_referencias.idfactura=t_facturas.idfactura where idestado=1 and idconcilref is null;";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $factura= new FacturaObj();
                     $factura->idfactura=$row['idfactura'];
                     $factura->fecha= $row['fecha'];
                     $factura->monto=$row['monto'];
                     $factura->numerofactura=$row['numerofactura'];
                     $ListFacturaObj->addItem($factura);
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function GetFacturasConciliadas($ConciliacionObj,$ListFacturaObj){
        if(!empty($ConciliacionObj) && !empty($ListFacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idconciliacion=  mysqli_real_escape_string($this->mysqlconector->conn,$ConciliacionObj->idconciliacion);
            $sql="select t_facturas.idfactura,date_format(fecha,'%d-%b-%Y') as fecha,numerofactura,monto from t_facturas inner join t_conciliacion_referencias on t_conciliacion_referencias.idfactura=t_facturas.idfactura where t_conciliacion_referencias.idconciliacion=$idconciliacion;";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $factura= new FacturaObj();
                     $factura->idfactura=$row['idfactura'];
                     $factura->fecha= $row['fecha'];
                     $factura->monto=$row['monto'];
                     $factura->numerofactura=$row['numerofactura'];
                     $ListFacturaObj->addItem($factura);
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function SetCustomer($FacturaObj){
        if(!empty($FacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idfactura = mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->idfactura);
            $customer_id= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->customer_id);
            $customer_name= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->customer_name);
            
            $sql = new SqlQueryBuilder("update");
            $sql->setTable("t_facturas");
            $sql->addColumn("customer_id");$sql->addValue($customer_id);
            $sql->addColumn("customer_name");$sql->addValue($customer_name);
            $sql->setWhere("idfactura=$idfactura");
            
            if($this->debug){
                echo '<br />'. $sql->buildQuery();
            }
            
            $result=  $this->mysqlconector->conn->query($sql->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);            
            $this->mysqlconector->CloseDataBase();
            
            unset($result);
            unset($sql);
        }
    }
}
