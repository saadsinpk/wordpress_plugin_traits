<?php /*
Plugin Name: Legende conditions
Plugin URI: http://sitechno.com
description: Legende conditions
Version: 1.2
Author: Muhammad Saad
Author URI: http://sidtechno.com
*/


function sid_techno_legende_conditions($attr) { 
	if(!is_admin()){
		$html = '';
		$html .= '<h3>Légende</h3><hr>
		<table>
			<thead>
				<tr>
					<td><b>Conditions observees</b></td>
					<td><b>Etat du sentier</b></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/etoile5.png"> Excellente</td>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/ouvert.png"> Ouvert</td>
				</tr>
				<tr>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/etoile4.png"> Très bonne</td>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/partiel.png"> Partiellement ouvert</td>
				</tr>
				<tr>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/etoile3.png"> Bonne</td>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/ferme.png"> Fermé</td>
				</tr>
				<tr>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/etoile2.png"> Moyenne</td>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/inconnu.png"> Information non-disponible</td>
				</tr>
				<tr>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/etoile1.png"> Mauvaise</td>
					<td></td>
				</tr>
				<tr>
					<td><img src="'.plugin_dir_url( __FILE__ ).'/images/etoile0.png"> Aucune observation</td>
					<td></td>
				</tr>
			</tbody>
		</table>';
		return $html;
	}
} 
// register shortcode
add_shortcode('legende_conditions', 'sid_techno_legende_conditions'); 


?>