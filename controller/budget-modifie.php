<?php 

    require_once '../model/DAO.class.php';
        
    if(isset($_POST['action'])){
                
        $idmariage = $_POST['idmariage'];
        $action = $_POST['action'];

        if ($action == "supprimer"){
            // suppression dans la base de donnée de idbudget
			$idbudget = $_POST['idbudget'];
            $dao->supBudget($idbudget);
            
        }else if ($action == "updatebudgetglobal"){
            // mise à jour du budget global
            $dao->updateBudgetGlobal($idmariage, $_POST['value']);
            
        }else if ($action == "annuler" || $action == "valider"){
            
            $idbudget = $_POST['idbudget'];
            
            if ($action == "valider"){
            // enregistrement dans la base de donnée des modifications
            // si idbudget n'existe pas dans la base, il faut creer un nouveau budget dans la base
                $tabdepense = null;
                
                // recuperation des données du formulaire
                foreach ($_POST as $name => $value) {
                    if (strripos($name, "depdescription") != false){ // si c'est une description de dépense
                        $tabdepense[str_replace("depdescription", "", $name)]['depdescription'] = $value;
                    }else if (strripos($name, "depvalue") != false){ // si c'est une valeur de depense
                        if ($value == null){
                            $tabdepense[str_replace("depvalue", "", $name)]['depvalue'] = 0;
                        }else{
							if ($value < 0){
								$tabdepense[str_replace("depvalue", "", $name)]['depvalue'] = 0;
							}else if ($value > 2000000000){
								$tabdepense[str_replace("depvalue", "", $name)]['depvalue'] = 2000000000;
							}else{
								$tabdepense[str_replace("depvalue", "", $name)]['depvalue'] = $value;
							}
                        }
                    }
                }
                
                // creation de objets depenses
                if ($tabdepense != null){
                    foreach ($tabdepense as $id => $depense) {
                        $depenseobj = new depense($id, $depense['depdescription'], $depense['depvalue']);
                        $tabdepense[$id] = $depenseobj;
                    }
                }
                
                $budget = new budget($idbudget, $idmariage, $_POST['description'], $_POST['value'], $tabdepense);
                
                // mise à jour de la base de donnée
                // et de l'objet avec le nouvelle id
                $idbudget = $dao->updateBudget($budget);
                $budget->setId($idbudget);
                
                // affichage du nouvelle id car il faut le communiquer
                // a la page web client en cas de création d'un nouveau budget.
                // il est récupéré par javascript
                ?> <?= $budget->getId() ?> <?php
                
            }else{
                $budget = $dao->getbudget($idbudget);
            }
            
            if ($budget != null){
                
?>
            
                <div class="row">
                    <p><b id="description<?= $idbudget ?>"><?= $budget->getDescription() ?></b> : <b id="value<?= $idbudget ?>"><?= $budget->getValue() ?></b> €</p>
                </div>
                <table id="tab<?= $idbudget ?>" class="row scroll form-control">
                    <tr class="row"><th class="champ-description-depense col-sm-12 text-center">Description</th><th class="col-sm-12 champ-description-depense">Prix</th></tr>
                    <?php
                    $tabdepense = $budget->getTabdepense();
                    if ($tabdepense != null){
                        foreach ($tabdepense as $depense) {
                            ?>
                            <tr id="dep<?= $depense->getId() ?>" class="depense<?= $idbudget ?> row"><td><?= $depense->getDescription() ?></td><td class="text-right"><?= $depense->getValue() ?> €</td></tr>
                            <?php
                        }
                    }
                    ?>
                </table>
                <div class="row table-margin">
                    <p class="row no-margin">Total dépensé : <b id="totaldepense<?= $idbudget ?>" class="text-right"><?= $budget->getTotalDepense() ?></b> €</p>
                    <p class="row no-margin">Budget restant : <b id="totalrestant<?= $idbudget ?>" class="totalrestant text-right"><?= $budget->getTotalRest() ?></b> €</p>
                </div>
                <div class="row">
                    <button class="btn-d col-xs-5 col-xs-offset-1 btn btn-primary" onClick="supprimer('<?= $idbudget ?>', '<?= $budget->getIdMariage() ?>')">Supprimer</button>
                    <button class="btn-d col-xs-5 btn btn-primary" onclick="modifier('<?= $idbudget ?>', '<?= $budget->getIdMariage() ?>')">Modifier</button>
                </div>
            
<?php

            } 
        }
    }
    
?> 