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
    
    $allowtoview=true;
    
    $SessionUser = new UserObj();
    if(isset($_SESSION['UserObj'])){
        $SessionUser= unserialize($_SESSION['UserObj']);
    }else{
        echo '<script type="text/javascript">document.location.href="../../index.php"</script>';
    }
    $SessionUser->GenerateToken();
    
    if(!empty($_GET)){
        if(isset($_GET['token']) && isset($_GET['param']) && isset($_GET['action'])){
            $token=$_GET['token'];
            if($token==$SessionUser->token){
                $action=$_GET['action'];
                $factura = new FacturaObj();
                $factura->idfactura=$_GET['param'];
                $_ADOFacturas = new ADOFacturas();
                $_ADOFacturas->getFacturasById($factura);
                $factura->getEstado();
                $pathfile= $config->domain."/".$config->pathServer.$factura->archivoruta;
                $estado =$factura->EstadoFacturaObj->estado;
                $factura->getPagosFactura();
                
                //Load the customers
                $ListofCustomers = new ArrayList();
                $_ADOOCClass = new ADOOCClass();
                //$_ADOOCClass->debug=true;
                $_ADOOCClass->getAllCustomers($ListofCustomers);
                //echo var_dump($ListofCustomers);
                
            }else{
                $allowtoview=false;
            }
        }else{
            $allowtoview=false;
        }
    }else{
        $allowtoview=false;
    }
    
    if($allowtoview==false){
        echo '<script type="text/javascript">document.location.href="../../index.php"</script>';
    }
    
    /**
     * Modificado el 4 Julio 2016
     * Poder modificar el monto de la factura si no ahi ningun pago regalizado
     * Marco Cantu
     * ***/
    
    $ModifyMontoFactura=false;
    $NewMontoFactura=0;
    if($factura->monto==$factura->montoactual){
        $ModifyMontoFactura=true;
    }
    
?>

<?php include '../../view/headinclude.php';?>
<?php include '../../view/menu.php';?>
<h1>Detalle de datos de Factura</h1>
<div id="controls"></div>
<div id="tableinfo">
    <table id="controlfacturas" class="tableInfo" style="width: 100%;">
        <tr >
            <th colspan="4">&nbsp;</th>
        </tr>

        <tr>
            <td >Factura Numero:<strong > <?php echo $factura->numerofactura;?></strong></td>
            <td>Fecha Factura: <strong><?php echo $factura->fecha;?></strong></td>
            <?php
                /**
                 * Modificacion 4 de Julio 2016
                 * Validar si se agrega la opcion de modificar monto.
                 * si la bandera es verdadera agrega la opcion para modificar el monto de la factura
                 * en caso de que ya existan pagos solo se mostrara el monto restante.
                 * **/
                 if ($ModifyMontoFactura){
            ?>
            <td>Monto Factura:<input type="text" id="txtMontoFactura" name="MontoFactura" style=" width: 70px;" value="<?php echo $factura->monto?>" /> <button id="btnModificaMontoFactura_<?php echo $factura->idfactura?>">Modificar</button></td>
            <?php 
            
                 }else{ 
            ?>
            <td>Saldo : $ <strong><span id="saldo_<?php echo $factura->idfactura;?>"> <?php echo number_format($factura->montoactual,2,'.',',');?></span></strong></td>
            
            <?php  
            
                 }
            ?>
            <td></td>
        </tr>
        <tr>
            <td>Numero de Orden : <strong><?php echo $factura->numeroorden;?></strong></td>
            <td>Vence: <strong><?php echo $factura->vencimiento;?></strong></td>
            <td>Estado: <strong><?php echo $estado;?></strong> </td>
            <td></td>

        </tr>
        <tr>
            
            <td style="padding-left: 15px;">
                <?php if($factura->idestado==0){ ?>
                Pago Parcial : <input type="text" name="parcialpayment_<?php echo $factura->idfactura;?>" value=""  style="width: 55px;">
                &nbsp;Fecha:<input id="dateparcial_<?php echo $factura->idfactura;?>" type="text" name="dateparcial_<?php echo $factura->idfactura;?>" value=""  style="width: 88px;">
                <button id="makepayment_<?php echo $factura->idfactura;?>" style="height: 30px;" > Hacer Pago</button>
                <?php } ?>
            </td>
            <td>
                
                Cliente: <select id="customer_selector_<?php echo $factura->idfactura;?>" style="font-size: larger;">
                    <?php 
                        foreach ($ListofCustomers->array as $items){
                            $selected="";
                            if($factura->customer_id===$items->customer_id){
                                $selected="selected";
                            }
                            echo '<option value="'.$items->customer_id.'" '.$selected.'>'.$items->firstname.' '.$items->lastname.'</option>';
                        }
                    ?>
                </select>
            </td>
            <td> 
                <?php if($factura->idestado==0){ ?>
                <button id="PayInvoice_<?php echo $factura->idfactura;?>" style="height: 30px; background-color: darkred;color: white" >  Cerrar Factura</button>
                <?php } ?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td  style="padding-left: 30px; width: 50%; " >
                <h3>Detalle de Pagos</h3>
                <table id="tpartialpayments" style="width: 450px;">
                    <tr>
                        <th>Fecha de Pago</th>
                        <th>Pago Parcial</th>
                        <th>Monto de Factura</th>
                        <th>Saldo</th>
                    </tr>
                    <?php 
                        foreach ($factura->ListPagosFactura->array as $item){
                    ?>
                    <tr>
                         <?php
                            /**
                             * Modificacion 31 de Agosto 2016
                             * Validar si se agrega la opcion de modificar monto.
                             * si la bandera es verdadera agrega la opcion para modificar la fecha  del pago
                             * 
                             * **/
                             if (!$ModifyMontoFactura){
                        ?>
                        <td><input id="pagoparcial_<?php echo $item->idfacturapagos?>" name="pagoparcial_<?php echo $item->idfacturapagos?>" value="<?php echo $item->fechadepago?>" style="width:125px;"> </td>
                        <?php }else{ ?>
                        <td><?php echo $item->fechadepago?></td>
                        <?php }?>
                        <td><?php echo number_format($item->pagoparcial,2,'.',',');?></td>
                        <td><?php echo number_format($item->montoantespago,2,'.',',');?></td>
                        <td><?php echo number_format($item->montoactual,2,'.',',');?></td>
                    </tr>
                    <?php } ?>
                </table>
            </td>
            <td></td>
            <td></td>
            <td></td>
