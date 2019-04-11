<?
session_start();
include "base.php";

function gethml()
{
	$html="";
	$totalp=0;
	$sliders=[];
	$html .= '<input type="hidden" id="items" name="items" value="'.count($_SESSION["proyects"]).'">';		
	$html.="<div id='header' class='row' style='margin-top: 20px;'>";		
	$html.="<div id='nameheader' class='col-3'><h5 style='font-family: \"Plain-Bold\"'>Project Type</h5></div>";
	$html.="<div id='nameheader' class='col-3'><h5 style='font-family: \"Plain-Bold\"'>&nbsp;</h5></div>";
	$html.="<div id='headertupe' class='col-3' style='font-size:12px; font-family: \"Plain-Bold\"'>";			
	$html.='<div style="float:left; text-align: center; width: 33%;"><h5>New</h5></div>';
	$html.='<div style="float:left; text-align: center; width: 33%;"><h5>Version</h5></div>';		
	$html.='<div style="float:left; text-align: center; width: 33%;"><h5>Edit</h5></div>';		
	$html .= '</div>';
	$html.="<div id='pname' class='col-2' style='text-align: left;'><h5 style='font-family: \"Plain-Bold\";'>Points</h5></div>";	
	$html .= '</div>';	
	$html .= '<hr style="border: 1px solid #e1e1e1;">';	

	for ($i=0; $i < count($_SESSION["proyects"]); $i++)
	{
		global $cfg;
		$extraval=0;
		$html.="<div id='p_".$i."' class='row project-list-item' style='margin-top: 20px;".((($i % 2)==1)?"background-color: #ededed;":"")."'>";		
		$html.="<div id='pname' class='col-3'><h3 style='font-family: \"Plain-Light\"; font-size: 24px;'>".$_SESSION["proyects"][$i]["name"]."</h3></div>";
		$html.="<div id='pname' class='col-3'>";
		if ($_SESSION["proyects"][$i]["expoints"]>0)
		{
			$html .= '<div id="slider_'.$i.'" class="sliderproyect" style="margin-top: 20px; margin-bottom: 30px;" min_value="'.$_SESSION["proyects"][$i]["inter_min"].'" max_value="'.$_SESSION["proyects"][$i]["inter_max"].'"  interval="'.$_SESSION["proyects"][$i]["interval"].'"';
			$html .= ($_SESSION["proyects"][$i]["no_showvalue"]) ? ' novalues="1" ' : "";
			$html .= '">';
			$html .= '<div id="custom-handle" class="ui-slider-handle '.(($_SESSION["proyects"][$i]["handle_class"]!='') ? $_SESSION["proyects"][$i]["handle_class"] : 'custom-handle').'">';		
			$html .= '</div>';
			for ($lab=0; $lab < count($_SESSION["proyects"][$i]["labels"]); $lab++)
			{
				$left = 100*($lab/(count($_SESSION["proyects"][$i]["labels"])-1));
			    $html .= "<label style='left:".$left."%;'>".$_SESSION["proyects"][$i]["labels"][$lab]."</label>";
			}
			$html .= '<input type="hidden" id="items" name="items" value="'.count($_SESSION["proyects"]).'">';		
			$html .='</div>';
			$html .= '<p style="text-align: center; font-size: 12px;">'.$_SESSION["proyects"][$i]["inter_name"].'</p>';			
			$sliders[]="slider_".$i;
		}
		$html .= "</div>";

		$html.="<div id='ptype' class='col-3' style='font-size:12px;'>";		
		$html .= '<input type="hidden" id="interval_'.$i.'" name="interval_'.$i.'" value="'.$_SESSION["proyects"][$i]["interval"].'">';	
		$html .= '<input type="hidden" id="extras_'.$i.'" name="extras_'.$i.'" value="'.$_SESSION["proyects"][$i]["extras"].'">';	
		if ($_SESSION["proyects"][$i]["type"]==0)
		{
			$_SESSION["proyects"][$i]["type"]=$_SESSION["proyects"][$i]["points"];
		}
		if ($_SESSION["proyects"][$i]["points"] == $_SESSION["proyects"][$i]["type"]) {
			$sel = " checked ";
			$extraval=$_SESSION["proyects"][$i]["expoints"];
		}else{			
			$sel = " ";
			$extraval=0;
		}
		$html.='<div style="float:left; text-align: center;  width: 33%;  font-size:14px;">';
		$html.="<label for='TypeChoice1_".$i."' style='display: block;'>".$_SESSION["proyects"][$i]["points"]."pts</label><input type='radio' id='TypeChoice1_".$i."' name='type_".$i."' value='".$_SESSION["proyects"][$i]["points"]."' onchange='TypeChange(".$i.")' ".$sel." style='display: block; margin: 0 auto;'>";
		$html .="</div>";


		if (($_SESSION["proyects"][$i]["points"] * $cfg["version"]) == $_SESSION["proyects"][$i]["type"])
		{
			$sel = " checked ";
			$extraval=$_SESSION["proyects"][$i]["expoints"] * $cfg["version"] ;
		}else{
			$sel = " ";			
		}

		$html.='<div style="float:left; text-align: center;  width: 33%;">';
		$html.="<label for='TypeChoice2_".$i."'  style='display: block; font-size:14px;'>".($_SESSION["proyects"][$i]["points"] * $cfg["version"])."pts</label><input type='radio' id='TypeChoice2_".$i."' name='type_".$i."' value='".($_SESSION["proyects"][$i]["points"]  * $cfg["version"] )."' onchange='TypeChange(".$i.")'  ".$sel."  style='display: block; margin: 0 auto;'>";
		$html .="</div>";

		if (($_SESSION["proyects"][$i]["points"] * $cfg["edit"]) == $_SESSION["proyects"][$i]["type"])
		{
			$sel = " checked ";
			$extraval=$_SESSION["proyects"][$i]["expoints"] * $cfg["edit"] ;
		}else{
			$sel = " ";			
		}

		$html.='<div style="float:left; text-align: center;  width: 33%;  font-size:14px;">';
		$html.="<label for='TypeChoice3_".$i."'  style='display: block;'>".($_SESSION["proyects"][$i]["points"] * $cfg["edit"])."pts</label><input type='radio' id='TypeChoice3_".$i."' name='type_".$i."' value='".($_SESSION["proyects"][$i]["points"]  * $cfg["edit"] )."' onchange='TypeChange(".$i.")'  ".$sel."  style='display: block; margin: 0 auto;'>";	
		$html .="</div>";

		$html .= '<br style="clear:both;" />';		
		$html.="</div>";
		$html.="<div id='total_".$i."' class='col-2' style='color: #3AD3D5; font-size:28px; text-align: left; font-weight: bolder;'>";
		$html.=$_SESSION["proyects"][$i]["type"] ;
		$html.=" pts</div>";
		$html.="<div id='remove_".$i."' class='col-1' >";
		$html.="<a href='#' style='font-family: \"Plain-Bold\"; font-size: 14px; text-align: center; color: #ce0052;' onClick ='DeleteProyect(".$i.");' >Remove</a>";
		$html.="</div>";
		$html .= '<input type="hidden" id="extrap_'.$i.'" name="extrap_'.$i.'" value="'.$extraval.'">';	
		$html .= '<input type="hidden" id="include_'.$i.'" name="include_'.$i.'" value="'.$_SESSION["proyects"][$i]["included"].'">';	
		$html.="</div>";

		$totalp += $_SESSION["proyects"][$i]["type"];
	}
	$html .= '<hr style="border: 1px solid #e1e1e1;">';	
	$html.="<div id='totalpoints' class='col-11' style='text-align: right; font-weight: bolder; font-size:38px; color: #3AD3D5; '><span>Total </span>";
	$html.= $totalp ;
	$html.=" pts</div>";
	if (empty($_SESSION["proyects"])) {
		$html = "";
   	}
	return $html;
}


