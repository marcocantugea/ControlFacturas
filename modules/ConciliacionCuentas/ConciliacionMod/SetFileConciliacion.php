<!DOCTYPE html>
<!--
Copyright (C) 2016 MarcoCantu

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
-->
<?php
 include 'topinclude.php';
 
 //load session user
 $SessionUser= new UserObj();
 $SessionUser = unserialize($_SESSION['UserObj']);
 $SessionUser->GenerateToken();
 
 
?>
<?php include '../../../view/headinclude.php'?>
<?php include '../../../view/menu.php'?>
<h1>Conciliacion de Cuentas</h1>
<div id="AddnewReg" class="addnew">
    <h3>Selecione el Archivo de Movimientos Bancarios</h3>
    <form id="fileconuploader" enctype="multipart/form-data" name="filebanco" action="uploadFile.php" method="post" style="text-align: center">
    <input id="fileselected" type="file" name="fileselected" /><br/><br/>
    <button>Subir archivo</button>
</div>
<?php include '../../../view/footerinclude.php'?>