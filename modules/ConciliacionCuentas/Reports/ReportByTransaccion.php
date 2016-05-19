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
 
 $view = "form";
if(!empty($_GET)){
    if(isset($_GET['view'])){
        $view=$_GET['view'];
    }
}

if(isset($_POST['applyfilter'])){
    $view=$_POST['view'];
    $mes=$_POST['mes'];
    $anio=$_POST['anio'];
    
    $Listofreport = new ArrayList();
    $_ADOConciliacion = new ADOConciliacion();
    //$_ADOConciliacion->debug=true;
    $_ADOConciliacion->GetReportByTransaction($Listofreport, $mes, $anio);
    //echo count($Listofreport);
}

 //obtiene años deacuerdo al actual
$ano= date("Y");
$anos=array();
for($i=2;$i>=0;$i--){
    $r=$ano-$i;
    $anos[]=$r;
    
}
for($e=1;$e<=3;$e++){
    $r=$ano+$e;
    $anos[]=$r;
    
}
 
?>
<?php include '../../../view/headinclude.php'?>
<?php 

if($view=="form"){    
    include '../../../view/menu.php';
}

?>
<h1>Reporte de Conciliaciones</h1>
<h3>Tipo de Transacciones</h3>
<?php 
    if($view=="form"){
        
?>
<div>
    <form id="formfilters" method="post" action="ReportByTransaccion.php">
        Mes: <select id="mes" name="mes" >
        <option value="1">01</option>
        <option value="2">02</option>
        <option value="3">03</option>
        <option value="4">04</option>
        <option value="5">05</option>
        <option value="6">06</option>
        <option value="7">07</option>
        <option value="8">08</option>
        <option value="9">09</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
    </select>
    A&ntilde;o:<select name="anio" id="sel_anio">
                <?php
                foreach($anos as $i){
                    if($i==$ano){
                        echo "<option selected>$i</option>";
                    }else{
                        echo "<option>$i</option>";
                    }
                }
                
                ?>
            </select>
    <button id="btnApplyFilter" name="applyfilter">Mostrar</button>&nbsp;&nbsp;<button id="PrintReport" name="applyfilter">Formato Para Imprecion</button>
    <input type="hidden" name="view" id="view" value="<?php echo $view;?>" />
</form>
</div>
<?php
    }
?>
<p>&nbsp;</p>
<?php
    include '../../../include/external/charts/chart.php';
    $datagastos=array();
    $dataingresos=array();
    
    foreach($Listofreport->array as $item){
        $valuegastos=array(
            "Transaccion"=>$item->descripcion,
            "Gasto"=>$item->cargos
        );
        $valuesingresos=array(
            "Transaccion"=>$item->descripcion,
            "Ingresos"=>$item->abonos
        );
        $datagastos[]=$valuegastos;
        $dataingresos[]=$valuesingresos;
    } 
   
?>
<h2>Gastos</h2>
<?php
    echo Chart::pie($datagastos, array('title'=>'Gastos del '.$mes.' del '.$anio,'height'=>400,'width'=>900));
?>
<table class="tableInfo" style="width: 300px; text-align: left; font-size: small; margin-left: 10%">
    <tr>
       <th>Tipo</th>
       <th>Gasto</th>
    </tr>
    <?php
        $totalcargos=0;
        foreach($Listofreport->array as $item){
            if($item->cargos>0){
            echo '<tr>';
            echo '<td>'. $item->descripcion .'</td>';
            echo '<td> $'. number_format($item->cargos, 2)  .'</td>';
            echo '</tr>';
            $totalcargos+=$item->cargos;
            }
        }
        echo '<tr>';
        echo '<th></th>';
        echo '<th>$'. number_format($totalcargos, 2) .'</th>';
        echo '</tr>';
    ?>
</table>
<div class="page-break"></div>
<h2>Ingresos</h2>
<?php
    echo Chart::pie($dataingresos, array('title'=>'Ingresos del '.$mes.' del '.$anio,'height'=>400,'width'=>900));
?>
<table class="tableInfo" style="width: 300px; text-align: left; font-size: small; margin-left: 10%">
    <tr>
       <th>Tipo</th>
       <th>Ingresos</th>
    </tr>
    <?php
        $totalingresos=0;
        foreach($Listofreport->array as $item){
            if($item->abonos>0){
            echo '<tr>';
            echo '<td>'. $item->descripcion .'</td>';
            echo '<td> $'. number_format($item->abonos, 2)  .'</td>';
            echo '</tr>';
            $totalingresos+=$item->abonos;
            }
        }
        echo '<tr>';
        echo '<th></th>';
        echo '<th>$'. number_format($totalingresos, 2) .'</th>';
        echo '</tr>';
    ?>
</table>

<script src="../../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    
    $('#PrintReport').click(function(){
     $('#view').val("print");
     $('#formfilters').attr("target","_blank");
     //$('#formfilters').submit();
     
     //return false;
 });
 
  $('#btnApplyFilter').click(function(){
    $('#formfilters').removeAttr("target");
    $('#view').val("form");
 });
</script>
<?php include '../../../view/footerinclude.php'?>
<?php

    unset($ListTipoTrans);
    unset($_ADOTipoTransaccion);
    unset($ListFacturas);
    unset($_ADOFactura);
    
?>