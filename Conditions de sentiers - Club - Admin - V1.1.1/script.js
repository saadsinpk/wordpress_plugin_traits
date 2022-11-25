jQuery(document).ready(function(){
    jQuery(function(){
        jQuery( "#dialog-confirm" ).dialog({
            resizable: false,
            height:140,
            width:300,
            modal: true,
            autoOpen: false,
            buttons: {
                "Détruire": function() {
                    jQuery( this ).dialog( "close" );
                    var delete_url = jQuery(this).closest("#dialog-confirm").attr("data-href");
                    window.location.replace(delete_url);
                    return true;
                },
                Annuler: function() {
                    jQuery( this ).dialog( "close" );
                    return false;
                }
            },
            create:function () {
                jQuery(this).closest(".ui-dialog")
                    .find(".ui-button:nth-child(1)") // the first button
                    .addClass("btn btn-danger");
                jQuery(this).closest(".ui-dialog")
                    .find(".ui-button:nth-child(2)") // the first button
                    .addClass("btn btn-success");
            }
        });
        jQuery( ".delete" ).click(function() {
            var url = jQuery(this).attr("data-href");
            jQuery("#dialog-confirm").attr("data-href", url)
            jQuery( "#dialog-confirm" ).dialog( "open" );
            return false;
        });
    });
    var ajax_url = jQuery("#tblLocations").attr("data-ajax-url");
    jQuery( "#tblLocations tbody" ).sortable({
        placeholder : "ui-state-highlight",
        update  : function(event, ui)
        {
            jQuery("body").append('<div id="loading" style="width: 100%;height: 100%;position: fixed;background: rgba(113, 148, 48, 0.3);top: 0;left: 0;z-index: 10;text-align: center;vertical-align: middle;padding: 9px 0;font-weight: bold;color: #fff;z-index: 1000;border-radius: 10px;font-size: 50px;"><div class="center_fix_verticle" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);"><span>loading</span></div>    </div>');
            var page_id_array = new Array();
            jQuery('#tblLocations tbody tr').each(function(){
                page_id_array.push(jQuery(this).attr("data-id"));
            });
            jQuery.ajax({
                url:ajax_url,
                method:"POST",
                data:{action:"update_order_list", page_id_array:page_id_array},
                success:function(data)
                {
                    jQuery("#loading").remove();
                }
            });
        }
    });
    jQuery( ".select_all_checkbox" ).change(function() {
        if (jQuery(this).is(':checked')) {
            jQuery('.multiselect').prop('checked', true); // Unchecks it
        } else {
            jQuery('.multiselect').prop('checked', false); // Unchecks it
        }
    });
    jQuery( ".editall" ).click(function() {
        // multiselect
        var list_of_trait = '';
        var var_checked = 0;
        jQuery( ".multiselect" ).each(function( index ) {
            if(jQuery(this).is(':checked')) {
                var_checked = 1;
                list_of_trait = list_of_trait+jQuery(this).attr("data-id")+',';
            }
        });
        if(var_checked != 0) {
            jQuery("body").append('<div id="popupform" style="width: 100%;height: 100%;position: fixed;background: rgba(113, 148, 48, 0.3);top: 0;left: 0;z-index: 10;text-align: center;vertical-align: middle;padding: 9px 0;font-weight: bold;color: #fff;z-index: 1000;border-radius: 10px;"><div class="center_fix_verticle" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);background: lightgray;width: 60%;height: 60%;border-radius: 10px;border: 1px solid;overflow-y: scroll;"><form action="" method="post"><input type="hidden" name="list_of_trait" value="'+list_of_trait+'">                         <div class="tab-content col-md-12 border shadow p-4" id="ship-tab-content">                             <div class="mb-3">                                                                                                                                                                                  <div class="col" style="text-align:left;">                                       <label for="etat" class="form-label">État (*)</label>                                       <select class="form-control form-control-sm" name="etat" id="etat">                                         <option value="--">Aucun changement</option> <option value="0">État non disponible</option>                                           <option value="1">Ouvert</option>                                         <option value="2">Partiellement ouvert</option>                                          <option value="3">Fermé</option>                                     </select>                                   </div>                                  <div class="col" style="text-align:left;">                                       <label for="conditions" class="form-label">Sélectionner sentier(s) (*)</label>                                       <select class="form-control form-control-sm" name="conditions" id="conditions">                                         <option value="--">Aucun changement</option> <option value="0">Non-disponible</option>                                            <option value="1">Mauvaises</option>                                           <option value="2">Moyennes</option>                                          <option value="3">Bonnes</option>                                          <option value="4">Très bonnes</option>                                         <option value="5">Excellentes</option>                                     </select>                                   </div>                                  <div class="col" style="text-align:left;">                                       <label for="commentaires" class="form-label">Commentaires</label>                                       <textarea class="form-control form-control-sm" name="commentaires" id="commentaires"></textarea>                                    </div>                                  <div class="col" style="text-align:left;">                                       <label for="comments" class="form-label">Comments</label>                                       <textarea class="form-control form-control-sm" name="comments" id="comments"></textarea>                                    </div>                                                                      <div class="col" style="text-align:left;">                                       <label for="dernierSurfacage" class="form-label" style="    width: 100%;">Dernier surfaçage</label>                                      <input type="date" name="dernierSurfacage" id="dernierSurfacage" value="">                                   </div> <div class="col" style="text-align:left;">                                       <label for="maj" class="form-label" style="    width: 100%;">MAJ</label>                                     <input type="date" name="maj" id="maj" value="">                                 </div>                                                                                                   </div>                          </div>                          <div>                               <div class="col-12 mb-3 ">                                  <button type="submit " class="btn btn-success" name="update_multi_club">Soumettre</button>                              </div>                          </div>                          </form></div>    </div>');
        } else {
            alert("Sélectionner sentier(s)");
        }
        return false;
    });
});