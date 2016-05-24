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

include 'topinclude.php';
$debug=false;
$SessionUser= new UserObj();
$SessionUser = unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['token'])){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $factura = new FacturaObj();
            $factura->idfactura= $_GET['param'];
            $factura->customer_id = $_GET['param2'];
            $factura->customer_name=$_GET['param3'];

            if($debug){
                echo json_encode($factura);
            }
            
            $_ADOFactura = new ADOFacturas();
            $_ADOFactura->debug=$debug;
            $_ADOFactura->SetCustomer($factura);
            
        }else{
            echo 'Token Invalido!';
        }
    }
}

unset($debug);
unset($token);
unset($newfactura);
unset($_ADOFacturas);

