<?php /*
Plugin Name: Conditions de sentiers - Club - Admin
Plugin URI: http://sitechno.com
description: Club Site Admin
Version: 1.5.0
Author: Muhammad Saad
Author URI: http://sidtechno.com
*/

add_action( 'admin_menu', 'register_conditions_de_sentiers_club_menu' );
function register_conditions_de_sentiers_club_menu() {
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  //add_menu_page( 'Conditions de sentiers', 'Conditions de sentiers', 'manage_options', 'conditions_de_sentiers_club', 'return_conditions_de_sentiers_club', 'dashicons-welcome-widgets-menus', 4 );
  add_menu_page( 'Conditions de sentiers', 'Conditions de sentiers', 'publish_posts', 'conditions_de_sentiers_club', 'return_conditions_de_sentiers_club', 'dashicons-welcome-widgets-menus', 4 );
}

function return_conditions_de_sentiers_club() {
	$is_user_admin = sid_user_has_role(get_current_user_id(), 'admin');
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
	$wp_site_id = get_current_blog_id();
	$site_id = get_blog_option( $wp_site_id, 'sid_club_id' );
	$sid_select_option = get_blog_option( $wp_site_id, 'sid_select_option' );
	if(isset($_GET['editid'])) {

		if(isset($_POST['update_club'])) {
			$error = '';
			$success = '';
			if($is_user_admin != 1) {
				$sql = "SELECT * FROM Sentiers WHERE type = 3 AND id = '".$_GET['editid']."' AND clubNo = '".$site_id."'";
				$result = $conn->query($sql);
				while($row = $result->fetch_assoc()) {
					$_POST['sentierNom'] = $row['sentierNom']; 
					$_POST['type'] = $row['type']; 
				}
			}
			if(!isset($_POST['dernierSurfacage'])) {
				$sql = "SELECT * FROM Sentiers WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."'";
				$result = $conn->query($sql);
				while($row = $result->fetch_assoc()) {
					$_POST['dernierSurfacage'] = $row['dernierSurfacage'];
				}
			}

			if(empty($_POST['sentierNom'])) {
				$error = 'Erreur : Remplir tous les champs requis (*)';
			}

				$region_name = get_blog_option( $wp_site_id, 'sid_region_name' );
      	$club_name = get_blog_option( $wp_site_id, 'sid_club_name');

      	if(empty($error)) {
					$sql_count = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type != 3 AND clubNo = '".$site_id."'";
					$result_count = $conn->query($sql_count);
					if ($result_count->num_rows > 0) {
						while($row_count = $result_count->fetch_assoc()) {
							$order = $row_count['total_sentiers'] + 1;
						}
					}

				$total_type_3 = 0;
	        	if($_POST['type'] == 3) {
					$sql_count = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type = 3 AND clubNo = '".$site_id."' AND id != '".$_GET['editid']."'";
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
							$sql = "SELECT * FROM Sentiers WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."'";
							$result = $conn->query($sql);
							while($row = $result->fetch_assoc()) {
								if($row['type'] == 3) {
									$to = 'sentiers@motoneiges.ca';
									$body = '<table style="border: 1px solid black;">
										<thead style="border: 1px solid black;">
											<td style="border: 1px solid black;">Date and Time</td>
											<td style="border: 1px solid black;">Region Name</td>
											<td style="border: 1px solid black;">Club Name</td>
										</thead>
										<tr style="border: 1px solid black;">
											<td style="border: 1px solid black;">'.date('Y-m-d H:i:s').'</td>
											<td style="border: 1px solid black;">'.$region_name.'</td>
											<td style="border: 1px solid black;">'.$club_name.'</td>
										</tr>
									</table>';

									$subject = 'Club Update Trail';
									$headers = array('Content-Type: text/html; charset=UTF-8');
									
									wp_mail( $to, $subject, $body, $headers );
								}
							}

	        		if($order == 0) {
	        			$sql_update = "UPDATE Sentiers SET clubNom='".$club_name."',region='".$region_name."',sentierNom='".$_POST['sentierNom']."',type='".$_POST['type']."',etat='".$_POST['etat']."',conditions='".$_POST['conditions']."',ordre='".$order."',commentaires='".$_POST['commentaires']."',comments='".$_POST['comments']."',distance='".$_POST['distance']."',maj='".$_POST['maj']."',dernierSurfacage='".$_POST['dernierSurfacage']."',actif='".$_POST['actif']."' WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."' ";
	        		} else {
		        		$sql_update = "UPDATE Sentiers SET clubNom='".$club_name."',region='".$region_name."',sentierNom='".$_POST['sentierNom']."',type='".$_POST['type']."',etat='".$_POST['etat']."',conditions='".$_POST['conditions']."',commentaires='".$_POST['commentaires']."',comments='".$_POST['comments']."',distance='".$_POST['distance']."',maj='".$_POST['maj']."',dernierSurfacage='".$_POST['dernierSurfacage']."',actif='".$_POST['actif']."' WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."' ";
		        	}
							$conn->query($sql_update);

              if($sid_select_option != 1) {
		        		$sql_update = "UPDATE Sentiers SET dernierSurfacage='".$_POST['dernierSurfacage']."' WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."' ";
								$conn->query($sql_update);
              } elseif($sid_select_option != 2) {
		        		$sql_update = "UPDATE Sentiers SET maj='".$_POST['maj']."' WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."' ";
								$conn->query($sql_update);
              }
							$sql_type_3 = "SELECT * FROM Sentiers WHERE type = '3' AND id = '".$_GET['editid']."'";
							$result_type_3 = $conn->query($sql_type_3);
							if ($result_type_3->num_rows > 0) {
								$sql_update = "UPDATE Sentiers SET auteur='Club' WHERE id = '".$_GET['editid']."' ";
								$conn->query($sql_update);
							}

					echo '<meta http-equiv="refresh" content="0; URL='.get_site_url().'/wp-admin/admin.php?page=conditions_de_sentiers_club" />';
					exit();
					$success = 'Thank you! Your entry is updated';
				}
			}

		}
		$sql = "SELECT * FROM Sentiers WHERE id = '".$_GET['editid']."' AND clubNo = '".$site_id."'";
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
			                            <input type="text" class="form-control form-control-sm" name="sentierNom" id="sentierNom" value="'.$row['sentierNom'].'"'; if($is_user_admin != 1 AND $row['type'] == 3) { echo 'readonly';} echo'>
			                        </div>
			                        <div class="col">
			                            <label for="type" class="form-label">Type (*)</label>
			                            <select class="form-control form-control-sm" name="type" id="type" '; if($is_user_admin != 1 AND $row['type'] == 3) { echo 'readonly';}	echo'>
			                            	<option value="0" '; if($row['type'] == 0) { echo 'selected'; } echo ' >Sentier local</option>
			                            	<option value="1" '; if($row['type'] == 1) { echo 'selected'; } echo ' >Sentier régional</option>
			                            	<option value="2" '; if($row['type'] == 2) { echo 'selected'; } echo ' >Sentier provincial</option>
			                            	<option value="3" '; if($row['type'] == 3) { echo 'selected'; } echo ' >État du club</option>
			                            	<option value="4" '; if($row['type'] == 4) { echo 'selected'; } echo ' >Sentiers provincial et régional</option>
			                            	<option value="5" '; if($row['type'] == 5) { echo 'selected'; } echo ' >Sentiers provincial et local</option>
			                            	<option value="6" '; if($row['type'] == 6) { echo 'selected'; } echo ' >Sentiers régional et local</option>
			                            	<option value="7" '; if($row['type'] == 7) { echo 'selected'; } echo ' >Sentiers provincial, régional et local</option>
			                            	<option value="8" '; if($row['type'] == 8) { echo 'selected'; } echo ' >Pont de glace</option>
			                            	<option value="9" '; if($row['type'] == 9) { echo 'selected'; } echo ' >Sentier sur glace</option>
		                            	</select>
			                        </div>';
			                        echo '<div class="col">
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
			                        </div>';
			                        if($sid_select_option != 2) {
				                        echo '<div class="col">
				                            <label for="maj" class="form-label">MAJ</label>
				                            <input type="date" class="form-control form-control-sm" name="maj" id="maj" value="'.$row['maj'].'">
				                        </div>';
				                      }
			                        if($sid_select_option != 1) {
				                        if($row['type'] != 3) {
					                        echo '<div class="col">
					                            <label for="dernierSurfacage" class="form-label">Dernier surfaçage</label>
					                            <input type="date" class="form-control form-control-sm" name="dernierSurfacage" id="dernierSurfacage" value="'.$row['dernierSurfacage'].'">
					                        </div>';
					                      }
					                    }
				                        echo '<div class="col">
				                            <label for="actif" class="form-label">Actif</label>
				                            <select class="form-control form-control-sm" name="actif" id="actif">
				                            	<option value="1" '; if($row['actif'] == 1) { echo 'selected'; } echo ' >Yes</option>
				                            	<option value="0" '; if($row['actif'] == 0) { echo 'selected'; } echo ' >No</option>
				                            </select>
				                        </div>';
			                    echo '</div>
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
		$wp_site_id = get_current_blog_id();
		$site_id = get_blog_option( $wp_site_id, 'sid_club_id' );
		$region_name = get_blog_option( $wp_site_id, 'sid_region_name' );
  	$club_name = get_blog_option( $wp_site_id, 'sid_club_name');
		$sid_select_option = get_blog_option( $wp_site_id, 'sid_select_option' );

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
				$sql = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type != 3 AND clubNo = '".$site_id."'";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$order = $row['total_sentiers'] + 1;
					}
				}

				$total_type_3 = 0;
	        	if($_POST['type'] == 3) {
					$sql = "SELECT count(*) as total_sentiers FROM Sentiers WHERE type = 3 AND clubNo = '".$site_id."'";
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
	        		if($sid_select_option == 1) {
	        			$_POST['dernierSurfacage'] == '';
	        		} elseif($sid_select_option == 2) {
	        			$_POST['maj'] == '';
	        		}
					$sql = "INSERT INTO Sentiers (clubNo, clubNom, region, sentierNom, type, etat, conditions, ordre, commentaires, comments, actif, distance, maj, dernierSurfacage)
					VALUES ('".$site_id."', '".$club_name."', '".$region_name."', '".$_POST['sentierNom']."', '".$_POST['type']."', '".$_POST['etat']."', '".$_POST['conditions']."', '".$order."', '".$_POST['commentaires']."', '".$_POST['comments']."', '".$_POST['actif']."', '".$_POST['distance']."', '".$_POST['maj']."', '".$_POST['dernierSurfacage']."')";
					$conn->query($sql);
					echo '<meta http-equiv="refresh" content="0; URL='.get_site_url().'/wp-admin/admin.php?page=conditions_de_sentiers_club" />';
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
	                            <label for="type" class="form-label">Type (*)</label>
	                            <select class="form-control form-control-sm" name="type" id="type">
		                            	<option value="0">Sentier local</option>
		                            	<option value="1">Sentier régional</option>
		                            	<option value="2">Sentier provincial</option>
		                            	<option value="3">État du club</option>
		                            	<option value="4">Sentiers provincial et régional</option>
		                            	<option value="5">Sentiers provincial et local</option>
		                            	<option value="6">Sentiers régional et local</option>
		                            	<option value="7">Sentiers provincial, régional et local</option>
		                            	<option value="8">Pont de glace</option>
		                            	<option value="9">Sentier sur glace</option>
	                            </select>
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
	                        </div>';
	        								if($sid_select_option != 1) {
		                        echo '<div class="col">
		                            <label for="dernierSurfacage" class="form-label">Dernier surfaçage</label>
		                            <input type="date" class="form-control form-control-sm" name="dernierSurfacage" id="dernierSurfacage" value="'.date("Y-m-d").'">
		                        </div>';
							        		} else if($sid_select_option != 2) {
			                        echo '<div class="col">
			                            <label for="actif" class="form-label">Actif</label>
			                            <select class="form-control form-control-sm" name="actif" id="actif">
			                            	<option value="1">Yes</option>
			                            	<option value="0">No</option>
			                            </select>
			                        </div>';
		                      }
	                    echo '</div>
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
		$wp_site_id = get_current_blog_id();
		$site_id = get_blog_option( $wp_site_id, 'sid_club_id' );
		$sid_select_option = get_blog_option( $wp_site_id, 'sid_select_option' );
		if(!empty($site_id) AND $site_id > 0) {
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
	    if(isset($_POST['update_multi_club'])) {
				$wp_site_id = get_current_blog_id();
				$site_id = get_blog_option( $wp_site_id, 'sid_club_id' );
				$region_name = get_blog_option( $wp_site_id, 'sid_region_name' );
				$club_name = get_blog_option( $wp_site_id, 'sid_club_name');

				$list_of_trait = explode (",", $_POST['list_of_trait']); 

				foreach ($list_of_trait as $key => $value) {
					if(!empty($value)) {
						$update_done = 0;
						if($_POST['etat'] != '--') {
							$sql_update = "UPDATE Sentiers SET etat='".$_POST['etat']."' WHERE id = '".$value."' AND clubNo = '".$site_id."' ";
							$conn->query($sql_update);
							$update_done = 1;
						}
						if($_POST['conditions'] != '--') {
							$sql_update = "UPDATE Sentiers SET conditions='".$_POST['conditions']."' WHERE id = '".$value."' AND clubNo = '".$site_id."' ";
							$conn->query($sql_update);
							$update_done = 1;
						}
						if($_POST['maj'] != '') {
							$sql_update = "UPDATE Sentiers SET maj='".$_POST['maj']."' WHERE id = '".$value."' AND clubNo = '".$site_id."' ";
							$conn->query($sql_update);
							$update_done = 1;
						}
						if($_POST['dernierSurfacage'] != '') {
							$sql_update = "UPDATE Sentiers SET dernierSurfacage='".$_POST['dernierSurfacage']."' WHERE id = '".$value."' AND clubNo = '".$site_id."' ";
							$conn->query($sql_update);
							$update_done = 1;
						}
						if($_POST['commentaires'] != '') {
							$sql_update = "UPDATE Sentiers SET commentaires='".$_POST['commentaires']."' WHERE id = '".$value."' AND clubNo = '".$site_id."' ";
							$conn->query($sql_update);
							$update_done = 1;
						}
						if($_POST['comments'] != '') {
							$sql_update = "UPDATE Sentiers SET comments='".$_POST['comments']."' WHERE id = '".$value."' AND clubNo = '".$site_id."' ";
							$conn->query($sql_update);
							$update_done = 1;
						}
						if($update_done == 1) {
							$sql_type_3 = "SELECT * FROM Sentiers WHERE type = '3' AND id = '".$value."'";
							$result_type_3 = $conn->query($sql_type_3);
							if ($result_type_3->num_rows > 0) {
								$sql_update = "UPDATE Sentiers SET auteur='Club' WHERE id = '".$value."' ";
								$conn->query($sql_update);
							}
						}

					}
				}


	    }
			if(isset($_GET['deleteid'])) {
				$sql_type_3 = "SELECT * FROM Sentiers WHERE type = '3' AND clubNo = '".$site_id."' AND id = '".$_GET['deleteid']."'";
				$result_type_3 = $conn->query($sql_type_3);
				if ($result_type_3->num_rows > 0) {
					if($user_is_subscriber == 1) {
						$sql = "DELETE FROM Sentiers WHERE id = '".$_GET['deleteid']."' AND clubNo=".$site_id;
						$conn->query($sql);
					}
				} else {
					$sql = "DELETE FROM Sentiers WHERE id = '".$_GET['deleteid']."' AND clubNo=".$site_id;
				}
			}
			$sql = "SELECT * FROM Sentiers WHERE type != '3' AND clubNo = '".$site_id."' ORDER BY ordre ASC";
			$result = $conn->query($sql);
			$sql_type_3 = "SELECT * FROM Sentiers WHERE type = '3' AND clubNo = '".$site_id."' ORDER BY ordre ASC";
			$result_type_3 = $conn->query($sql_type_3);

			echo "<a href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers_club&addnew' class='btn btn-primary'>Ajouter un sentier</a>";
			echo " | <a href='#' class='btn btn-primary editall'>Modifier la sélection</a>";
			echo "<table class='table' id='tblLocations' data-ajax-url='".admin_url('admin-ajax.php')."'>";
			echo '<thead><tr>
			<th scope="col"><input type="checkbox" class="select_all_checkbox"></th>
			<th scope="col">Sentier</th>
			<th scope="col">État</th>
			<th scope="col">Conditions</th>';
			if($sid_select_option != 2) {
				echo '<th scope="col">MAJ</th>';
			}
			if($sid_select_option != 1) {
				echo '<th scope="col">Dernier surfaçage</th>';
			}
			echo '<th scope="col">Action</th>
			</tr></thead>';
				if ($result_type_3->num_rows > 0) {
				  while($row = $result_type_3->fetch_assoc()) {
						echo"<tr data-id='".$row['id']."' "; if($row['type'] == 3) { echo "style='background:#D3D3D3;'";} echo ">
						<td>";  if($row['type'] != 3) { echo "<input type='checkbox' name='checkbox[]' data-id='".$row['id']."' class='multiselect'>"; } echo "</td>
						<td><img src='".plugin_dir_url( __FILE__ )."/images/".$row['type'].".png'> ".$row['sentierNom']; if(empty($row['commentaires']) AND empty($row['comments'])) { } else { echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/note-.png">'; } echo "</td>
						<td>"; if($row['etat'] == 0){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['etat'] == 1){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/ouvert.png">'; } elseif($row['etat'] == 2){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/partiel.png">'; } elseif($row['etat'] == 3){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/ferme.png">'; } echo "</td>
						<td>"; if($row['conditions'] == 0){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['conditions'] == 1){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile1.png">'; } elseif($row['conditions'] == 2){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile2.png">'; } elseif($row['conditions'] == 3){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile3.png">'; } elseif($row['conditions'] == 4){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile4.png">'; } elseif($row['conditions'] == 5){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile5.png">'; } echo "</td>";
						if($sid_select_option != 2) {
							echo "<td>".$row['maj']."</td>";
						}
						if($sid_select_option != 1) {
							echo "<td></td>";
						}
						echo "<td><a href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers_club&editid=".$row['id']."'>Édition</a>";
						if($user_is_subscriber == 1) {
							echo " | <a href='#' data-href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers_club&deleteid=".$row['id']."' class='delete'>Supprimer</a>";echo "</td></tr>";
						}
				  }
				}
				if ($result->num_rows > 0) {
				  // output data of each row
				  while($row = $result->fetch_assoc()) {
					echo"<tr data-id='".$row['id']."' "; if($row['type'] == 3) { echo "style='background:#D3D3D3;'";} echo ">
					<td>";  if($row['type'] != 3) { echo "<input type='checkbox' name='checkbox[]' data-id='".$row['id']."' class='multiselect'>"; } echo "</td>
					<td><img src='".plugin_dir_url( __FILE__ )."/images/".$row['type'].".png'> ".$row['sentierNom']; if(empty($row['commentaires']) AND empty($row['comments'])) { } else { echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/note-.png">'; } echo "</td>
					<td>"; if($row['etat'] == 0){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['etat'] == 1){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/ouvert.png">'; } elseif($row['etat'] == 2){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/partiel.png">'; } elseif($row['etat'] == 3){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/ferme.png">'; } echo "</td>
					<td>"; if($row['conditions'] == 0){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['conditions'] == 1){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile1.png">'; } elseif($row['conditions'] == 2){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile2.png">'; } elseif($row['conditions'] == 3){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile3.png">'; } elseif($row['conditions'] == 4){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile4.png">'; } elseif($row['conditions'] == 5){ echo '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile5.png">'; } echo "</td>";
					if($sid_select_option != 2) {
						echo "<td>".$row['maj']."</td>";
					}
					if($sid_select_option != 1) {
						echo "<td>".$row['dernierSurfacage']."</td>";
					}
					echo "<td><a href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers_club&editid=".$row['id']."'>Édition</a> | <a href='#' data-href='".get_site_url()."/wp-admin/admin.php?page=conditions_de_sentiers_club&deleteid=".$row['id']."' class='delete'>Supprimer</a></td></tr>";
				  }
				}
			echo "</table>";
			echo '<div id="dialog-confirm" title="Détruire ce sentier?">
			</div>';
		}
	}
	$conn->close();
}

