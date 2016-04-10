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
 * Description of ADOPagosFactura
 *
 * @author MarcoCantu
 */
class ADOPagosFactura {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    public function AddPago($PagoFacturaObj){
        if(!empty($PagoFacturaObj)){
            $this->AddPagoFactura($PagoFacturaObj);
            $_ADOFacturas=  new ADOFacturas();
            $_ADOFacturas->UpdateMontoActual($PagoFacturaObj);
            if($PagoFacturaObj->montoactual<=0){
                $PagoFacturaObj->FacturaObj->idestado=1;
                $_ADOFacturas->UpdateEstado($PagoFacturaObj->FacturaObj);
            }
        }
    }
    
    private function AddPagoFactura($PagoFacturaObj){
        if(!empty($PagoFacturaObj)){
            $this->mysqlconector->OpenConnection();
            //$idfacturapagos=  mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->idfacturapagos);
            $idfactura= mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->idfactura);
            $montoactual=  mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->montoactual);
            $pagoparcial=  mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->pagoparcial);
            $montoantespago= mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->montoantespago);
            $fechadepago= mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->fechadepago);
            $commentarios= mysqli_real_escape_string($this->mysqlconector->conn,$PagoFacturaObj->commentarios);
            $sql="insert into t_facturaspagos(idfactura,montoactual,pagoparcial,montoantespago,fechadepago,comentarios) values ("
                    ."$idfactura,$montoactual,$pagoparcial,$montoantespago,'$fechadepago','$commentarios')";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);

            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function getPagosByFacturaId($ListaPagosFacturaObj,$FacturaObj){
        if(!empty($ListaPagosFacturaObj) && !empty($FacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idfactura= mysqli_real_escape_string($this->mysqlconector->conn,$FacturaObj->idfactura);
            $sql="select idfactura,montoactual,pagoparcial,montoantespago,date_format(fechadepago,'%d - %b - %Y') as fechadepago,comentarios from t_facturaspagos where idfactura=$idfactura";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $Pagofactura= new PagoFacturaObj();
                     $Pagofactura->idfacturapagos=$row['idfacturapagos'];
                     $Pagofactura->montoactual=$row['montoactual'];
                     $Pagofactura->pagoparcial=$row['pagoparcial'];
                     $Pagofactura->montoantespago=$row['montoantespago'];
                     $Pagofactura->fechadepago=$row['fechadepago'];
                     $Pagofactura->comentarios=$row['comentarios'];
                     
                     $ListaPagosFacturaObj->addItem($Pagofactura);
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
    public function GetPagosByQuery($ListaPagosFacturaObj,$SQLQueryObj){
        if(!empty($ListaPagosFacturaObj) && !empty($SQLQueryObj)){
            $this->mysqlconector->OpenConnection();
            $sql=$SQLQueryObj->buildQuery();
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                     $Pagofactura= new PagoFacturaObj();
                     $Pagofactura->idfacturapagos=$row['idfacturapagos'];
                     $Pagofactura->idfactura=$row['idfactura'];
                     $Pagofactura->montoactual=$row['montoactual'];
                     $Pagofactura->pagoparcial=$row['pagoparcial'];
                     $Pagofactura->montoantespago=$row['montoantespago'];
                     $Pagofactura->fechadepago=$row['fechadepago'];
                     $Pagofactura->comentarios=$row['comentarios'];
                     
                     $ListaPagosFacturaObj->addItem($Pagofactura);
                 }
             }
            $this->mysqlconector->CloseDataBase();
        }
    }
    
}
