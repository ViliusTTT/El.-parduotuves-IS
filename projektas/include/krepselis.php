<?php
class Krepselis {
    var $db;

    function Krepselis() {
        global $database;
		if (isset($database)) {
			$this->db = $database;
		} else {
			include("database.php");
			$this->db = $database;
		}
					
		if (isset($_POST['func']) && isset($_POST['args'])) {
			session_start();
			if (!isset($_SESSION['selectedItems@'.$_SESSION['username']]))
				$_SESSION['selectedItems@'.$_SESSION['username']] = [];
			call_user_func(array( $this, $_POST['func'] ), $_POST['args']);
		}
    }

    function parser($array, $item){
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
	
	function checkCoupon($code) {
		$q = "SELECT * FROM `kuponai` WHERE `kuponai`.`kuponoKodas`='{$code}'";
        $array = $this->db->query($q);
		$data = json_encode($this->parser($array, 0)[0]);
		if (isset($_POST['args']))
			echo $data;
        else
			return $data;
	}
	
    function getCart($id) {
        $q = "SELECT * FROM `krepselis` WHERE `krepselis`.`fk_Uzsakymasid_Uzsakymas`='{$id}'";
        $array = $this->db->query($q);
        return $this->parser($array, 0);
    }	
	
	function removeCart($id) {
        $q = "DELETE FROM `krepselis` WHERE `krepselis`.`fk_Uzsakymasid_Uzsakymas`='{$id}'";
        $this->db->query($q);
    }	
	
	function uploadCart() {
		// Calculate current "uzsakymasID"
		$q = "SELECT MAX(`fk_Uzsakymasid_Uzsakymas`) AS `uzsakymoID` FROM `krepselis`";
		$array = $this->db->query($q);
		$currentID = intval($this->parser($array, 1)['uzsakymoID']);
		
		// Loop
		$selectedItems = $_SESSION['selectedItems@'.$_SESSION['username']];
		foreach ($selectedItems as $item) {
			if (isset($item)) {
				// Insert to db
				$ID = $item->id;
				$Kiekis = $item->Kiekis;
				$uzsakymoID = $currentID + 1;
				
				$query = "INSERT INTO `krepselis` (`id`, `prekesKiekis`, `fk_preke`, `fk_Uzsakymasid_Uzsakymas`) VALUES (NULL, '{$Kiekis}', '{$ID}', '{$uzsakymoID}')";
				$this->db->query($query);
			}
		}	
		$this->emptySessionItems();
		echo $currentID + 1;
	}
	
	function setItem($array){
		$query = "INSERT INTO `krepselis`(`id`, `prekesKiekis`, `fk_preke`, `fk_Uzsakymasid_Uzsakymas`) VALUES (NULL, 1, '{$array['id']}', 1))";
        $this->db->query($query);
    }
	
	function getSessionItems() {
		return $_SESSION['selectedItems@'.$_SESSION['username']];
	}
	
	function setSessionItem($post) {
		// My selected item
		$suggestedItem = json_decode($post['suggestItem']);
		// Add to array
		array_push($_SESSION['selectedItems@'.$_SESSION['username']], $suggestedItem);
		// Remove dupes
		$_SESSION['selectedItems@'.$_SESSION['username']] = array_map("unserialize", array_unique(array_map("serialize", $_SESSION['selectedItems@'.$_SESSION['username']])));	
	}
	
	function removeSessionItem($id) {
		array_splice($_SESSION['selectedItems@'.$_SESSION['username']], $id, 1);
	}
	
	function emptySessionItems() {
		$_SESSION['selectedItems@'.$_SESSION['username']] = [];
	}
	
	function updateSessionItems($selectedItems) {
		$_SESSION['selectedItems@'.$_SESSION['username']] = json_decode($selectedItems);
	}
	
}

$krepselis = new Krepselis;
?>