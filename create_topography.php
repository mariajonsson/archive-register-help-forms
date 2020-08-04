<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Skapa identifierare</title>
    
    <style>
body {font-size:1em;font-family: 'Arial';line-height:1.2em; text-align:left; }
form {font-size:1em;font-family: 'Arial';}
input {font-size:0.9em;margin-bottom:0.4em;}
input[type=submit] {margin-top:8px;margin-bottom:8px;}
legend {font-size:0.9em;font-weight:bold;margin-top:5px;}
label {font-size:0.9em};
.disabledoption {color: lightgrey}
.help {font-size:0.9em;color: darkgrey}
div#top-container,div#identifierheader,  div#filenameheader {
display: inline;
vertical-align: top;
text-align: left;
}

div#metadata {
display: block;
width: 600px;
vertical-align: top;
text-align: left;
}
div#metadataheader{
width: 100%;
font-size: 12px;
display: block;
vertical-align: top;
text-align: left;
margin-right: 2em;

}
.datarow {
display: block;
vertical-align: top;
text-align: left;
margin-bottom: 4px;
}
div#identifierheader,  div#filenameheader, .identifierdata, .filenamedata {
width: 40%;
font-size: 12px;
display: inline-block;
vertical-align: top;
text-align: left;
margin-right: 2em;
border-top-color: #efeded;
border-top-width: 1px;
border-top-style: solid;
}

div#identifierheader,  div#filenameheader {
font-weight: bold;
background-color: #efeded;
}

div#choosetype, div#choosednrtype, div#topo {
display: inline-block;
vertical-align: top;
text-align: left;
margin-left: 5em;
width:15em;
}
div#data-container, div.form-container {
margin-top:3em;
text-align: center;
}

</style>
</head>
<body>



<?php


//Funktion för att hämta vallista landskap
$landskap = file("Landskap.csv",FILE_IGNORE_NEW_LINES);
$socken = "";
$byggnader = "";
$kyrkor = "";
$landskap_selected = isset($_GET['landskap']) ? $_GET['landskap'] : null;
$landskapskod_selected = isset($landskap_selected) ? getLandscapeCode() : null;
$socken_selected = isset($_GET['socken']) ? $_GET['socken'] : null; 
$byggnad_selected = isset($_GET['byggnader']) ? $_GET['byggnader'] : null;;
$kyrka_selected = isset($_GET['kyrkor']) ? $_GET['kyrkor'] : null;

//echo $landskap_selected;
//echo $landskapskod_selected;

//print_r($landskap);


function getLandscapeCode() {
	global $landskap;
	$code;
	$array = array_filter($landskap,"checkLandscape");
	$data = $array[0];
	//echo $data;
	$dataarray = explode(";",$data);
	$code = $dataarray[0];
	
	return $code;
}

//Funktion för att skapa HTML-vallista från array

function createSelectForm($typeofselect,$array,$keyforvalue,$keyforlabel,$listid,$label,$selected) {
	$row;
	
	
	if($typeofselect == "datalist") {
		echo "<label for=\"$listid\">$label </label>";
		echo "<input list=\"$listid\" name=\"$listid\" value=\"$selected\">";
	}
	else {
		echo "<label for=\"$listid\">$label </label>";
	}
	
	echo "<br><".$typeofselect." name=\"$listid\" id=\"".$listid."\" >";
	foreach($array as $a) {
		$row = explode(";",$a);
		
		echo "<option value=\"$row[$keyforvalue]\" >$row[$keyforlabel]</option>";		
	}
	echo "</$typeofselect>";

}

function checkLandscape($var){
		global $landskap_selected;
		$result = strpos($var,$landskap_selected);
		$result = is_numeric($result) ? true : false;
		
		return $result;
}

function checkLandscapeCode($var){
		global $landskapskod_selected;
		$result = strpos($var,$landskapskod_selected);
		$result = is_numeric($result) ? true : false;
		
		return $result;
}

function checkSocken($var){
		global $socken_selected;
		$result = strpos($var,$socken_selected);
		$result = is_numeric($result) ? true : false;
		
		return $result;
}


//Välj landskap först

echo "<form name=\"landskap\" action=\"create_topography.php\" method=\"GET\">";
createSelectForm("datalist",$landskap,1,0,"landskap","Landskap",$landskap_selected);
echo "<input type=\"submit\" name=\"skicka_landskap\" value=\"Välj\">";
echo "</form>";

//Funktion för att hämta vallista socknar utifrån valt landskap

if(isset($landskap_selected)) {
	global $landskap_selected;

	$socken = file($landskapskod_selected.".csv",FILE_IGNORE_NEW_LINES);
	
	echo "<form name=\"socken\" action=\"create_topography.php\" method=\"GET\">";
	echo "<input type=\"hidden\" name=\"landskap\" value=\"$landskap_selected\">";
	createSelectForm("datalist",$socken,0,1,"socken","Socken",$socken_selected);
	echo "<input type=\"submit\" name=\"skicka_socken\" value=\"Välj\">";
	echo "</form>";
}

//Funktion för att hämta vallista byggnader utifrån vald socken och landskap

if(isset($socken_selected)) {
	
	global $byggnad_selected;

	$byggnader = file("Byggnader.csv",FILE_IGNORE_NEW_LINES);
	$filtreradebyggnader = array_filter($byggnader,"checkSocken");
	
	echo "<form name=\"byggnader\" action=\"create_topography.php\" method=\"GET\">";
	echo "<input type=\"hidden\" name=\"landskap\" value=\"$landskap_selected\">";
	echo "<input type=\"hidden\" name=\"socken\" value=\"$socken_selected\">";
	echo "<input type=\"hidden\" name=\"kyrkor\" value=\"$kyrka_selected\">";
	
	createSelectForm("datalist",$filtreradebyggnader,2,2,"byggnader","Bebyggelseregistret",$byggnad_selected);
	
	echo "<input type=\"submit\" name=\"skicka_byggnad\" value=\"Välj\">";
	echo "</form>";
}

if(isset($landskapskod_selected)) {
	
	global $kyrka_selected;
	
	$kyrkor = file($landskapskod_selected."_kyrkor.csv",FILE_IGNORE_NEW_LINES);
	$filtreradekyrkor = array_filter($kyrkor,"checkLandscapeCode");
	
	echo "<form name=\"kyrkor\" action=\"create_topography.php\" method=\"GET\">";
	echo "<input type=\"hidden\" name=\"landskap\" value=\"$landskap_selected\">";
	echo "<input type=\"hidden\" name=\"socken\" value=\"$socken_selected\">";
	echo "<input type=\"hidden\" name=\"byggnader\" value=\"$byggnad_selected\">";
	
	createSelectForm("datalist",$filtreradekyrkor,1,2,"kyrkor","Kyrko ID",$kyrka_selected);
	
	echo "<input type=\"submit\" name=\"skicka_kyrka\" value=\"Välj\">";
	echo "</form>";
}

?>


<?php


?>




</body>
</html>