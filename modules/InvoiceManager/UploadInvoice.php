<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    include 'topinclude.php';
    
    $SessionUser = new UserObj();
    if(isset($_SESSION['UserObj'])){
        $SessionUser= unserialize($_SESSION['UserObj']);
    }else{
        echo '<script type="text/javascript">document.location.href="../../index.php"</script>';
    }
    $SessionUser->GenerateToken();
    
    
?>
<?php include '../../view/headinclude.php';?>
<?php include '../../view/menu.php';?>

<h1>Agregar Nueva Factura</h1>
<h3>Seleciona el archivo PDF de la factura</h3>
<div class="addnew" style="padding: 30px; width: calc(100%-30px); margin-left: 0;">
    <form id="invoiceuplader" enctype="multipart/form-data" name="invoiceuplader" action="uploadFactura.php" method="post" style="text-align: center">
    <input id="fileselected" type="file" name="fileselected" accept="image/x-eps,application/pdf" /><br/><br/>
    <button>Subir Factura</button>
</form>
</div>
<?php include '../../view/footerinclude.php';?>