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

if(isset($_SESSION['FileTmp'])){
    $file=$_SERVER['DOCUMENT_ROOT'].$direccion. $_SESSION['FileTmp'];
    if($debug){
        echo $file."<br>";
    }
    $ListConciliacion = new ArrayList();
    $myfile = fopen($file, "r") or die("Unable to open file!");
     $frstline=fgets($myfile);
    while(!feof($myfile)) {
      $str= fgets($myfile);
      $str=  str_replace("\n", "", $str);
      $comp = preg_split("/[\t]/", $str);

      if(!empty($comp[0])){

          $conciliacion= new ConciliacionObj();
          $conciliacion->dia=$comp[0];
          $conciliacion->concepto=$comp[1];
          $conciliacion->cargo= str_replace(",", "", $comp[2]) ;
          $conciliacion->abono=str_replace(",", "", $comp[3]) ;
          $conciliacion->saldo=str_replace(",", "", $comp[4]) ;
          $ListConciliacion->addItem($conciliacion);
          if($debug){
              echo json_encode($conciliacion);
          }
      }
    }
    fclose($myfile);
    
    if(count($ListConciliacion->array)>0){
        $_SESSION['TmpConciliacion']= serialize($ListConciliacion);
    }
    
    if(!$debug){
         echo '<script type="text/javascript">document.location.href="SelectCuenta.php"</script>';
    }
    
}else{
    echo '<script type="text/javascript">document.location.href="../../../index.php"</script>';
}