<?php
/*
* @name smartrelatedarticles 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
	//importo il framework joomla
    	define('_JEXEC', 1 );
	defined('_JEXEC') or die;
    	define( 'DS', DIRECTORY_SEPARATOR );
    	define( 'JPATH_BASE', realpath( '..'.DS.'..'.DS.'..'.DS ) );   
    	require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
    	require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
 
   	$mainframe = JFactory::getApplication('administrator');       
    	jimport( 'joomla.plugin.plugin' ); 

	//if dell'ajax per la generazione del codice
	if(@$_POST["related_gen"]!=""){
		include dirname(__FILE__).'/../../../components/com_content/helpers/route.php';
		$db = JFactory::getDBO();
		$return="";
		foreach($_POST["related_gen"] as $id){
			if(is_numeric($id)){
				$db->setQuery('SELECT * FROM #__content WHERE id = '.$id);
				$rows = $db->loadObjectList();
				$return.='<a href="'.ContentHelperRoute::getArticleRoute($id, $rows[0]->catid).'">'.$rows[0]->title.'</a><br />';
			}
		}
		echo "<p>".$return."</p>";
		JFactory::getApplication()->close();
	}

	//if dell'ajax per la ricerca degli articoli
	if(@$_POST["get_search_word"]==1){
		$database = JFactory::getDBO();
		$words=@$_POST["search_word"];

		//creo il WHERE
		$where="1=1";
		if(count($_POST["search_cats"])){
			$where="";
			foreach($_POST["search_cats"] as $cat){
				$where.="catid=".$cat." OR ";
			}
			$where="(".substr($where,0,-4).")";
		}

		//cerco articoli da consigliare
		$related=array();
		if(count($words)){
			$sql="";
			foreach($words as $word){
				$wor=preg_replace('/[^a-zA-Zàèéìòù]/','',$word);
				$sql.='(CAST((LENGTH(CONCAT(introtext,`fulltext`)) - LENGTH(REPLACE(CONCAT(introtext,`fulltext`), "'.$wor.'", ""))) / LENGTH("'.$wor.'") AS UNSIGNED)) + ';
			}
//die('SELECT id, '.substr($sql,0,-3).' AS findme_count FROM #__content WHERE '.$where.' ORDER BY findme_count DESC');
			$database->setQuery('SELECT id, '.substr($sql,0,-3).' AS findme_count FROM #__content WHERE '.$where.' ORDER BY findme_count DESC');
			$related = $database->loadAssocList();
		}

		//cerco tutti gli articoli se il software non consiglia dei buoni articoli
		$database->setQuery('SELECT id, title FROM #__content WHERE '.$where.' ORDER BY created DESC');
		$all = $database->loadAssocList();

		$return=array("related"=>$related, "all"=>$all);
		header('Content-type: application/json');
		echo json_encode($return);

		JFactory::getApplication()->close();
	}
?>
<div id="related_article_" style="text-align:center;">
	Loading... may take a few minutes<br /><br />
	<img src="https://www.findshare.com/assets/images/loading-blue.gif" style="max-width:200px;"/>
</div>
<link rel="stylesheet" href="chosen/chosen.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
	var n_art=<?php echo $_GET["n_art"]; ?>;
	var cats=<?php echo json_encode(explode(",",$_GET["cats"])); ?>;
	var elem=jQuery("#related_article_");

	if(typeof parent.tinyMCE !=="undefined")
		var content=parent.tinyMCE.get('<?php echo $_GET["name"]; ?>').getContent();
	else if(typeof parent.CKEDITOR !=="undefined")
		var content=parent.CKEDITOR.instances.jform_articletext.getData();
	//var content=tinyMCE.get('<?php echo $_GET["name"]; ?>').getContent();
	var word=content.replace(/<\/?[^>]+(>|$)/g, "");
	word=word.split(" ");
	var counter={};
	//creo un array dove conto tutte le parole
	for(var i=0;i<word.length;i++){
		if(word[i].length<5)
			continue;
		if(typeof counter[word[i]] === "undefined")
			counter[word[i]]=0;
		counter[word[i]]++;
	}
	//faccio N cicli, tanti quanti saranno gli articoli correlati
	var most_word=[];
	for(var i=0;i<n_art;i++){
		//estraggo la parola che appare più spesso
		var mas=0; var key="";
		for(var j=0;j<word.length;j++){
			if(counter[word[j]]>mas && word[j].length>=5 && word[j]!=""){
				mas=counter[word[j]];
				key=word[j];
			}
		}
		//inserisco la parola presente più volte e la rimuovo da word
		if(key!="" && most_word.indexOf(key)==-1){
			most_word.push(key);
			for(var j=0;j<word.length;j++){
				if(word[j]==key)
					word[j]="";
			}
		}
	}

	//lancio l'ajax che cercherà gli articoli più pertinenti
	jQuery.ajax({
		url:"<?php echo JURI::base(); ?>dialog.php",
	  	type: "POST",
	  	data: { search_word: most_word, get_search_word:1, search_cats:cats},
	  	success:function(e){
			//creo le select
			elem.html("");
			for(var i=0;i<n_art;i++){
				var select_cont=jQuery("<div>").appendTo(elem);
				var select=jQuery('<select style="width:100%;">').appendTo(select_cont);
				for(var j=0;j<e.all.length;j++){
					jQuery('<option value="'+e.all[j].id+'">'+e.all[j].title+'</option>').appendTo(select);
				}
				if(typeof e.related[i] !== "undefined")
					select.val(e.all[i].id);
				select.chosen();
			}
			
			var button=jQuery('<button id="related_button_">Ok</button>').appendTo(elem);
	  	}
	});

	//clicco il bottone che aggiungerà i link all'articolo
	jQuery(document).delegate("#related_button_","click",function(event){
		event.stopImmediatePropagation();
		var list_select=[];
		var selects=elem.find("select");
		elem.find("select").each(function(){
			list_select.push(jQuery(this).val());
		});
		jQuery.ajax({
			url:"<?php echo JURI::base(); ?>dialog.php",
		  	type: "POST",
		  	data: { related_gen: list_select},
		  	success:function(e){
				<?php if(substr(JVERSION,0,1)=="2"){ ?>
					if(typeof parent.tinyMCE !=="undefined")
						parent.tinyMCE.get('<?php echo $_GET["name"]; ?>').setContent(content+e);
					else if(typeof parent.CKEDITOR !=="undefined")
						parent.CKEDITOR.instances.jform_articletext.setData(content+e);
				<?php }else{ ?>
					if(typeof parent.tinyMCE !=="undefined")
						tinyMCE.get('<?php echo $_GET["name"]; ?>').setContent(content+e);
					else if(typeof parent.CKEDITOR !=="undefined")
						parent.CKEDITOR.instances.jform_articletext.setData(content+e);
				<?php }?>
				parent.document.getElementById('sbox-btn-close').click();
			}
		});
	});
</script>
