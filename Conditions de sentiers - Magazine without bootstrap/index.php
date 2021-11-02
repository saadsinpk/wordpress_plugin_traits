<?php /*
Plugin Name: Conditions de sentiers - Magazine
Plugin URI: http://sitechno.com
description: Conditions de sentiers
Version: 1.1
Author: Muhammad Saad
Author URI: http://sidtechno.com
*/

function sid_techno_conditions_de_sentiers_two($attr) { 
	if(!is_admin()){
		wp_enqueue_script('plugin_one_script', plugin_dir_url( __FILE__ ).'/script.js?2', array('jquery'));
		$html ='';
		// $site_id = $attr['club_id'];
		$html .= '
		<style>
			.modal-body, .modal-header { text-align:center;}
			.modal-header, .modal-footer { background: lightgray;}
			.modal-footer button {margin:0 auto;}
		</style>
		';

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

		if(isset($_GET['reg'])) {
			$sql = "SELECT * FROM Sentiers WHERE actif = '1' AND type='3' AND region = '".$_GET['reg']."' ORDER BY region, clubNom ASC";
		} else {
			$sql = "SELECT * FROM Sentiers WHERE actif = '1' AND type='3' ORDER BY region, clubNom ASC";
		}
		$result = $conn->query($sql);
		$html .= "<table class='table'>";
		$html .= '<thead><tr>
		<th scope="col">Sentiers</th>
		<th scope="col">État</th>
		<th scope="col">Conditions</th>
		<th scope="col">Dernier surfaçage</th>
		<th scope="col">Mise à jour</th>
		</tr></thead>';
			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
				$html .= "<tr>
				<td>".$row['sentierNom']; if(empty($row['commentaires']) AND empty($row['comments'])) { } else { $html .= '<a href="#" class="datamodal" data-toggle="modal" data-target="#mainModal" data-title="'.$row['sentierNom'].'" data-body="'; if(!empty($row['commentaires'])) { $html .='<h2>Commentaires</h2><br>'.$row['commentaires']; } $html .= '<hr>'; if(!empty($row['comments'])) { $html .= '<br><h2>Comments</h2><br>'.$row['comments']; } $html .='"><img src="'.plugin_dir_url( __FILE__ ).'/images/note-.png"></a>'; } $html .= "</td>
				<td style='text-align:center;'>"; if($row['etat'] == 0){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['etat'] == 1){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/ouvert.png">'; } elseif($row['etat'] == 2){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/partiel.png">'; } elseif($row['etat'] == 3){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/ferme.png">'; } $html .= "</td>
				<td style='text-align:center;'>"; if($row['conditions'] == 0){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png">'; } elseif($row['conditions'] == 1){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile1.png">'; } elseif($row['conditions'] == 2){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile2.png">'; } elseif($row['conditions'] == 3){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile3.png">'; } elseif($row['conditions'] == 4){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile4.png">'; } elseif($row['conditions'] == 5){ $html .= '<img src="'.plugin_dir_url( __FILE__ ).'/images/etoile5.png">'; } $html .= "</td>
				<td>".$row['dernierSurfacage']."</td>
				<td>".$row['maj']."</td></tr>";
			  }
			}
		$html .= "</table>";
		$html .= '<!-- Modal -->
		<div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="mainModalLabel">Modal title</h1>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        ...
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
		      </div>
		    </div>
		  </div>
		</div>';
		return $html;
	}
} 
// register shortcode
add_shortcode('conditions_de_sentiers_magazine', 'sid_techno_conditions_de_sentiers_two'); 


?>