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



$not_set_term = "Ingen";
$not_set_key = "ingen";
$reset_term = "reset";
//Fraser för typer av handlingar
//För att fraserna ska kunna bytas men för att koden ändå ska fungera så lagras de i variabler. 

$rapportbyggnad_term = "Inkommen rapport byggnad";
$rapportbyggnad_key = "rapportbyggnad_term";
$ritning_term = "Ritning";
$ritning_key = "ritning_term";
$handlingarvolym_term = "Hel arkivvolym";
$handlingarvolym_key = "handlingarvolym_term";
$fotografi_term = "Fotografi";
$fotografi_key = "fotografi_term";

$typeoptions = array($ritning_key => $ritning_term,$fotografi_key => $fotografi_term,$handlingarvolym_key =>$handlingarvolym_term,$rapportbyggnad_key => $rapportbyggnad_term);

$idtypeplurals = array($ritning_term => "ritningar",$fotografi_term => "fotografier",$handlingarvolym_term => "volymer",$rapportbyggnad_term => "dokument och bilagor",$not_set_term => "inga");


//Fraser för typer av diarienummer
$aldrediariet_term = "Äldre diariet";
$aldrediariet_key = "aldrediariet_term";
$dossier_term = "Dossier";
$dossier_key = "dossier_term";
$klassificering_term = "Klassificering";
$klassificering_key = "klassificering_term";
$ingetdnr_term = "Inget RAÄ diarienummer";
$ingetdnr_key = "ingetdnr_term";

$dnr_types = array($aldrediariet_key => $aldrediariet_term,$dossier_key => $dossier_term,$klassificering_key => $klassificering_term,$ingetdnr_key => $ingetdnr_term);

//Fraser för topografi
$topo_term = "Topografi";
$topo_key = "topo_term";
$nottopo_term = "Ej topografisk";
$nottopo_key = "nottopo_term";
$abroad_term = "Utlandet";
$abroad_key = "abroad_term";

$topo_types = array($topo_key => $topo_term,$nottopo_key => $nottopo_term,$abroad_key => $abroad_term);

//Fraser för filändelser
$pdf_term = ".pdf";
$pdf_key = "pdf";
$jpg_term = ".jpg";
$jpg_key = "jpg";
$tif_term = ".tif";
$tif_key = "tif";

$file_types = array($jpg_key => $jpg_term,$tif_key => $tif_term,$pdf_key => $pdf_term);

$newid_set = $not_set_term;


//sätta värden på variabler från POST och GET, samt skapa inputfält och submitknappar


$idtype_set = $not_set_term;
$idtype_set = setVariableFromPostorGet("idtype","start_type",$idtype_set,$typeoptions);
$input_idtype = createHiddenInput("idtype",$idtype_set);
$idtypebutton = createSubmitButton("Välj","Ändra val","idtype_button",$idtype_set,$reset_term,$typeoptions);


$dnrtype_set = $not_set_term;
$dnrtype_set = setVariableFromPostorGet("dnrtype","start_dnr",$dnrtype_set,$dnr_types);
$input_dnrtype = createHiddenInput("dnrtype",$dnrtype_set);
$dnrtypebutton = createSubmitButton("Välj","Ändra val","dnrtype_button",$dnrtype_set,$reset_term,$dnr_types);

$topotype_set = $not_set_term;
$topotype_set = setVariableFromPostorGet("topotype","start_topo",$topotype_set,$topo_types);
$input_topotype = createHiddenInput("topotype",$topotype_set);
$topotypebutton = createSubmitButton("Välj","Ändra val","topotype_button",$topotype_set,$reset_term,$topo_types);


$archiveobjects_term = translateVariable($idtype_set,$idtypeplurals);

$newidbutton = createSubmitButton("Skapa identifierare","Återställ","newid_button",$newid_set,"createId",array("empty"));

$today = date("Y-m-d");


if (isset($_POST["newid_button"])) {

	//kontroll
	//echo "skapa identifierare ".$idtype_set." ".$dnrtype_set." ".$topotype_set;
	
	$newid = createId();

}

/*****************************************
 * ************************************* *
 * *                                   * *
 * *           Funktioner              * *
 * *                                   * *
 * ************************************* *
 *****************************************/
 

