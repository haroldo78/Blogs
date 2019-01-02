/**
 * @package   	Egolt Voting
 * @link 		http://www.egolt.com
 * @copyright 	Copyright (C) Egolt www.egolt.com
 * @author    	Soheil Novinfard
 * @license    	GNU/GPL 2
 *
 * Name:		Egolt Voting
 * License:    	GNU/GPL 2
 * Project: 	http://www.egolt.com/products/egoltvoting
 */
 
function egoltvoting(lval, id, service){

	var currentURL = window.location;

	var live_site = currentURL.protocol+'//'+currentURL.host+rooturi;

	var lsXmlHttp = '';
	
	var sumdiv = document.getElementById('esumv_'+id);
	
	sumdiv.innerHTML='<img src="'+live_site+'/media/egoltvoting/images/loading4.gif" border="0" align="absmiddle"/>';
			
		try	{
			lsXmlHttp=new XMLHttpRequest();
		} catch (e) {
			try	{ lsXmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try { lsXmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {
					alert('text0');
					return false;
				}
			}
		}

	if ( lsXmlHttp != '' ) {
		lsXmlHttp.onreadystatechange=function() {
			var response;
			if(lsXmlHttp.readyState==4){
				setTimeout(function(){ 
					response = lsXmlHttp.responseText; 
					if(response=='thanks'){
						sumdiv.innerHTML='<small>'+eg_th_str+'</small>';					
					}
					if(response=='liked'){
						sumdiv.innerHTML='<small>'+eg_vt_str+'</small>';
					}
					if(response=='noaccess'){
						sumdiv.innerHTML='<small>'+eg_ac_str+'</small>';
					}
				},500);
			}
		}
		lsXmlHttp.open("GET",live_site+"/plugins/content/egoltvoting/egoltvoting_ajax.php?task=like&lval="+lval+"&cid="+id+"&sr="+service,true);
		lsXmlHttp.send(null);
	}
	
}
