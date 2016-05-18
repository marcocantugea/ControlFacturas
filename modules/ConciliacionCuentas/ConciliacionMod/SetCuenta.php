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
//load session user
 $SessionUser= new UserObj();
 $SessionUser = unserialize($_SESSION['UserObj']);
 $SessionUser->GenerateToken();
 
 echo $_POST['cuenta'];
 
 if(!empty($_POST)){
    if(isset($_SESSION['TmpConciliacion'])){
        $idcuenta=$_POST['cuenta'];
        $mes=$_POST['mes'];
        $anio=$_POST['anio'];
        if($debug){
            echo $idcuenta."|".$mes."|".$anio."<br\>";
        }
       $ListConciliacion = unserialize($_SESSION['TmpConciliacion']);
       $_ADOConciliacion = new ADOConciliacion();
       $_ADOConciliacion->debug=$debug;
       foreach($ListConciliacion->array as $item){
           $item->anio=$anio;
           $item->mes=$mes;
           $item->idcuenta=$idcuenta;
           $_ADOConciliacion->AddNew($item);
           if($debug){
               echo '<br/>';
               echo json_encode($item);
           }
       }
       
       $_SESSION['TmpConciliacion']=  serialize($ListConciliacion);
       unset($ListConciliacion);
       unset($_ADOConciliacion);
       if(!$debug){
         echo '<script type="text/javascript">document.location.href="ConciliacionDatos.php"</script>';
       }
    } 
 }