function insertZerosBefore($string,$currentlength,$maxlength) {


	for ($i=$currentlength;$i < $maxlength;$i++) {
			$string = "0".$string;
			}
	return $string;
}
    
//Funktion som översätter parametrar som har skickats med GET i URL från en array med översatta termer. 
function translateVariable($termkey,$termarray) {

	return $termarray[$termkey];
	
}

function createId() {
	
    global $rapportbyggnad_term;
    global $ritning_term;
    global $fotografi_term;
    global $handlingarvolym_term;
    global $topo_term;
    global $nottopo_term;
    global $ingetdnr_term;
    global $not_set_term;
    global $newidbutton;
	
	$metadata = "";
	$html = "";
	$newid = "";
	
	$metadatastart="<div id=\"metadata\">";
	$headerrowstart="<div id=\"metadataheader\">";
	$identifierheader="<div id=\"identifierheader\">";
	$filenameheader="<div id=\"filenameheader\">";
	$datarowopen="<div class=\"datarow\" id=\"";
	$datarowid="datarow-";
	$datarowclose="\">";
	$identifierdataopen="<div class=\"identifierdata\" id=\"";
	$identifierid="identifierdata-";
	$identifierdataclose="\">";
	$filenamedataopen="<div class=\"filenamedata\" id=\"";
	$filenameid="filenamedata-";
	$filenamedataclose="\">";
	$enddiv="</div>";
	//kontroll
	//print_r($_POST);
	
	$typeofobject = $_POST["idtype"];	
	$typeofid = $_POST["newid_button"];
	
	echo $metadatastart;
	
	
	switch($typeofobject) {
	
	case $ritning_term :
		
		
		
		if ($typeofid == "Skapa dnr identifierare")
			{
			$year =  $_POST["dnr_year"];
			$number = ltrim($_POST["dnr_number"],"0");
			$filetypes = isset($_POST["file_type"]) ? $_POST["file_type"] : array(".xxx") ;
			$lengthnumber = strlen($number);
			$number = insertZerosBefore($number,$lengthnumber,4);
			$total = $_POST["numdnr"];
			
									
			for($i=1;$i<=$total;$i++) {
			$metadata = "";
			$num = insertZerosBefore($i,strlen($i),3);
			$newid = $year.$number."-".$num;
			
			//starta ny rad
			$html .= $datarowopen.$datarowid.$i.$datarowclose;
			
			//skapa cell för identifieraren
			$html .= $identifierdataopen.$identifierid.$i.$identifierdataclose.$newid.$enddiv;
			
			//starta cell för filnamn
			$html .= $filenamedataopen.$identifierid.$i.$filenamedataclose;
			
			$filename = cleanString($newid);
			foreach($filetypes as $suffix) {
				
				$metadata = $metadata.$filename.$suffix."<br>";
			}
			
			//stäng cell för filnamn och rad
			$html .= $metadata.$enddiv.$enddiv;
			
			}
			echo $headerrowstart;
			echo $identifierheader."Identifierare (Ritningsnummer)".$enddiv;
			echo $filenameheader."Filnamn".$enddiv;
			echo $enddiv;
			
			//div containing data
			echo $html;
			
			}
			
			elseif ($typeofid == "Skapa topografisk identifierare")
			{
			$landscape =  $_POST["landscape"];
			$lastnumber = ltrim($_POST["topo_count"],"0");
			$objecttype = substr($_POST["topoobject_type"],0,1);
			$num = $lastnumber+1;
			$filetypes = isset($_POST["file_type"]) ? $_POST["file_type"] : array(".xxx") ;
			$newid = $landscape." ".$num." ".$objecttype;
			$filename = cleanString($newid);
						
			//starta ny rad
			$html .= $datarowopen.$datarowid."1".$datarowclose;
			
			//skapa cell för identifieraren
			$html .= $identifierdataopen.$identifierid."1".$identifierdataclose.$newid.$enddiv;
			
			//starta cell för filnamn
			$html .= $filenamedataopen.$identifierid."1".$filenamedataclose;
			
			
			$filename = cleanString($newid);
			foreach($filetypes as $suffix) {

				$metadata = $metadata.$filename.$suffix."<br>";
			}
			
			//stäng cell för filnamn och rad
			$html .= $metadata.$enddiv.$enddiv;
			
			
			echo $headerrowstart;
			echo $identifierheader."Identifierare (Ritningsnummer)".$enddiv;
			echo $filenameheader."Filnamn".$enddiv;
			echo $enddiv;
			
			//div containing data
			echo $html;
			}
		
		break;
	
			
	case $rapportbyggnad_term:
		
		if ($typeofid == "Skapa topografisk identifierare")
			{
			$landscape =  $_POST["landscape"];
			$topoobjectname =  $_POST["topoobjectname"];
			$inputdate =  $_POST["inputdate"];
			$date = str_replace("-","",$inputdate);
			$total = $_POST["numtopo"];
			$filetypes = isset($_POST["file_type"]) ? $_POST["file_type"] : array(".xxx") ;
				
			$newid = $topoobjectname." ".$date;
			$filename = strtolower(cleanString($newid));
			
			//starta ny rad
				$html .= $datarowopen.$datarowid."1".$datarowclose;
				
				//skapa cell för identifieraren
				$html .= $identifierdataopen.$identifierid."1".$identifierdataclose.$newid.$enddiv;
				
				//starta cell för filnamn
				$html .= $filenamedataopen.$identifierid."1".$filenamedataclose;
			
			for($i=1;$i<=$total;$i++) {
				$num = insertZerosBefore($i,strlen($i),3);
				
				
				foreach($filetypes as $suffix) {
					$metadata = $metadata.$filename."-".$num.$suffix."<br>";
				}
		
			}
			//stäng cell för filnamn och rad
				$html .= $metadata.$enddiv.$enddiv;
				
			echo $headerrowstart;
			echo $identifierheader."Identifierare (Ritningsnummer)".$enddiv;
			echo $filenameheader."Filnamn".$enddiv;
			echo $enddiv;
			
			//div containing data
			echo $html;
			
			}
			
			elseif ($typeofid == "Skapa dnr identifierare")
			{
			$year =  $_POST["dnr_year"];
			$number = ltrim($_POST["dnr_number"],"0");
			
			$lengthnumber = strlen($number);
			
			$number = insertZerosBefore($number,$lengthnumber,4);
						
			$total = $_POST["numdnr"];
			
			
			
			for($i=1;$i<=$total;$i++) {
			$num = insertZerosBefore($i,strlen($i),3);
			$newid = $number."-".$year." ".$num;
			$filename = cleanString($newid);
			$metadata = $metadata."<br>".$newid."	".$filename;
			}
			
			echo "<pre>".$metadata."</pre>";
			}
		
		break;
		
	case $handlingarvolym_term:
		
		if ($typeofid == "Skapa arkivref identifierare")
			{
			$reference =  $_POST["archiveref"];
			$total = $_POST["volfiles"];
			$filetypes = isset($_POST["file_type"]) ? $_POST["file_type"] : array(".xxx") ;
			$filereference = ltrim($reference, "SE/");
			$idreference = ltrim($filereference, "ATA/");
			$filereference = str_replace(" ", "",$filereference);
			$filename = strtolower(cleanString($filereference));
			$idreference = str_replace(" ", "",$idreference);
			$newid = str_replace("/", " ",$idreference);
			
			
			//starta ny rad
			$html .= $datarowopen.$datarowid."1".$datarowclose;
			
			//skapa cell för identifieraren
			$html .= $identifierdataopen.$identifierid."1".$identifierdataclose.$newid.$enddiv;
			
			//starta cell för filnamn
			$html .= $filenamedataopen.$identifierid."1".$filenamedataclose;
			
			for($i=1;$i<=$total;$i++) {
				$num = insertZerosBefore($i,strlen($i),3);		
				foreach($filetypes as $suffix) {
					$metadata = $metadata.$filename."-".$num.$suffix."<br>";
				}
			}
			
			
			//stäng cell för filnamn och rad
			$html .= $metadata.$enddiv.$enddiv;
			
			
			echo $headerrowstart;
			echo $identifierheader."Identifierare (Ritningsnummer)".$enddiv;
			echo $filenameheader."Filnamn".$enddiv;
			echo $enddiv;
			
			//div containing data
			echo $html;
			
			
			
			}
		
		break;
		
	case $fotografi_term:
		
		break;
	}
	
	echo $enddiv;
	return $newid;
}


