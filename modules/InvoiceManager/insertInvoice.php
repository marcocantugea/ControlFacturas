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

$debug = false;

if(!empty($_POST)){
    if(isset($_POST['token'])){
        $token=$_POST['token'];
        if($token==$SessionUser->token){
            $newfactura = new FacturaObj();
            $newfactura->fecha= $_POST['fecha'];
            $newfactura->numerofactura=$_POST['numerofactura'];
            $newfactura->numeroorden=$_POST['numeroorden'];
            $newfactura->monto=$_POST['monto'];
            $newfactura->montoactual=$_POST['monto'];
            $newfactura->idestado=0;
            $newfactura->vencimiento=$_POST['vencimiento'];
            $newfactura->archivoruta=$_POST['archivoruta'];
            
            $_ADOFacturas = new ADOFacturas();
            
            $_ADOFacturas->AddFactura($newfactura);
            
            if(isset($_SESSION['InvoiceTmp'])){
                unset($_SESSION['InvoiceTmp']);
            }
            
            echo 'true';
        }else{
            echo 'Token Invalido!';
        }
    }
}

unset($debug);
unset($token);
unset($newfactura);
unset($_ADOFacturas);