<!--            <td colspan="2" style="padding-left: 10%; ">
                <textarea style="width: 50%; height: 50px;" id="comment" name="text" ></textarea><br/>
                <button style="width: 50%;" >Guardar Comentarios</button><br />
                <table style="width: 50%;">
                    <tr>
                        <th>Fecha</th>
                        <th>Comentario</th>
                    </tr>
                </table>
                
            </td>
-->
        </tr>
    </table>
</div>
<div style="margin: 0 auto; width : 80%; height: 1500px;">
    <object data="<?php echo $pathfile;?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH" 
        type="application/pdf" 
        width="100%" 
       height="75%">
</object>
</div>

<script src="../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
$('table tr td strong').css("padding-left","20px")

$('#tpartialpayments td').css("text-align","center");

$(function() {
        $("input[name^='dateparcial']" ).datepicker({dateFormat: "yy-mm-dd"});
        $("input[name^='pagoparcial']" ).datepicker({dateFormat: "yy-mm-dd"});
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
                   /*var valsplit=result;
                   var v=valsplit.split("|");
                   var montoactual=v[1];
                   var estado=v[2];
                   $('#saldo_'+idfactura).html(montoactual);
                   if(estado==1){
                       document.location.href="invoiceManager.php";
                   }
                    $('#message_'+idfactura).html("");
                    */
                   document.location.reload();
                   
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
                    document.location.reload();
                }
            );
    
});


// Added 24 may 2016
$('select[id^=customer_selector_]').change(function(){
   var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[2];
    var customer_id=$(this).val();
    var customer_name=$('select[id^=customer_selector_] option:selected').text();
    SetCustomer(idfactura,customer_id,customer_name);
});

function SetCustomer(param,param2,param3){
    //alert('setCustomerToInvoice.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2+'&param3='+param3);
    $.ajax({
            url:'setCustomerToInvoice.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2+'&param3='+param3,
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                //alert(obj1);
            }
            
    });
}

/**
* Modificacion 4 Julio 2016
* 
* Accion del boton de modificar factura
* llama funcion de ajax para modificar monto.
* 
* **/
$("button[id^='btnModificaMontoFactura']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var newmonto=$('#txtMontoFactura').val();
    UpdateMontoFactura(idfactura,newmonto);
});

function UpdateMontoFactura(param1,param2){
    //alert('UpdateAmountInvoice.php?param1='+param1+'&token=<?php echo $SessionUser->token;?>&param2='+param2+'')
    $.ajax({
            url:'UpdateAmountInvoice.php?param1='+param1+'&token=<?php echo $SessionUser->token;?>&param2='+param2+'',
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                //alert(obj1);
            }
            
    });
}

/**
* Modificacion 31 Agosto actualizacion de fecha en pago 
* 
* Accion para actualizar la fecha en el pago si esta erroneo
*
*/
$("input[name^='pagoparcial']").datepicker({
    onSelect:function(selected){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        var idpagofactura=val[1];
        var newvalue=selected;
        var anio= newvalue.substring(6,10);
        var mes= newvalue.substring(1,2);
        var dia= newvalue.substring(3,5);
        var fechatoinsert=anio+"-"+mes+"-"+dia
        
        
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
          ];
        
        var fechatodisplay=dia+" - "+monthNames[mes]+" - "+anio
        
        $(this).val(fechatodisplay);
        UpdatePagoFactura(idpagofactura,fechatoinsert);
    }
 });
function UpdatePagoFactura(param1,param2){
    //alert('updatePagoFecha.php?param1='+param1+'&token=<?php echo $SessionUser->token;?>&param2='+param2+'');
    $.ajax({
            url:'updatePagoFecha.php?param1='+param1+'&token=<?php echo $SessionUser->token;?>&param2='+param2+'',
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                alert(obj1);
            }
            
    });
}


</script>
<?php include '../../view/footerinclude.php';?>
<?php 
    unset($allowtoview);
    unset($token);
    unset($action);
    unset($factura);
    unset($_ADOFacturas);
    unset($pathfile);
    unset($estado);
    
?>