//Hämta GET variabler som kommer från startsidan eller POST från formuläret, för att starta i en viss punkt i formuläret
function setVariableFromPostorGet($internalvariablename,$variablenamefromURL,$currentvalue,$termarray) {
	global $not_set_term;
	global $reset_term;
	
	$newvariablevalue;
	$variablekey = "";
	
	
	if (isset($_GET[$variablenamefromURL])) {
		
		$newvariablevalue = translateVariable($_GET[$variablenamefromURL],$termarray);
	
	}
	elseif (isset($_POST[$internalvariablename])) {
	 $newvariablevalue = $_POST[$internalvariablename];
	}
	else {
	$newvariablevalue = $currentvalue;
	}
	
	if ($newvariablevalue != $not_set_term) {
	$variablekey = array_keys($termarray,$newvariablevalue);
	//echo "<br>från funktionen: ".$variablekey[0];
	}
	
	if(isset($_POST[$reset_term])) {
		//echo "<br>från formuläret1: ";
		//print_r ($_POST[$reset_term]);
		
		$change = array_keys($_POST[$reset_term],"Ändra val");
		
		//echo "<br>från formuläret2: ".$change[0];
		
		if ($change == $variablekey) {
			 $newvariablevalue = $not_set_term;
		}
	 }
	
	 return $newvariablevalue;

}

