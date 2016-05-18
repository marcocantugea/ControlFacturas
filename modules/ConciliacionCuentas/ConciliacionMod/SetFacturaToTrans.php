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

$debug=true;
$redirectpage="ConciliacionDatos.php";

//load user session
if(isset($_SESSION['UserObj'])){
    $SessionUser= unserialize($_SESSION['UserObj']);
}
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['token']) && isset($_GET['param']) && isset($_GET['param2']) && isset($_GET['param3'])){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
           $relconcilfac= new RelConcilFacturaObj();
           $relconcilfac->idfactura=$_GET['param'];
           $relconcilfac->idconciliacion=$_GET['param2'];
           $relconcilfac->comentario=$_GET['param3'];
           $_ADOConciliacion = new ADOConciliacion();
           $_ADOConciliacion->debug=$debug;
           $_ADOConciliacion->AddReference($relconcilfac);
        }
    }
}

unset($_ADOConciliacion);
unset($relconcilfac);
unset($token);

if(!$debug){
    //echo '<script type="text/javascript">document.location.href="'. $redirectpage .'"</script>';
}