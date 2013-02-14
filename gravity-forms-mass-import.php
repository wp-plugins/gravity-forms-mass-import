<?php 
/**
 * Plugin Name: Gravity Forms Mass Import
 * Plugin URI: http://wordpress.org/extend/plugins/gravity-forms-mass-import/
 * Donate link: http://aryanduntley.com/donations
 * Description: Allows mass import of form entries derived from CSV files.
 * CSV headers are indicated if unkown in order to allow users to organize correctly structured CSV files.
 * Version: 1.1
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
/*Enqueue the javascript that calls the ajax when you select a form and the headers table shows up*/function impform_scripts_plug() {	if(is_admin()){		wp_register_script( 'backcallsc', plugins_url( 'js/backtocall.js', __FILE__ ), array('jquery'));			wp_enqueue_script('backcallsc');					$file_for_jav = admin_url('admin-ajax.php');		$tran_arr = array( 'jaxfile' => $file_for_jav );		wp_localize_script( 'backcallsc', 'fromphp', $tran_arr );	}} add_action('init', 'impform_scripts_plug');/*This is the ajax callback.  dataVal is passed with the form number and get_field_list_massimport is called*/add_action('wp_ajax_give_backstuff', 'aad_callback');add_action('wp_ajax_nopriv_give_backstuff', 'aad_callback');function aad_callback() {	if(isset($_POST['dataVal'])){		$retdat = $_POST['dataVal'];		$rethmil = get_field_list_massimport($retdat);		echo json_encode(array("sucs"=>$rethmil));	}	else {		echo json_encode(array("sucs"=>"WRONG"));	}	die();}/*Get all the fields relative to the selected form.  Filter out headers that are not stored in the database or that I have not supported because I was too lazy.  Return table html that displays a list of valid headers and how to info.*/function get_field_list_massimport($num){	$ararr = apply_filters('massimport_getfields', $num);	$html = '<div style="margin-top: 20px;padding: 5px;background:#E4E4E4;"><strong>ALL csv files should be comma delimited and double quote encased.  Make sure you export your files from excel or other similar programs appropriately.  Additionaly, if you are using special characters or language characters foreign to English, please make sure that you use UTF-8 encoding before saving the file! </strong></div><br/><br/><h3>These are the fields within the form you wish to import.  Make sure the CSV headers match those listed below:</h3><br/><div style="clear:both"></div><div style="width: 90%;float:left"><table style="border:1px solid #ceccce;-moz-border-radius: 5px;border-radius: 5px;width:100%;"><colgroup><col style="" /><col style="border-left:1px solid #ceccce;" /><col style="border-left:1px solid #000;" /></colgroup><thead><tr><th style="background: #ceccce; color:white; text-align:left;">ID</th><th style="background: #ceccce; color:white;text-align:left;">LABEL</th><th style="background: #ceccce;color:white;text-align:left;">TYPE</th><th style="background: #ceccce;color:white;text-align:left;">Syntax</th></tr></thead><tbody>';	$j = 0;	for($i=0;$i<sizeof($ararr);$i++){		$checkarey = array("section","page","html","fileupload","captcha","post_title","post_content","post_excerpt","post_tags","post_category","post_image","post_custom","product","quantity","option","shipping","total");		if(in_array($ararr[$i]['type'], $checkarey)){}		elseif($ararr[$i]['id'] == null){} 				else{		if($j%2 == 0){		$evenstyle = 'style = "background: #f5f5f5;-webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;-moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;"';		}else {$evenstyle='';}		$j++;		$html .= "<tr ". $evenstyle . "><td>" . $ararr[$i]['id'] . "</td><td style='width:auto;white-space: nowrap;padding-right:5px;'>" . $ararr[$i]['label'] . "</td><td>" . $ararr[$i]['type'] . "</td><td>";		switch($ararr[$i]['type']){			case 'list':				$html .= "example: apples,oranges,peaches";				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";								$pieces = explode(",", $ararr[$i]['value']);				for($ms=0;$ms<sizeof($pieces);$ms++){					$html .= $pieces[$ms] . ", ";				}}}			break;			case 'multiselect':				$html .= "example: apples,oranges,peaches";				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";								$pieces = explode(",", $ararr[$i]['value']);				for($ms=0;$ms<sizeof($pieces);$ms++){					$html .= $pieces[$ms] . ", ";				}}}			break;			case 'checkbox':				$html .= "example: apples,oranges,peaches";				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";								$pieces = explode(",", $ararr[$i]['value']);				for($ms=0;$ms<sizeof($pieces);$ms++){					$html .= $pieces[$ms] . ", ";				}}}			break;			case 'select':				$html .= "example: Some Entry";				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";								$pieces = explode(",", $ararr[$i]['value']);				for($ms=0;$ms<sizeof($pieces);$ms++){					$html .= $pieces[$ms] . ", ";				}}}			break;			case 'radio':				$html .= "example: Some Entry";				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";								$pieces = explode(",", $ararr[$i]['value']);				for($ms=0;$ms<sizeof($pieces);$ms++){					$html .= $pieces[$ms] . ", ";				}}}			break;			case 'address':				$html .= "example: (address 1),(address 2),(city),(state),(zip),(country). IMPORTANT: if you are using the address type, and do not use a filed above, leave an empty space <span style='color:red'>** 123 fake st,,San Diego,CA,92109, ** </span> (in that example, address2 and country were ommitted.";			break;			case 'name':				$html .= "example: (firstname),(lastname). **John,Doe** <span style='color:red'>If no first Name: ,Doe ** If no Last Name:  John,</span>";			break;			default:				$html .= "example: Some Entry";		}				$html .= "</td></tr>";				}	}	$html .= '</tbody></table></div><div style="clear:both"><div style="width: 90%"><div style="float:left;"> <ul><li>Only the <strong>LABEL</strong> of the table above should be in the header.</li><li>The &quot;<strong>TYPE</strong>&quot; field above tells you the type of field the associated name represents.  If the type of field is one that will accept multiple inputs and you have multiple values for that field (such as a list, or multiple select), make sure the values are entered as a comma separated group.  For instance, if you have a list of fruits: apples, oranges, and pears, the entry in that field should look like this:  <strong>apples,oranges,pears</strong>.</ul></div><br/><div><p><strong>IF</strong> you are using type: "address" as a field, you should have five <strong>COMMA SEPARATED</strong> fields that represent the following: <strong>(address 1)&nbsp;&nbsp ,&nbsp;&nbsp (address 2) &nbsp;&nbsp,&nbsp;&nbsp (city) &nbsp;&nbsp,&nbsp;&nbsp (state)&nbsp;&nbsp ,&nbsp;&nbsp (zip) &nbsp;&nbsp,&nbsp;&nbsp (country)</strong>.  If you have any empty fields, MAKE SURE you still put the comma! For example, if you omit address 2, your input for the address field should look like this: <strong>(address 1)&nbsp;&nbsp;&nbsp;,,&nbsp;&nbsp;&nbsp;(city)&nbsp;&nbsp,&nbsp;&nbsp(state)&nbsp;&nbsp,&nbsp;&nbsp (zip)&nbsp;&nbsp ,&nbsp;&nbsp (country)</strong>.</p></div><div><p><strong>IF</strong> you are using the "NAME" field type, your input into that filed should look like this in your csv (also in your excel file) file: <strong>(first name)&nbsp;&nbsp; ,&nbsp;&nbsp; (last name) </strong>.  If you omit, for instance, the last name, you should still put a comma after the first name like this: <strong>(first name)&nbsp;&nbsp;,</strong> .  Or <strong>,&nbsp;&nbsp; (last name)!</strong></p></div><div><p><strong>Making sure you add the commas appropriately will ensure that the correct fields will be filled in with the correct data, otherwise you may get certain data in the wrong fields</strong> (like the city in the address field and the like).</p></div><div><p><strong>TAKE NOTE:</strong> This mass importer works only for the listings in "Standard Fields" and "Advanced Fields" in the "New Form" area. <strong>This will NOT! work for custom fields or price fields.  This will NOT work for "CAPTCHA" OR "FILE UPLOAD" in the Advanced Field listings!</strong> <br/>Additionally: <br/><strong>IF you are entering data for any field that was set up to with a default list of values such as a checkbox or radio button list, although the "Recorded Values" are listed above, if you enter something that is not a recorded value, it will be entered into the database as a record.  This program does not check to verify whether an entered data value is included in the predefined list.</strong></p></div><br/><div style="clear:both"></div></div><div class="uperlod" style=""><form action="admin.php?page=gf_import_entries" method="post" enctype="multipart/form-data"><input type="hidden" name="mode" value="submit"><input type="hidden" name="formnum" value="'.$num.'" /><input type="file" name="csv_file" /><input type="submit" value="Import" /></form></div>';	return $html;}/*Just adding the admin settings page and putting the link in the gravity forms menu section*/
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
}/*Gets a list of all the saved forms.  Returns each list with title and id.*/
function get_me_all_forms(){
	$firmarr = array();
	$i=0;
	$forms = RGFormsModel::get_forms();
        foreach($forms as $form){
			$firmarr[$i] = array('title'=>($form->title), 'id'=>($form->id));
			$i++;
		}
	return $firmarr;
}/*Gets the data from the database table for a specific form.  This data is stored as json, so the recursiveiteratoriterator parses that out.*/
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
/*Don't ask me why the admin print scripts/styles is there.  I may have had a purpose when originally doint this, but it's lost on me now.  This should probably be at the very top of the page.  This is the initial choose a form stuff. Gravity forms wants you to have a callback function for displaying the admin settings stuff.  It's called in the add_nother_item function above.  Show stuff div is empty until the ajax call is made upon form selection.  It is then populated with the necessary info.  It has the csv file upload and posts that info back to this page with a refresh.  Then this funct checks for that post everytime it's called. If it exists.  The file is uploaded, parsed and parsecsv.lib.php is called and does it's thing.*/
function mass_import_handler(){
	global $wpdb;
	$frmarr = get_me_all_forms();
	//add_action('admin_print_scripts', 'the_admin_scripts');
	//add_action('admin_print_styles', 'the_admin_styles');
?>
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
			//$retarn = $csv->auto($_FILES['csv_file']['tmp_name'], true, null, ',', '"');
			$retarn = $csv->parse_file($_FILES['csv_file']['tmp_name']);
			//stuff here;
			echo '<div class="updated" style="padding-bottom:10px;">'.$retarn.'</div>';
		}else{
		echo '<div class="updated" style="color: red;">It seems the file was not uploaded correctly.</div>';
		}
	}
  }
}
?>