//Skapa ett dolt fält för att skicka variabler som fångats upp av de andra formulären.

function createHiddenInput($inputfieldname,$inputfieldvalue) {

	return "<input type=\"hidden\" name=\"$inputfieldname\" value=\"$inputfieldvalue\">";
	
	
}
function cleanString($stringtoclean)
{
		$forbiddencharacters = array ("Å", "å", "Ä", "ä","Ö", "ö", " ", "/");
		$allowedcharacters = array ("A","a", "A","a","O", "o", "_", "_");
		$cleanstring = str_replace($forbiddencharacters,$allowedcharacters,$stringtoclean);
		
		return $cleanstring;
};

//Skapa en submit-knapp för varje typ av formulär

function createSubmitButton($submittext,$resettext,$buttonname,$activatevalue,$resetname,$termarray) {
	$buttonhtml = "";
	global $not_set_term;
	
	$key = $activatevalue != $not_set_term ? array_keys($termarray,$activatevalue) : "ingen";
	//echo ("<br>Inne i knappen ".$key[0]);
	
	if ($activatevalue == $not_set_term) {
	
		$buttonhtml = "<input type=\"submit\" name=\"$buttonname\" value=\"$submittext\" />";
	}
	
	else {
		$buttonhtml = "<input type=\"submit\" name=\"".$resetname."[".$key[0]."]\" value=\"$resettext\" />";
	}

	
	return $buttonhtml;
}

function optionsFormHTML($radioorcheckbox,$idtypeoptions,$type_selected,$input_name,$showunselected,$isrequired,$separator)
{
 foreach($idtypeoptions as $typevalue)	
	{
			
		$typeid = strtolower(cleanString($typevalue));
		$required = $isrequired ? "required=\"true\"" : ""; 
		$checked = $typevalue == $type_selected ? "checked" : "";
		
		$class = $checked != "checked" ? "disabledoption" : "";
		
		if ($checked == "checked") {
			echo "$separator<input type=\"$radioorcheckbox\" name=\"$input_name\" value=\"$typevalue\" id=\"$typeid\" $required $checked>
			<label for=\"$typeid\" class=\"". $class ."\" >$typevalue</label>";
		}
		elseif ($showunselected) {
			echo "$separator<input type=\"$radioorcheckbox\" name=\"$input_name\" value=\"$typevalue\" id=\"$typeid\" $required $checked>
			<label for=\"$typeid\" class=\"". $class ."\" >$typevalue</label>";
		}
		
		}
};

