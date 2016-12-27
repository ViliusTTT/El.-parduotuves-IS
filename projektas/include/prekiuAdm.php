<?php
class BrazzersHelper {
    var $db;

    function BrazzersHelper(){
        global $database;
        $this->db = $database;
    }

    function parser($array,$item){
        $num_rows = mysqli_num_rows($array);
        for($i = 0; $i < $num_rows; $i++){
            $array->data_seek($i);
            $row = $array->fetch_assoc();
            $data[] = $row;
        }
        if(isset($data)){
            if($item==1)
                return $data[0];
            else
                return $data;
        }
    }

    function getItem($id){
        $q = "SELECT * FROM `prekes` WHERE `prekes`.`id`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function getAllItems(){
        $q = "SELECT * FROM `prekes`";
        $array = $this->db->query($q);
        return $this->parser($array,0);
    }

    function getType($id){
        $q = "SELECT * FROM `tipai` WHERE `tipai`.`id`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function getAllTypes(){
        $q = "SELECT * FROM `tipai`";
        $array = $this->db->query($q);
        return $this->parser($array,0);
    }

    function getAssociatedTypes($id){
        $q = "SELECT `tipai`.`id`, `tipai`.`pavadinimas` FROM `tipai` INNER JOIN `turi2` ON `turi2`.`fk_tipaiid_tipai`=`tipai`.`id` INNER JOIN `prekes` ON `turi2`.`fk_prekesid_prekes`= `prekes`.`id` WHERE `prekes`.`id` = '{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,0);
    }

    function getWarehouseForItem($id){
        $q = "SELECT `Adresas`,`sandeliai`.`id` FROM `sandeliai` INNER JOIN `prekes` ON `prekes`.`fk_sandeliaiid_sandeliai` = `sandeliai`.`id` WHERE `prekes`.`id`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function getWarehouse($id){
        $q = "SELECT * FROM `sandeliai` WHERE `sandeliai`.`id` = '{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function getAllWarehouses(){
        $q = "SELECT * FROM `sandeliai`";
        $array = $this->db->query($q);
        return $this->parser($array,0);
    }

    function deleteType($id){
        $q1 = "DELETE FROM `turi2` WHERE `turi2`.`fk_tipaiid_tipai` = '{$id}'";
        $q2 = "DELETE FROM `tipai` WHERE `tipai`.`id` = '{$id}'";
        $this->db->query($q1); $this->db->query($q2);
    }

    function deleteItem($id){
        $q1 = "DELETE FROM `turi2` WHERE `turi2`.`fk_prekesid_prekes` = '{$id}'";
        $q2 = "DELETE FROM `prekes` WHERE `prekes`.`id`='{$id}'";
        $this->db->query($q1); $this->db->query($q2);
    }

    function getAmmountAtWh($id){
        $q = "SELECT COUNT(`prekes`.`fk_sandeliaiid_sandeliai`) FROM `prekes` WHERE `prekes`.`fk_sandeliaiid_sandeliai`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function deleteWh($id){
        $q = "DELETE FROM `sandeliai` WHERE `sandeliai`.`id` = '{$id}'";
        $this->db->query($q);
    }

    function removeType($id){
        $q = "DELETE FROM `turi2` WHERE `turi2`.`fk_prekesid_prekes`='{$id}'";
        $this->db->query($q);
    }
    function removeTypeConnection($id1, $id2){
        $q = "DELETE FROM `turi2` WHERE `turi2`.`fk_prekesid_prekes`='{$id1}' AND `turi2`.`fk_tipaiid_tipai` = '{$id2}'";
        $this->db->query($q);
    }

    function addTypeConnection($id1, $id2){
        $q = "INSERT INTO `turi2`(`fk_prekesid_prekes`,`fk_tipaiid_tipai`) VALUES ('{$id1}','{$id2}')";
        $this->db->query($q);
    }

    function createItem($POST){
        $q="INSERT INTO `prekes`".
        "(`prekesKodas`, `Pavadinimas`, `Galiojimas`, `Kaina`, `Kiekis`,`fk_sandeliaiid_sandeliai`) ".
        "VALUES ('{$POST['kodas']}','{$POST['pavadinimas']}','{$POST['galiojimas']}','{$POST['kaina']}','{$POST['kiekis']}','{$POST['sand']}')";
        $this->db->query($q);
        $q1="SELECT `id` FROM `prekes` WHERE `prekes`.`prekesKodas`='{$POST['kodas']}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function editItem($id, $POST){
        $q="UPDATE `prekes` SET ".
            "`prekesKodas`='{$POST['kodas']}',".
            "`Pavadinimas`='{$POST['pavadinimas']}',".
            "`Galiojimas`='{$POST['galiojimas']}',".
            "`Kaina`='{$POST['kaina']}',".
            "`Kiekis`='{$POST['kiekis']}',".
            "`fk_sandeliaiid_sandeliai`='{$_POST['sand'][0]}' WHERE `prekes`.`id`='{$id}'";
        $status = $this->db->query($q);
    }

    function editType($id, $POST){
        if($id == -1)
            $q="INSERT INTO `tipai`(`Pavadinimas`) VALUES ('{$POST['pav']}')";
		else
            $q="UPDATE `tipai` SET `Pavadinimas`='{$POST['pav']}' WHERE `tipai`.`id`='{$id}'";
        $this->db->query($q);
    }

