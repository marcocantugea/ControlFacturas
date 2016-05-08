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
    
    $ListFacturas = new ArrayList();
    $_ADOFacturas = new ADOFacturas();
    $_ADOFacturas->getFacturas($ListFacturas);
    
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

<h1>Control de Facturas</h1>
<div id="controls"></div>
<div id="tableinfo">
    <table id="controlfacturas" class="tableInfo" style="width: 100%">
        <tr>
            <th colspan="4">&nbsp;</th>
        </tr>
        <?php 
        
            if(count($ListFacturas->array)==0){
                echo '<tr><td colspan="4">No ahi Facturas Registradas</td></tr>';
            }
            
            foreach($ListFacturas->array as $item){
                $pathfile= $config->domain."/".$config->pathServer.$item->archivoruta;
                $estado="";
                $item->getEstado();
                $estado=$item->EstadoFacturaObj->estado;
        ?>
        <tr>
            <td rowspan="2" style="text-align: center; width: 120px; padding-top: 10px; padding-bottom: 10px;">
                <a href="<?php echo $pathfile;?>" target="_blank"><img src="../../images/factura-incono.png" style="width: 40px; height: 40px;" /></a><br/>
                <a href="<?php echo $pathfile;?>" target="_blank">ver</a>
            </td>
            <td>Factura Numero:<strong> <?php echo $item->numerofactura;?></strong></td>
            <td>Fecha Factura: <strong><?php echo $item->fecha;?></strong></td>
            <td>Saldo : $ <strong><span id="saldo_<?php echo $item->idfactura;?>"> <?php echo number_format($item->montoactual,2,'.',',');?></span></strong></td>
        </tr>
        <tr>
            <td>Numero de Orden : <strong><?php echo $item->numeroorden;?></strong></td>
            <td>Vence: <strong><?php echo $item->vencimiento;?></strong></td>
            <td>Estado: <strong><?php echo $estado;?></strong> </td>

        </tr>
        <tr>
            
            <td colspan="2" style="padding-left: 15px;">
                <?php if($item->idestado==0){ ?>
                Pago Parcial : <input type="text" name="parcialpayment_<?php echo $item->idfactura;?>" value=""  style="width: 55px;">
                &nbsp;Fecha:<input id="dateparcial_<?php echo $item->idfactura;?>" type="text" name="dateparcial_<?php echo $item->idfactura;?>" value=""  style="width: 88px;">
                <button id="makepayment_<?php echo $item->idfactura;?>" style="height: 30px;" > Hacer Pago</button>
                <?php } ?>
            </td>
            <td> 
                <?php if($item->idestado==0){ ?>
                <button id="PayInvoice_<?php echo $item->idfactura;?>" style="height: 30px; background-color: darkred;color: white" >  Cerrar Factura</button>
                <!--<button id="AddComments_<?php echo $item->idfactura;?>" style="height: 30px;" > Agregar Comentario</button>-->
                <?php } ?>
            </td>
            <td><button id="viewDetail_<?php echo $item->idfactura;?>" style="height: 30px;" >  Detalle de Factura</button></td>
        </tr>
        <tr>
            <td colspan="4" >
                <div id="message_<?php echo $item->idfactura;?>" style="color: darkred; font-weight: bold; padding-left: 30px;" ></div>
                <hr />
            </td>
        </tr>
        <?php 
            }
        ?>
    </table>
</div>

<script src="../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">

$(function() {
        $("input[name^='dateparcial']" ).datepicker({dateFormat: "yy-mm-dd"});
    });

$("button[id^='makepayment']").click(function(){
    
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var pagoparcial=$("input[name='parcialpayment_"+idfactura+"']").val();
    var fechapago=$("input[name='dateparcial_"+idfactura+"']").val();
    var token="<?php echo $SessionUser->token;?>";
    var submitdata= true;
    var message="";
    //alert("pago="+pagoparcial+" fecha="+fechapago);
    $('#message_'+idfactura).html("");
    
    if(pagoparcial===""){
        submitdata=false;
        message=message+"Dato de Pago Vacio!<br />";
    }
    
    if(isNaN(pagoparcial)){
        submitdata=false;
         message=message+"Dato de Pago Invalido!<br />";
    }
    
    if(fechapago===""){
        submitdata=false;
        message=message+"Dato de Fecha Vacio!<br />";
    }
    
    
    if(submitdata){
        $.post(
                "AddPayment.php",
                {token:token,idfactura:idfactura,pagoparcial:pagoparcial,fechapago:fechapago},
                function(result){
                    //alert(result);
                   var valsplit=result;
                   var v=valsplit.split("|");
                   var montoactual=v[1];
                   var estado=v[2];
                   $('#saldo_'+idfactura).html(montoactual);
                   if(estado==1){
                       document.location.href="invoiceManager.php";
                   }
                    $('#message_'+idfactura).html("");
                   
                }
                );
        
        
    }else{
        $('#message_'+idfactura).html(message);
        
    }
    
    
    
    
});


$("button[id^='PayInvoice_']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var token="<?php echo $SessionUser->token;?>";
    
    $.post(
            "CloseInvoice.php",
            {token:token,idfactura:idfactura},
                function(result){
                    document.location.href="invoiceManager.php";
                }
            );
    
});

$("button[id^='AddComments']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var token="<?php echo $SessionUser->token;?>";
    
    document.location.href="viewInvoice.php?token="+token+"&param="+idfactura+"&action=comment";
});

$("button[id^='viewDetail']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var token="<?php echo $SessionUser->token;?>";
    
    document.location.href="viewInvoice.php?token="+token+"&param="+idfactura+"&action=view/edit";
});

</script>
<?php include '../../view/footerinclude.php';?>
<?php
        unset($ListFacturas);
        unset($_ADOFacturas);
?>