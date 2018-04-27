$(document).ready(function(e){
	
	//$(window).load(function(){
	//	resizePosSpace();
	//});
	$(window).resize(function(){
		resizePosSpace();
	});
	var $bill_id = $('.active-bill-tab').val();
	var $input = $('.pos-quick-create-input-text');
	if($input.length>0){
		$input.focus();
		 
		document.onkeydown = function Open(event){ 
			var $keyCode ;
			if(window.event){
				$keyCode = window.event.keyCode;
			}else{
				$keyCode = event.which;
			}
			
			 
			 
			 
			switch($keyCode){
			
			case 113 : // F2
				$input.focus();
				break;
			case 119: // F8
				$(".list-bills-"+$bill_id).find('.btn-function').each(function(i,e){
					var $kc = parseInt(jQuery(e).attr('data-keycode'));
					if($keyCode == $kc){
						jQuery(e).click();
					}
				}); 
				return false;
				break;
			case 120: // F9
			case 121: // F10
				$(".list-bills-"+$bill_id).find('.btn-function').each(function(i,e){
					var $kc = parseInt(jQuery(e).attr('data-keycode'));
					if($keyCode == $kc){
						jQuery(e).click();
					}
				});
				
				break;
			}

		}
		
		$input.keydown(function(event) {
			//var $data = getAttributes($e);
			var $keyCode = event.which;
			switch($keyCode){
			case 13:
				var $item = {};
				$item.barcode = $input.val();
				$item.loadbarcode = 1;
				$bill.add($item,$input.attr('data-bill-index'));
				$input.val('').focus();
				break;
			}
		});
		
	}
	
	$('.autocomplete2').each(function(i,e){
		var $e = jQuery(e);
		//if($e.attr('data-loaded') == undefined){
			var $data = getAttributes($e);
			$delimiter = $e.attr('data-delimiter') ? $e.attr('data-delimiter') : ',';
			$e
		     // don't navigate away from the field on tab when selecting an item
		     .on( "keydown", function( event ) {
		       if ( event.keyCode === $.ui.keyCode.TAB &&
		           $( this ).autocomplete( "instance" ).menu.active ) {
		         event.preventDefault();
		       }
		     })
		     .autocomplete({
		       source: function( request, response ) {
		    	 $data['term'] = $data['q'] = extractLast( request.term );
		    	 //console.log($data)
		         $.getJSON( $cfg.adminUrl + '/ajax', $data, response );
		       },
		       search: function() {
		         // custom minLength
		         var term = extractLast( this.value );
		         if ( term.length < 1 ) {
		           return false;
		         }
		       },
		       focus: function() {
		         // prevent value inserted on focus
		    	  
		         return false;
		       },
		       select: function( event, ui ) {
		    	   $bill.add(ui.item,$data['bill-index']); 
		    	   
		         //return false;
		       },
		       close: function( event, ui ) {
		    	   
		    	   $e.val('').focus();
		       }
		     })
		     
		     .autocomplete( "instance" )._renderItem = function( ul, item ) {
			      return $( "<li>" )
			        .append( "<div><img class=\"w50p\" src=\""+item.icon+"\" /> " + item.label + "<br>" + item.desc + "</div>" )
			        .appendTo( ul );
			    };

			
		//}		
	});
	resizePosSpace();
});

var $bill = {};
$bill.add = function ($item, $bill_id){
	var $d = {};
	var $data = {};
	$data.item = $item;
	$data.bill_id = $bill_id;
	$data.action = 'bill_add_action';
	$data.event = 'add';
	$data['_csrf-frontend'] = $cfg['_csrf-frontend'];
	
	//console.log($data);
	
	jQuery.ajax({
	  	  type: 'post',	  		 	
	  	  datatype: 'text',	  			
	  	  url: $cfg.cBaseUrl +'/ajax',	  			
	  	  data:$data,	          
	  	  beforeSend: function() {
	  		  	
	  	  },	  			
	  	  success: function(data) {  	  		   
	  		  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	  	  },	  	  	  				           
	  	  complete: function() {
	  		  if($d !== false && $d != null && $d.complete){
	    		  eval($d.complete_function);	    		 
	    	  }
	  	  },
	  	  error : function(err, req) {	  			
	  		   
	  	  }
	});	      	 
}

function resizePosSpace(){
	var $w = jQuery(window).width();
	var $h = jQuery(window).height();
	//console.log($w + ' / ' + $h);
	
	var $body = jQuery('.pos-quick-create-body');
	var $bottom = jQuery('.pos-quick-create-bottom');
	
	var $hh = $h - ($bottom.height()) - 147;
	$body.height($hh);
}

function bill_loadAutocompleteCustomer(){
	jQuery('.autocomplete_customer').each(function(i,e){
		var $e = jQuery(e);
		//if($e.attr('data-loaded') == undefined){
			var $data = getAttributes($e);
			$delimiter = $e.attr('data-delimiter') ? $e.attr('data-delimiter') : ',';
			$e
		     // don't navigate away from the field on tab when selecting an item
		     .on( "keydown", function( event ) {
		       if ( event.keyCode === $.ui.keyCode.TAB &&
		           $( this ).autocomplete( "instance" ).menu.active ) {
		         event.preventDefault();
		       }
		     })
		     .autocomplete({
		       source: function( request, response ) {
		    	 $data['term'] = $data['q'] = extractLast( request.term );
		    	 //console.log($data)
		         $.getJSON( $cfg.adminUrl + '/ajax', $data, response );
		       },
		       search: function() {
		         // custom minLength
		         var term = extractLast( this.value );
		         if ( term.length < 1 ) {
		           return false;
		         }
		       },
		       focus: function() {
		         // prevent value inserted on focus
		    	  
		         return false;
		       },
		       select: function( event, ui ) {
		    	   var $dx = {};
		    	   $dx.action = 'bill_update_customer';
		    	   $dx.item = ui.item;
		    	   $dx.bill_id = $e.attr('data-bill_id');
		    	   sentAjaxData($dx);  
		    	   
		         //return false;
		       },
		       close: function( event, ui ) {
		    	    
		       }
		     })
		     
		     .autocomplete( "instance" )._renderItem = function( ul, item ) {
			      return $( "<li>" )
			        .append( "<div> " + item.label + "<br>" + item.desc + "</div>" )
			        .appendTo( ul );
			    };

			
		//}		
	});
}