$res["success"]=false;
switch($_REQUEST["cmd"])
{
	case "ClearProyects":
		$_SESSION["proyects"]=[];
		$res["html"]=gethml();
		break;
	case "RemoveProyect":
		array_splice($_SESSION["proyects"],$_REQUEST["item"],1);
		
		if (empty($_SESSION["proyects"])) {
			$res["html"]= "";
		}
		else {
			$res["html"]=gethml();
		}
		$res["cant_items"]=count($_SESSION["proyects"]);
		break;		
	case "ChangeType":
		$_SESSION["proyects"][$_REQUEST["index"]]["type"]=$_REQUEST["type"];
		if ($_SESSION["proyects"][$_REQUEST["index"]]["expoints"]>0)
		{
			if ($_SESSION["proyects"][$_REQUEST["index"]]["points"] == $_SESSION["proyects"][$_REQUEST["index"]]["type"]) {
				$res["extraval"] = $_SESSION["proyects"][$_REQUEST["index"]]["expoints"];
			}elseif (($_SESSION["proyects"][$_REQUEST["index"]]["points"] * $cfg["version"]) == $_SESSION["proyects"][$_REQUEST["index"]]["type"])
			{
				$res["extraval"] = $_SESSION["proyects"][$_REQUEST["index"]]["expoints"] * $cfg["version"];
			}elseif (($_SESSION["proyects"][$_REQUEST["index"]]["points"] * $cfg["edit"]) == $_SESSION["proyects"][$_REQUEST["index"]]["type"])
			{
				$res["extraval"] = $_SESSION["proyects"][$_REQUEST["index"]]["expoints"] * $cfg["edit"];
			}		
		}
		break;

	case "SetExtras":
		$_SESSION["proyects"][$_REQUEST["index"]]["extras"]=$_REQUEST["extras"];

		break;
	case "AddProyect":		
		//$res["msj"]=print_r($base,true);
		for ($i=0; $i <= count($base); $i++)
		{
			if ($base[$i]["id"]==$_REQUEST["id"])
			{
				$proyect=$base[$i];
				$proyect["type"]=0;
				$proyect["extras"]=0;
				$proyect["id"]=$base[$i]["id"];
				$_SESSION["proyects"][]=$proyect;
			}
		}
		$res["cant_items"]=count($_SESSION["proyects"]);
		$res["success"]=true;
		$res["data"]=$_SESSION["proyects"];
		$res["html"]=gethml();		
		break;
}



header('Content-type: application/json; charset=utf-8');
echo json_encode($res, JSON_FORCE_OBJECT);

?>
