<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
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
$view = "form";
if(!empty($_GET)){
    if(isset($_GET['view'])){
        $view=$_GET['view'];
    }
}

if(isset($_POST['applyfilter'])){

    $view=$_POST['view'];
    $ListPagos = new ArrayList();
    $listofcolumns= array();
    $_ADOPagoFacturas = new ADOPagosFactura();
    
    $listofcolumns[]="idfactura";
    $listofcolumns[]="idfacturapagos";
    $listofcolumns[]="montoactual";
    $listofcolumns[]="pagoparcial";
    $listofcolumns[]="montoantespago";
    $listofcolumns[]="date_format(fechadepago,'%d - %b - %Y') as fechadepago";
    $listofcolumns[]="comentarios";
    
    $sqlquery = new SqlQueryBuilder("select");
    $sqlquery->setTable("t_facturaspagos");
    foreach($listofcolumns as $item){
        $sqlquery->addColumn($item);
    }
    $fechainicio=$_POST['fechainicio'];
    $fechafin=$_POST['fechafin'];
    $initialdate =$fechainicio." 00:00:00";
    $enddate = $fechafin." 00:00:00";
    $sqlquery->setWhere("(fechadepago between '$initialdate' and '$enddate')");
    $sqlquery->setOrderBy("fechadepago");
    $_ADOPagoFacturas->GetPagosByQuery($ListPagos, $sqlquery);
}

if($view=="form"){    
    include '../../view/menu.php';
}

?>

<h1>Reporte de Pagos Parciales</h1>
<?php 
    if($view=="form"){
        
?>

<div id="controls">
    <form id="formfilters" method="post" action="ReportePagosParciales.php">
    <input type="hidden" name="view" id="view" value="<?php echo $view;?>" />
    Del : <input type="text" name="fechainicio" id="fechainicio" value="<?php echo $fechainicio;?>"  style="width: 88px;"/>
    al : <input type="text" name="fechafin" id="fechafin" value="<?php echo $fechafin;?>" style="width: 88px;"/>
    <button id="btnApplyFilter" name="applyfilter">Buscar</button>
    <span style="margin-left: 30px;"><button id="PrintReport" name="applyfilter">Imprimir</button></span>
    </form>
    
</div>
<?php } ?>
<p>&nbsp;</p>
<table class="tableInfo" style="width: 100%; text-align: center; font-size: small">
    <tr>
        <th>Fecha de Pago</th>
        <th>Factura</th>
        <th>Orden</th>
        <th>Monto de pago</th>
    </tr>
    <?php
        if(isset($ListPagos)){
            $totaldepagos=0;
            $dialabel="";
            $diasubtotal=0;
            $subtotaldiadisp=0;
            $displaysubtotalday=false;
            foreach($ListPagos->array as $item){
                $item->GetFactura();
                $totaldepagos=$totaldepagos+$item->pagoparcial;
                if($dialabel==""){
                    $dialabel=$item->fechadepago;
                }
                
                if($dialabel==$item->fechadepago){
                    $diasubtotal=$diasubtotal+$item->pagoparcial;
                    $displaysubtotalday=false;
                }else{
                    $dialabel=$item->fechadepago;
                    //$diasubtotal=0;
                    $subtotaldiadisp=$diasubtotal;
                    $diasubtotal=0;
                    $diasubtotal=$diasubtotal+$item->pagoparcial;
                    $displaysubtotalday=true;
                }
                
                
    ?>
    <?php
        if($displaysubtotalday){
    ?>
    <tr>
        <th colspan="2" style="text-align: right; height: 30px;"></th>
        <th></th>
        <th  style="text-align: center"> $<?php echo number_format($subtotaldiadisp,2,'.',',');?></th>
    </tr>
        <?php }?>
    <tr>
        <td><?php echo $item->fechadepago;?></td>
        <td><?php echo $item->FacturaObj->numerofactura;?></td>
        <td><?php echo $item->FacturaObj->numeroorden;?></td>
        <td>$<?php echo number_format($item->pagoparcial,2,'.',',');?></td>
    </tr>
    <?php }?> 
    <?php 
        if($totaldepagos>0){
    ?>
    <tr>
        <th colspan="2" style="text-align: right"></th>
        <th>Total de pagos:</th>
        <th  style="text-align: center"> $<?php echo number_format($totaldepagos,2,'.',',');?></th>
    </tr>
    <?php }}?>
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