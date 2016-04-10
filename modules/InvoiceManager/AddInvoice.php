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
    
    $SessionUser = new UserObj();
    $SessionUser = unserialize($_SESSION['UserObj']);
    $SessionUser->GenerateToken();
    
    if(isset($_SESSION['InvoiceTmp'])){
        $pathfile= $config->domain."/".$config->pathServer. $_SESSION['InvoiceTmp'];
        //echo $pathfile;
    }
    
?>

<?php include '../../view/headinclude.php';?>
<?php include '../../view/menu.php';?>

<h1>Agregar nueva Factura</h1>
<div style="float: left;width: 350px;">
    <!--<form id="newinvoiceupload" name="newinvoiceupload"  action="" method="POST">-->
        <table class="tableInfo">
           <!-- <tr>
                <th style="color:red">Factura :</th>
                <td>
                    <form id="invoiceuplader" enctype="multipart/form-data" name="invoiceuplader" action="uploadFactura.php">
                        <input id="fileselected" type="file" name="archivo" accept="image/x-eps,application/pdf" />
                    </form> 
                </td>
            </tr>-->
            <tr>
                <th style="color:red">Numero de Factura :</th>
                <td>
                    <input id="numfactura" type="text" name="numerofactura" value="" />
                </td>
            </tr>
            <tr>
                <th>Numero de Orden :</th>
                <td>
                    <input id="numorden" type="text" name="numeroorden" value="" />
                </td>
            </tr>
            <tr>
                <th style="color:red">Monto Factura:</th>
                <td>
                    <input id="montofactura" type="text" name="monto" value="" />
                </td>
            </tr>
            <tr>
                <th style="color:red">Fecha Factura:</th>
                <td>
                    <input id="fechafactura" type="text" name="fecha" value="" />
                </td>
            </tr>
            <tr>
                <th>Vence :</th>
                <td>
                    <input id="vence" type="text" name="vencimiento" value="" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <div id="loading" ><br/>Guardando, Espere un momento...<br/><img src="../../images/loading.gif"/></div>
                    <span id="mensage" style=""></span>
                    <br/>
                    <input type="hidden"  id="fileuploaded" value="<?php echo $_SESSION['InvoiceTmp'];?>" />
                    <br />
                    <button id="btnSave" style="width: 300px">Guardar Factura</button>
                    
                    <br />
                     <br />
                </td>
            </tr>
        </table>
    <!--</form>-->
</div>
<div style="float: right;width: calc(100% - 360px) ;background: lightblue; height: 575px">
    <object data="<?php echo $pathfile;?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH" 
        type="application/pdf" 
        width="100%" 
       height="100%">
</object>
</div>

<script src="../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" >
    function isFloat(x) { return !!(x % 1); }
    function isInteger(x) { return typeof x === "number" && isFinite(x) && Math.floor(x) === x; }
    
    $(function() {
        $( "#fechafactura" ).datepicker({dateFormat: "yy-mm-dd",appendText:"yyyy-mm-dd"});
        $( "#vence" ).datepicker({dateFormat: "yy-mm-dd",appendText:"yyyy-mm-dd"});
    });
    
    $('document').ready(function(){
        $('#loading').hide();
    });
    
    //$('#invoiceuplader').submit(function(){
    function SendFile(){
        var data = new FormData($('#invoiceuplader'));
        $.when(
        $.ajax({
                url:"uploadFactura.php",
                data:data,
               //cache: false,
                contentType: false,
                processData: false,
                type: 'POST',     
                success: function(result){
                    $('#fileuploaded').val($.trim(result));
                    return false;
                }
            })
        )
        .progress(function(){
            $('#loading').show();
        })
        .done(function(){
               $('#loading').hide();
               $('#mensage').html("Archivo subido al servidor.");
               sendData();
            });
            
        
        return false;
        
       //});
    } 
    $('#btnSave').click(function(){
        //$('#invoiceuplader').submit();
        //SendFile()
        sendData();
    });
    
    //$('#btnSave').click(function(){
    function sendData(){
         $('#mensage').html("");
        var sendform=true;
        var errmessage="";
        var snumfactura=$('#numfactura').val();
        var vfechafact=$('#fechafactura').val();
        var montofact=$('#montofactura').val();
        
        
        
        if(snumfactura===""){
            sendform=false;
            errmessage="Numero de factura invalido!<br/>";
        }else{
            if(isNaN(snumfactura)===true){
                sendform=false;
                errmessage="Numero de factura invalido!<br/>";
            }
        }
        if(vfechafact==""){
            sendform=false;
            errmessage=errmessage+"Fecha de factura invalido!<br/>";
        }
        
        if(montofact==""){
            sendform=false;
            errmessage=errmessage+"Monto de Factura invalido!<br/>";
        }else{
            if(isNaN(montofact)){
                sendform=false;
                errmessage=errmessage+"Monto de Factura invalido!<br/>";
            }else{
                
            }
            /*if(!isFloat(montofact) || !isInteger(montofact)){
                
            }*/
        }
        /*if ($('#fileselected').get(0).files.length === 0) {
             sendform=false;
            errmessage=errmessage+"Selecione un archivo pdf!<br/>";
        }*/
        
        

        if(sendform){
            $(this).attr('disabled','disabled');
            $('#loading').show();
            $('#invoiceuplader').submit();
            //setTimeout(function(){

            var numfactura=$('#numfactura').val();
            var numorden=$('#numorden').val();
            var montofactura=$('#montofactura').val();
            var fechafactura=$('#fechafactura').val();
            var fileuploaded=$('#fileuploaded').val();
            var vence=$('#vence').val();
            
            if(numorden==""){
                numorden=0;
            }

            var token="<?php echo $SessionUser->token;?>";
            $.post(
                    "insertInvoice.php",
                    {fecha:fechafactura,numerofactura:numfactura,numeroorden:numorden,monto:montofactura,vencimiento:vence,archivoruta:fileuploaded,token:token},
                    function(result){
                        var r=result;
                        if($.trim(r)==="true"){
                           $('#mensage').css("font-size","x-large");
                           $('#mensage').css("color","darkgreen");
                           $('#mensage').html("Factura Agregada con Exito");
                        }else{
                           $('#mensage').css("font-size","x-large");
                           $('#mensage').css("color","red");
                           $('#mensage').html(result); 
                        }

                    }
                );
            $('#loading').hide();
            $('#btnSave').removeAttr('disabled');

            setTimeout(function(){
             $('#mensage').html("");
                document.location.href="invoiceManager.php";
             },4000);
            //},3000);
        }
        if(!sendform){
            $('#mensage').css("font-size","x-large");
            $('#mensage').css("color","red");
            $('#mensage').html(errmessage); 
            
        }
        
       return false; 
        
    //});
    }
</script>


<?php include '../../view/footerinclude.php';?>