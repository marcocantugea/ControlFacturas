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
include '../../view/headinclude.php';
 
$SessionUser = new UserObj();
if(isset($_SESSION['UserObj'])){
    $SessionUser= unserialize($_SESSION['UserObj']);
}else{
    echo '<script type="text/javascript">document.location.href="../../index.php"</script>';
}
$SessionUser->GenerateToken();
$montodebe=0;
$montopagado=0;

//load customers
$ListofCustomers = new ArrayList();
$_ADOOCClass = new ADOOCClass();
$_ADOOCClass->getAllCustomers($ListofCustomers);

$view = "form";
if(!empty($_GET)){
    if(isset($_GET['view'])){
        $view=$_GET['view'];
    }
}

if(isset($_POST['applyfilter'])){

    $view=$_POST['view'];
    $selcustid=$_POST['customer_selector'];
    //Load Customer Selected
    $customer = new OCCustomerObj();
    foreach($ListofCustomers->array as $item){
        if($item->customer_id==$selcustid){
            $customer=$item;
        }
    }
    $listoffacturas = new ArrayList();
    $_ADOFactura = new ADOFacturas();
    $_ADOFactura->getCustomerFacturas($listoffacturas,$customer->customer_id);
    $customer->Listfacturas=$listoffacturas;
    
}

if($view=="form"){    
    include '../../view/menu.php';
}

?>

<h1>Reporte de Facturas por Clientes</h1>
<?php 
    if($view=="form"){
        
?>

<div id="controls">
    <form id="formfilters" method="post" action="FacturasPorCliente.php">
    <input type="hidden" name="view" id="view" value="<?php echo $view;?>" />
    Cliente: <select id="customer_selector" name="customer_selector">
        <?php
            foreach($ListofCustomers->array as $item){
                $selected="";
                if($item->customer_id==$selcustid){
                    $selected="selected";
                }
                echo '<option value="'.$item->customer_id.'" '.$selected.'>'.$item->firstname.' '.$item->lastname.'</option>';
            }
        ?>
    </select>
    <button id="btnApplyFilter" name="applyfilter">Buscar</button>
    <span style="margin-left: 30px;"><button id="PrintReport" name="applyfilter">Imprimir</button></span>
    </form>
    
</div>
<?php } ?>
<p>&nbsp;</p>
<table class="tableInfo" style="width: 100%; text-align: center; font-size: small">
    <tr>
        <th colspan="4">
            <h3>Estado de Cliente : <?php echo $customer->firstname.' '.$customer->lastname?></h3>
        </th>
    </tr>
    <tr>
        <td colspan="4">
            <h3 style="text-align: center">Facturas Pendientes</h3>
        </td>
    </tr>
    <tr>
        <th>Factura</th>
        <th>Fecha de Factura</th>
        <th>Fecha de Vencimiento</th>
        <th>Monto</th>
    </tr>
    <?php
    if (isset($listoffacturas)){
        
        foreach($listoffacturas->array as $item){
            if($item->idestado==0){
                $montodebe+=$item->monto;
                echo '<tr>';
                echo '<td>'. $item->numerofactura .'</td>';
                echo '<td>'. $item->fecha.'</td>';
                echo '<td>'. $item->vencimiento.'</td>';
                echo '<td>$'.number_format($item->monto,2).'</td>';
                echo '</tr>';
            }
        }
    }else{
        echo '<tr>';
            echo '<td colspan="4"></td>';
            echo '</tr>';
    }
    ?>
    <tr>
        <th></th>
        <th colspan="2" style="text-align: right"> Monto Total&nbsp;&nbsp;</th>
        <th><?php echo '$'. number_format($montodebe,2)?></th>
        
    </tr>
    <tr>
        <td colspan="5">
            <h3 style="text-align: center">Facturas Pagadas</h3>
        </td>
    </tr>
    <tr>
        <th>Factura</th>
        <th>Fecha de Factura</th>
        <th>Fecha de Vencimiento</th>
        <th>Monto</th>
    </tr>
    <?php
    if (isset($listoffacturas)){
        
        foreach($listoffacturas->array as $item){
            if($item->idestado==1){
                $montopagado+=$item->monto;
                echo '<tr>';
                echo '<td>'. $item->numerofactura .'</td>';
                echo '<td>'. $item->fecha.'</td>';
                echo '<td>'. $item->vencimiento.'</td>';
                echo '<td>$'.number_format($item->monto,2).'</td>';
                echo '</tr>';
            }
        }
    }else{
        echo '<tr>';
            echo '<td colspan="5"></td>';
            echo '</tr>';
    }
    ?>
    <tr>
        <th></th>
        <th colspan="2" style="text-align: right"> Monto Total&nbsp;&nbsp;</th>
        <th><?php echo '$'. number_format($montopagado,2)?></th>
    </tr>
</table>

<script src="../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
        $("input[name^='fecha']" ).datepicker({dateFormat: "yy-mm-dd"});
    });
 
 $('#btnApplyFilter').click(function(){
    $('#formfilters').removeAttr("target");
    $('#view').val("form");
 });
 
 $('#PrintReport').click(function(){
     $('#view').val("print");
     $('#formfilters').attr("target","_blank");
     //$('#formfilters').submit();
     
     //return false;
 });
 
 
 $('#buttonReturn').click(function(){
     $('#view').val("form");
     $('#formfilters').submit();
 });
</script>
<?php include '../../view/footerinclude.php';?>

<?php



?>