    function getAllCompanies(){
        $q = "SELECT * FROM `imone`";
        $array = $this->db->query($q);
        return $this->parser($array,0);
    }

    function getCompany($id){
        $q = "SELECT `imones_kodas`,`imones_pavadinimas` FROM `imone` INNER JOIN `sandeliai` ON `sandeliai`.`fk_Imoneid_Imone`=`imone`.`imones_kodas` WHERE `sandeliai`.`id`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function editWh($id, $POST){
        if($id == -1)
            $q="INSERT INTO `sandeliai`".
            "(`Adresas`, `Talpa`, `Kontaktinis_nr`, `Miestas`, `fk_Imoneid_Imone`) ".
            "VALUES ('{$POST['adresas']}','{$POST['talpa']}','{$POST['numeris']}','{$POST['miestas']}','{$POST['im'][0]}')";
		else
            $q="UPDATE `sandeliai` SET ".
            "`Adresas`='{$POST['adresas']}',".
            "`Talpa`='{$POST['talpa']}',".
            "`Kontaktinis_nr`='{$POST['numeris']}',".
            "`Miestas`='{$POST['miestas']}',".
            "`fk_Imoneid_Imone`='{$POST['im'][0]}' WHERE `id`='{$id}'";
        $this->db->query($q);
    }

    function getTypeAvg($id){
        $q = "SELECT AVG(`prekes`.`Kaina`) AS `avgKaina` FROM `prekes` ".
            "INNER JOIN `turi2` ON `prekes`.`id` = `turi2`.`fk_prekesid_prekes` ".
            "INNER JOIN `tipai` ON `tipai`.`id` = `turi2`.`fk_tipaiid_tipai` ".
            "WHERE `tipai`.`id`='{$id}'";
            $array = $this->db->query($q);
            return $this->parser($array,1);
    }
    function getTypeItems($id){
        $q = "SELECT *, `prekes`.`Pavadinimas` as prek FROM `prekes`".
            "INNER JOIN `turi2` ON `prekes`.`id` = `turi2`.`fk_prekesid_prekes` ".
            "INNER JOIN `tipai` ON `tipai`.`id` = `turi2`.`fk_tipaiid_tipai` ".
            "WHERE `tipai`.`id`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,0);
    }
    function getSumOfType($id){
        $q = "SELECT SUM(`prekes`.`Kaina`) AS `sumKaina` FROM `prekes` ".
            "INNER JOIN `turi2` ON `prekes`.`id` = `turi2`.`fk_prekesid_prekes` ".
            "INNER JOIN `tipai` ON `tipai`.`id` = `turi2`.`fk_tipaiid_tipai` ".
            "WHERE `tipai`.`id`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array,1);
    }

    function transfer($id){
        $this->db->query($q1);
        $q2 = "SELECT `tipaitiekejo`.`id`,`tipaitiekejo`.`Pavadinimas` FROM `tipaitiekejo`".
        " INNER JOIN `turi3` ON `turi3`.`fk_tipaiTiekejo`=`tipaitiekejo`.`id`".
        " INNER JOIN `prekestiekejo` ON `turi3`.`fk_PrekesTiekejo`=`prekestiekejo`.`id` WHERE `prekestiekejo`.`id`='{$id}'";
        $array = $this->db->query($q2);
        $newTypes = $this->parser($array,0);
        $backupNew = $newTypes;
        $myTypes = $this->getAllTypes();
        $m = sizeof($newTypes);
        $n = sizeof($myTypes);
        if($m!=0){
            for($i = 0; $i < $m; $i++){
                for($ii = 0; $ii < $n; $ii++){
                    if(array_key_exists($i,$newTypes)){
                        if($myTypes[$ii]['Pavadinimas']==$newTypes[$i]['Pavadinimas']){
                            unset($newTypes[$i]);
                        }
                    }
                }
            }
        }
        $q3 = "SELECT `pruzsakymas`.`prekiuKiekis` AS `kiekis`, `prekiusarasas`.`fk_ImonesSandelys` AS `sand`, `prekestiekejo`.`prekesKodas` AS `kodas`, `prekestiekejo`.`Galiojimas` AS `galiojimas`,`prekestiekejo`.`Kaina` AS `kaina`,`prekestiekejo`.`Pavadinimas` AS `pavadinimas` FROM `prekestiekejo` INNER JOIN `pruzsakymas` ON `prekestiekejo`.`id`=`pruzsakymas`.`fk_Preke` INNER JOIN `prekiusarasas` ON `prekiusarasas`.`uzsakymoKodas`=`pruzsakymas`.`fk_PrekiuSarasasid_PrekiuSarasas` WHERE `prekestiekejo`.`id`='{$id}'";
        $array = $this->db->query($q);
        $itemToCreate = $this->parser($array,1);
        $newId = $this->createItem($itemToCreate);
        if(sizeof($newTypes)!=0){
            foreach($newTypes as $newType){
                $newArray = array('pav'=>$newType['Pavadinimas']);
                $this->editType(-1,$newArray);
            }
            $myTypes = $this->getAllTypes();
            foreach($backupNew as $smth){
                foreach($myTypes as $myType){
                    if($smth['Pavadinimas']==$myType['Pavadinimas']){
                        $this->addTypeConnection($newId,$myType['id']);
                    }
                }
            }
        }
    }
}
