<?php
include_once('../model/DAO.class.php');
include_once('session_create.ctrl.php');
  $idacc=$_SESSION['account'];
if (isset($_POST['creation'])) {
  $nom1=$_POST['nom1'];
  $prenom1=$_POST['prenom1'];
  $nom2=$_POST['nom2'];
  $prenom2=$_POST['prenom2'];

  $date=$_POST['date'];
  $adresse=$_POST['adresse'];

  $tabdate = explode("/", $date);
  $date=$tabdate[2].'/'.$tabdate[1].'/'.$tabdate[0];

  if ($dao->getMariage($idacc)) {
    $idm=$dao->getIdMariage($idacc);//On récupère l'id du mariage
    $info=$dao->getMariage($idacc);//Infos sur le mariage qu'on va modifier
    //On stock les anciens noms et prenoms
    $nomH=$info['maria_nomH'];
    $prenomH=$info['maria_prenomH'];
    $nomF=$info['maria_nomF'];
    $prenomF=$info['maria_prenomF'];
    //On les recupere dans la bdd
    $elem=$dao->getMaries($idm,$nomF,$prenomF,$nomH,$prenomH);
    //On les modifie avec les nouveaux noms et prenoms
    $mail=$dao->getMailAccount($idacc);
    $elem[0]->setCont_nom($nom1);
    $elem[0]->setCont_prenom($prenom1);
    $elem[0]->setCont_mail($mail);
    $dao->updateContactInfo($elem[0]);

    $elem[1]->setCont_nom($nom2);
    $elem[1]->setCont_prenom($prenom2);
    $elem[1]->setCont_mail($mail);
    $dao->updateContactInfo($elem[1]);

    //On met à jour la table mariage avec les nouvelles infos
    $retour=$dao->modifMariage($idacc,$nom1,$prenom1,$nom2,$prenom2,$date,$adresse);
    $info=$dao->getMariage($idacc);//Infos sur le mariage qu'on vient de modifier
    $_SESSION['date'] = $info[0]; //On met la nouvelle date dans SESSION

    $modif='Informations sur le mariage modifiées !';
  }else {
    $retour=$dao->createMariage($idacc,$nom1,$prenom1,$nom2,$prenom2,$date,$adresse);

    $idm=$dao->getIdMariage($idacc);//On récupère l'id du mariage que l'on vient de créer
    $dao->insertHash($idm); //on crée le hash par rapport à l'id du mariage

    //On insert dans contact les infos des organisateurs du mariage
    $cnt = new contacts();
    $info=$dao->getMariage($idacc);
    $mail=$dao->getMailAccount($idacc);
    $_SESSION['idM'] = $idm;
    $_SESSION['date'] = $info[0];
    $cnt->faux_construct(NULL, $idm, $info['maria_nomF'], $info['maria_prenomF'], NULL, $mail, NULL, NULL);
    $dao->setContact($cnt);
    $cnt->faux_construct(NULL, $idm, $info['maria_nomH'], $info['maria_prenomH'], NULL, $mail, NULL, NULL);
    $dao->setContact($cnt);

    $modif='Mariage créé !';
  }

  if ($retour==false) {
    $erreur='La creation/modification a échoué';
  }

}

include_once('../view/creation.view.php');
?>
