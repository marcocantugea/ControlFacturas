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

include "topinclude.php";

$debug=false;
$SessionUser= new UserObj();
if(isset($_SESSION['UserObj'])){
    $SessionUser= unserialize($_SESSION['UserObj']);
}
$SessionUser->GenerateToken();

if(!empty($_POST)){
    if(isset($_POST['token']) && isset($_POST['idfactura']) ){
        $token=$_POST['token'];
        if($token==$SessionUser->token){
            $pagofactura= new PagoFacturaObj();
            $pagofactura->idfactura=$_POST['idfactura'];
            $pagofactura->GetFactura();
            $pagofactura->pagoparcial = $pagofactura->FacturaObj->montoactual;
            $pagofactura->fechadepago = date("Y-m-d");
            if($pagofactura->FacturaObj->montoactual>0){
                $pagofactura->RecalculaMontoFactura();             
                $_ADOPagoFacturas = new ADOPagosFactura();
                $_ADOPagoFacturas->AddPago($pagofactura);
               echo 'return|'.$pagofactura->montoactual."|".$pagofactura->FacturaObj->idestado;
            }
            
        }else{
            echo 'Error|Invalid Token!|0';
        }
    }
}

unset($debug);
unset($token);
unset($pagofactura);
unset($_ADOPagoFacturas);