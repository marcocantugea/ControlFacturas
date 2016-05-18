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

if(isset($_SESSION['UserObj'])){
//verifica si se subio el archivo correctamente
    if($_FILES['fileselected']['error']>0){
        echo "Error al subir el archivo codigo: ".$_FILES['fileselected']['error']."<br />";
    }else{
        $kb=(int)$_FILES['fileselected']['size'];
        $l_kb= round($kb/1024);
        if($debug){
            echo "Name: " . $_FILES['fileselected']['name']."<br/>";
            echo "Ext: " . $_FILES['fileselected']['type']."<br/>";
            echo "Size: ".$_FILES['fileselected']['size']."($l_kb KB) <br/>";
            echo "Ruta TMP: ".$_FILES['fileselected']['tmp_name']."<br/>";
        }
        $carpeta_dest = "./files/";
        $filefullname=$carpeta_dest.$_FILES['fileselected']['name'];
        copy($_FILES['fileselected']['tmp_name'], $filefullname);
        if(isset($_SESSION['FileTmp'])){
            unset($_SESSION['FileTmp']);
        }
        $_SESSION['FileTmp']="/modules/ConciliacionCuentas/ConciliacionMod/files/".$_FILES['fileselected']['name'];
        //echo "/modules/InvoiceManager/invoices/".$_FILES['archivo']['name'];
    }
}

unset($debug);
unset($kb);
unset($l_kb);
unset($carpeta_dest);
unset($filefullname);


echo '<script type="text/javascript" > document.location.href="processFile.php"</script>';