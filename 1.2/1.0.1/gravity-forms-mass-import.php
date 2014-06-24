<?php 
/**
 * Plugin Name: Gravity Forms Mass Import
 * Plugin URI: http://wordpress.org/extend/plugins/gravity-forms-mass-import/
 * Donate link: http://aryanduntley.com/donations
 * Description: Allows mass import of form entries derived from CSV files.
 * CSV headers are indicated if unkown in order to allow users to organize correctly structured CSV files.
 * Version: 1.0.1
 * Author: Aryan Duntley
 * Author URI: http://www.aryanduntley.com/gravity-forms-mass-import
 * License: GPLv2 or later
 * 
 *     Copyright 2012  Aryan Duntley  (email : http://www.aryanduntley.com/contact)
 * 
 *     This program is free software; you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License, version 2, as 
 *     published by the Free Software Foundation.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program; if not, see <http://www.gnu.org/licenses/>.
 * 
*/

add_filter("gform_addon_navigation", "add_nother_item");
add_filter('massimport_getfields', 'get_meta_all_fields', 10, 1);
function add_nother_item($menu_items){
$permis = current_user_can("gform_full_access");
	$menu_items[] = array(
						"name" => "gf_import_entries", 
						"label" => "Import Entries",
						"callback" => "mass_import_handler",
						"permission" => "activate_plugins");
	return $menu_items;
}
function get_me_all_forms(){
	$firmarr = array();
	$i=0;
	$forms = RGFormsModel::get_forms();
        foreach($forms as $form){
			$firmarr[$i] = array('title'=>($form->title), 'id'=>($form->id));
			$i++;
		}
	return $firmarr;
}
function get_meta_all_fields($id){
	$fledarr = array();
	$frmarr = RGFormsModel::get_forms_by_id($id);
	$fldobj = $frmarr[0]['fields'];
	$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($fldobj));
	$i = 0;
	$arrfor = array();
	$valarr = array();
	$currid = 0;
	foreach($iterator as $key=>$value) {
		if($currid != $i && $i != 0){
			$arrfor['value'] = implode(",", $valarr);
			$fledarr[$i-1] = $arrfor;$arrfor = array();
			$valarr = array();				
			$arrfor['value'] = '';
			$currid += 1;
			}
		if($key == 'id'){$arrfor[$key] = $value;}
		if($key == 'label'){$arrfor[$key] = $value;}
		if($key == 'type'){$arrfor[$key] = $value;$i++;}
		if($key == 'value'){array_push($valarr,$value);}
			
	}
	return $fledarr;
}

function mass_import_handler(){
	global $wpdb;
	$frmarr = get_me_all_forms();
	add_action('admin_print_scripts', 'the_admin_scripts');
	add_action('admin_print_styles', 'the_admin_styles');
?>
<script type="text/javascript">

jQuery(document).ready(function(){
var thi_val;
jQuery('.showstuff').hide();
jQuery('#myList').change(function () {
		thi_val = jQuery(this).val();
		jQuery.ajax({
             type: "POST",
             url: '<?php echo plugins_url( '/mass_importer_h.php' , __FILE__ ); ?>',
             data: "dataVal=" + thi_val,
             success: function(data){
			 jQuery('.showstuff').hide(300);
			 jQuery('.showstuff').html(data);
			 jQuery('.showstuff').show(300);
			 }
		});
	});
});

</script>
<div style="padding: 5px 0 20px 0;"><img style="float:left;padding-right: 20px;" src="http://aryanduntley.com/wp-content/plugins/gravityforms/images/gravity-edit-icon-32.png"><h1>Import CSV</h1> <h2>choose which form you wish to import into:</h2></div>
<div style="clear:both;padding-bottom:20px"><form style="float: left; padding-left: 20px;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_s-xclick" /><input type="hidden" name="cpp_header_image" value="http://aryanduntley.com/donatemy.png" /><input type="hidden" name="page_style" value="paypal" /><input type="hidden" name="cpp_logo_image" value="http://aryanduntley.com/donatemy.png" /><input type="hidden" name="cpp_payflow_color" value="#FAFAFA" /><input type="hidden" name="cn" value="Feel free to add any notes or comments about the plugin! Please name the plugin you are donating to so I can keep track of which are most valuable to users." /><input type="hidden" name="cpp_headerback_color" value="#F0F0F0" /><input type="hidden" name="hosted_button_id" value="ZXSGVRM9DWEUY" /><input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online!" /><img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" border="0" /></form></div>
    <p>
            <label style="float:left;padding-top:3px;">Select which form you would like to import into</label>
            <select id="myList" style="float:left; margin-left: 20px">
			<option value = "" disabled="true" selected="true"> Select One...</option>
				<?php 
				for($r=0;$r<sizeof($frmarr);$r++) {
					echo '<option value='.$frmarr[$r]['id'].'>'.$frmarr[$r]['title'].'</option>';
				}
				?>
            </select>
    </p>
	<div style="clear:both"></div>
	<div class="showstuff"></div>
	<div style="clear:both"></div>
	<div id="up_load_er"></div>
<?php
if(isset($_POST['mode'])){
if ($_POST['mode'] == "submit") {
require('parsecsv.lib.php');
		if(isset($_POST['formnum'])) {
			//$arr_rows = file($_FILES['csv_file']['tmp_name']);//You can use file_get_contents() to return the contents of a file as a string. 
			$entry_tracking_table =  $wpdb->prefix . "rg_lead";//This first in order to track lead_id for rest.
			$entry_standard_stuff =  $wpdb->prefix . "rg_lead_meta";
			$actual_entry_data =  $wpdb->prefix . "rg_lead_detail";
			$lastnum = $wpdb->get_col( $wpdb->prepare("SELECT * FROM $entry_tracking_table",0) );
			//print ($lastnum[sizeof($lastnum)-1]);
			$formnum = $_POST['formnum'];
			//echo ("<div class='updated'>All users appear to be have been imported successfully.<br/>".$formnum."</div>");
			$ararr = apply_filters('massimport_getfields', $formnum);
			//require('../../../wp-load.php');
			$csv = new parseCSV();
			$csv->last_form_id = max($lastnum);//$lastnum[sizeof($lastnum)-1];
			$csv->form_using = $formnum;
			$csv->actHeaders = $ararr;
			$retarn = $csv->auto($_FILES['csv_file']['tmp_name'], true, null, ',', '"');
			//stuff here;
			echo '<div class="updated" style="padding-bottom:10px;">'.$retarn.'</div>';
		}else{
		echo '<div class="updated" style="color: red;">It seems the file was not uploaded correctly.</div>';
		}
	}
  }
}
?>