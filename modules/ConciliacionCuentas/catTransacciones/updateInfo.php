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
$redirectpage="CatalogoTransManager.php";

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_POST)){
    if(isset($_POST['token']) && isset($_POST['update_descripcion']) && isset($_POST['update_active']) && isset($_POST['update_idctrans'])){
        $token=$_POST['token'];
        if($token==$SessionUser->token){
            $CatTrans= new TipoTransaccion();
            $CatTrans->idctrans=$_POST['update_idctrans'];
            $CatTrans->descripcion=$_POST['update_descripcion'];
            $CatTrans->active=$_POST['update_active'];
            
            $_ADOTipoTransaccion = new ADOTipoTransaccion();
            $_ADOTipoTransaccion->UpdateTipoTrans($CatTrans);
        }
    }
}

unset($_ADOTipoTransaccion);
unset($CatTrans);
unset($token);

if(!$debug){
    echo '<script type="text/javascript">document.location.href="'. $redirectpage .'"</script>';
}