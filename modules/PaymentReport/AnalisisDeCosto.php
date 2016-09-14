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

$debug=false;

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

$valuestoshow=array();

if(!empty($_POST)){
    foreach ($_POST as $key => $value) {
        $pos=  strspn($key,"chk");
        if($pos>0){
            $strval=  explode("_", $key);
            $valuestoshow[]=$strval[1];
        }
    }
}

if($view=="form"){    
    include '../../view/menu.php';
}


 //obtiene años deacuerdo al actual
$ano= date("Y");

if(isset($_POST['anio'])){
    $ano=$_POST['anio'];
}

$anos=array();
for($i=2;$i>=0;$i--){
    $r=$ano-$i;
    $anos[]=$r;
    
}
for($e=1;$e<=3;$e++){
    $r=$ano+$e;
    $anos[]=$r;
    
}


/*
 * 
 * Get all Tipe of Transactions
 * 
 */
$ListTipoTrans = new ArrayList();
$_ADOTipoTransaccion= new ADOTipoTransaccion();
$_ADOTipoTransaccion->debug=$debug;
$_ADOTipoTransaccion->GetAllTipoTrans($ListTipoTrans);


/*
 * 
 * Get the Data
 * 
 */
$ListofData= new ArrayList();
$_ADOConciliacion= new ADOConciliacion();
$_ADOConciliacion->debug=$debug;
$_ADOConciliacion->GetDataAnalisisGastosIngresos($ListofData,$ano);

?>

<h1>Analisis de Ingresos y Gastos</h1>
<div id="controls" class="checkboxes">
    <form id="formfilters" method="post" action="AnalisisDeCosto.php">
        <div style="margin: 10px;">
            A&ntilde;o: &nbsp;<select name="anio" id="sel_anio" style="font-size: xx-large;">
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
        </div>
        <fieldset>
        <input type="hidden" name="view" id="view" value="<?php echo $view;?>" />
        <legend>Tipos de Ingresos y Gastos</legend>
        <table style="font-size: medium;">
            <tr>
            <?php
                $trow=0;
                foreach($ListTipoTrans->array as $item){
                    $checkeditem='';
                    if(count($valuestoshow)>0){
                        if(in_array($item->idctrans, $valuestoshow)){
                            $checkeditem='checked="true"';
                        }
                    }
            ?>
           
                <td>
                    <input type="checkbox" name="chk_<?php echo $item->idctrans?>" value="<?php echo $item->descripcion?>" <?php echo $checkeditem;?> /><?php echo $item->descripcion?> <br />
                </td>
               
                
            <?php 
                 $trow+=1;
                 if($trow==5){
                     echo ' </tr><tr>';
                      $trow=1;
                 }
                }?>
            <tr>
        </table>
        </fieldset>
        <br/>
        <button>Mostrar Informaci&oacute;n</button>
    </form>
</div>
<p>&nbsp;</p>
<?php
    include '../../include/external/charts/chart.php';
    $datagastos=array();
    $dataingresos=array();
    $roles=  array(
        "role"=>"type: 'number', role: 'annotation'"
    );
   $valuedata;
    foreach($ListofData->array as $item){
        if(in_array($item->idctrans, $valuestoshow)){
            $mes=" ".$item->mes."-".$item->anio." ";
            $valuedata=array(
                "Concepto"=>$item->description,
                "Monto"=>$item->monto,
                "role"=>$item->monto
                //"Mes"=>$mes
                //

            );
            $dataval[]=$valuedata;
        }
    } 
   
?>

<?php
    $colors=array('blue','#e6693e','#ec8f6e','#f3b49f','#f6c7b6');
    
    $options=array('title'=>'Analisis de Gastos y Informacion','height'=>400,'width'=>900,'colors'=>$colors,"hAxis"=> "{textStyle:{fontSize:12}}");
    $chart= new Chart();
    $chart->setRoles($roles);
    if(isset($dataval)){
        echo $chart->column($dataval,$options );
    }
?>

<?php include '../../view/footerinclude.php';?>
<?php

?>