add_action('admin_footer', 'sidtechno_custom_fields');
function sidtechno_custom_fields(){
    global $pagenow;
		$is_user_admin = sid_user_has_role(get_current_user_id(), 'admin');
    if( 'site-info.php' == $pagenow OR 'site-new.php' == $pagenow ) {

        if(ctype_digit($_GET['id']))
            $custom_limit_site_id = $_GET['id']; 
        else
            $custom_limit_site_id ='';

        ?><table><tr class="sidtechno_custom_fields">
            <th scope="row">Club ID</th>
            <td><input type="text" name="blog[sid_club_id]" value="<?=get_blog_option( $custom_limit_site_id, 'sid_club_id' );?>"/></td>
        </tr>
        <tr class="sidtechno_custom_fields">
            <th scope="row">Region Name</th>
            <td><input type="text" name="blog[sid_region_name]" value="<?=get_blog_option( $custom_limit_site_id, 'sid_region_name' );?>"/></td>
        </tr>
				<tr class="sidtechno_custom_fields">
            <th scope="row">Create new select option</th>
            <td>
							<select class="form-control form-control-sm" name="blog[sid_select_option]" id="">
								<option value="0" <?php if(get_blog_option( $custom_limit_site_id, 'sid_select_option' ) == 0) { echo 'selected'; }?>>Utiliser toutes les dates</option>
								<option value="1" <?php if(get_blog_option( $custom_limit_site_id, 'sid_select_option' ) == 1) { echo 'selected'; }?>>Date mise à jour seulement</option>
								<option value="2" <?php if(get_blog_option( $custom_limit_site_id, 'sid_select_option' ) == 2) { echo 'selected'; }?>>Date surfaçage seulement</option>
							</select>
						</td>
        </tr>        
        <tr class="sidtechno_custom_fields">
            <th scope="row">Club Name</th>
            <td><input type="text" name="blog[sid_club_name]" value="<?=get_blog_option( $custom_limit_site_id, 'sid_club_name');?>"/></td>
        </tr></table>
        <script>jQuery(function($){
            $('.form-table tbody').append($('.sidtechno_custom_fields'));
        });</script><?php
    }
}

