<?php /*
Plugin Name: Conditions de sentiers - Magazine - Admin
Plugin URI: http://sitechno.com
description: Club Site Admin
Version: 1.5
Author: Muhammad Saad
Author URI: http://sidtechno.com
*/

add_action( 'admin_menu', 'register_conditions_de_sentiers_menu' );
function register_conditions_de_sentiers_menu() {
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  add_menu_page( 'Conditions de sentiers', 'Conditions de sentiers', 'manage_options', 'conditions_de_sentiers', 'return_conditions_de_sentiers', 'dashicons-welcome-widgets-menus', 5 );
}

function return_conditions_de_sentiers() {
	echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

	$servername = "68.71.39.130";
	$username = "freelancer";
	$password = "FrEe!LaNCeR2021";
	$dbname = "dlavoie_data";

	// Create connection

	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	$conn->query("SET NAMES 'utf8'");

	if(isset($_GET['editid'])) {

		if(isset($_POST['update_club'])) {
			$error = '';
			$success = '';
			if(empty($_POST['sentierNom'])) {
				$error = 'Erreur : Remplir tous les champs requis (*)';
			}


    	if(empty($error)) {
				$sql_count = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type != 3";
				$result_count = $conn->query($sql_count);
				if ($result_count->num_rows > 0) {
					while($row_count = $result_count->fetch_assoc()) {
						$order = $row_count['total_sentiers'] + 1;
					}
				}

				$total_type_3 = 0;
	        	if($_POST['type'] == 3) {
					$sql_count = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type = 3 AND id != '".$_GET['editid']."'";
					$result_count = $conn->query($sql_count);
					if ($result_count->num_rows > 0) {
						while($row_count = $result_count->fetch_assoc()) {
							$total_type_3 = $row_count['total_sentiers'];
						}
					}

	        		$_POST['sentierNom'] = $club_name;
	        		$order = 0;
	        	}
	        	if($total_type_3 > 0) {
							$error = 'Un sentier global existe déjà pour les rapports dans Motoneiges.ca';
	        	} else {
	        		$sql_update = "UPDATE Sentiers SET auteur='MCA',clubNo='".$_POST['clubNo']."',clubNom='".$_POST['clubNom']."',region='".$_POST['region']."',sentierNom='".$_POST['sentierNom']."',etat='".$_POST['etat']."',conditions='".$_POST['conditions']."',ordre='".$order."',commentaires='".$_POST['commentaires']."',comments='".$_POST['comments']."',distance='".$_POST['distance']."',maj='".$_POST['maj']."',actif='".$_POST['actif']."' WHERE id = '".$_GET['editid']."' ";
					$conn->query($sql_update);
					echo '<meta http-equiv="refresh" content="0; URL='.get_site_url().'/wp-admin/admin.php?page=conditions_de_sentiers" />';
					exit();
				}
			}
		}

		$sql = "SELECT * FROM Sentiers WHERE id = '".$_GET['editid']."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo '
					<div class="d-flex align-items-start mt-5 gap-5">
					    <div class="tab-content col-md-12 border shadow p-4" id="ship-tab-content">
		            		<form action="" method="post">
						    <div class="tab-content col-md-12 border shadow p-4" id="ship-tab-content">
				                <div class="mb-3">
				                    <h2 class="subtitle">'.$row['clubNom'].'</h6>';
						        	if(!empty($error)) {
						        		echo "<p style='color:red;'>".$error."</p>";
						        	} elseif(!empty($success)) {
						        		echo "<p style='color:green;'>".$success."</p>";
						        	} 

			                        echo '<div class="col">
			                            <label for="sentierNom" class="form-label">Sentier (*)</label>
			                            <input type="text" class="form-control form-control-sm" name="sentierNom" id="sentierNom" value="'.$row['sentierNom'].'">
			                        </div>
			                        <div class="col">
			                            <label for="clubNo" class="form-label">Club ID (*)</label>
			                            <input type="text" class="form-control form-control-sm" name="clubNo" id="clubNo" value="'.$row['clubNo'].'">
			                        </div>
			                        <div class="col">
			                            <label for="clubNom" class="form-label">Club Nom (*)</label>
			                            <input type="text" class="form-control form-control-sm" name="clubNom" id="clubNom" value="'.$row['clubNom'].'">
			                        </div>
			                        <div class="col">
			                            <label for="region" class="form-label">Région (*)</label>
			                            <input type="text" class="form-control form-control-sm" name="region" id="region" value="'.$row['region'].'">
			                        </div>
			                        <div class="col">
			                            <label for="etat" class="form-label">État (*)</label>
			                            <select class="form-control form-control-sm" name="etat" id="etat">
			                            	<option value="0" '; if($row['etat'] == 0) { echo 'selected'; } echo ' >État non disponible</option>
			                            	<option value="1" '; if($row['etat'] == 1) { echo 'selected'; } echo ' >Ouvert</option>
			                            	<option value="2" '; if($row['etat'] == 2) { echo 'selected'; } echo ' >Partiellement ouvert</option>
			                            	<option value="3" '; if($row['etat'] == 3) { echo 'selected'; } echo ' >Fermé</option>
			                            </select>
			                        </div>
			                        <div class="col">
			                            <label for="conditions" class="form-label">Sélectionner sentier(s) (*)</label>
			                            <select class="form-control form-control-sm" name="conditions" id="conditions">
			                            	<option value="0" '; if($row['conditions'] == 0) { echo 'selected'; } echo ' >Non-disponible</option>
			                            	<option value="1" '; if($row['conditions'] == 1) { echo 'selected'; } echo ' >Mauvaises</option>
			                            	<option value="2" '; if($row['conditions'] == 2) { echo 'selected'; } echo ' >Moyennes</option>
			                            	<option value="3" '; if($row['conditions'] == 3) { echo 'selected'; } echo ' >Bonnes</option>
			                            	<option value="4" '; if($row['conditions'] == 4) { echo 'selected'; } echo ' >Très bonnes</option>
			                            	<option value="5" '; if($row['conditions'] == 5) { echo 'selected'; } echo ' >Excellentes</option>
			                            </select>
			                        </div>
			                        <div class="col">
			                            <label for="commentaires" class="form-label">Commentaires</label>
			                            <textarea class="form-control form-control-sm" name="commentaires" id="commentaires">'.$row['commentaires'].'</textarea>
			                        </div>
			                        <div class="col">
			                            <label for="comments" class="form-label">Comments</label>
			                            <textarea class="form-control form-control-sm" name="comments" id="comments">'.$row['comments'].'</textarea>
			                        </div>
			                        <div class="col">
			                            <label for="distance" class="form-label">Distance</label>
			                            <input type="text" class="form-control form-control-sm" name="distance" id="distance" value="'.$row['distance'].'">
			                        </div>
			                        <div class="col">
			                            <label for="maj" class="form-label">MAJ</label>
			                            <input type="date" class="form-control form-control-sm" name="maj" id="maj" value="'.$row['maj'].'">
			                        </div>
			                        <div class="col">
			                            <label for="actif" class="form-label">Actif</label>
			                            <select class="form-control form-control-sm" name="actif" id="actif">
			                            	<option value="1" '; if($row['actif'] == 1) { echo 'selected'; } echo ' >Yes</option>
			                            	<option value="0" '; if($row['actif'] == 0) { echo 'selected'; } echo ' >No</option>
			                            </select>
			                        </div>
			                    </div>
		                    </div>

			                <div>
			                    <div class="col-12 mb-3 ">
			                        <button type="submit " class="btn btn-success" name="update_club">Soumettre</button>
			                    </div>
			                </div>
		            		</form>
	            		</div>
            		</div>';
			}
		}
	} elseif(isset($_GET['addnew'])) {
		if(isset($_POST['addnew_club'])) {
			$error = '';
			$success = '';
			if(empty($_POST['sentierNom'])) {
				$error = 'Erreur : Remplir tous les champs requis (*)';
			}
			$servername = "68.71.39.130";
			$username = "freelancer";
			$password = "FrEe!LaNCeR2021";
			$dbname = "dlavoie_data";

			// Create connection

			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			$conn->query("SET NAMES 'utf8'");


        	if(empty($error)) {
				$sql = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type != 3";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$order = $row['total_sentiers'] + 1;
					}
				}

				$total_type_3 = 0;
	        	if($_POST['type'] == 3) {
					$sql = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type = 3";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$total_type_3 = $row['total_sentiers'];
						}
					}

	        		$_POST['sentierNom'] = $club_name;
	        		$order = 0;
	        	}
	        	if($total_type_3 > 0) {
							$error = 'Un sentier global existe déjà pour les rapports dans Motoneiges.ca';
	        	} else {
					$sql = "INSERT INTO Sentiers (clubNo, clubNom, region, sentierNom, etat, conditions, ordre, commentaires, comments, actif, distance, maj, type)
					VALUES ('".$_POST['clubNo']."', '".$_POST['clubNom']."', '".$_POST['region']."', '".$_POST['sentierNom']."', '".$_POST['etat']."', '".$_POST['conditions']."', '".$order."', '".$_POST['commentaires']."', '".$_POST['comments']."', '".$_POST['actif']."', '".$_POST['distance']."', '".$_POST['maj']."', '3')";
					$conn->query($sql);
					echo '<meta http-equiv="refresh" content="0; URL='.get_site_url().'/wp-admin/admin.php?page=conditions_de_sentiers" />';
					exit();
				}
			}

		}
			echo '<div class="d-flex align-items-start mt-5 gap-5">
			    <div class="tab-content col-md-12 border shadow p-4" id="ship-tab-content">
            		<form action="" method="post">
				    <div class="tab-content col-md-12 border shadow p-4" id="ship-tab-content">
		                <div class="mb-3">
		                    <h6 class="subtitle">Ajouter un sentier</h6>';
				        	if(!empty($error)) {
				        		echo "<p style='color:red;'>".$error."</p>";
				        	} elseif(!empty($success)) {
				        		echo "<p style='color:green;'>".$success."</p>";
				        	} 
	                        echo '<div class="col">
	                            <label for="sentierNom" class="form-label">Sentier (*)</label>
	                            <input type="text" class="form-control form-control-sm" name="sentierNom" id="sentierNom" value="">
	                        </div>
	                        <div class="col">
	                            <label for="clubNo" class="form-label">Club ID (*)</label>
	                            <input type="text" class="form-control form-control-sm" name="clubNo" id="clubNo">
	                        </div>
	                        <div class="col">
	                            <label for="clubNom" class="form-label">Club Nom (*)</label>
	                            <input type="text" class="form-control form-control-sm" name="clubNom" id="clubNom">
	                        </div>
	                        <div class="col">
	                            <label for="region" class="form-label">Région (*)</label>
	                            <input type="text" class="form-control form-control-sm" name="region" id="region">
	                        </div>
	                        <div class="col">
	                            <label for="etat" class="form-label">État (*)</label>
	                            <select class="form-control form-control-sm" name="etat" id="etat">
	                            	<option value="0">État non disponible</option>
	                            	<option value="1">Ouvert</option>
	                            	<option value="2">Partiellement ouvert</option>
	                            	<option value="3">Fermé</option>
	                            </select>
	                        </div>
	                        <div class="col">
	                            <label for="conditions" class="form-label">Sélectionner sentier(s) (*)</label>
	                            <select class="form-control form-control-sm" name="conditions" id="conditions">
	                            	<option value="0">Non-disponible</option>
	                            	<option value="1">Mauvaises</option>
	                            	<option value="2">Moyennes</option>
	                            	<option value="3">Bonnes</option>
	                            	<option value="4">Très bonnes</option>
	                            	<option value="5">Excellentes</option>
	                            </select>
	                        </div>
	                        <div class="col">
	                            <label for="commentaires" class="form-label">Commentaires</label>
	                            <textarea class="form-control form-control-sm" name="commentaires" id="commentaires"></textarea>
	                        </div>
	                        <div class="col">
	                            <label for="comments" class="form-label">Comments</label>
	                            <textarea class="form-control form-control-sm" name="comments" id="comments"></textarea>
	                        </div>
	                        <div class="col">
	                            <label for="distance" class="form-label">Distance</label>
	                            <input type="text" class="form-control form-control-sm" name="distance" id="distance">
	                        </div>
	                        <div class="col">
	                            <label for="maj" class="form-label">MAJ</label>
	                            <input type="date" class="form-control form-control-sm" name="maj" id="maj" value="'.date("Y-m-d").'">
	                        </div>
	                        <div class="col">
	                            <label for="actif" class="form-label">Actif</label>
	                            <select class="form-control form-control-sm" name="actif" id="actif">
	                            	<option value="1">Yes</option>
	                            	<option value="0">No</option>
	                            </select>
	                        </div>
	                    </div>
                    </div>

	                <div>
	                    <div class="col-12 mb-3 ">
	                        <button type="submit " class="btn btn-success" name="addnew_club">Soumettre</button>
	                    </div>
	                </div>
            		</form>
          		</div>
        		</div>';
	} else {
    if(isset($_POST['update_multi_club'])) {

			$list_of_trait = explode (",", $_POST['list_of_trait']); 

			foreach ($list_of_trait as $key => $value) {
				if(!empty($value)) {
					if($_POST['etat'] != '--') {
						$sql_update = "UPDATE Sentiers SET auteur='MCA', etat='".$_POST['etat']."' WHERE id = '".$value."' ";
						$conn->query($sql_update);
					}
					if($_POST['conditions'] != '--') {
						$sql_update = "UPDATE Sentiers SET auteur='MCA', conditions='".$_POST['conditions']."' WHERE id = '".$value."' ";
						$conn->query($sql_update);
					}
					if($_POST['maj'] != '') {
						$sql_update = "UPDATE Sentiers SET auteur='MCA', maj='".$_POST['maj']."' WHERE id = '".$value."' ";
						$conn->query($sql_update);
					}
					if($_POST['commentaires'] != '') {
						$sql_update = "UPDATE Sentiers SET auteur='MCA', commentaires='".$_POST['commentaires']."' WHERE id = '".$value."' ";
						$conn->query($sql_update);
					}
					if($_POST['comments'] != '') {
						$sql_update = "UPDATE Sentiers SET auteur='MCA', comments='".$_POST['comments']."' WHERE id = '".$value."' ";
						$conn->query($sql_update);
					}
				}
			}


    }
		if(isset($_GET['deleteid'])) {
			$sql = "DELETE FROM Sentiers WHERE id = '".$_GET['deleteid']."'";
			$conn->query($sql);
		}
		if(isset($_GET['region'])) {
			$sql = "SELECT * FROM Sentiers WHERE actif = '1' AND type='3' AND region = '".$_GET['region']."' ORDER BY region, clubNom ASC";
		} else {
			$sql = "SELECT * FROM Sentiers WHERE actif = '1' AND type='3' ORDER BY region, clubNom ASC";
		}
		$result = $conn->query($sql);
    wp_enqueue_script('jquery_ui', 'https://code.jquery.com/ui/1.13.0/jquery-ui.min.js', array('jquery'));
    wp_enqueue_script('plugin_one_script', plugin_dir_url( __FILE__ ).'/script.js', array('jquery'));
    echo '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />';
		echo '<style>.selected
		{
		  background-color: #666;
		  color: #fff;
		}
		.ui-dialog-titlebar.ui-corner-all.ui-widget-header.ui-helper-clearfix.ui-draggable-handle {
		background: none;
		border: none;
		}
		button.ui-button.ui-corner-all.ui-widget.btn.btn-danger, button.ui-button.ui-corner-all.ui-widget.btn.btn-success { padding: 10px;
		  height: auto !important;}
		button.ui-button.ui-corner-all.ui-widget.ui-button-icon-only.ui-dialog-titlebar-close.btn.btn-success { display : none;}
		</style>';

		echo "<a href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers&addnew' class='btn btn-primary'>Ajouter un sentier</a>";
		echo " | <a href='#' class='btn btn-primary editall'>Modifier la sélection</a>";
		echo " <select id='SelectRegion' data-url='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers'>
		<option value=''>Select Region</option>";
		$region_sql = "SELECT * FROM Sentiers GROUP BY region";
		$region_result = $conn->query($region_sql);
		if ($region_result->num_rows > 0) {
			while($region_row = $region_result->fetch_assoc()) {
				if(isset($_GET['region'])) {
					echo "<option value='".$region_row['region']."' "; if($_GET['region'] == $region_row['region']) { echo "selected"; } echo ">".$region_row['region']."</option>";
				} else {
					echo "<option value='".$region_row['region']."'>".$region_row['region']."</option>";
				}
			}
		}

		echo "</select>";
		echo "<table class='table'>";
		echo '<thead><tr>
		<th scope="col"><input type="checkbox" class="select_all_checkbox"></th>
		<th scope="col">Région</th>
		<th scope="col">Sentier</th>
		<th scope="col">État</th>
		<th scope="col">Conditions</th>
		<th scope="col">MAJ</th>
		<th scope="col">Author</th>
		<th scope="col">Action</th>
		</tr></thead>';
			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
				echo"<tr>
				<td><input type='checkbox' name='checkbox[]' data-id='".$row['id']."' class='multiselect'></td>
				<td>".$row['region']."</td>
				<td>".$row['sentierNom']; if(empty($row['commentaires']) AND empty($row['comments'])) { } else { echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/note-.png">'; } echo"</td>
				<td>"; if($row['etat'] == 0){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['etat'] == 1){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/ouvert.png">'; } elseif($row['etat'] == 2){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/partiel.png">'; } elseif($row['etat'] == 3){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/ferme.png">'; } echo "</td>
				<td>"; if($row['conditions'] == 0){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['conditions'] == 1){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile1.png">'; } elseif($row['conditions'] == 2){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile2.png">'; } elseif($row['conditions'] == 3){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile3.png">'; } elseif($row['conditions'] == 4){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile4.png">'; } elseif($row['conditions'] == 5){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile5.png">'; } echo "</td>
				<td>".$row['maj']."</td>
				<td>".$row['auteur']."</td>				
				<td><a href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers&editid=".$row['id']."'>Édition</a> | <a href='#' data-href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers&deleteid=".$row['id']."' class='delete'>Supprimer</a></td></tr>";
			  }
			}
		echo "</table>";
		echo '<div id="dialog-confirm" title="Détruire ce sentier?">
		</div>';
	}
	$conn->close();
}

?>