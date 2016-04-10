<!DOCTYPE html>

<?php
include 'topInclude.php';
$Showlogin= $_SESSION['Show_Loggin'];
$failsattemp=0;
if(isset($_SESSION['failedattemps'])){
    $failsattemp=$_SESSION['failedattemps'];
}else{
    $_SESSION['failedattemps']=0;
}
$_SESSION['captcha'] = simple_php_captcha();

?>
<?php include './view/headinclude.php'?>

<?php 
    if($Showlogin){
        include './loggincontrol.php';
    }else{
        include './view/menu.php';
        include './HomeControlPanel.php';
    }
    
?>


<?php include './view/footerinclude.php'?>