function sidtechno_save_custom_fields(){
    global $pagenow;
		$is_user_admin = sid_user_has_role(get_current_user_id(), 'admin');
    if( 'site-info.php' == $pagenow && isset($_REQUEST['action']) && 'update-site' == $_REQUEST['action'] ) 
    {
        if ( isset( $_POST['blog']['sid_region_name'] ) ) 
        {
        	$new_field_value_region = $_POST['blog']['sid_region_name'];
            update_blog_option( $_POST['id'], 'sid_region_name', $new_field_value_region );

        		$new_field_value_select_option = $_POST['blog']['sid_select_option'];
            update_blog_option( $_POST['id'], 'sid_select_option', $new_field_value_select_option );

            $new_field_value_region = $_POST['blog']['sid_club_id'];
            update_blog_option( $_POST['id'], 'sid_club_id', $new_field_value_region );

            $new_field_value_club = $_POST['blog']['sid_club_name'];
            update_blog_option( $_POST['id'], 'sid_club_name', $new_field_value_club );

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

						$sql = "UPDATE Sentiers SET club='".$new_field_value_club."', region='".$new_field_value_region."' WHERE clubNo=".$_POST['id'];
						$conn->query($sql);

        }
    }
}
add_action('admin_init', 'sidtechno_save_custom_fields');


function sid_update_order_list() {
	if(isset($_POST['page_id_array'])) {
		$page_id_array = $_POST['page_id_array'];
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
		$count = 1;
		foreach ($page_id_array as $post_key => $post_id) {
			$sql = "SELECT * FROM Sentiers WHERE id = '".$post_id."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					if($row['type'] != 3) {
        		$sql_update = "UPDATE Sentiers SET ordre='".$count."' WHERE id = '".$post_id."' ";
						$conn->query($sql_update);
						$count++;
					}
				}
			}
		}
		echo json_encode(array("result"=>"success"));
	}
	exit();
}
add_action('wp_ajax_update_order_list', 'sid_update_order_list');
add_action('wp_ajax_nopriv_update_order_list', 'sid_update_order_list');
function sid_user_has_role($user_id, $role_name)
{
    $user_meta = get_userdata($user_id);
    $user_roles = $user_meta->roles;
    return in_array($role_name, $user_roles);
}
?>