function formDnr($dnrtype_selected) {
	
	global $aldrediariet_term;
	global $dossier_term;
	global $klassificering_term;
	global $ingetdnr_term;
	global $not_set_term;
	
	$dnrtype_lower = strtolower(cleanString($dnrtype_selected));
	$dnrtype_label = $dnrtype_selected == $not_set_term ? "" : $dnrtype_selected;
	
	if ($dnrtype_selected != $ingetdnr_term) {
		echo "<legend>Ange diarienummer</legend>";
		
		if ($dnrtype_selected == $dossier_term) {
			echo "<br><label for='$dnrtype_lower'>$dnrtype_label </label><input type='number' min='100' max='600' placeholder='000' value='' name='' required='true'>";
		}
		elseif ($dnrtype_selected == $klassificering_term) {
			echo "<br><label for='$dnrtype_lower'>$dnrtype_label </label><input type='text' size='3' placeholder='0.0.0' value='' name='' required='true'>";
		}
		
		echo "<br><label for='dnr_year'>Diarienumrets år: </label><input type='number'  min='1800' max='2050' placeholder='Årtal' value='' name='dnr_year' required='true'>";
		echo "<br><label for='dnr_number'>Diarienumrets löpnummer: </label><input type='number' placeholder='Löpnummer' min='1' max='9999'  value='' name='dnr_number' required='true'>";
		
		
		if ($dnrtype_selected == $dossier_term) {
			echo "<p class=\"help\">Dossiernummer anges som tre siffror, t.ex. om diarienumret är 421-00345-2001, så anges här 421</p>";
			echo "<p class=\"help\">Löpnummer anges som siffror, inga extra inledande nollor, t.ex. om diarienumret är 421-00345-2001, så anges här 345</p>";
			echo "<p class=\"help\">Årtal anges med fyra siffror, t.ex. om diarienumret är 421-00345-2001, så anges här 2001</p>";
		}
		
		elseif ($dnrtype_selected == $klassificering_term) {
			echo "<p class=\"help\">Klassificering anges som tre siffror med två punkter mellan siffrorna, t.ex. 3.5.1</p>";
			echo "<p class=\"help\">Löpnummer anges som siffror, med extra inledande nollor, t.ex. om diarienumret i Platina är RAÄ-00345-2020, så anges här 00345</p>";
			echo "<p class=\"help\">Årtal anges med fyra siffror, t.ex. om diarienumret är RAÄ-00345-2020, så anges här 2020</p>";
		}
		
		elseif ($dnrtype_selected == $aldrediariet_term) {
			
			echo "<p class=\"help\">Löpnummer anges som siffror, inga extra inledande nollor, t.ex. om diarienumret är 34/1960 eller 34-1960, så anges här 34</p>";
			echo "<p class=\"help\">Årtal anges med fyra siffror, t.ex. om diarienumret är är 34/1960 eller 34-1960, så anges här 1960</p>";
		}
		
	}
	
		
}


//Skapa en vallista med antal

function formSelectNo($id,$question,$min,$max) {

		echo "<legend>".$question."</legend>";
		echo "<br><select name=\"$id\">";
		for ($x = $min; $x <= $max; $x++) {
			echo "<option  value=\"$x\">$x</option>";
		}
		echo "</select>";

}

