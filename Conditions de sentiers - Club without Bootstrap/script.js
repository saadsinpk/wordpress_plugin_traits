jQuery('body').on('click', 'a.datamodal', function() {
	var title = jQuery(this).attr("data-title");
	var body_content = jQuery(this).attr("data-body");
	jQuery("#mainModalLabel").html(title);
	jQuery(".modal-body").html(body_content);
});


/*
function activate_plugin_name() {
   $role = get_role( 'editor' );
   $role->add_cap( 'manage_options' ); // capability
}
// Register our activation hook
register_activation_hook( __FILE__, 'activate_plugin_name' );

function deactivate_plugin_name() {

  $role = get_role( 'editor' );
  $role->remove_cap( 'manage_options' ); // capability
}
// Register our de-activation hook
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );`
*/
