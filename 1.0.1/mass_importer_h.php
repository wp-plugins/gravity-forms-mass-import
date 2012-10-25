<?php
require('../../../wp-load.php');
if(isset($_POST['dataVal'])){
$retdat = $_POST['dataVal'];
get_field_list_massimport($retdat);
}
function get_field_list_massimport($num){
	$ararr = apply_filters('massimport_getfields', $num);
	$html = '<div style="margin-top: 20px;padding: 5px;background:#E4E4E4;"><strong>ALL csv files should be comma delimited and double quote encased.  Make sure you export your files from excel or other similar programs appropriately.  Additionaly, if you are using special characters or language characters foreign to English, please make sure that you use UTF-8 encoding before saving the file! </strong></div><br/><br/><h3>These are the fields within the form you wish to import.  Make sure the CSV headers match those listed below:</h3><br/><div style="clear:both"></div><div style="width: 90%;float:left"><table style="border:1px solid #ceccce;-moz-border-radius: 5px;border-radius: 5px;width:100%;"><colgroup><col style="" /><col style="border-left:1px solid #ceccce;" /><col style="border-left:1px solid #000;" /></colgroup><thead><tr><th style="background: #ceccce; color:white; text-align:left;">ID</th><th style="background: #ceccce; color:white;text-align:left;">LABEL</th><th style="background: #ceccce;color:white;text-align:left;">TYPE</th><th style="background: #ceccce;color:white;text-align:left;">Syntax</th></tr></thead><tbody>';
	$j = 0;
	for($i=0;$i<sizeof($ararr);$i++){
		if($ararr[$i]['type'] == 'section' || $ararr[$i]['type'] == 'page' || $ararr[$i]['id'] == null){} else{
		if($j%2 == 0){
		$evenstyle = 'style = "background: #f5f5f5;-webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;-moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;"';
		}else {$evenstyle='';}
		$j++;
		$html .= "<tr ". $evenstyle . "><td>" . $ararr[$i]['id'] . "</td><td style='width:auto;white-space: nowrap;padding-right:5px;'>" . $ararr[$i]['label'] . "</td><td>" . $ararr[$i]['type'] . "</td><td>";
		switch($ararr[$i]['type']){
			case 'list':
				$html .= "example: apples,oranges,peaches";
				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){
				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";				
				$pieces = explode(",", $ararr[$i]['value']);
				for($ms=0;$ms<sizeof($pieces);$ms++){
					$html .= $pieces[$ms] . ", ";
				}}}
			break;
			case 'multiselect':
				$html .= "example: apples,oranges,peaches";
				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){
				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";				
				$pieces = explode(",", $ararr[$i]['value']);
				for($ms=0;$ms<sizeof($pieces);$ms++){
					$html .= $pieces[$ms] . ", ";
				}}}
			break;
			case 'checkbox':
				$html .= "example: apples,oranges,peaches";
				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){
				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";				
				$pieces = explode(",", $ararr[$i]['value']);
				for($ms=0;$ms<sizeof($pieces);$ms++){
					$html .= $pieces[$ms] . ", ";
				}}}
			break;
			case 'select':
				$html .= "example: Some Entry";
				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){
				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";				
				$pieces = explode(",", $ararr[$i]['value']);
				for($ms=0;$ms<sizeof($pieces);$ms++){
					$html .= $pieces[$ms] . ", ";
				}}}
			break;
			case 'radio':
				$html .= "example: Some Entry";
				if($ararr[$i]['value']){if($ararr[$i]['value'][0] != ""){
				$html .= "&nbsp;&nbsp;&nbsp;&nbsp;Recorded Values: ";				
				$pieces = explode(",", $ararr[$i]['value']);
				for($ms=0;$ms<sizeof($pieces);$ms++){
					$html .= $pieces[$ms] . ", ";
				}}}
			break;
			case 'address':
				$html .= "example: (address 1),(address 2),(city),(state),(zip),(country). IMPORTANT: if you are using the address type, and do not use a filed above, leave an empty space <span style='color:red'>** 123 fake st,,San Diego,CA,92109, ** </span> (in that example, address2 and country were ommitted.";
			break;
			case 'name':
				$html .= "example: (firstname),(lastname). **John,Doe** <span style='color:red'>If no first Name: ,Doe ** If no Last Name:  John,</span>";
			break;
			default:
				$html .= "example: Some Entry";
		}
		
		$html .= "</td></tr>";
		
		}
	}
	$html .= '</tbody></table></div><div style="clear:both"><div style="width: 90%"><div style="float:left;"> <ul><li>Only the <strong>LABEL</strong> of the table above should be in the header.</li><li>The &quot;<strong>TYPE</strong>&quot; field above tells you the type of field the associated name represents.  If the type of field is one that will accept multiple inputs and you have multiple values for that field (such as a list, or multiple select), make sure the values are entered as a comma separated group.  For instance, if you have a list of fruits: apples, oranges, and pears, the entry in that field should look like this:  <strong>apples,oranges,pears</strong>.</ul></div><br/><div><p><strong>IF</strong> you are using type: "address" as a field, you should have five <strong>COMMA SEPARATED</strong> fields that represent the following: <strong>(address 1)&nbsp;&nbsp ,&nbsp;&nbsp (address 2) &nbsp;&nbsp,&nbsp;&nbsp (city) &nbsp;&nbsp,&nbsp;&nbsp (state)&nbsp;&nbsp ,&nbsp;&nbsp (zip) &nbsp;&nbsp,&nbsp;&nbsp (country)</strong>.  If you have any empty fields, MAKE SURE you still put the comma! For example, if you omit address 2, your input for the address field should look like this: <strong>(address 1)&nbsp;&nbsp;&nbsp;,,&nbsp;&nbsp;&nbsp;(city)&nbsp;&nbsp,&nbsp;&nbsp(state)&nbsp;&nbsp,&nbsp;&nbsp (zip)&nbsp;&nbsp ,&nbsp;&nbsp (country)</strong>.</p></div><div><p><strong>IF</strong> you are using the "NAME" field type, your input into that filed should look like this in your csv (also in your excel file) file: <strong>(first name)&nbsp;&nbsp; ,&nbsp;&nbsp; (last name) </strong>.  If you omit, for instance, the last name, you should still put a comma after the first name like this: <strong>(first name)&nbsp;&nbsp;,</strong> .  Or <strong>,&nbsp;&nbsp; (last name)!</strong></p></div><div><p><strong>Making sure you add the commas appropriately will ensure that the correct fields will be filled in with the correct data, otherwise you may get certain data in the wrong fields</strong> (like the city in the address field and the like).</p></div><div><p><strong>TAKE NOTE:</strong> This mass importer works only for the listings in "Standard Fields" and "Advanced Fields" in the "New Form" area. <strong>This will NOT! work for custom fields or price fields.  This will NOT work for "CAPTCHA" OR "FILE UPLOAD" in the Advanced Field listings!</strong> <br/>Additionally: <br/><strong>IF you are entering data for any field that was set up to with a default list of values such as a checkbox or radio button list, although the "Recorded Values" are listed above, if you enter something that is not a recorded value, it will be entered into the database as a record.  This program does not check to verify whether an entered data value is included in the predefined list.</strong></p></div><br/><div style="clear:both"></div></div><div class="uperlod" style=""><form action="admin.php?page=gf_import_entries" method="post" enctype="multipart/form-data"><input type="hidden" name="mode" value="submit"><input type="hidden" name="formnum" value="'.$num.'" /><input type="file" name="csv_file" /><input type="submit" value="Import" /></form></div>';
	echo $html;
}

?>