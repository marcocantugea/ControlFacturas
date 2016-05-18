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

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['param']) && isset($_GET['token']) && isset($_GET['type'])){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $idcuenta=$_GET['param'];
            $type=$_GET['type'];
            
            if($idcuenta>0){
                $cuenta= new CuentaConcilObj();
                $cuenta->idcuenta=$idcuenta;
                $_ADOCuentasConsil= new ADOCuentasConsil();
                $_ADOCuentasConsil->debug=$debug;
                $_ADOCuentasConsil->GetCuentaByID($cuenta);
            }
            
            switch ($type){
                case "json": echo json_encode($cuenta);                break;
                case "text": echo $cuenta->idcuenta." ". $cuenta->cuenta." ". $cuenta->descripcion;break;
                case "csv": echo $cuenta->idcuenta.";". $cuenta->cuenta.";". $cuenta->descripcion;break;
                case "pipe": echo $cuenta->idcuenta."|". $cuenta->cuenta."|". $cuenta->descripcion;break;
            }
            
        }
    }
}

unset($token);
unset($idcuenta);
unset($type);
unset($cuenta);
unset($_ADOCuentasConsil);