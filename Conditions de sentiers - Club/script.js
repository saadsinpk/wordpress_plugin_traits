jQuery('body').on('click', 'a.datamodal', function() {
	var title = jQuery(this).attr("data-title");
	var body_content = jQuery(this).attr("data-body");
	jQuery("#mainModalLabel").html(title);
	jQuery(".modal-body").html(body_content);
});