//Vallista för Landskap
function formTopoIdentifier($activate,$activatedate,$activatecount) {
	
	global $input_dnrtype;
	global $input_idtype;
	global $input_topotype;
	global $newidbutton;
	global $newid_set;
	global $archiveobjects_term;
	
	if($activate) {
		
	formHeader("topodata",array($input_dnrtype,$input_idtype,$input_topotype));
	
	echo "<legend>Ange topografiska uppgifter</legend>";
	echo "<br>";
	echo "<label for=\"landscape\" >Landskap: </label><select name=\"landscape\">";
	echo "<option value=\"Bl\">Blekinge</option>";
	echo "<option value=\"Bo\">Bohuslän</option>";
	echo "<option value=\"Da\">Dalarna</option>";
	echo "<option value=\"Ds\">Dalsland</option>";
	echo "<option value=\"Go\">Gotland</option>";
	echo "<option value=\"Gä\">Gästrikland</option>";
	echo "<option value=\"Hr\">Halland</option>";
	echo "<option value=\"Hr\">Härjedalen</option>";
	echo "<option value=\"Hs\">Hälsingland</option>";
	echo "<option value=\"Jä\">Jämtland</option>";
	
	echo "<option value=\"La\">Lappland</option>";
	echo "<option value=\"Me\">Medelpad</option>";
	echo "<option value=\"Nb\">Norrbotten</option>";
	echo "<option value=\"Nä\">Närke</option>";
	echo "<option value=\"Sk\">Skåne</option>";
	echo "<option value=\"Sm\">Småland</option>";
	echo "<option value=\"Sö\">Södermanland</option>";
	echo "<option value=\"Up\">Uppland</option>";
	echo "<option value=\"Vr\">Värmland</option>";
	echo "<option value=\"Vb\">Västerbotten</option>";
	echo "<option value=\"Vg\">Västergötland</option>";
	echo "<option value=\"Vs\">Västmanland</option>";
	echo "<option value=\"Ån\">Ångermanland</option>";
	echo "<option value=\"Öl\">Östergötland</option>";
	echo "<option value=\"Ög\">Östergötland</option>";
	echo "</select>";

	echo "<legend>Ange uppgifter om objektet</legend>";
	
	optionsFormHTML("radio",array("Byggnad","Fornlämning","Övrigt"),"Byggnad","topoobject_type",true,true," ");
	
	echo "<br><label for=\"topoobjecttype\">Objekt/Byggnad: </label>";
	echo "<input type=\"text\" name=\"topoobjectname\" required=\"true\">";
	
	echo "<p class=\"help\">Ange namnet på kyrkan eller byggnaden</p>";

	formDate($activatedate);
	
	if($activatecount) {
	echo "<br><label for='topo_count'>Högsta nummer i serien hittills: </label><input type='number'  min='1' max='99999' value='' name='topo_count' required='true'>";
	echo "<p class=\"help\">Sök upp det högsta talet i serien</p>";
		
	}
	
	if(!$activatecount) {
		formSelectNo("numtopo","Hur många $archiveobjects_term hör till samma post?",1,20);
	}
	
	formFileTypes(true);

$newidbutton = createSubmitButton("Skapa topografisk identifierare","Återställ","newid_button",$newid_set,"createId",array("empty"));

formEnd($newidbutton);
	}

}

function formDate($activate) {
	
	if ($activate) {
	echo "<legend>Vilket datum kom handlingen in, eller upprättades?</legend>";
		
	echo "<input type=\"date\" name=\"inputdate\" id=\"createdate\" required=\"true\">";
			
	}

}


function formArchiveRef() {
	
	global $input_idtype;
	global $newidbutton;
	global $newid_set;
	
	formHeader("archiveref",array($input_idtype));
	
	echo "<legend>Ange arkivreferenskod för volymen från NAD</legend>";	
	
	echo "<br><input type=\"text\" name=\"archiveref\" id=\"archiveref\" required=\"true\">";
	echo "<p class=\"help\">Ex. SE/ATA/ENSK_33/K 1 C/23</p>";
	
	formSelectNo("volfiles","Ska volymen delas upp i flera filer (men sparas på samma post?)",1,20);
	
	formFileTypes(true);

	$newidbutton = createSubmitButton("Skapa arkivref identifierare","Återställ","newid_button",$newid_set,"createId",array("empty"));
	
	formEnd($newidbutton);

}

function formTopoType($activate) {

	global $input_idtype;
	global $input_dnrtype;
	global $input_topotype;
	global $topo_types;
	global $topotype_set;
	global $topotypebutton;
	global $not_set_term;

	if ($activate) {
		
		formHeader("topo",array($input_dnrtype,$input_idtype,$input_topotype));
	
		echo "<legend>Finns det topografiska uppgifter?</legend>";
			optionsFormHTML("radio",$topo_types,$topotype_set,"topotype",$topotype_set==$not_set_term,true,"<br>");
			
		formEnd($topotypebutton);
		
	
	}	
}

function formFileTypes($activate){
	
	global $file_types;

	if($activate) {
		
		echo "<legend>Vilka filtyper ska skapas?</legend>";
		
		foreach($file_types as $suffix) {
		optionsFormHTML("checkbox",array($suffix),"","file_type[".ltrim($suffix,".")."]",true,false," ");
		}
	}
}


