<?php
  include_once('session_create.ctrl.php');
  require_once '../model/DAO.class.php';

  //Recup id du mariage
  $idM = $_SESSION['idM'];

  if( isset($_POST['idCnt'])){
    $dao->updCntTable($idM, $_POST['idCnt'], NULL);
  }
  else {
    echo "Failed";
  }
?>
