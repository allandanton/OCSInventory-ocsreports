<?php
	$list_fields=array();
	if (!isset($protectedPost['SHOW']))
		$protectedPost['SHOW'] = 'NOSHOW';
$majuscule="onKeyPress=\"return scanTouche(event,/[0-9 a-z A-Z]/)\" onkeydown='convertToUpper(this)'
		  onkeyup='convertToUpper(this)' 
		  onblur='convertToUpper(this)'";
$chiffres="onKeyPress=\"return scanTouche(event,/[0-9]/)\" onkeydown='convertToUpper(this)'
		  onkeyup='convertToUpper(this)' 
		  onblur='convertToUpper(this)'";
if (isset($protectedPost['Valid_modif_x'])){
		foreach ($protectedPost as $key=>$value){
			$temp="";
			if (substr($key, 0, 5) == "check"){
				$temp=substr($key, 5);
				$tag_value=$protectedPost[$temp];
				if ($temp == TAG_LBL)
					$temp="TAG";
				$list_tag[$temp]=$tag_value;
			} 				
		}
	if (isset($list_tag)){	
		if ($protectedPost['CHOISE'] == 'SEL'){
			$array_id=$protectedGet['idchecked'];		
		}elseif ($protectedPost['CHOISE'] == 'REQ'){
			if (is_array($_SESSION['ID_REQ']))
			$array_id=implode(',',$_SESSION['ID_REQ']);	
			else
			$array_id=$_SESSION['ID_REQ'];	
			//print_r($_SESSION['ID_REQ']);
		}
		$sql= "update accountinfo set ";
		foreach($list_tag as $tag=>$value){
			 $sql.=$tag." = \"".$value."\" ,";
		}
		$sql=substr($sql,0, -1)." where hardware_id in (".$array_id.")";
		//echo "<br>".$sql;
		mysql_query($sql, $_SESSION["writeServer"]) or die(mysql_error($_SESSION["writeServer"]));	
		unset($_SESSION['DATA_CACHE']['TAB_MULTICRITERE']);
		echo "<script language='javascript'> window.opener.document.multisearch.submit();</script>";
		//echo $sql;
	}
	//print_r($array_id);
		
		
		
}


	echo "<form name='".$form_name."' id='".$form_name."' method='POST' action=''>";
	if (isset($select_choise))
		echo "<div align=center>".$l->g(984)." ".$select_choise."</div><br>";		
	else{
		$protectedPost['CHOISE']='REQ';
		echo "<input type='hidden' name='CHOISE' value='".$protectedPost['CHOISE']."'>";	
	}
	//print_r($protectedPost);
	if (isset($protectedPost['CHOISE']) and $protectedPost['CHOISE'] != ""){
		$queryDetails = "show columns from accountinfo";
		$resultDetails = mysql_query($queryDetails, $_SESSION["readServer"]) or die(mysql_error($_SESSION["readServer"]));
		$i=0;
		while($item = mysql_fetch_object($resultDetails)){
			if ($item->Field != "HARDWARE_ID"){
				if ($item->Field == "TAG")
					$truename=TAG_LBL;
				else
					$truename=$item->Field;
				$java="";
				switch ($item->Type){
					case "int(11)" : $java = $chiffres;
									break;
					case "varchar(255)"  : $java = $majuscule;
									break;
					case "date"  : $java = "READONLY ".dateOnClick($truename);
									//$tab_typ_champ[$i]['COMMENT_BEHING'] =datePick($truename);
									break;
					default : $java;
				}
				$tab_typ_champ[$i]['COMMENT_BEHING']="<input type='checkbox' name='check".$truename."' id='check".$truename."' ".(isset($protectedPost['check'.$truename])? " checked ": "").">";
				$tab_typ_champ[$i]['INPUT_NAME']=$truename;
				$tab_typ_champ[$i]['INPUT_TYPE']=0;
				$tab_typ_champ[$i]['CONFIG']['JAVASCRIPT']=$java." onclick='document.getElementById(\"check".$truename."\").checked = true' ";
				$tab_typ_champ[$i]['CONFIG']['MAXLENGTH']=100;
				$tab_typ_champ[$i]['CONFIG']['SIZE']=40;
				$tab_typ_champ[$i]['DEFAULT_VALUE']=$protectedPost[$truename];
				$tab_name[$i]=$truename;
				$i++;
			}
		}
	
			tab_modif_values($tab_name,$tab_typ_champ,array('TAG_MODIF'=>$protectedPost['MODIF'],'FIELD_FORMAT'=>$type_field[$protectedPost['MODIF']]),$l->g(895),"");
	}	
echo "</form>";
?>