//Skapar toppen av ett formulär

function formHeader($formid,$hiddeninputarray) {

	echo "<div id=\"$formid\" class=\"form-container\">";
	echo "<form id=\"$formid\" method=\"post\" action=\"create_identifier.php\">";
		
	foreach ($hiddeninputarray as $a) {
		echo $a;
	}
	
}

//Skapar slutet av ett formulär

function formEnd($buttontype) {

	echo "<br>".$buttontype;
	echo "</form>";
	echo "</div>";
}

//Val av typ av diarienummer

function formDnrIdentifier() {

	global $input_idtype;
	global $input_dnrtype;
	global $input_topotype;
	global $dnr_types;
	global $dnrtype_set;
	global $ingetdnr_term;
	global $dnrtypebutton;
	global $newidbutton;
	global $not_set_term;
	global $newid_set;
	global $archiveobjects_term;
	
	
	formHeader("choosednrtype",array($input_idtype,$input_topotype));
				
	//Valalternativ för typ av diarienummer och skicka-knapp
	
	echo "<legend>Typ av RAÄ-diarienummer?</legend>";
	
	optionsFormHTML("radio",$dnr_types,$dnrtype_set,"dnrtype",$dnrtype_set==$not_set_term,true,"<br>");
	
	formEnd($dnrtypebutton);
	

		
		//Nytt formulär för att hämta data som skapar nytt id
		if ($dnrtype_set != $not_set_term && $dnrtype_set != $ingetdnr_term) {
		
		formHeader("dnr",array($input_idtype,$input_dnrtype,$input_topotype));	
		
								
		formSelectNo("numdnr","Hur många $archiveobjects_term hör till samma diarienummer?",1,100);
			
		formDnr($dnrtype_set);
		
		formFileTypes(true);
		
		$newidbutton = createSubmitButton("Skapa dnr identifierare","Återställ","newid_button",$newid_set,"createId",array("empty"));
					
		formEnd($newidbutton);
			
		}

}


//Skapar formulär med alternativ som ska bilda ny identifierare
function formOptionsForIdentifier($type_selected,$dnrtype_selected,$dnr_types,$dnrtypebutton,$topo_types)
{
    global $topotypebutton;
    global $topotype_set;   
    global $input_idtype;
    global $input_dnrtype;
    global $input_topotype;
    global $rapportbyggnad_term;
    global $ritning_term;
    global $fotografi_term;
    global $handlingarvolym_term;
    global $topo_term;
    global $nottopo_term;
    global $ingetdnr_term;
    global $not_set_term;
    global $newidbutton;
    
	
	
	switch($type_selected)
		
		{			
		case $ritning_term : 
			
			formDnrIdentifier();
			formTopoType($dnrtype_selected == $ingetdnr_term);
			echo "</div><div id=\"data-container\">";
			formTopoIdentifier($topotype_set == $topo_term && $dnrtype_selected == $ingetdnr_term,false,true);
			
			
			break;
			
			
		case $fotografi_term : 
			
			formDnrIdentifier();
			
			
			
			break;
			
		case $rapportbyggnad_term : 
			
			formDnrIdentifier();
			echo "</div><div id=\"data-container\">";
			formTopoIdentifier($dnrtype_selected == $ingetdnr_term,true,false);
			
			
			
			
			break;
			
		case $handlingarvolym_term : 
			
			formArchiveRef();
			
			
			
			
			break;
		
		default : 
		
		//Inget visas om inga giltiga inställningar i POST eller GET är satta.
		
		break;
			
			
		
		}
	
	
		
};


?>
<p>

<div id="top-container">
<div id="choosetype" class="form-container">
<form id="choosetype" action="create_identifier.php" method="post">

<legend>Typ av identifierare</legend>

<?php 

//Skapa en lista med vilka typer av handlingar som kan få nya ID

optionsFormHTML("radio",$typeoptions,$idtype_set,"idtype",$idtype_set==$not_set_term,true,"<br>"); ?>


<br> <?php 
echo $idtypebutton ; ?>
</form>
</div>

<?php
formOptionsForIdentifier($idtype_set,$dnrtype_set,$dnr_types,$dnrtypebutton,$topo_types);
?> 

</div>




</body>
</html>