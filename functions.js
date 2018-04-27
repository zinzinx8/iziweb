
function loadScrollDiv(){
	jQuery('.div-slim-scroll').each(function(i,e){
		var $e = jQuery(e);	
		if($e.attr('data-loaded-tag') == undefined){
			$h = $e.attr('data-height') ? $e.attr('data-height') : 300; 
			if($h == 'auto'){
				$h = Math.max(200,jQuery(window).height() - 370);
			}
			$e.slimScroll({
		        height: $h
		    });
		}
	});
}
function loadSelectTagsinput(){
	jQuery('.selectTagsinput').each(function(i,e){
		var $e = jQuery(e);	
		if($e.attr('data-loaded-tag') == undefined){
		var cities = new Bloodhound({
	    	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
	    	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	    	  prefetch: $cfg.cBaseUrl+ '/ajax/cities?action='+jQuery(e).attr('data-action')
	    	});
	    	cities.initialize();
	    	var elt = $e.tagsinput({    	 
	    	  itemValue: 'value',
	    	  itemText: 'text',
	    	  typeaheadjs: {
	    	    name: 'cities',
	    	    displayKey: 'text',
	    	    source: cities.ttAdapter()
	    	  },
	    	  
	    	});
	    	$e.on('itemAdded', function(event) {
	    		  if($e.attr('data-call-back')){
	    			 // alert('a')
	    			  eval($e.attr('data-call-back'));
	    		  }
	    	});;
	    	if($e.hasClass('autocomplete')){
				$e.parent().find('.bootstrap-tagsinput input').addClass('autocomplete').attr('data-action',$e.attr('data-action'))
				.attr('onkeypress','if (event.keyCode==13){return false;}')
			//if($e.attr('data-loaded') == undefined){
			//	jQuery(e).tagsinput('items').attr('data-loaded',true);
			//}
			$e.removeClass('autocomplete');
			}
	    	$e.attr('data-loaded-tag',true);
		}
	});
}

function loadSelectTagsinput1(){
	jQuery('.selectTagsinput').each(function(i,e){
		var $e = jQuery(e);	
		if($e.attr('data-loaded-tag') == undefined){
	 
	    	var elt = $e.tagsinput({    	 
	    	  itemValue: 'value',
	    	  itemText: 'text',
	    	   
	    	  
	    	});
	    	$e.on('itemAdded', function(event) {
	    		  if($e.attr('data-call-back')){
	    			 // alert('a')
	    			  eval($e.attr('data-call-back'));
	    		  }
	    	});;
	    	if($e.hasClass('autocomplete')){
				$e.parent().find('.bootstrap-tagsinput input').addClass('autocomplete').attr('data-action',$e.attr('data-action'))
				.attr('onkeypress','if (event.keyCode==13){return false;}')
			//if($e.attr('data-loaded') == undefined){
			//	jQuery(e).tagsinput('items').attr('data-loaded',true);
			//}
			$e.removeClass('autocomplete');
			}
	    	$e.attr('data-loaded-tag',true);
		}
	});
}


function loadTagsInput1(){
	
	jQuery('.tagsinput1').each(function(i,e){
		var $e = jQuery(e);
		if($e.attr('data-loaded-tag') == undefined){
		$e.tagsInput({
	    	delimiter:';',
	    	width:'100%',
	    	height:$e.attr('data-height') ? $e.attr('data-height') : 50,
	    	defaultText:$e.attr('placeholder') ? $e.attr('placeholder') : 'Thêm tag',
	    	confirmKeys:[13,59]
	    }).attr('data-loaded-tag',true);
		if($e.hasClass('autocomplete')){
			$e.parent().find('.tagsinput input').addClass('autocomplete').attr('data-action',$e.attr('data-action'))
			.attr('onkeypress','if (event.keyCode==13){return false;}')
			.attr('data-delimiter', $e.attr('data-delimiter') ? $e.attr('data-delimiter') : ';') 
		//if($e.attr('data-loaded') == undefined){
		//	jQuery(e).tagsinput('items').attr('data-loaded',true);
		//}
		$e.removeClass('autocomplete');
		}
		}
	});
	
}

function loadAutocomplete(){
	jQuery('.autocomplete').each(function(i,e){
		var $e = jQuery(e);
		if($e.attr('data-loaded') == undefined){
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
		    	 $data['term'] = extractLast( request.term );
		    	 //console.log($data)
		         $.getJSON( $cfg.adminUrl + '/ajax', $data, response );
		       },
		       search: function() {
		         // custom minLength
		         var term = extractLast( this.value );
		         if ( term.length < 2 ) {
		           return false;
		         }
		       },
		       focus: function() {
		         // prevent value inserted on focus
		    	  
		         return false;
		       },
		       select: function( event, ui ) {
		         var terms = split( this.value );
		         // remove the current input
		         terms.pop();
		         // add the selected item
		         terms.push( ui.item.value );
		         // add placeholder to get the comma-and-space at the end
		         terms.push( "" );
		         this.value = terms.join($delimiter);
		         //loadTagsInput();
		         //console.log(jQuery('.tagsinput1').val())
		         
		         return false;
		       },
		        
		     }).attr('data-loaded',true);
			
		}		
	});
}
 


function load_select2($t){
	jQuery(".ajax-select2,select.select2,.ajax-select2-no-search").each(function(i,e){
		var $this = jQuery(e); 
		if($this.attr('data-loaded') == undefined || $t == true){
//			console.log($this.attr('data-loaded'))
			$this.css({'width':'100%'})
			$this.select2({
					language: "vi",
					minimumResultsForSearch:$this.attr('data-search') == 'hidden' ? Infinity : ($this.attr('data-search') ? $this.attr('data-search') : 0),
			});
			$this.attr('data-loaded',true);
		}
	});
	jQuery.fn.select2.amd.require(["select2/core","select2/utils","select2/compat/matcher"], function (Select2, Utils, oldMatcher) {
		function formatRepo (repo) {
			if (repo.loading) return repo.text;
			var markup = "<div class='select2-result-repository clearfix'>" +                                   
			"<div class='select2-result-repository__meta'>" +                                     
			"<div class='select2-result-repository__title'>" + repo.text + "</div>";                                
			if (repo.description) {                                   
				markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";                                 
			}       
			markup += '';                
			markup += "</div></div>";
			return markup;
                                 
		}
		function formatRepoSelection (repo) {
			return repo.full_name || repo.text;
		}
                             
		jQuery('select.ajax-js-select-data-ajax,select.js-select-data-ajax,select.select2-ajax').each(function(index,element){
			var $this = jQuery(element);
			
			
			if($this.attr('data-loaded') == undefined){
			$this.select2({                             	  
				language: "vi",                                 
				ajax: {                                   
					url: $cfg.adminUrl + '/ajax/select2_ajax' ,                                   
					dataType: 'json',                                   
					delay: 250,                                   
					type:'POST',                                   
					data: function (params) {  
						var $data = getAttributes(jQuery(element));
						$data['q'] = params.term;
						$data['page'] = params.page; 
						//$data['role'] = jQuery(element).attr('data-role');
						return $data;                                  
					},                                   
					processResults: function (data) {                                       
						return {                                           
							results: data                                       
						};                                   
					},
					cache: true                               
				},                                 
				cache: false,                                 
				escapeMarkup: function (markup) { return markup; },                                 
				minimumInputLength: 1,                                 
				templateResult: formatRepo,                
			});          
			}
		});              
	});
}
function validate_form_before_submit($t){
	var $this = jQuery($t);
	var $target = $this.attr('data-target') ? jQuery($this.attr('data-target')) : $this; 
	var $submit = true;
	$target.find('.error.check_error').each(function(i,e){
		$submit = false; jQuery(e).focus();
        $er = jQuery(e).parent().find('.error_field');
        if($er.length == 0){$er = jQuery('<div class="error_field"></div>');jQuery(e).parent().append($er);}
        $erText = jQuery(e).attr('data-alert') ? jQuery(e).attr('data-alert') : '';
        $erText = $erText.replace(/{VAL}/g,jQuery(e).val());
        $er.html($erText);return false;
	});
	if($submit){
		var $error = $target.find('.error');
		var $required = $target.find('.required');
		$error.removeClass('error');
		$required.each(function(i,e){			
         	var $input = jQuery(e); var $val = $input.val();
         	var $type = $input.attr('data-type') ;//? jQuery(e).attr('data-select') : false;
         	$num = $input.attr('data-num') ? $input.attr('data-num') : false;
			//console.log($val)
         	switch($type){
			
			
			case 'select': case 'select2':
				if($num && parseFloat($val) > 0){
					
				}else{
					if($val != null && $val.length > 0){
						
					}else{
						$input.addClass('error').focus();
	         			$input.next().addClass('error');
	         			$submit = false;
					}
				}
				break;
			default:
				if($val.length == 0 || ($num && parseFloat($val) == 0)){
					$input.addClass('error').focus();
					$submit = false;return false;
				}
			$minlength = parseInt($input.attr('data-check-min') ? $input.attr('data-check-min') : $input.attr('data-min'));
 		   	if($minlength > 0 && $val.length < $minlength){
 		   		//
 		   		$div = '<div class="error_field error clear">Nhập tối thiểu '+$minlength+' ký tự.</div>';
 		   		$input.parent().find('.error_field').remove().append($div);
 		   		$input.parent().append($div);
 		   		//
 		   		$input.addClass('error').focus();     		   		 
 		   		$submit = false;return false;
 		   	}
 		    $compare = jQuery(e).attr('data-compare') ? jQuery(jQuery(e).attr('data-compare')) : false;
 		    if($compare){
 		    	if($val != $compare.val()){
     		   		//
     		   		$div = '<div class="error_field error">Giá trị không hợp lệ.</div>';
     		   		$input.parent().find('.error_field').remove();
     		   		$input.parent().append($div);
     		   		//
     		   		$input.addClass('error').focus();     		   		 
     		   		$submit = false;return false;
     		   	}
 		    }
 		    
 		    var $j1 = $target.find('.input_password');
 		    var $j2 = $target.find('.input_repassword');
 		   if($j1.length>0 && $j2.length>0){
     		 // $j1 = jQuery('.input_password');$j2 = jQuery('.input_repassword');
     		  
     		  $val1 = $j1.val();$val2 = $j2.val();       
     		  if($val1.length > 0){
     			$minlength = parseInt($j1.attr('data-min'));
     			if($minlength > 0 && $val1.length < $minlength){
      		   		//
      		   		$div = '<div class="error_field error">Nhập tối thiểu '+$minlength+' ký tự.</div>';
      		   		$j1.parent().find('.error_field').remove();
      		   		$j1.parent().append($div);
      		   		//
      		   		$j1.addClass('error').focus();     		   		 
      		   		$submit = false;
      		   		return false;
      		   	}else{
      		   		$j1.parent().find('.error_field').remove();
      		   	}
     			if($val2 != $val1){
     				$div = '<div class="error_field error">Mật khẩu không khớp.</div>';
      		   		$j2.parent().find('.error_field').remove().append($div);
      		   		$j2.parent().append($div);
      		   		//
      		   		$j2.addClass('error').focus();     		   		 
      		   		$submit = false;
      		   		return false;
     			}else{
     				$j2.parent().find('.error_field').remove();
     			}
     		  }        
 		   } 
				break;
			}
		});
	}
	return $submit;
}
function submitForm($t){
    var $this = jQuery($t);
    var $role = $this.attr('data-role');
    var $target = jQuery($this.attr('data-target'));    
    if(parseInt($role) == 5){
    	
       window.location = $cfg.cBaseUrl + '/' + $cfg.controller_text + $cfg.returnUrl;
    }else{
    	jQuery('input.btnSubmit').val($role);
    	$submit = validate_form_before_submit($t);
    	if($submit) $target.submit();    	
        return false;
    }


} 
function set_current_tab($t){
	var $this = jQuery($t);
	var $target = jQuery($this.attr('data-target'));
}
function Ad_quick_change_item($t){ 
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $val = $this.val();
	
	var $old = $this.attr('data-old');
	//
	if($this.attr('data-boolean') == 1){
		$val = $this.is(':checked') ? 1 : 0;
	}	
	//
	$data['value'] = $val;	 
	if($old != $val){
	jQuery.ajax({
  	  type: 'post',	  		 	
  	  datatype: 'json',	  			
  	  url: $cfg.cBaseUrl +'/ajax/update',	  			
  	  data:$data,	          
  	  beforeSend: function() {
  		  show_left_small_loading('show');             
  	  },	  			
  	  success: function(data) {  
  		  //console.log(data)
  		  $this.attr('data-old',$val);
          show_left_small_loading('hide');
            
  	  },	  				  			
  	  error : function(err, req) {	  			
  		  show_left_small_loading('error');
  	  },	  				            
  	  complete: function() {
  		   
  	  }
    });	      	        
	       
	}
}
function show_left_small_loading($action,$delay){
	$delay = $delay > 0 ? $delay : 1500;
	if(jQuery(".alert-success").length == 0) jQuery('body').append('<div class="alert-success"></div>');
	switch($action){
	case 'show':
		jQuery(".alert-success").removeClass('hide').addClass('in loading');
		break;
	case 'hide':
		jQuery(".alert-success").removeClass('loading') ;
		window.setTimeout(function() {jQuery(".alert-success").addClass('hide'); }, $delay); 
		break;
	case 'error':
		jQuery(".alert-success").removeClass('loading').addClass('error alert-danger alert-dismissible') ;
		window.setTimeout(function() {jQuery(".alert-success").addClass('hide'); }, $delay);  
		break;	
	}
	 
}
function checkUserExisteds($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $val = $this.val();
	
	var $old = $this.attr('data-old');
	//
	if($this.attr('data-boolean') == 1){
		var $val = $this.is(':checked') ? 1 : 0;
	}	
	//
	$data['value'] = $val;	 
	if($old != $val){
	jQuery.ajax({
  	  type: 'post',	  		 	
  	  datatype: 'json',	  			
  	  url: $cfg.cBaseUrl +'/ajax',	  			
  	  data:$data,	          
  	  beforeSend: function() {
  		  //show_left_small_loading('show');             
  	  },	  			
  	  success: function(data) {  		          
  		  $d = JSON.parse(data);
  		  $this.attr('data-old',$val);
	  	  if($d.state > 0){
	  		jQuery('.submitFormBtn').attr('disabled','');
	  		jQuery('#error-respon').html('<i class="glyphicon glyphicon-remove"></i> Tài khoản không hợp lệ hoặc đã được sử dụng.').removeClass('bg-success').addClass('bg-danger').show();
	  	  } else{
	  		jQuery('.submitFormBtn').removeAttr('disabled');
	  		jQuery('#error-respon').html('<i class="glyphicon glyphicon-ok"></i> Bạn có thể sử dụng tài khoản này.').removeClass('bg-danger').addClass('bg-success').show();
	  	  } 
            
  	  },	  				  			
  	  error : function(err, req) {	  			
  		  //show_left_small_loading('error');
  	  },	  				            
  	  complete: function() {
  		   
  	  }
    });	      	        
	       
	}
}
//

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function reload_app($t){
	switch($t){
	case 'switch-btn':
		 
		    jQuery("input[type=checkbox].ajax-switch-btn").switchButton({
		        labels_placement: "left"
		    }).removeClass('ajax-switch-btn');
		    
		break;
	case 'number_format':case 'number-format':
		jQuery('.ajax-number-format').each(function(i,e){
   		 $d = jQuery(e).attr('data-decimal') ? jQuery(e).attr('data-decimal') : 0; 
   		jQuery(e).number( true,$d); 
		});
	break;
	case 'date-time':case 'datepicker':case 'timepicker':
		jQuery('.ajax-datetimepicker').datetimepicker({
		       
		    	 format:'d/m/Y H:i:s',
		         //pickTime:false
		     });
		 
		jQuery('.ajax-datepicker').datetimepicker({
		      //language:'vi',//dateFormat:'d/m/Y',
		    	 format:'d/m/Y',
		         //pickTime:false
		     });
		 
		jQuery('.ajax-timepicker').datetimepicker({
		      //language:'vi',//dateFormat:'d/m/Y',
		    	 format:'H:i:s',
		         //pickTime:false
		    	 //inline:true
		     });
		break;
	case 'chosen':case 'chosen-select':
		var chosen_config = {
			'.chosen-select'           : {search_contains:true,case_sensitive_search:true},
		       '.chosen-select-deselect'  : {allow_single_deselect:true,search_contains:true,case_sensitive_search:true},
		       '.chosen-select-no-single' : {disable_search_threshold:10,search_contains:true,case_sensitive_search:true},
		       '.chosen-select-no-results': {no_results_text:'Oops, nothing found!',search_contains:true,case_sensitive_search:true},
		       '.chosen-select-width'     : {width:"95%",search_contains:true,case_sensitive_search:true},
			    '.chosen-select-no-search'     : {disable_search: true},
  }
  for (var selector in chosen_config) {
    if(jQuery(selector).length>0){
    	chosen_config[selector]['allow_single_deselect'] = jQuery(selector).attr('data-deselect') ? true : false;
    	//console.log(chosen_config[selector]);
    	jQuery(selector).chosen(chosen_config[selector]);
    }
  }
		jQuery('select.ajax-chosen-select-ajax').each(function(index,element){
			var $data = getAttributes(jQuery(element));
			$data['action'] = 'CHOSEN_AJAX';
			$data['role'] = $data.role ? $data.role : jQuery(element).attr('role');
			$data['dtype'] = $data.dtype ? $data.dtype : jQuery(element).attr('data-type');
			
			jQuery(element).ajaxChosen({
	         		   dataType: 'json',
	         		   type: 'POST',
	         		   data:$data,
	         		   url: $cfg.adminUrl + '/ajax/chosen_ajax',
	         		  search_contains:true
	            },{
	         		   loadingImg: $cfg.absoluteUrl+'/loading.gif'
	            }).removeClass('ajax-chosen-select-ajax'); 
	            
	 	});
  break;
	
	case 'select2':
		load_select2();
		 
		break;
	}
}
//
function submitAjax(t){
    
   var $this= jQuery(t); var $href;   
    if($this.attr('data-action') == 'current'){
    	$href = $this.attr('action') != "" ? $this.attr('action') : window.location.href;
    }else{
    	$href = $cfg.cBaseUrl + '/ajax';
    }
         
    var $submit = true;
    jQuery('.er').remove();    
    var $ckc = true;
    jQuery('.error.check_error').each(function(i,e){
    	 $submit = false;
    	 jQuery(e).focus();
    	 var $er = jQuery(e).parent().find('.error_field');
    	 if($er.length == 0){
    		 $er = jQuery('<div class="error_field"></div>');
    		 jQuery(e).parent().append($er);
    	 }
    	 var $erText = jQuery(e).attr('data-alert') ? jQuery(e).attr('data-alert') : '';
    	 $erText = $erText.replace(/{VAL}/g,jQuery(e).val());
    	 $er.html($erText);
         return false;
     });
     if($submit){
     $this.find('.required').each(function(i,e){
    	 var $e = jQuery(e);
         if($e.val().trim() == ""){        	
        	
        	if($e.attr('data-select') == 'select2'){
        		$e.parent().find('.select2-selection').addClass('error');
        	}else{
        		$e.focus();
        		$e.addClass('error')
        	}
            $ckc = false;
            return false;
         } 
     });
     
    if(!$ckc)  return false;
if($this.find('.cke_editor_ckeditor_content').length>0)  CKupdate();
     
    jQuery.ajax({
      type: 'post',
      datatype: 'json',
      url: $href,						 		 
      data: $this.serialize(),
      beforeSend:function(){
    	 // console.log($this.attr("data-hide-full-loading"));
    	  if($this.attr("data-hide-full-loading") == "1" ){
    		  
    	  }else{
    		  showFullLoading();
    	  } 
      },
      success: function (data) {
    	  
          hideFullLoading(); 
          if(data != ""){        	 
              var $d = JSON.parse(data);

              if($d.error == true  ){
                  showModal('Thông báo',$d.error_content)
              }else{
                  if($d.modal == true){
                      showModal('Thông báo',$d.modal_content) 
                  }
              }
              if($d.redirect == true  ){
                  window.location = $d.target;
              } 
              if($d.callback){
	    		  eval($d.callback_function);
	    	  }  
              if($d.event!=undefined){
              	switch($d.event){
              	 
              	case 'quick-add-more-room-to-supplier':
              		
              		break;
              	case 'quick-quick-edit-food':
              		$modal = jQuery('.mymodal1');
              		$modal.modal('hide');
              		jQuery('.after-event-'+$d.id).html($d.new_value);
              		break;
              	case 'quick-add-more-guides':
              		$modal = jQuery('.mymodal');
              		$modal.modal('hide');
              		loadGuidePrices($d['supplier_id']);
              		break;
              	case 'quick-add-more-menu-supplier':
              		$modal = jQuery('.mymodal');
              		$modal.modal('hide');
              		loadMenus($d['supplier_id']);
              		break;
              	case 'quick-add-foods-to-menu':
              		//console.log(data)
              		jQuery('.ajax-result-add-more-foods').append($d.html)
              		$modal = jQuery('.mymodal1');
              		$modal.modal('hide');
              		jQuery('.mymodal').css({'overflow-y':'scroll'});
              		jQuery('.btn-add-foods-to-menu').attr('data-existed',$d.existed).attr('data-count',$d.count);
              		break;
              	case 'quick-add-more-nationality-group-to-tickets':
              		var $target = jQuery('.ajax-load-group-nationality');
              		$modal = jQuery('.mymodal1');
              		$modal.modal('hide');
              		
              		$target.append($d.html);
              		jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed);
              		reload_app('number-format'); 
              		
              		break;
              	case 'quick_update_seo':
           			jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');
           			$this.find('input,textarea').attr('disabled','disabled');
           			
           			break;	
              	case 'edit_user_success':
           			jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');
           			$this.find('input').attr('disabled','disabled');
           			break;
              	case 'submit-controller-form':
              		show_left_small_loading('show');
              		show_left_small_loading('hide');
              		break;
              	case 'hide-modal':
              		if($d.modal_target){
              			$modal = jQuery($d.modal_target);
              		}else{
              			$modal = jQuery('.mymodal');
              		}
              		$modal.modal('hide');
              		 
              		break;
              	case 'relogin':
           			jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');
           			$this.find('input').attr('disabled','disabled');
           			window.location = $d.target;
           		break;
              	case 'forgot':
           			
           			if(!$d.state){
           				$r = '<p class="text-danger bg-danger pd15"><i class="glyphicon glyphicon-remove"></i> Rất tiếc, hệ thống không tìm thấy thông tin tài khoản của bạn.</p>';
           				jQuery('.error_respon').html($r);
           			}else{
           				$r = '<p class="text-success bg-success pd15"><i class="glyphicon glyphicon-ok"></i> Thông tin tài khoản đã được gửi tới email <b>'+$d.email+'</b>.<br/>Vui lòng kiểm tra và làm theo hướng dẫn.</p>';
           				jQuery('.error_respon').html($r);
           				jQuery('.remove-after-submit').remove();
           			}
           			break;
              	case 'quick-add-more-season-to-supplier':
              		var $target = jQuery($d.target);
              		$target.append($d.html);
              		$modal = jQuery('.mymodal');
              		$modal.modal('hide');
              		jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
              		reload_app('switch-btn');
              	break;
              	case 'quick-add-more-room-to-hotel':
              		var $target = jQuery('.ajax-result-quick-add-more-before');
              		$target.before($d.html);
              		$modal = jQuery('.mymodal');
              		$modal.modal('hide');
              		jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
              		reload_app('switch-btn');
              		break;
              	case 'quick-add-more-hight-way':
              		var $target = jQuery('.ajax-result-more-hight-way');
              		$target.append($d.html);
              		$modal = jQuery('.mymodal');
              		$modal.modal('hide');
              		jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
              		reload_app('switch-btn');
              		break;
              	case 'add_new_cost_distance':
              		jQuery.each($d.r,function($i,$e){
              			jQuery('.'+$d.target_class+$i).append($e);
              		});              		
              		
              		jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed).attr('data-count',$d.index);
              		jQuery('.mymodal').modal('hide');
              		reload_app('chosen');reload_app('select2');
              		reload_app('number-format');
              		reload_app('switch-btn');
              		break;
              	case 'set_quantity_vehicles_categorys':
              		
              	case 'set_quantity_currency':
              		jQuery('.'+$d.target_class).before($d.html);
              		jQuery('.'+$d.target_class).find('.btn-add-more').attr('data-existed',$d.existed_id).attr('data-count',$d.index);
              		jQuery('.mymodal').modal('hide'); 
              	break;
              	case 'quick_add_more_vehicle_category':
              		jQuery('.mymodal1').modal('hide');
              		jQuery('.mymodal').modal('show');
              		get_list_vehicles_makers('#select-chon-xe');
              		break;
              	case '_tour_program_add_service':
              		$pr = jQuery($d.parent);
              		var $target = $pr.find($d.target);
              		$target.before($d.html);
              		$c = $target.find('.btn-option .btn-count-array');
              		$cx = parseInt($c.attr('data-count')) > 0 ? parseInt($c.attr('data-count')) : 0;
              		$c.attr('data-count',$cx+1);
              		tour_program_calculation_price();
              		jQuery('.mymodal').modal('hide');
              		reload_app('number-format');
              		reload_app('chosen');
              		
              	break;
              	case '_tour_program_edit_service':
              		$pr = jQuery($d.parent);
              		var $target = $pr.find($d.target);
              		$target.replaceWith($d.html);
              		tour_program_calculation_price();
              		//$c = jQuery($d.target).find('.btn-option .btn-count-array');
              		//$cx = parseInt($c.attr('data-count')) > 0 ? parseInt($c.attr('data-count')) : 0;
              		//$c.attr('data-count',$cx+1);
              		//console.log($d.html)
              		jQuery('.mymodal').modal('hide');
              		reload_app('number-format');
              		reload_app('chosen');
              		
              	break;
              	case 'quick_edit_field':
              		jQuery($d.target).html($d.title);
              		jQuery('.mymodal').modal('hide');
              	break;
              		case 'redirect_link':
              		$timeout = $d.delay != undefined ? $d.delay : 0;
              		window.setTimeout(
              	              function() 
              	              {
              	                window.location = $d.target;
              	              }, $timeout);
              		break;
              		case 'clearInput':
	              		jQuery($d.target).val(''); 
	              		break;
              		case 'reload':
              			$timeout = $d.delay != undefined ? $d.delay : 0;
                  		window.setTimeout(
                  	              function() 
                  	              {
                  	            	  window.location = window.location;
                  	              }, $timeout);
              			
              			break;
              		case 'add_loai_thu_chi':
              			//alert($d.ptc.target );
              			jQuery('.mymodal1').modal('hide');
              			jQuery($d.target).html($d.select).trigger("chosen:updated").change();
              			break;
              		case 'chon_khach_san':
              			
              			$action = $d.action;
              			switch ($action) {
						case 'add':
							//$tbody = jQuery('.private-row-hotel-'+ $d.option + '-' +$d.pindex);
							$tbody = jQuery('.select_hotel_option_'+ $d.option).find('.private-row-hotel-'+$d.pindex);
							
							//$tbody.addClass('xxxxxxxxxxxxxxxxx'); 
							$v = parseInt(jQuery('#numberOfHotel').val())+1;
							jQuery('#numberOfHotel').val($v);
							var $target = jQuery($d.target); 
							$target.attr('data-index',$d.index);
							//alert($d.price); 
							$tbody.before($d.price);
							break;

						default:
							$input_name = jQuery('.input-hotel-name-'+$d.pindex+'-'+$d.index);
							$input_star = jQuery('.input-hotel-star-'+$d.pindex+'-'+$d.index);
              				$input_name.val($d.hotel['name']);
              				$input_star.val($d.hotel['star']);
              				//alert('.input-hotel-name-'+$d.pindex+'-'+$d.index)
              				//hotel-detail-body-index-1-0
              				$tbody = jQuery('.hotel-detail-body-index-'+ $d.option + '-' + $d.pindex + '-' + $d.index);
              				//alert('.hotel-detail-body-index-' + $d.pindex + '-' + $d.index)
              				//alert('.hotel-detail-body-index-'+ $d.option + '-' + $d.pindex + '-' + $d.index); 
              				$tbody.html($d.price);
							break;
						}
              			
              			 
              			jQuery('.mymodal').modal('hide');
              			reload_app('number_format');
              			changeHotelCost(jQuery('.sl-hotel-cost-amount'));
              			reloadCost();
              			break;
              		case 'chon_xe':
              			$action = $d.action;
              			//alert($d.item)
              			switch ($action) {
						case 'add':
							$tbody = jQuery('.public-row-car-0');
							//$tbody.addClass('xxxxxxxxxxxxxxxxx'); 
							$v = parseInt(jQuery('#numberOfCar').val())+1;
							jQuery('#numberOfCar').val($v);
							$tbody.before($d.price);
							jQuery('.btn-add-more-transport').attr('data-index', $d.index);
							break;

						default:
							$input_name = jQuery('.input-car-name-'+$d.index);
							//$input_star = jQuery('.input-hotel-star-'+$d.index);
              				$input_name.val($d.item['name']);
              				//$input_star.val($d.item['star']);
              				$tbody = jQuery('.car-detail-body-index-'+$d.index);
              				$tbody.html($d.price);
							break;
						}
              			
              			 
              			jQuery('.mymodal').modal('hide');
              			reload_app('number-format');
              			reloadCost();
              			break;	
              			
              		case 'checkInError':
              			$e = jQuery('.cError');
              			switch($d.error_code){
              			case 'SUCCESS':
              				$e.html('<p>Điểm danh thành công.</p>');
              				break;
              			case 'CHECKED':
              				$e.html('<p>Bạn đã điểm danh rồi.</p>');
              				break;
              			case 'USER_NOT_EXIST':
              				$e.html('<p>Không tìm thấy tài khoản.</p>');
              				break;
              			case 'NOT_FOUND':
              				$e.html('<p>Không tìm thấy lớp học.</p>');
              				break;
              			}
              			break;
              		case 'them_danh_muc_chiphi':
              			jQuery('#addCostCateID').append('<option ="'+$d.data['id']+'" selected>'+$d.data['name']+'</option>').trigger("chosen:updated");;
              			jQuery('.mymodal1').modal('hide');
              			break;
              	}
              	
              }
              if($d.callback_after){
	    		  eval($d.callback_after_function);
	    	  }
          }
          //else
          //window.location = window.location;
          
      },
      error : function(err, req) {
          hideFullLoading();
           
				 
				//alert('Lá»—i káº¿t ná»‘i, vui lÃ²ng thá»­ láº¡i.');
			}
    });
     }
    return false;  
}
function check_all_item($t){
	var $this = jQuery($t);
	var $role = $this.attr('data-role');
	$checkboxes = jQuery('input[data-role='+$role+']');
	$checkboxes.prop('checked', $this.is(':checked'));
}
function checkAllChildItem($t){
	var $this = jQuery($t);
	var $target = $this.attr('data-target');
	$checkboxes = jQuery('input.'+$target);
	//$checkboxes.prop('checked', $this.is(':checked'));
	if($this.attr('data-switch-btn') =='true'){
		$checkboxes.each(function(i,e){
			//console.log(jQuery(e).is(':checked'))
			if(jQuery(e).is(':checked') != $this.is(':checked')){
				jQuery(e).switchButton({
			        labels_placement: "left",
			        checked:$this.is(':checked')
			    }).prop('checked', $this.is(':checked'));
			}
		}) 
		
	}
}

function openUTab(t){
	var $this = jQuery(t);
	$p = $this.parent().parent();
	$p.find('.utab-header a, .utab-panel .tpanel').removeClass('active');
	$this.addClass('active');
	//
	$href = $this.attr('href') ? $this.attr('href') : $this.attr('data-href');
	$p.find('.utab-panel '+$href).addClass('active');
	return false;
}
function templete_load_child($t){
	 var $this = jQuery($t);
	 var $target = jQuery($this.attr('data-target'));
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'templete_load_child',id:$this.val()},
	      beforeSend:function(){
	    	  //showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	 // alert(data)
	    	 $target.html(data).select2();
	         
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function set_main_domain(t){
	var $this = jQuery(t);
	//$er = $this.parent().find('.error_field');
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'set_main_domain',val:$this.val(),id:$id },
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	 //console.log(data);
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function showLoading(){
	 jQuery(".alert-success").removeClass('hide').addClass('in loading');
}
function hideLoading(){
jQuery(".alert-success").removeClass('loading') ;
window.setTimeout(function() {
	jQuery(".alert-success").addClass('hide'); }, 500);
}
function setCheckboxBool(t){
	var $this = jQuery(t) ;
	$ck = $this.is(':checked');
	$this.val($ck ? 1 : 0);
}
function setRadioBool(t){
	var $this = jQuery(t) ;
	var $role = $this.attr('data-role');
	$sid = $this.attr('data-sid') ? parseInt($this.attr('data-sid')) : 0;
	$ck = $this.is(':checked');
	var checkboxes = $('.'+$role);     
    checkboxes.prop('checked', false).val(0);   
    $ck = $sid > 0 ? $ck : true;
	$this.prop('checked', $ck).val($ck ? 1 : 0);
}
function add_more_language($t){
	var $this = jQuery($t); $tr = $this.parent().parent();
	$k = parseInt($this.attr('data-count'));$this.attr('data-count',$k+1);
	$html  = '<tr><th scope="row"></th>'; 
	$html += '<td><input type="text" name="f['+$k+'][title]" value="" class="form-control input-sm"/></td>'; 
	 
	$html += '<td><input type="text" name="f['+$k+'][code]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="f['+$k+'][domain]" value="" class="form-control input-sm"/></td>';
	$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="f['+$k+'][root_active]" value="0" /></td>';
	$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="f['+$k+'][is_active]" value="0" /></td>'; 
	$html += '<td class="center"><input onchange="setRadioBool(this);" data-role="radio_bool1" type="radio" name="f['+$k+'][default]" value="0" class="radio_bool1"/></td>'; 
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td></tr>';
	$tr.before($html);
}
function checkDomain(t){
	var $this = jQuery(t);
	//$er = $this.parent().find('.error_field');
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'checkDomain',val:$this.val(),id:$id },
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  //alert(data)
	    	  $d = JSON.parse(data);
	    	  
	    	  //$this.attr('data-old',$new);
	    	  // alert(data)
	    	  //$code.val(data)
	           if($d.state == true){
	        	   $this.removeClass('error');jQuery('.submitFormBtn').removeAttr('disabled');
	        	   $er.addClass('success').html($d.msg);
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          }else{
	        	  jQuery('.submitFormBtn').attr('disabled','disabled');
	        	  $this.addClass('error');
	        	  $add = '<p class="red">'+$d.msg+'</p>';
	        	   
	        	  $er.removeClass('success').html($add);
	          }
	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  $this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function setdefaultTemplete($t ){
	
	var $this = jQuery($t);
	$lang = $this.attr('data-lang');
	$id = parseInt($this.val());
 
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'setdefaultTemplete',lang:$lang ,id:$id },
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	   	//alert(data)
	    	  // jQuery('body').append('<div class="hide" id="default_formstyle_'+$role+'">'+data+'</div>');
	    	  
	    	  //hideFullLoading();
	    	  hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
	 
}
function add_delete_item($id,$t){
	var $this = jQuery($t);
	var $target = $this.attr('data-target') ? $this.attr('data-target') : '.form_quick_remove_item';
	$name = $this.attr('data-name') ? $this.attr('data-name') : 'delete_item';
	if(jQuery($target).length == 0 ){
		jQuery('#editFormContent').prepend('<div class="'+($target.replace('.',''))+' hide"></div>');
	}
	jQuery($target).append('<input type="hidden" name="'+$name+'[]" value="'+$id+'"/>');
}
function add_more_rooms_categorys($t){
	var $this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	var $target = $this.parent().parent();
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="f['+$c+'][title]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="f['+$c+'][note]" value="" class="form-control input-sm"/></td>';  
	$html += '<td class="center"><input type="text" name="f['+$c+'][seats]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>';  
	$html += '<td class="center"><input type="text" name="f['+$c+'][position]" value="9" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="x['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmin]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
    
	$html += '<td class="center"><div class="onoff-button-div"><input type="checkbox" name="f['+$c+'][is_active]" checked class="switchBtn ajax-switch-btn" /></div></td>'; 
	
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
	reload_app('switch-btn');reload_app('number-format');
}
///////////////////////////////////////////////////////////////////////////////////////////////
function showCModal($content,t){ 
	$html = '';var $this = jQuery(t);
	$today = $cfg.time;//jQuery.format.date(new Date(), "d/m/Y H:m") ;
	//alert(jQuery.format.date("2009-12-18 10:54:50.546", "Test: d/m/Y"));
	$load_type = $this.attr('data-type') ? $this.attr('data-type') : 'normal';
	$utype = $this.attr('data-utype') ? $this.attr('data-utype') : 'CUS';
	$cusID = $this.attr('data-cusID') ? $this.attr('data-cusID') : 0;
	$book = $this.attr('data-book') ? $this.attr('data-book') : 1;
    switch ($content) {
    case 'quick_edit_field':
    	//$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	//$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	//$sendType = $this.attr('data-send') ?  ($this.attr('data-send')) : 'normal';
    	$id = $this.attr('data-id');
    	//$email = $this.attr('data-email') ?  ($this.attr('data-email')) : false; 
    	$titlex = $this.attr('data-title') ?  ($this.attr('data-title')) : ''; 
    	$table = $this.attr('data-table') ?  ($this.attr('data-table')) : ''; 
    	$sid = $this.attr('data-sid') ?  ($this.attr('data-sid')) : 0; 
    	var $target = $this.attr('data-target') ?  ($this.attr('data-target')) : '';  
    	$title =  'Chỉnh sửa nhanh' ;
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group">';
        //$html += '<label class="control-label col-sm-2" >TiÃªu Ä‘á»� thÆ°</label>';
        $html += '<div class="col-sm-12 " >';
       
        $html += '<input name="f[title]" type="text" value="'+$titlex+'" class="form-control first-target " placeholder="TiÃªu Ä‘á»�" />';
        //$html += '<input name="field[email]" id="sEmailList" type="hidden" value="'+$email+'"  />'; 
        //$html += '<input name="field[reply]" type="hidden" value="'+$reply+'"  />';         
        $html += '</div>';            
        $html += '</div>'; 
       
      
        
       // $html += '<div class="form-group">';
       // $html += '<label class="control-label col-sm-2" >LÃ½ do</label>';
       // $html += '<div class="col-sm-12">';
        //$html += '<textarea id="'+$this.attr('data-editor')+'" class="form-control ckeditorSENDxxx required" name="field[text]"></textarea>';
         
       // $html += '</div>';            
       // $html += '</div>';
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> LÆ°u láº¡i</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		//$html += '<input type="hidden" name="field[cusID]" value="'+$cusID+'"><input type="hidden" name="field[classID]" value="'+$classID+'">';
		$html += '<input type="hidden" name="action" value="quick_edit_field">';
		$html += '<input type="hidden" name="f[id]" value="'+$id+'">';
		$html += '<input type="hidden" name="f[table]" value="'+$table+'">';
		$html += '<input type="hidden" name="f[sid]" value="'+$sid+'">';
		$html += '<input type="hidden" name="f[_target]" value="'+$target+'">';
		///$html += '<input type="hidden" name="f[field]" value="'+$this.attr('data-field')+'">';
		$html += '</form>';
		 
    	break;
    case 'send_email_to':
    	$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	$sendType = $this.attr('data-send') ?  ($this.attr('data-send')) : 'normal';
    	$id = $this.attr('data-id');
    	$email = $this.attr('data-email') ?  ($this.attr('data-email')) : false; 
    	$reply = $this.attr('data-reply') ?  ($this.attr('data-reply')) : false; 
    	$eT = $email;
    	if($sendType == 'all'){
    		$email = jQuery('#avEmailList').val();
    		$eT = 'Táº¥t cáº£ há»�c viÃªn trong lá»›p';
    	}
    	$title =  'Gá»­i email tá»›i: <span style="text-transform:none;font-weight:300">' + $eT + '</span>' ;
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group">';
        //$html += '<label class="control-label col-sm-2" >TiÃªu Ä‘á»� thÆ°</label>';
        $html += '<div class="col-sm-12 " >';
       
        $html += '<input name="field[title]" type="text" value="" class="form-control" placeholder="TiÃªu Ä‘á»� thÆ°" />';
        $html += '<input name="field[email]" id="sEmailList" type="hidden" value="'+$email+'"  />'; 
        $html += '<input name="field[reply]" type="hidden" value="'+$reply+'"  />';         
        $html += '</div>';            
        $html += '</div>'; 
       
      
        
        $html += '<div class="form-group">';
       // $html += '<label class="control-label col-sm-2" >LÃ½ do</label>';
        $html += '<div class="col-sm-12">';
        $html += '<textarea id="'+$this.attr('data-editor')+'" class="form-control ckeditorSENDxxx required" name="field[text]"></textarea>';
         
        $html += '</div>';            
        $html += '</div>';
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Gá»­i yÃªu cáº§u</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="field[cusID]" value="'+$cusID+'"><input type="hidden" name="field[classID]" value="'+$classID+'">';
		$html += '<input type="hidden" name="action" value="sendEmailTo"><input type="hidden" name="field[sendType]" value="'+$sendType+'">';
		$html += '</form>';
		 
    	break;	
    case 'select_hotel':
    	$quot = jQuery('#inputQuotation').val() == 'true' ? true : false;
    	$index = $this.attr('data-index') ?  $this.attr('data-index') : false; 
    	$pindex = $this.attr('data-pindex') ?  $this.attr('data-pindex') : false; 
    	$action = $this.attr('data-action') ?  $this.attr('data-action') : false; 
    	var $target = $this.attr('data-target') ?  $this.attr('data-target') : false;
    	$iOption = $this.attr('data-option') ?  $this.attr('data-option') : false;
    	//alert($iOption)
    	//var $target = $this.attr('data-target') ?  $this.attr('data-target') : false;
    	$tourType = jQuery('.radio_ctype input:checked').val();
    	$totalGuest = jQuery('#input-tour-sokhach').val(); 
    	$night = jQuery('#input-night-amount').val();
    	if($action == 'add'){
    		//$index = jQuery('#numberOfHotel').val();
    	}
    	$title =  'Chá»�n khÃ¡ch sáº¡n' ;
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
  
       

        $html += '<div class="form-group">';
        //$html += '<label class="control-label col-sm-2" for="inputLoaithu">Ká»³ ná»™p</label>';
        $html += '<div class="col-sm-12">';        
        $html += '<select id="select-chon-khach-san" onchange="get_list_chon_phong(this);" name="f[hotelID]" role="select_hotel" class="form-control ajax-chosen-select-ajax"><option></option></select>';
        $html += '</div>';            
        $html += '</div>';
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-6 aleft bold" >Danh sÃ¡ch phÃ²ng</label>';
        $html += '<div class="col-sm-12">';        
        $html += '<div id="bang_list_chon_xe"></div><div class="show_error_out"></div>';
        $html += '</div> ';            
        $html += '</div>';
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button onclick="return check_form_chon_xe();" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chá»�n</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="f[index]" value="'+$index+'">';
		$html += '<input type="hidden" name="f[pindex]" value="'+$pindex+'">';
		$html += '<input type="hidden" name="f[target]" value="'+$target+'">';
		$html += '<input type="hidden" name="f[action]" value="'+$action+'">';
		$html += '<input type="hidden" name="f[night]" value="'+$night+'">';
		$html += '<input type="hidden" name="f[type]" value="'+$tourType+'">';
		$html += '<input type="hidden" name="f[guest]" value="'+$totalGuest+'">';
		$html += '<input type="hidden" name="f[quot]" value="'+$quot+'">'; 
		$html += '<input type="hidden" name="f[iOption]" value="'+$iOption+'">'; 
		$html += '<input type="hidden" name="action" value="chon_khach_san">';
		$html += '</form>';
		 
    	break;	
    case 'select_car':
    	 
    	$index = $this.attr('data-index'); 
    	$action = $this.attr('data-action') ?  $this.attr('data-action') : false; 
    	$radio_quotation_type = get_quotation_type();
    	$night = jQuery('#input-night-amount').val();
        $quot = jQuery('#inputQuotation').val() == 'true' ? true : false;
    	if($action == 'add'){
    		//$index = jQuery('#numberOfHotel').val();
    	}
    	$title =  'Chá»�n xe' ;
		 
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
  
       

        $html += '<div class="form-group">';
        //$html += '<label class="control-label col-sm-2" for="inputLoaithu">Ká»³ ná»™p</label>';
        $html += '<div class="col-sm-12">';        
        $html += '<select id="select-chon-xe" onchange="get_list_chon_xe(this);" name="f[carID]" data-role="select_car" class="form-control ajax-chosen-select-ajax"><option></option></select>';
        $html += '</div></div>';
  
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-6 aleft bold" >Danh sÃ¡ch xe</label>';
        $html += '<div class="col-sm-12">';        
        $html += '<div id="bang_list_chon_xe"></div><div class="show_error_out"></div>';
        $html += '</div> ';            
        $html += '</div>';
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button onclick="return check_form_chon_xe();" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chá»�n</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="f[index]" value="'+$index+'">';
		$html += '<input type="hidden" name="f[action]" value="'+$action+'">';
		$html += '<input type="hidden" name="f[night]" value="'+$night+'">';
        $html += '<input type="hidden" name="f[quot]" value="'+$quot+'">'; 
        $html += '<input type="hidden" name="f[qtype]" value="'+$radio_quotation_type+'">';
		$html += '<input type="hidden" name="action" value="chon_xe">';
		$html += '</form>';
		 
    	break;	
    case 'them_phan_hoi':
    	$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	$title =   'Ä�Ã³ng gÃ³p Ã½ kiáº¿n';
    	$id = $this.attr('data-id');
    	 
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
  
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Lá»›p</label>';
        $html += '<div class="col-sm-10" id="BAOLUU_CLASSID">';
        if($cusID > 0 && $classID > 0){
        	$html += '<b style="line-height:30px">'+$this.attr('data-cname')+'</b><input id="M_CUSID" name="field[cusID]" type="hidden" value="'+$cusID+'" /><input id="M_CLASSID" type="hidden" name="field[classID]"  value="'+$classID+'" />';
        }else{
        	//$html += '<select name="field[classID]"  class="form-control chosen-select-no-single required baoluu_class" role="load_class" data-type="CUS"><option></option></select>';
        }
                            
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TrÃ¬nh há»�c</label>';
        $html += '<div class="col-sm-10" >';
         
        $html += '<select name="field[blockID]" id="M_BLOCKID" class="form-control chosen-select-no-single required respon_class" ><option></option></select>';
        
                            
        $html += '</div>';            
        $html += '</div>'; 
    
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Ná»™i dung</label>';
        $html += '<div class="col-sm-10">';
        $html += '<textarea class="form-control required" name="field[text]" style="height:150px"></textarea>';
         
        $html += '</div>';            
        $html += '</div>';
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Gá»­i yÃªu cáº§u</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
	 
		$html += '<input type="hidden" name="action" value="hocvien_phanhoi"><input type="hidden" name="_time_field" value="from_date,to_date">';
		$html += '</form>';
		 
    	break;	
    case 'quickAddCustomer':
    	$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	$title = 'ThÃªm má»›i khÃ¡ch hÃ ng';
    	$id = $this.attr('data-id');    	 
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Mã KH (<i class="red">*</i>)</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[code]" data-alert="Mã khách hàng <b class=red>{VAL}</b> Ä‘Ã£ Ä‘Æ°á»£c sá»­ dá»¥ng." onchange="checkCustomerCode(this);" type="text" class="form-control check_error required" value="" title="Chiá»�u dÃ i tá»‘i Ä‘a 32 kÃ½ tá»±." placeholder="MÃ£ KH gá»“m 3 kÃ½ tá»±." />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TÃªn Cty (<i class="red">*</i>)</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[name]" type="text" class="form-control required" value="" placeholder="Nháº­p tÃªn khÃ¡ch hÃ ng / CÃ´ng ty" />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TÃªn viáº¿t táº¯t</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[short_name]" type="text" class="form-control" value="" placeholder="TÃªn viáº¿t táº¯t" />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Tá»‰nh thÃ nh</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<select class="chosen-select" name="f[localID]" id="selectLoadLocation" style="width:100%"></select>';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Ä�á»‹a chá»‰ (<i class="red">*</i>)</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[address]" type="text" class="form-control required" value="" placeholder="Nháº­p Ä‘á»‹a chá»‰" />';         
                
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Ä�iá»‡n thoáº¡i (<i class="red">*</i>)</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[phone]" type="text" class="form-control required" value="" placeholder="Nháº­p sá»‘ Ä‘iá»‡n thoáº¡i" />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Email</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[email]" type="email" class="form-control" value="" placeholder="Nháº­p email" />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >MÃ£ sá»‘ thuáº¿</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[tax]" type="text" class="form-control" value="" placeholder="Nháº­p mÃ£ sá»‘ thuáº¿" />';
         
                
        $html += '</div>';            
        $html += '</div>'; 
        

        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Ä�á»‹a chá»‰ thuáº¿</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[taxAddress]" type="text" class="form-control" value="" placeholder="Nháº­p Ä‘á»‹a chá»‰ thuáº¿" />';
                       
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Sá»‘ tÃ i khoáº£n</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="biz[accountNumber]" type="text" class="form-control" value="" placeholder="Sá»‘ tÃ i khoáº£n ngÃ¢n hÃ ng giao dá»‹ch" />';
                       
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TÃªn ngÃ¢n hÃ ng</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="biz[accountName]" type="text" class="form-control" value="" placeholder="TÃªn ngÃ¢n hÃ ng giao dá»‹ch" />';
                       
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >NgÆ°á»�i liÃªn há»‡</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="biz[contactPerson]" type="text" class="form-control" value="" placeholder="TÃªn ngÆ°á»�i liÃªn há»‡" />';
                       
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Sá»‘ Ä‘iá»‡n thoáº¡i</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="biz[contactPhone]" type="text" class="form-control" value="" placeholder="Sá»‘ Ä‘iá»‡n thoáº¡i ngÆ°á»�i liÃªn há»‡." />';
                       
        $html += '</div>';            
        $html += '</div>';         
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Cáº­p nháº­t</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
	 
		$html += '<input type="hidden" name="action" value="quickAddCustomer"><input type="hidden" name="_time_field" value="from_date,to_date">';
		$html += '</form>';
		 
    	break;
    case 'quickAddTourType':
    	 
    	$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	$title = 'ThÃªm nhÃ³m tour / Chá»§ Ä‘á»� tour';
    	$id = $this.attr('data-id');    	 
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TiÃªu Ä‘á»�</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[name]" type="text" class="form-control required" value="" title="" placeholder="Nháº­p tiÃªu Ä‘á»� cho nhÃ³m." />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Cáº­p nháº­t</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
 
	 
		$html += '<input type="hidden" name="action" value="quickAddTourType"><input type="hidden" name="_time_field" value="from_date,to_date">';
		$html += '</form>';
		 
    	break;	
    case 'quickAddCost':
    	$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	var $type = $this.attr('data-type') ? parseInt($this.attr('data-type')) : 1;
    	$title = 'ThÃªm má»›i chi phÃ­';
    	$id = $this.attr('data-id');    	 
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';       
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TiÃªu Ä‘á»�</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[name]" type="text" class="form-control required" value="" placeholder="Nháº­p tÃªn chi phÃ­" />';
                         
        $html += '</div>';            
        $html += '</div>';         
        
        $html += '<div class="form-group">';
        
        $html += '<label class="control-label col-sm-2" >Danh má»¥c</label><div class="col-sm-10">';
                   
        $html += '<div class="input-group group-sm34 cs-select-no-border-radius-right">';
		$html += '<select id="addCostCateID" data-type="0" data-num="true" data-select="chosen" class="ajax-chosen-select select_costs_from_data" role="load_cost_category" data-placeholder="Chá»�n danh má»¥c" style="width: 100%"><option></option></select>';
		$html += '<span class="input-group-btn"><button class="btn btn-success h34" title="Táº¡o má»›i loáº¡i chi phÃ­" onclick="themdanhmucchiphi(this);" type="button"><i class="glyphicon glyphicon-plus"></i></button></span>';
		    
          
        $html += '</div> </div></div>';
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Loáº¡i chi phÃ­</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<select class="form-control" name="f[type]">';
        $html += '<option '+($type == 1 ? 'selected' : '')+' value="1">Chi phÃ­ chung </option>';
        $html += '<option '+($type == 2 ? 'selected' : '')+' value="2">Chi phÃ­ riÃªng </option>';
        $html += '</select>';                 
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Ä�Æ¡n giÃ¡</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[price]" type="text" class="form-control numberFormat" value="" placeholder="Ä�Æ¡n giÃ¡" />';
                         
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >Ä�Æ¡n vá»‹ tÃ­nh</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[unit]" type="text" class="form-control" value="" placeholder="VD:[8000,000Ä‘] / xe, [3 khÃ¡ch] / phÃ²ng, [2 chiáº¿c] / ngÆ°á»�i, ..." />';
         
                
        $html += '</div>';            
        $html += '</div>'; 
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" ></label>';
        $html += '<div class="col-sm-10 " >';
        $html += '<div class="checkbox"><label><input name="f[default]" type="checkbox">Ä�áº·t lÃ m máº·c Ä‘á»‹nh</label></div>';

       // $html += '<input name="f[taxAddress]" type="text" class="form-control" value="" placeholder="Nháº­p Ä‘á»‹a chá»‰ thuáº¿" />';
                       
        $html += '</div>';            
        $html += '</div>'; 
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Cáº­p nháº­t</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
	 
		$html += '<input type="hidden" name="action" value="quickAddCost"><input type="hidden" name="_time_field" value="from_date,to_date">';
		$html += '</form>';
		 
    	break;	
    	
    case 'quickAddTourType':
    	$cusID = $this.attr('data-cusID') ? parseInt($this.attr('data-cusID')) : 0;
    	$classID = $this.attr('data-classID') ? parseInt($this.attr('data-classID')) : 0;
    	$title = 'ThÃªm má»›i loáº¡i tour';
    	$id = $this.attr('data-id');    	 
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';               
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" >TiÃªu Ä‘á»� (<i class="red">*</i>)</label>';
        $html += '<div class="col-sm-10 " >';
        
        $html += '<input name="f[name]" type="text" class="form-control required" value="" placeholder="Nháº­p tiÃªu Ä‘á»�" />';
                         
        $html += '</div>';            
        $html += '</div>';           
                
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Cáº­p nháº­t</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
	 
		$html += '<input type="hidden" name="action" value="quickAddTourType"><input type="hidden" name="_time_field" value="from_date,to_date">';
		$html += '</form>';
		 
    	break;	
    	
    case 'showAttach':
    	$title = 'TÃ i liá»‡u Ä‘Ã­nh kÃ¨m';
    	$id = $this.attr('data-id');
		//$lydo = $content == 'phieuthu' ? 'LÃ½ do thu' : 'LÃ½ do chi';
		//$nn = $content == 'phieuthu' ? 'NgÆ°á»�i ná»™p' : 'NgÆ°á»�i nháº­n';
		//$loaithu = $content == 'phieuthu' ? 'Loáº¡i thu' : 'Loáº¡i chi';
		
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxFormd form-horizontal f12e" method="post" onsubmit="return ajaxUploadForm(this);" enctype="multipart/form-data">';
		//$html += '';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group" style="border-bottom:1px solid #e5e5e5">';
        //$html += '<label for="inputMaphieu" class="col-sm-2 control-label">TÃªn TL</label>';
        $html += '<div class="col-sm-12">';
        $html += '<table class="table table-bordered table-striped table-hover">';
        $html += '<thead><tr><th style="width:50px">STT</th><th>TÃªn tÃ i liá»‡u</th><th class="center" style="width:100px">Táº£i vá»�</th></tr></thead>';
        $html += '<tbody class="list_file_attach">';
        $html += '<tr><th scope="row">1</th><td>Otto</td><td class="center"><a href=""><i class="glyphicon glyphicon-cloud-download"></i></a></td></tr>';
        
      	$html +='</tbody></table>';
        $html += '</div>';
        $html += '</div>';
        
        $html += '<div class="form-group">';
        $html += '<label for="inputMaphieu" class="col-sm-2 control-label">TÃªn tÃ i liá»‡u</label>';
        $html += '<div class="col-sm-10">';
        $html += '<input type="text" class="form-control required" name="field[name]" id="inputMaphieu" placeholder="Nháº­p tÃªn tÃ i liá»‡u">';
        $html += '</div>';
        $html += '</div>';
        
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Chá»�n file</label>';
        $html += '<div class="col-sm-10">';
        //$html += '<input type="text" class="form-control" id="inputLoaithu" aria-describedby="inputLoaithu">';
        $html += '<input type="file" name="file_attach" id="file_attach"  class="form-control required" />';
 
        $html += '</div>';            
        $html += '</div>';

       
         
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-cloud-upload"></i> Upload tÃ i liá»‡u</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="submitAction" value="save">';
		 
		$html += '<input type="hidden" name="action" value="fileAttach">';
		$html += '<input type="hidden" value="'+$id+'" name="field[blockID]" class="input_hidden_ptc_ghino" />';
		//$html += '<input type="hidden" value="" name="ptc[ghico]" class="input_hidden_ptc_ghico" />';
		$html += '';
		
		$html += '</form>';
		$html += '';
    	break;
    	
	case 'phieuthu':
	case 'phieuchi':
		$title = $content == 'phieuthu' ? 'Phiáº¿u thu' : 'Phiáº¿u chi';
		$lydo = $content == 'phieuthu' ? 'LÃ½ do thu' : 'LÃ½ do chi';
		$nn = $content == 'phieuthu' ? 'NgÆ°á»�i ná»™p' : 'NgÆ°á»�i nháº­n';
		$loaithu = $content == 'phieuthu' ? 'Loáº¡i thu' : 'Loáº¡i chi';
		$ht = $content == 'phieuthu' ? 'HÃ¬nh thá»©c' : 'HÃ¬nh thá»©c';
		
		var $type = $this.attr('data-type') ? $this.attr('data-type') : 'normal';
		$cusID = $this.attr('data-cusID') ? $this.attr('data-cusID') : 0;
		
		
		$html += '<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group">';
        $html += '<label for="inputMaphieu" class="col-sm-2 control-label">MÃ£ phiáº¿u</label>';
        $html += '<div class="col-sm-10">';
        $html += '<input type="text" class="form-control" name="ptc[maso]" id="inputMaphieu" placeholder="MÃ£ phiáº¿u tá»± Ä‘á»™ng" readonly>';
        $html += '</div>';
        $html += '</div>';
        
        $html += '<div class="form-group">';
        $html += '<label for="inputMaphieu" class="col-sm-2 control-label">Thá»�i gian</label>';
        $html += '<div class="col-sm-10">'; 
        $html += '<div class="input-group datetimepicker"><input type="text" id="PTC_Time" name="ptc[time]" class="form-control required" value="'+$today+'" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
        $html += '</div>';
        $html += '</div>';
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">'+$loaithu+'</label>';
        $html += '<div class="col-sm-10">';
        //$html += '<input type="text" class="form-control" id="inputLoaithu" aria-describedby="inputLoaithu">';
        $html += '<div class="input-group"><div class="cs-select-no-border-radius-right"><select name="ptc[maloai]" data-type="'+$load_type+'" onchange="changeNguoiNop(this);loadCustomerClass(this);" id="PTC_LoaiThuChi"  class="form-control chosen-select-no-single select_chon_loai_thu_chi"><option></option></select></div><span class="input-group-addon pointer" data-target=".select_chon_loai_thu_chi" data-type="'+$content+'" onclick="them_loai_thu(this);"><span class="glyphicon glyphicon-plus"></span></span></div>';
 
        $html += '</div>';            
        $html += '</div>';
        if($content == 'phieuthu'){
        $html += '<div class="form-group">';
        $html += '<label for="inputMaphieu" class="col-sm-2 control-label"></label>';
        $html += '<div class="col-sm-10">';
        $html += '<label style="margin-right:15px">';
        $html += '<input onchange="loadNguoiNop(\'.select_chon_nguoi_nop\','+$cusID+') ;loadCustomerClass(this); " type="radio" name="ptc[nguoinop]" class="optionsRadios1 doi_tuong_thu doi_tuong_thu_HP" value="CUS"  >';
        $html += ' Há»�c viÃªn ';
        $html += '</label>';    
        
        $html += '<label style="margin-right:15px">';
        $html += '<input onchange="loadNguoiNop(\'.select_chon_nguoi_nop\','+$cusID+') ;loadCustomerClass(this); " type="radio" name="ptc[nguoinop]" class="optionsRadios1 doi_tuong_thu  doi_tuong_thu_NV" value="EMP"  >';
        $html += ' NhÃ¢n viÃªn ';
        $html += '</label>';  
        
        $html += '<label style="margin-right:15px">';
        $html += '<input type="radio" onchange="loadNguoiNop(\'.select_chon_nguoi_nop\','+$cusID+');loadCustomerClass(this); " name="ptc[nguoinop]" class="optionsRadios1 doi_tuong_thu" value="OTHER" checked >';
        $html += 'KhÃ¡c';
        $html += '</label>';      
        $html += '</div>';
        $html += '</div>';
        
        $html += '<div class="form-group">';
        $html += '<label for="inputMaphieu" class="col-sm-2 control-label"></label>';
        $html += '<div class="col-sm-10">';
        $html += '<label style="margin-right:15px">';
        $html += '<input type="radio" name="ptc[type]" class="optionsRadios2 doi_tuong_thu" value="TM"  checked>';
        $html += 'TM';
        $html += '</label>';      
        
        $html += '<label style="margin-right:15px">';
        $html += '<input type="radio" name="ptc[type]" class="optionsRadios2 doi_tuong_thu" value="CK"  >';
        $html += 'CK';
        $html += '</label>';
      
        $html += '</div>';
        $html += '</div>';
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">'+$nn+'</label>';
        $html += '<div class="col-sm-10">';
        $html += '<div class="input-group"><div class="cs-select-no-border-radius-right"><select name="ptc[cusID]" id="PTC_NguoiNop" onchange="loadCustomerClass(this);" class="form-control ajax-chosen-select select_chon_nguoi_nop" role="load_nguoi_nop" data-type="OTHER"><option></option></select></div><span class="input-group-addon pointer" data-target=".select_chon_nguoi_nop" data-type="'+$content+'" onclick="them_nguoi_nop(this);"><span class="glyphicon glyphicon-plus"></span></span></div>';
        $html += '<input type="hidden" value="" name="ptc[hoten]" class="input_hidden_ptc_hoten" />';
        $html += '<input type="hidden" value="" name="ptc[diachi]" class="input_hidden_ptc_diachi" />';
        $html += '</div>';            
        $html += '</div>';
        }else{
        	$html += '<div class="form-group" >';
            $html += '<label class="control-label col-sm-2" for="inputLoaithu">Chá»�n lá»›p</label>';
            $html += '<div class="col-sm-10">';        
            $html += '<select name="ptc[classID]" id="PTC_Class" class="form-control chosen-select-no-single select_chon_lop"><option></option></select>';
            $html += '</div>';            
            $html += '</div>';
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">'+$nn+'</label>';
        $html += '<div class="col-sm-10">';       
        $html += '<input type="text" class="form-control required" value="" name="ptc[hoten]" />';
  
        $html += '<input type="hidden" value="0" name="ptc[cusID]" class="" />';
        $html += '</div>';            
        $html += '</div>';
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Ä�á»‹a chá»‰</label>';
        $html += '<div class="col-sm-10">';       
        $html += '<input type="text" value="" name="ptc[diachi]" class="form-control" />';
        //$html += '<input type="hidden" value="0" name="ptc[cusID]" class="" />';
        $html += '</div>';            
        $html += '</div>';
        }
        if($content == 'phieuthu'){
        $html += '<div class="form-group hidden_class hclass" style="display:none">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Chá»�n lá»›p</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<select name="ptc[classID]" data-type="'+$utype+'" id="PTC_Class" onchange="loadClassBlock(this);" class="form-control chosen-select-no-single select_chon_lop"><option></option></select>';
        $html += '</div>';            
        $html += '</div>';
        
        $html += '<div class="form-group hidden_class hblock" style="display:none">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Ká»³ ná»™p</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<select name="ptc[blockID][]" multiple onchange="loadClassBlockPrice(this);"  class="form-control chosen-select-no-single select_chon_ky_nop"><option></option></select>';
        $html += '</div>';            
        $html += '</div>';
        }

        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">GiÃ¡ trá»‹</label>';
        $html += '<div class="col-sm-10">';
        //$html += '<input type="text" class="form-control" id="inputLoaithu" aria-describedby="inputLoaithu">';
        $html += '<input name="ptc[sotien]" id="PTC_Sotien" onblur="changeDiscount(this);"  class="form-control inputAmountVND required " />';
 
        $html += '</div>';            
        $html += '</div>';

        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">'+$lydo+'</label>';
        $html += '<div class="col-sm-10">';
        //$html += '<input type="text" class="form-control" id="inputLoaithu" aria-describedby="inputLoaithu">';
        $html += '<textarea id="PTC_Lydo" class="form-control required" name="ptc[lydo]" rows=3></textarea>';
 
        $html += '</div>';            
        $html += '</div>';
        if($content == 'phieuthu'){
        	$html += '<div class="form-group huudai">';
            $html += '<label class="control-label col-sm-2" for="inputLoaithu">Æ¯u Ä‘Ã£i</label>';
            $html += '<div class="col-sm-10">';             
            $html += '<input name="ptc[discount]" id="PTC_Discount" onblur="changeDiscount(this);" class="form-control inputAmountVND" placeholder="Nháº­p sá»‘ tiá»�n < 100 sáº½ tÃ­nh theo %" />';
     
            $html += '</div>';            
            $html += '</div>';
            
            $html += '<div class="form-group huudai">';
            $html += '<label class="control-label col-sm-2" for="inputLoaithu">Ná»™i dung</label>';
            $html += '<div class="col-sm-10">';             
            $html += '<input name="ptc[discountInfo]" id="PTC_Discount_Info"  class="form-control" placeholder="" />';
     
            $html += '</div>';            
            $html += '</div>';
            
            $html += '<div class="form-group huudai">';
            $html += '<label class="control-label col-sm-2" for="inputLoaithu">CÃ²n láº¡i</label>';
            $html += '<div class="col-sm-10">';             
            $html += '<input name="ptc[thucthu]" id="PTC_Thucthu"  class="form-control bold red inputAmountVND" placeholder="Sá»‘ tiá»�n thá»±c thu sau Æ°u Ä‘Ã£i." readonly/>';
     
            $html += '</div>';            
            $html += '</div>';
        }
         
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> LÆ°u</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="submitAction" value="save">';
		//$html += '<input name="ptc[cusID]" type="hidden" class="pt_input pt_x01  fr" style="width:509pt; margin-left:5px" value="">';
		$html += '<input type="hidden" name="congno" value="">';
		$html += '<input type="hidden" name="ptc[quyenso]" value="">';
		$html += '<input type="hidden" name="ptc[book]" value="'+$book+'" >';
		$html += '<input type="hidden" name="loaiphieu" value="'+$content+'">';
		$html += '<input type="hidden" id="PTC_Load_type" name="ptype" value="'+$load_type+'">';
		$html += '<input type="hidden" name="action" value="lapphieu">';
		$html += '<input type="hidden" value="" name="ptc[ghino]" class="input_hidden_ptc_ghino" />';
		$html += '<input type="hidden" value="" name="ptc[ghico]" class="input_hidden_ptc_ghico" />';
		$html += '<input type="hidden" id="PTC_ID" name="ptc[id]" value="0">';
		
		$html += '</form>';
		break;
	case 'add_cus_to_class':
		
		$type  = $this.attr('data-type');
		switch($type){
		case 'TEA':
			$title = 'ThÃªm GiÃ¡o viÃªn';
			break;
		default :
			$title = 'ThÃªm há»�c viÃªn';
			break;
		}
		$classID  = $this.attr('data-classID');
		$html += '<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
 
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Chá»�n há»�c viÃªn</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<div class="input-group"><div class="cs-select-no-border-radius-right"><select multiple name="field[cusID][]"  class="form-control ajax-chosen-select  select_chon_hoc_vien" data-type="'+$type+'" role="load_customer"><option></option></select></div><span class="input-group-addon pointer" data-target=".select_chon_hoc_vien" onclick="them_hoc_vien(this);"><span class="glyphicon glyphicon-plus"></span></span></div>'; 
        $html += '</div>';            
        $html += '</div>';
        
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> LÆ°u</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="field[state]" value="'+($type == 'TEA' ? 1 : -1)+'">';
		 
		$html += '<input type="hidden" name="action" value="add_customer_to_class">';
		$html += '<input type="hidden" name="field[classID]" value="'+$classID+'">'; 
		$html += '';
		
		$html += '</form>';
		break;
	case 'change_customer_to_class':
		$title = 'Chuyá»ƒn lá»›p';
		$type  = $this.attr('data-type');
		$classID  = $this.attr('data-classID');
		$cusID  = $this.attr('data-cusID');
		
		$html += '<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
 
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Chá»�n lá»›p</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<div class="input-group"><div class="cs-select-no-border-radius-right"><select name="field[classID]"  class="form-control ajax-chosen-select  select_chon_lop" data-type="'+$type+'" role="load_class"><option></option></select></div><span class="input-group-addon pointer" data-target=".select_chon_hoc_vien" onclick="them_lop(this);"><span class="glyphicon glyphicon-plus"></span></span></div>'; 
        $html += '</div>';            
        $html += '</div>';
        
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> LÆ°u</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Há»§y</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		//$html += '<input type="hidden" name="submitAction" value="save">';
		 
		$html += '<input type="hidden" name="action" value="change_customer_to_class">';
		$html += '<input type="hidden" name="field[oldClassID]" value="'+$classID+'">';
		$html += '<input type="hidden" name="field[cusID]" value="'+$cusID+'">'; 
		
		$html += '';
		
		$html += '</form>';
		break;
	case 'addNewUser':
		$title = 'Thêm người dùng';
		$type  = $this.attr('data-type');
		$classID  = $this.attr('data-classID');
		$cusID  = $this.attr('data-cusID');
		
		$html += '<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
        $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
        $html += '<section class="boxInfo lbl-cl">';
        $html += '<article class="boxForm uln fll w100 mb10">';
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Email</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<input class="form-control required" name="f[email]"/> '; 
        $html += '</div>';            
        $html += '</div>';
        $html += '<p class="help-block italic col-sm-offset-2">(*) Email sẽ sử dụng làm tài khoản đăng nhập, reset mật khẩu.</p>';
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Tên đăng nhập</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<input class="form-control " name="f[username]"/> '; 
        $html += '</div>';            
        $html += '</div>';
        
        $html += '<div class="form-group">';
        $html += '<label class="control-label col-sm-2" for="inputLoaithu">Số điện thoại</label>';
        $html += '<div class="col-sm-10">';        
        $html += '<input class="form-control" name="f[phone]"/> ';  
        $html += '</div>';            
        $html += '</div>';
        $html += '</article>';
        $html += '</section>';
     
        $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		//$html += '<input type="hidden" name="submitAction" value="save">';
		 
		$html += '<input type="hidden" name="action" value="change_customer_to_class">';
		$html += '<input type="hidden" name="field[oldClassID]" value="'+$classID+'">';
		$html += '<input type="hidden" name="field[cusID]" value="'+$cusID+'">'; 
		
		$html += '';
		
		$html += '</form>';
		break;	
		
		
	default:
		break;
	} 
    jQuery('.mymodal').html($html).modal('show');
    //reloadapp(); 
    reload_app('chosen');
    switch($content){
    case 'quickAddCost':
    	loadCostCategory('#addCostCateID');
    	break;
    case 'select_hotel':
    	loadHotel('#select-chon-khach-san');
    	break;
    case 'select_car':
    	loadCar('#select-chon-xe');
    	break;	
    case 'quickAddCustomer':
    	loadLocaltion('#selectLoadLocation');
    	break;
    }
    //loadLoaiThuChi('.select_chon_loai_thu_chi',$content);
   //alert($cusID);
    //loadNguoiNop('.select_chon_nguoi_nop',$cusID);
    if($this.attr('data-editor')){
    	$t = randomStr(10)
    	$this.attr('data-editor',$t);
    	jQuery('.ckeditorSENDxxx').attr('id',$t);
    	//$('.ckeditor_full').each(function(i,e){
            //$id = $(e).attr('id');
            //$width = parseInt($(e).attr('data-width'));
            //$height = parseInt($(e).attr('data-height'));
            //$expand = $(e).attr('data-expand') ? $(e).attr('data-expand') : true;
            //$expand = $expand == 'false' ? false : true;
            CKEDITOR.replace( $t, {
                 //width:$width, 
                 height:300,
                 toolbar:  'Full',
                 toolbarStartupExpanded : false,
                 filebrowserBrowseUrl : $_config.libsDir + "/editor/ckfinder/ckfinder.html",
                 filebrowserImageBrowseUrl : $_config.libsDir + "/editor/ckfinder/ckfinder.html?type=Images",
 		filebrowserFlashBrowseUrl : $_config.libsDir + "/editor/ckfinder/ckfinder.html?type=Flash",
 		filebrowserUploadUrl : $_config.libsDir + "/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
 		filebrowserImageUploadUrl : $_config.libsDir + "/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
 		filebrowserFlashUploadUrl : $_config.libsDir + "/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
                });
        // });
    }
    jQuery('.first-target').focus();
   
}
///////////////////////////////////////////////////////////////////////////////////////////////
function enabledInput($t){
	var $this = jQuery($t);
	var $target = jQuery($this.attr('data-target'));
	var $reverse = $this.attr('data-function') == 'reverse' ? true : false;
	
	var $val = $this.is(':checked') ? !$reverse : $reverse;
	 
	if($val){
		$target.removeAttr('disabled').addClass('required').focus();
	}else{
		$target.removeClass('required').attr('disabled','');
	}
}
function validate_seo_preview($t){$this=jQuery($t);$min=parseInt($this.attr('data-min'));$max=parseInt($this.attr('data-max'));$role=($this.attr('data-role'));$target=jQuery($this.attr('data-target')).find('.progress-bar');$prev=jQuery('.seo_preview').find('.preview-'+$role);$val=$this.val();$len=$val.length;$du=0;if($len<$min){$cl='progress-bar-warning';$c1='';}else{if($len<$max+1){$cl='progress-bar-success';$c1='';}else{$cl='progress-bar-danger';$c1='danger';$du=$len-$max;}}$w=$len/$max*80;$w=$w>100?100:$w;if($role=='url'){jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/systemAjax',data:{action:'get_item_link',url:$val,},beforeSend:function(){},success:function(data){$prev.html(data);},complete:function(){},error:function(err,req){}});}else{$prev.html($val);$target.html($len+' kÃ½ tá»± '+($du>0?'<i>('+($du*-1)+')</i>':'')).css({"width":$w+"%"}).removeClass('progress-bar-warning progress-bar-success progress-bar-danger').addClass($cl);}}
//BEGIN AJAX UPLOAD
var http_arr = new Array();
function doUpload($o,$temp_file) {
	$o = $o == undefined ? '' : $o;
	$temp_file = $temp_file == undefined ? 'list_images[]' : $temp_file;
	$token = jQuery('input[name=_csrf-frontend]').val();
	//alert($o)
	document.getElementById('progress-group'+$o).innerHTML = ''; //Reset lại Progress-group
	var files = document.getElementById('myfile'+$o).files; 
	for (i=0;i<files.length;i++) {
		uploadFile(files[i], i,$o,$temp_file,$token);
	}
	return false;
}
function uploadFile(file, index,$o,$temp_file,$token) {
	//alert($o)
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khởi tạo vùng tiến trình **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$o);
	var $ffx = document.getElementById('myfile'+$o);
	//alert('progress-group'+$o);
	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
	ProgressGroup.appendChild(Progress);
    //
    var Respon_image_uploaded = document.getElementById('respon_image_uploaded'+$o);

	//Biến hỗ trợ tính toán tốc độ
	var oldLoaded = 0;
	var oldTime = 0;
	//Sự kiện bắt tiến trình
	http.upload.addEventListener('progress', function(event) {	
		if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
			oldTime = event.timeStamp;
		}	
		//Khởi tạo các biến cần thiết
		var fileName = file.name; //Tên file
		var fileLoaded = event.loaded; //Đã load được bao nhiêu
		var fileTotal = event.total; //Tổng cộng dung lượng cần load
		var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; //Tiến trình xử lý
		var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
		//Sử dụng biến
		ProgressBar.innerHTML = fileName + ' đang được upload...';
		ProgressBar.style.width = fileProgress + '%';
		ProgressText.innerHTML = fileProgress + '% Tốc độ: '+speed+'KB/s';
		//Chờ dữ liệu trả về
		if (fileProgress == 100) {
			ProgressBar.style.background = 'url("'+$cfg.absoluteUrl+'/themes/admin/images/progressbar.gif")';
		}
		oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
		oldLoaded = event.loaded; //Set dữ liệu đã nhận được
	}, false);
	

	//Bắt đầu Upload
	var data = new FormData();
	data.append('filename', file.name);
	data.append('myfile', file);
    data.append('action','ajax_upload');
    data.append('_csrf-frontend',$token)
	http.open('POST', $cfg.adminUrl +'/ajax', true);
	http.send(data);
 

	//Nhận dữ liệu trả về
	http.onreadystatechange = function(event) {
		//Kiểm tra điều kiện
		//alert($cfg.adminUrl +'/ajax');
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý		
			try { //Bẫy lỗi JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
                
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //Thêm class Success
					ProgressBar.innerHTML = server.message; //Thông báo	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    var InputRsx = document.createElement('input');
                    InputRsx.value = server.image;
                    InputRsx.className   = 'form-control inputPreview';
                    Respon_image_uploaded.appendChild(InputRs);	
                    Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$o);
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                   
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
					ProgressBar.innerHTML = server.message; //Thông báo
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger				
				ProgressBar.innerHTML = e ; //'Có lỗi xảy ra :('; //Thông báo
			}
		}
		http.removeEventListener('progress',function(x){}); //Bỏ bắt sự kiện
	}
}
function doUploads($t) {
	var $this = jQuery($t);
	$o = $this.attr('data-option') ? $this.attr('data-option') : '';
	$index = $this.attr('data-index') ? parseInt($this.attr('data-index')) : 0;
	$temp_file = $this.attr('data-name') ? $this.attr('data-name') : 'list_images';
	
	document.getElementById('progress-group'+$o).innerHTML = ''; //Reset lại Progress-group
	var files = document.getElementById('myfile'+$o).files; 
	for (i=0;i<files.length;i++) {
		uploadFiles(files[i], i,$o,$temp_file+ '['+(i + $index)+'][image]');
	}
	$this.attr('data-index',files.length + $index) ;
	return false;
}
function uploadFiles(file, index,$o,$temp_file,$token) {
	//alert($o)
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khởi tạo vùng tiến trình **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$o);
	var $ffx = document.getElementById('myfile'+$o);
	//alert('progress-group'+$o);
	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
	ProgressGroup.appendChild(Progress);
    //
    var Respon_image_uploaded = document.getElementById('respon_image_uploaded'+$o);

	//Biến hỗ trợ tính toán tốc độ
	var oldLoaded = 0;
	var oldTime = 0;
	//Sự kiện bắt tiến trình
	http.upload.addEventListener('progress', function(event) {	
		if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
			oldTime = event.timeStamp;
		}	
		//Khởi tạo các biến cần thiết
		var fileName = file.name; //Tên file
		var fileLoaded = event.loaded; //Đã load được bao nhiêu
		var fileTotal = event.total; //Tổng cộng dung lượng cần load
		var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; //Tiến trình xử lý
		var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
		//Sử dụng biến
		ProgressBar.innerHTML = fileName + ' đang được upload...';
		ProgressBar.style.width = fileProgress + '%';
		ProgressText.innerHTML = fileProgress + '% Tốc độ: '+speed+'KB/s';
		//Chờ dữ liệu trả về
		if (fileProgress == 100) {
			ProgressBar.style.background = 'url("'+$cfg.absoluteUrl+'/themes/admin/images/progressbar.gif")';
		}
		oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
		oldLoaded = event.loaded; //Set dữ liệu đã nhận được
	}, false);
	

	//Bắt đầu Upload
	var data = new FormData();
	data.append('filename', file.name);
	data.append('myfile', file);
    data.append('action','ajax_upload');
    data.append('_csrf-frontend',$token)
	http.open('POST', $cfg.adminUrl +'/ajax', true);
	http.send(data);
 

	//Nhận dữ liệu trả về
	http.onreadystatechange = function(event) {
		//Kiểm tra điều kiện
		//alert(http.status);
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý
			try { //Bẫy lỗi JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
               // alert(http.responseText);
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //Thêm class Success
					ProgressBar.innerHTML = server.message; //Thông báo	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    var InputRsx = document.createElement('input');
                    InputRsx.value = server.image;
                    InputRsx.className   = 'form-control inputPreview';
                    Respon_image_uploaded.appendChild(InputRs);	
                    Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$o);
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                   
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
					ProgressBar.innerHTML = server.message; //Thông báo
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
				//alert(e)
				ProgressBar.innerHTML = e ; //'Có lỗi xảy ra :('; //Thông báo
			}
		}
		http.removeEventListener('progress',function(x){}); //Bỏ bắt sự kiện
	}
}



function ajax_upload_files($t) {
	var $this = jQuery($t);
	$o = $this.attr('data-option') ? $this.attr('data-option') : '';
	$token = $this.attr('data-token') ? $this.attr('data-token') : jQuery('input[name=_csrf-frontend]').val();
	$group = $this.attr('data-group') ? $this.attr('data-group') : '';
	$index = $this.attr('data-index') ? parseInt($this.attr('data-index')) : 0;
	$count = $this.attr('data-count') ? parseInt($this.attr('data-count')) : 0;
	$temp_file = $this.attr('data-name') ? $this.attr('data-name') : 'files_attach';
	$multiple = $this.attr('data-multiple') ? $this.attr('data-multiple') : 'true';
	$filetype = $this.attr('data-filetype') ? parseInt($this.attr('data-filetype')) : 'files';
	$input_name = $this.attr('data-input-name') ? ($this.attr('data-input-name')) : 'file';
	document.getElementById('progress-group'+$group).innerHTML = ''; //Reset láº¡i Progress-group
	var files = document.getElementById('myfile'+$group).files; 
	for (i=0;i<files.length;i++) {
		if($multiple == 'false'){
			_ajax_upload_files(files[i], ($count+i+1),$group,$temp_file+ '['+$input_name+']',$filetype,$token);
		}else{			
			_ajax_upload_files(files[i], ($count+i+1),$group,$temp_file+ '['+(i + $count)+']['+$input_name+']',$filetype,$token);
		}
		
	}
	$this.attr('data-count',files.length + $count) ;
	return false;
}
function _ajax_upload_files(file, $count,$group,$temp_file,$filetype,$token) {
	//alert($o)
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khá»Ÿi táº¡o vÃ¹ng tiáº¿n trÃ¬nh **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$group);
	var $ffx = document.getElementById('myfile'+$group);
	//alert('progress-group'+$o);
	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//ThÃªm Div.Progress-bar vÃ  Div.Progress-text vÃ o Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//ThÃªm Div.Progress vÃ  Div.Progress-bar vÃ o Div.Progress-group	
	ProgressGroup.appendChild(Progress);
    //
    var Respon_image_uploaded = document.getElementById('respon_image_uploaded'+$group);

	//Biáº¿n há»— trá»£ tÃ­nh toÃ¡n tá»‘c Ä‘á»™
	var oldLoaded = 0;
	var oldTime = 0;
	//Sá»± kiá»‡n báº¯t tiáº¿n trÃ¬nh
	http.upload.addEventListener('progress', function(event) {	
		if (oldTime == 0) { //Set thá»�i gian trÆ°á»›c Ä‘Ã³ náº¿u nhÆ° báº±ng khÃ´ng.
			oldTime = event.timeStamp;
		}	
		//Khá»Ÿi táº¡o cÃ¡c biáº¿n cáº§n thiáº¿t
		var fileName = file.name; //TÃªn file
		var fileLoaded = event.loaded; //Ä�Ã£ load Ä‘Æ°á»£c bao nhiÃªu
		var fileTotal = event.total; //Tá»•ng cá»™ng dung lÆ°á»£ng cáº§n load
		var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; //Tiáº¿n trÃ¬nh xá»­ lÃ½
		var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
		//Sá»­ dá»¥ng biáº¿n
		ProgressBar.innerHTML = fileName + ' đang được upload...';
		ProgressBar.style.width = fileProgress + '%';
		ProgressText.innerHTML = fileProgress + '% Tốc độ: '+speed+'KB/s';
		//Chá»� dá»¯ liá»‡u tráº£ vá»�
		if (fileProgress == 100) {
			ProgressBar.style.background = 'url("'+$cfg.absoluteUrl+'/themes/admin/images/progressbar.gif")'; 
		}
		oldTime = event.timeStamp; //Set thá»�i gian sau khi thá»±c hiá»‡n xá»­ lÃ½
		oldLoaded = event.loaded; //Set dá»¯ liá»‡u Ä‘Ã£ nháº­n Ä‘Æ°á»£c
	}, false);
	

	//Báº¯t Ä‘áº§u Upload
	var data = new FormData();
	data.append('filename', file.name);
	data.append('myfile', file);
	data.append('filetype', $filetype);
    data.append('action','ajax_uploads');
    data.append('_csrf-frontend',$token)
	http.open('POST', $cfg.adminUrl +'/ajax', true);
	http.send(data);
 

	//Nháº­n dá»¯ liá»‡u tráº£ vá»�
	http.onreadystatechange = function(event) {
		//Kiá»ƒm tra Ä‘iá»�u kiá»‡n
		//console.log(http.status);
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bá»� hÃ¬nh áº£nh xá»­ lÃ½
			//alert(http.responseText);
			try { //Báº«y lá»—i JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
                //alert(http.responseText);
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //ThÃªm class Success
					ProgressBar.innerHTML = server.message; //ThÃ´ng bÃ¡o	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    //var InputRsx = document.createElement('input');
                  //  InputRsx.value = server.image;
                  // / InputRsx.className   = 'form-control inputPreview';
                    Respon_image_uploaded.appendChild(InputRs);	
                   // Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$group);
                    var respon_btable = document.getElementById('respon-btable'+$group);
                    switch ($group) {
                    case '-files-attach-images':
                    	
                    break;
					case '-files-attach':
						if(respon_btable != null){
	                    	$tr = document.createElement("tr");
	                    	
	                    	$tdc = document.createTextNode($count);
	                    	$td = document.createElement("td");
	                    	$td.className = 'center';
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	//////////////////////////
	                    	$td = document.createElement("td");
	                    	$tdc = document.createElement('input');
	                    	$tdc.className = 'form-control  input-sm';
	                    	$tdc.type = 'type';
	                    	$tdc.name = 'biz[files_attach]['+($count-1)+'][title]';
	                    	$tdc.value = file.name;
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	//////////////////////////////////
	                    	$td = document.createElement("td");
	                    	$tdc = document.createElement('input');
	                    	$tdc.className = 'form-control  input-sm';
	                    	$tdc.type = 'type';
	                    	$tdc.name = 'biz[files_attach]['+($count-1)+'][info]';
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	////////////////////////////////// 
	                    	$td = document.createElement("td");
	                    	$tr.appendChild($td);
	                    	//////////// <i onclick="removeTrItem(this);" class="glyphicon glyphicon-trash pointer"></i>     
	                    	$td = document.createElement("td");
	                    	$tdc = document.createElement('i');
	                    	$tdc.className = 'glyphicon glyphicon-trash pointer';
	                    	$tdc.onclick =function(){removeTrItem(this);};
	                    	$td.className = 'center';
	                    	
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	respon_btable.appendChild($tr);
	                    }
						break;

					default:
						
						break;
					}
                    
                     
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                   
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //ThÃªm class Danger
					ProgressBar.innerHTML = server.message; //ThÃ´ng bÃ¡o
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //ThÃªm class Danger
				//alert(e)
				ProgressBar.innerHTML = e ; //'CÃ³ lá»—i xáº£y ra :('; //ThÃ´ng bÃ¡o
			}
		}
		http.removeEventListener('progress',function(x){}); //Bá»� báº¯t sá»± kiá»‡n
	}
}
function cancleUpload() {
	for (i=0;i<http_arr.length;i++) {
		http_arr[i].removeEventListener('progress');
		http_arr[i].abort();
	}
	var ProgressBar = document.getElementsByClassName('progress-bar');
	for (i=0;i<ProgressBar.length;i++) {
		ProgressBar[i].className = 'progress progress-bar progress-bar-danger';
	}	
}
function speedRate(oldTime, newTime, oldLoaded, newLoaded) {
		var timeProcess = newTime - oldTime; //Ä�á»™ trá»… giá»¯a 2 láº§n gá»�i sá»± kiá»‡n
		if (timeProcess != 0) {
			var currentLoadedPerMilisecond = (newLoaded - oldLoaded)/timeProcess; // Sá»‘ byte chuyá»ƒn Ä‘Æ°á»£c 1 Mili giÃ¢y
			return parseInt((currentLoadedPerMilisecond * 1000)/1024); //Tráº£ vá»� giÃ¡ trá»‹ tá»‘c Ä‘á»™ KB/s
		} else {
			return parseInt(newLoaded/1024); //Tráº£ vá»� giÃ¡ trá»‹ tá»‘c Ä‘á»™ KB/s
		}
}
// END AJAX UPLOAD////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Ad_change_select_active($t){
	var $this = jQuery($t);
	$pr = $this.parent().parent().parent();
	switch($this.val()){
		case '-1':			
			$pr.find('input.to-date').removeAttr('disabled').val('');
			$pr.find('input.from-date').removeAttr('disabled').val('').focus();
			return false;
			break;
		default:
			
			$pr.find('input.to-date').attr('disabled','').val('');
			$pr.find('input.from-date').attr('disabled','').val('');
			break;
	}
}
function checkCustomerCode(t){
	var $this = jQuery(t);
	var $field = $this.attr('data-field') ? $this.attr('data-field') : 'code';
	var $p = $this.attr('data-parent') ?  parseInt($this.attr('data-parent')) : 1;
	var $parent = $p == 2 ? $this.parent().parent() : $this.parent();
	var $er = $parent.find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'checkCustomercode',val:$this.val(),id:$id,'field':$field},
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  //alert(data)
	    	  $d = JSON.parse(data);
	    	  
	    	  //$this.attr('data-old',$new);
	    	  // alert(data)
	    	  //$code.val(data)
	           if($d.state == false){
	        	   $this.removeClass('error');jQuery('.submitFormBtn').removeAttr('disabled');
	        	   $er.addClass('success').html('Bạn có thể sử dụng giá trị này.');
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          }else{
	        	  jQuery('.submitFormBtn').attr('disabled','disabled');
	        	  $this.addClass('error');
	        	  $add = '<p class="red"><b>'+$this.val()+'</b> không hợp lệ hoặc đã được sử dụng.</p>';
	        	  $add += '<p>'+$d.data['name']+'</p>';
	        	  $add += '<p>Địa chỉ: '+$d.data['address']+'</p>';
	        	  $add += '<p>Điện thoại: '+$d.data['phone']+'</p>';
	        	  $er.removeClass('success').html($add);
	          }
	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  $parent.append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function add_more_contacts_list($t){
	var $this = jQuery($t);
	$count = parseInt($this.attr('data-count'));
	$html = '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
	$html += '<label  class="control-label bold">Họ tên</label>';
	$html += '<input type="text" name="biz[contacts]['+$count+'][name]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Số điện thoại</label>';
	$html += '<input type="text" name="biz[contacts]['+$count+'][phone]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Email</label>';
	$html += '<input type="email" name="biz[contacts]['+$count+'][email]" class="form-control" placeholder="" value="" />';
	
	$html += '<label  class="control-label bold"><input type="radio" data-role="slx-radio" onchange="setRadioBool(this);" '+($count==0 ? 'checked' : '')+' name="biz[contacts]['+$count+'][is_default]" class="slx-radio" placeholder="" value="'+($count==0 ? 1 : 0)+'" /> Đặt làm liên hệ chính</label>';
	$html += '';
	
	$html += '</div>';
	$this.attr('data-count',$count+1);
	jQuery('.contacts_list').prepend($html);
}
function add_more_bank_list($t){
	var $this = jQuery($t);
	$count = parseInt($this.attr('data-count'));
	$html = '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
	$html += '<label  class="control-label bold">Số tài khoản</label>';
	$html += '<input type="text" name="biz[bank]['+$count+'][account]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Tên ngân hàng</label>';
	$html += '<input type="text" name="biz[bank]['+$count+'][name]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Chi nhánh</label>';
	$html += '<input type="email" name="biz[bank]['+$count+'][branch]" class="form-control" placeholder="" value="" />';
	$html += '</div>';
	$this.attr('data-count',$count+1);
	jQuery($this.attr('data-target')).prepend($html);
}
function add_more_fileds_list($t){
	var $this = jQuery($t);
	$count = parseInt($this.attr('data-count'));
	$html = '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
	$html += '<label  class="control-label bold">Tiêu đề</label>';
	$html += '<input type="text" name="biz[fileds]['+$count+'][name]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Nội dung</label>';
	$html += '<input type="text" name="biz[fileds]['+$count+'][text]" class="form-control" placeholder="" value="" />';
	//$html += '<label  class="control-label bold">Email</label>';
	//$html += '<input type="email" name="biz[contacts]['+$count+'][email]" class="form-control" placeholder="" value="" />';
	$html += '</div>';
	$this.attr('data-count',$count+1);
	jQuery('.customers_fileds_list').prepend($html);
}
function checkAllItemTree($t){
	$j = jQuery;
	var $this = $j($t);
	$id = $this.attr('data-id');
	var $role = $this.attr('data-role');
	$parent_id = $this.attr('data-parent');
	$ck = $this.is(':checked');
	$j('.'+$role+$id).each(function(i,e){
		$j(e).prop('checked', $ck).change();
	});
}
function add_ctab_tab($t){ 
	 var $this = jQuery($t);
	 $c_type = $this.attr('data-c_type') ? true : false;
    var $role = parseInt($this.attr('data-count'));
    $id = parseInt($this.attr('data-id'));
    $tab = 'edetail-tab-'+$role;
     
    $this.parent().before('<li class="pr" role="presentation"><a href="#" class="delTab" onclick="delTab(\'#'+$tab+'\');">x</a><a href="#'+$tab+'"  role="tab" data-toggle="tab">Tab '+($role + 1)+'</a></li>');
    $html = '<div role="tabpanel" class="tab-pane" id="edetail-tab-'+$role+'"><div class="p-content"><div class="row">';
    $html += '<div class="col-sm-6 "><div class="form-group"><label class="col-sm-2 control-label">Tiêu đề</label><div class="col-sm-10">';
    $html += '<input type="text" name="content[ctab]['+$role+'][title]" class="form-control required" placeholder="Title" value="" />';
    $html += '</div></div></div><div class="col-sm-6 "><div class="form-group"><label class="col-sm-2 control-label">Style</label><div class="col-sm-10 group-sm34"><select name="content[ctab]['+$role+'][style]" class="form-control select2 ajax-select2-hide-seearch ">';    
    $html += '<option value="0">--</option>'; 

for($i = 1;$i<11;$i++){
	$html += '<option value="'+$i+'">Style '+$i+'</option>';
}

	$html += '</select></div></div></div><div class="col-sm-12"><div class="form-group"><div class="col-sm-12 col8respon">';
	$html += '<textarea class="ckeditor_full form-control" id="xckc_'+$tab+'" data-height="350" name="content[ctab]['+$role+'][text]" ></textarea>';
      
   $html += '</div></div></div></div></div></div>';
    jQuery('#append-tabs').append($html);
	        jQuery('a[href="#'+$tab+'"]').tab('show'); 
	    	 $this.attr('data-count',$role+1);
	        editor = CKEDITOR.replace('xckc_'+$tab,{
	          height:400,
	          filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
	          filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
	          filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
	          filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	          filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	          filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

	        });
	       
    return false;
}
function delTab(f){
  if(confirm('Xác nhận.')){
    $('a[href="'+f+'"]').parent().remove();
    $(f).remove();
  }
}
function selectDeparturePlace(t){$this=jQuery(t);$new=$this.val();jQuery.ajax({type:'post',datatype:'json',url:$cfg.cBaseUrl+'/ajax',data:{action:'selectDeparturePlace',val:$new},beforeSend:function(){},success:function(data){$d=JSON.parse(data);jQuery('#dropPositionEnd').html($d.d);jQuery('#positionStart').html($d.s);},complete:function(){},error:function(err,req){}});}
function autoSetNight($t){
	var $this = jQuery($t);
	var $target = jQuery($this.attr('data-target'));
	$v1 = parseInt($this.val());
	$v2 = ($target.val());
	//alert($v1 + ' - ' + $v2)
	if($v1 > 0 && ($v2 < 1 || $v2 == "" || $v2 == 'NaN')){
		$target.val($v1-1);
	}
}
function addNewTab($t){
    var $this = jQuery($t);
    
    var  $c_type = $this.attr('data-c_type') ? true : false;
    var $role = parseInt($this.attr('data-role'));
    var $id = parseInt($this.attr('data-id'));
    var $tab = 'edetail-tab-'+$role;
    $this.parent().before('<li class="pr" role="presentation"><a href="#" class="delTab" onclick="delTab(\'#'+$tab+'\');">x</a><a href="#'+$tab+'"  role="tab" data-toggle="tab">Tab '+($role + 1)+'</a><input type="hidden" name="tab_position[]" value="'+$role+'"/></li>');
    jQuery.ajax({
 	      type: 'post',
 	      datatype: 'json',
 		  url: $cfg.adminUrl  + '/ajax',						 		 
 	      data: {action:'addNewTab',role:$role ,id:$id ,c_type:$c_type,tab:$tab},
 	      beforeSend:function(){
 	      },
 	      success: function (data) { 	    	
 	    	  var $html = data;  
 	        jQuery('#append-etabs').append($html);
 	        jQuery('a[href="#'+$tab+'"]').tab('show'); 
 	    	 $this.attr('data-role',$role+1);
 	        editor = CKEDITOR.replace('xckc_'+$tab,{
 	          height:200,
 	          filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
 	          filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
 	          filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
 	          filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 	          filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 	          filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

 	        });
 	      },
 	      complete:function(){
 	    	  //hideLoading();  
 	    	  //$this.parent().append($er);
 	      },
 	      error : function(err, req) {
 	           
 				}
 	});
       
       
       
       
       
       //jQuery('#inputTitleTab'+$role).select();

       return false;
}
function changeFilterCode(t){
	var $this = jQuery(t);
	//$er = $this.parent().find('.error_field');
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	///$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'changeFilterCode',val:$this.val()  },
	      beforeSend:function(){
	    	 // showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	  // 	alert(data)
	    	  $d = JSON.parse(data);
	    	  $rs = jQuery('.rs_code_filter');
	    	  if($d.state){
	    		  $rs.val($d.code);
	    	  }else{
	    		  if($rs.val() == ""){
	    			  $rs.val($d.code);
	    		  }
	    	  }
	    	  
	    	  //hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function changeFormLessionType($t){
	var $this = jQuery($t);
    var $type = $this.val();
    var $role = $this.attr('data-role');
	$class = $this.val() + '_only';
    $html = '';
    switch($type){
        case 'video':
        $html += '<div class="f_form_video f_form_change '+$type+'_only"><div class="browser_images">';		   
		$html += '<div class="form-group col-sm-3"><input type="file" class="form-control input-sm" name="myfile_'+$role+'_video" id="myfile_'+$role+'_video" accept="video/*"/></div>';			
		$html += '<button type="button" class="btn btn-default btn-sm btn-sd" onclick="return doUpload(\'_'+$role+'_video\',\'tab_biz['+$role+'][video]\');" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';  	  
	    $html += '<div class="col-sm-12"><div class="row"><div class="col-sm-6">';
        $html += '<div id="progress-group_'+$role+'_video" class="row" ><p style="font: italic 1.2em arial; margin-bottom: 20px;">*** Chấp nhận định dạng mp4, avi, mpeg</p>';
	    $html += '<input type="text" id="removeAfterUpload_'+$role+'_video" class="form-control inputPreview removeAfterUpload" name="tab_biz['+$role+'][video]" value=""/>';
        $html += '</div></div>';
        $html += '<div class="" id="respon_image_uploaded_'+$role+'_video"></div></div></div></div>';
     
         $html += '<div class="f_form_srt f_form_change '+$type+'_only"><div class="browser_images">';		   
		$html += '<div class="form-group col-sm-3"><input type="file" class="form-control input-sm" name="myfile_'+$role+'_srt" id="myfile_'+$role+'_srt" accept="*"/></div>';			
		$html += '<button type="button" class="btn btn-default btn-sm btn-sd" onclick="return doUpload(\'_'+$role+'_srt\',\'tab_biz['+$role+'][srt]\');" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';  	  
	    $html += '<div class="col-sm-12"><div class="row"><div class="col-sm-6">';
        $html += '<div id="progress-group_'+$role+'_srt" class="row" ><p style="font: italic 1.2em arial; margin-bottom: 20px;">*** Chấp nhận định dạng srt</p>';
	    $html += '<input type="text" id="removeAfterUpload_'+$role+'_srt" class="form-control inputPreview removeAfterUpload" name="tab_biz['+$role+'][srt]" value=""/>';
        $html += '</div></div>';
        $html += '<div class="" id="respon_image_uploaded_'+$role+'_srt"></div></div></div></div>';
        
        $html += '<div class="f_form_change '+$type+'_only"><div class="timeloc"><table class="table table-bordered table-hover"> <thead> <tr> <th width="50" class="center">STT</th><th>Hội thoại</th><th>Nghĩa</th><th>Thời gian hiển thị (giây)</th><th></th> </tr> </thead> <tbody class="tbl_conversation_'+$role+'">'; 
        $kx = -1;
     
		$html += '</tbody></table><table><tr class="vmiddle"><th scope="row" class="center"></th><td></td><td></td>';
		$html += '<td class="aright"><button data-role="'+$role+'" data-index="'+($kx)+'" onclick="addConversation(this);" type="button" class="btn btn-success input-sm"><i class="glyphicon glyphicon-plus"></i> Thêm hội thoại</button></td></tr></table></div></div>';
        
        jQuery('.ajax_result_form_change'+$role).html($html);
        break;
        
        default :
        $tab = "editor-detail-tab"+$role;
        //alert($tab)
        $html += '<div class="f_form_video f_form_change '+$type+'_only"><div class="browser_images">';		 
        $html += '<textarea data-id="0" name="tab_biz['+$role+'][text]" class="form-control" id="xckc_'+$tab+'"  ></textarea> ';
        
        $html += '</div></div>';
        jQuery('.ajax_result_form_change'+$role).html($html);	
        
        editor = CKEDITOR.replace('xckc_'+$tab,{
         height:400,
         filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
         filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
         filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
         filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
         filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
         filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

       });	 
        break;
    }
	//jQuery('.f_form_change').hide();
    
	////jQuery('.'+$class).show();
}
function addConversation($t){
	var $this = jQuery($t);
	$index = parseInt($this.attr('data-index'))+1;
    var $role = parseInt($this.attr('data-role'));
	$this.attr('data-index',$index);
	$html = '<tr class="vmiddle"><th scope="row" class="center">'+($index+1)+'</th>';
	$html += '<td><input class="form-control input-sm" name="tab_biz['+$role+'][text_trans]['+$index+'][text]" value=""/></td>';
	$html += '<td><input class="form-control input-sm" name="tab_biz['+$role+'][text_trans]['+$index+'][mean]" value=""/></td>';	 
	$html += '<td><input data-decimal="2" class="form-control numberFormat input-sm center" name="tab_biz['+$role+'][text_trans]['+$index+'][time]" value=""/></td>';
	$html += '<td><input data-decimal="2" class="form-control numberFormat input-sm center" name="tab_biz['+$role+'][text_trans]['+$index+'][end]" value=""/></td>';
	$html += '<td class="center"><i onclick="removeItemTR(this);" class=" pointer glyphicon glyphicon-trash"></i></td></tr>';	 
	 
	jQuery('.tbl_conversation_'+$role).append($html);
    reload_app('number-format');
	 
}
function changeDropdownCurrency($t){
	var $this = jQuery($t);
	$id = $this.attr('data-id');
	$symbol = $this.attr('data-symbol');
	$pr = $this.parent().parent().parent();
	$pr.find('.input-currency-symbol').html($symbol)
	$pr.find('.input-currency').val($id)
	$pr.parent().parent().find('.input-price-decimal-field').attr('data-decimal',$this.attr('data-decimal')).number(true,$this.attr('data-decimal'));
}
function change_adv_html_type($t){
	var $this = jQuery($t);
	var $val = $this.val();
	var $target = jQuery('.adv_type_'+$val);
	$x = jQuery('.adv_type');
	$x.hide();
	$target.show();
}
function turnoff_editor($t){
	var $this = jQuery($t);
	  //view_obj(CKEDITOR.instances.ckeditor_text_detail );
	var $target = $this.attr('data-target');
	if (!$this.is(':checked') ){		 
		var config = {};
		var $id =  '#'+jQuery('.ajax_ckeditor').attr('id');
		jQuery($id).addClass('ckeditor_full');
		editor = create_ckeditor($id);
	}else {
		
	
	

	// Retrieve the editor contents. In an Ajax application, this data would be
	// sent to the server or used in any other way.
	//document.getElementById($target).innerHTML =  editor.getData();
	//document.getElementById( 'contents' ).style.display = '';

	// Destroy the editor.
	CKEDITOR.instances.ckeditor_text_detail.destroy();
	//CKEDITOR.instances.ckeditor_text_detail = null;
	}
	 
	 
}
function turnon_editor($t){
	var $this = jQuery($t);
	  //view_obj(CKEDITOR.instances.ckeditor_text_detail );
	var $target = $this.attr('data-target');
	var $id =  jQuery($target).attr('id');
	if ($this.is(':checked') ){		 
		var config = {};
		
		//jQuery($id).addClass('ckeditor_basic');
		//editor = create_ckeditor($id,'basic');
		
		CKEDITOR.replace( $id, {
            height:100, 
            toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
             ] 
             });
	}else {
		
	
	

	// Retrieve the editor contents. In an Ajax application, this data would be
	// sent to the server or used in any other way.
	//document.getElementById($target).innerHTML =  editor.getData();
	//document.getElementById( 'contents' ).style.display = '';

	// Destroy the editor.
		switch ($id) {
		case 'ckeditor_text_detail_summary':
			CKEDITOR.instances.ckeditor_text_detail_summary.destroy();
			break;
		case 'ckeditor_text_detail_info':
			CKEDITOR.instances.ckeditor_text_detail_info.destroy();
			break;
		default:
			break;
		}
	
	//CKEDITOR.instances.ckeditor_text_detail = null;
	}
	 
	 
}
function create_ckeditor($t,$toolbar){
	var $this = jQuery($t);
	$toolbar = $toolbar ? $toolbar :"Full";
    var $id = $this.attr('id');
   // alert($id)
    $width = parseInt($this.attr('data-width'));
    $height = parseInt($this.attr('data-height'));
    $expand = $this.attr('data-expand') ? $this.attr('data-expand') : true;
    $expand = $expand == 'false' ? false : true;
    editor  = CKEDITOR.replace( $id, {
         width:$width, height:$height,
         toolbar:  $toolbar,
         toolbarStartupExpanded : $expand,
         filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
         filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
         filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
         filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
         filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
         filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
        });
    return editor;
 };

 function parseSeoUrl($t){
		$j = jQuery;
		var $this = $j($t); var $val = $this.val();
		jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.cBaseUrl  + '/ajax',						 		 
		      data: {action:'parseSeoUrl',val:$val},
		      beforeSend:function(){
		    	 // showLoading();
		    	  //showFullLoading();
		      },
		      success: function (data) {
		    	  $d = JSON.parse(data);
		    	  if($d.state == false){
		    		  jQuery('.btn-submit').attr('disabled','');
		    		  $j('#error-respon').html('<i class="glyphicon glyphicon-remove"></i> Đường dẫn không hợp lệ.').removeClass('bg-success').addClass('bg-danger').show();
		    	  }else{	    		  
		    		  $j('#error-respon').html('<i class="glyphicon glyphicon-ok"></i> Bạn có thể sử dụng url này.').removeClass('bg-danger').addClass('bg-success').show();
		    		  jQuery('#inputSeoTitle').val($d.item['seo_title']);
		    		  jQuery('#inputSeoKeyword').val($d.item['seo_keyword']);
		    		  jQuery('#inputDescription').val($d.item['seo_description']);
		    		  jQuery('.btn-submit').removeAttr('disabled');
		    		  jQuery('#seo_table').val($d.item['item_type']);
		    		  jQuery('#seo_id').val($d.item['id']);
		    	  }
		    	  
		    	   
		    	  
		      },
		      complete:function(){
		    	 // hideLoading();  
		      },
		      error : function(err, req) {
		           
					}
		});
}
function set_default_ftp_server($t){
		var $this = jQuery($t);
		$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
		$sid = $this.attr('data-sid') ? $this.attr('data-sid') : 0;
		$ck = $this.is(':checked');
		jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.adminUrl  + '/ajax',						 		 
		      data: {action:'set_default_ftp_server',sid:$sid ,id:$id,val:$ck ? 1 : 0},
		      beforeSend:function(){
		    	  showLoading();
		    	 // showFullLoading();
		      },
		      success: function (data) {
		    	  // alert(data)
		         
		      },
		      complete:function(){
		    	  hideLoading();  
		    	  //$this.parent().append($er);
		      },
		      error : function(err, req) {
		           
					}
		});
}
function add_more_currency($t){
	var $this = jQuery($t); 
	$title =  'Chọn thêm tiền tệ' ;
	$html = '';
	$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
	$html += '<div class="modal-dialog" role="document">';
	$html += '<div class="modal-content">';
	$html += '<div class="modal-header">';
	$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
	$html += '</div>';
	$html += '<div class="modal-body">';
    $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
    $html += '<section class="boxInfo lbl-cl">';
    $html += '<article class="boxForm uln fll w100 mb10">';
  
    $html += '<div class="form-group">';    
    $html += '<div class="col-sm-12">';        
    $html += '<div data-existed="'+($this.attr('data-existed'))+'" data-index="'+($this.attr('data-count'))+'" id="bang_list_ket_qua"></div><div class="show_error_out"></div>';
    $html += '</div> ';            
    $html += '</div>';
            
    $html += '</article>';
    $html += '</section>';
 
    $html += '</section>';
	$html += '</div>';
	$html += '<div class="modal-footer">';		
	$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
	$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
	$html += '</div>';
	$html += '</div>';
	$html += '</div>';
	$html += '<input type="hidden" name="index" value="'+($this.attr('data-count'))+'">';
	$html += '<input type="hidden" name="existed_id" value="'+($this.attr('data-existed'))+'">';
	//$html += '<input type="hidden" name="type_id" value="'+($this.attr('data-type_id'))+'">';
    //$html += '<input type="hidden" name="f[quot]" value="'+$quot+'">'; 
   // $html += '<input type="hidden" name="f[qtype]" value="'+$radio_quotation_type+'">';
	$html += '<input type="hidden" name="action" value="set_quantity_currency">';
	$html += '</form>';
	jQuery('.mymodal').html($html).modal('show');
	
	load_all_currency('#bang_list_ket_qua');
	 
}
 
function load_all_currency($target){	 
	var $target= jQuery($target);
	var $this = jQuery($target);
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'load_all_currency',index:$this.attr('data-index'),existed:$this.attr('data-existed')},
	      beforeSend:function(){
	    	  //showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	  $target.html(data);
	    	   
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
} 

function add_more_holidays_categorys($t){
	var $this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	var $type = parseInt($this.attr('data-type')); 
	var $target = $this.parent().parent();
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="fx['+$c+'][title]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="fx['+$c+'][code]" value="" class="form-control input-sm"/></td>';  
	 
	$html += $type == 2 ? '<td class="center"><select class="form-control input-sm" name="fx['+$c+'][type_id]"><option value="0">Khác</option><option value="2" selected="">Mùa dịch vụ</option><option value="3">Nhóm cuối tuần</option><option value="4">Nhóm ngày trong tuần</option></select></td>' : ''; 
	//$html += '<td><input type="text" name="fx['+$c+'][incurred]" value="" class="form-control aright bold red input-sm ajax-number-format" data-decimal="2"/></td>';
	$html += '<td></td>'; 
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
	reload_app('number-format'); 
}
function add_more_holidays($t){
	var $this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	var $target = $this.parent().parent();
	var $sl = jQuery('#ajax_select_htype');
	$sl.find('select').attr('name','f['+$c+'][parent_id]');
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="f['+$c+'][from_date]" value="" class="form-control input-sm ajax_datepicker"/></td>'; 
	$html += '<td><input type="text" name="f['+$c+'][to_date]" value="" class="form-control input-sm ajax_datepicker"/></td>'; 
	$html += '<td><input type="text" name="f['+$c+'][title]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td>'+($sl.html())+'</td>';  
	 
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
	jQuery('.ajax_datepicker').datetimepicker({
	      //language:'vi',
	    	 format:'d/m/Y',
	      //pickTime:false,
	      //dateFormat:'d/m/Y'
	     });
}

function add_more_weekend($t){
	var $this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	var $target = $this.parent().parent();
	var $sl = jQuery('#ajax_select_htype_3');
	$sl.find('select').attr('name','f1['+$c+'][parent_id]');
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><select name="f1['+$c+'][from_date]" class="form-control input-sm"><option value="0">Chủ nhật</option><option value="1">Thứ 2</option><option value="2">Thứ 3</option><option value="3">Thứ 4</option><option value="4">Thứ 5</option><option value="5">Thứ 6</option><option value="6">Thứ 7</option></select></td>';
	$html += '<td><input type="text" name="f1['+$c+'][from_time]" value="00:00:00" class="form-control input-sm ajax-timepicker"/></td>';
	$html += '<td><select name="f1['+$c+'][to_date]" class="form-control input-sm"><option value="0">Chủ nhật</option><option value="1">Thứ 2</option><option value="2">Thứ 3</option><option value="3">Thứ 4</option><option value="4">Thứ 5</option><option value="5">Thứ 6</option><option value="6">Thứ 7</option></select></td>';
	$html += '<td><input type="text" name="f1['+$c+'][to_time]" value="00:00:00" class="form-control input-sm ajax-timepicker"/></td>';   
	 
	$html += '<td><input type="text" name="f1['+$c+'][title]" value="" class="form-control input-sm"/></td>';
	$html += '<td>'+($sl.html())+'</td>'; 
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
	reload_app('timepicker')
}

function show_help($t){
	 var $this = jQuery($t);
	 $id = $this.attr('data-id');$rid = $this.attr('data-rid');
	 jQuery.post($cfg.cBaseUrl +'/ajax/helps',{action:'get_item',id:$id,rid:$rid},function(r){
			
			// Setting a timeout for the next request,
			// depending on the chat activity:
			//alert(r)
			 
			// 2 seconds
			//if(chat.data.noActivity > 3){
			//	nextRequest = 2000;
			//}
			
			//if(chat.data.noActivity > 10){
			//	nextRequest = 5000;
			//}
			
			// 15 seconds
			//if(chat.data.noActivity > 20){
			//	nextRequest = 15000;
			//}
		
			 
		},'json').done(function (d) {
			$this.parent().parent().html(d.text); 
			window.history.pushState({"html":d.text,"pageTitle":d.title},"", d.link);
			// jstree_right_panel js-helps-panel
			jQuery('.jstree_right_panel').removeClass('js-helps-panel');
		});
	 return false;
}

function open_ajax_modal($t){
	 
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 $m = $this.attr('data-modal-target') ? $this.attr('data-modal-target') : '.mymodal';
	 if(jQuery($m).length == 0) jQuery('body').append('<div class="modal '+($m.replace('.',''))+'"></div>'); 
	 $modal = jQuery($m);
	 $state = true;
	 $check_required_save = $this.attr('data-required-save') ? true : false;
	 if($check_required_save && jQuery('.field-required-save').length>0){
		 $state = confirm('Dữ liệu chưa đc lưu !!! Bạn có muốn tiếp tục ?');
	 }
	 if(!$this.attr('data-title')){
		 $data['title'] = $this.attr('title');
	 }
	 
	 if($state){
		 $this.removeAttr('data-required-save');
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  $html = '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
	    	  $html += '<div class="modal-dialog '+$this.attr('data-class')+'" role="document">';
	    	  $html += '<div class="modal-content">';
	    	  $html += '<div class="modal-header">';
	    	  $html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	    	  $html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$data.title+'</h4>';
	    	  $html += '</div>';
	    	  $html += '<div class="modal-body ajax-modal-body">';
	    	  $html += '<p class="ajax-loading-data">Đang tải dữ liệu.</p>';
	    	  $html += '</div></div></div></form>';
	    	  $modal.html($html).modal({'show':true,backdrop: 'static', keyboard: true});
	      },
	      success: function (data) {
//	    	  console.log(data)
	    	  $d = JSON.parse(data);
	    	  //$modal
	    	  $modal.find('.ajax-modal-body').html($d.html);
	    	  $modal.draggable({
	    		    handle: ".modal-header"
	    		});
	    	  if($d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	reload_app('chosen');  reload_app('select2');
	    	reload_app('number-format'); 
	    	reload_app('switch-btn');reload_app('datepicker');
	    	loadTagsInput();
	    	loadTagsInput1();
	    	loadAutocomplete();
	    	jQuery('.tooltip').remove();
	      },
	      error : function(err, req) {}
	 });
	 }
	 return false;
}
function quick_find_room_category($t){
	 
	 var $this = jQuery($t);
	 //console.log($this.val())
	 //$this.keyup(function(e){
		// if(e.which == 13) return false;
		 jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.adminUrl  + '/ajax',						 		 
		      data: {action:'quick_find_room_category',val:$this.val()+''},
		      beforeSend:function(){
		    	  //showLoading();
		      },
		      success: function (data) {
		    	  $d = JSON.parse(data);
		    	  $p = $this.parent().parent().parent();
		    	  $p.find('.tr_item').hide();
		    	  jQuery.each($d.list,function(i,e){
		    		  $p.find('.tr_item_'+e).show();
		    	  });
		    	  //console.log(data)
		    	  //hideLoading()
		    	  //removeTrItem($t);
		      },
		      complete: function(){
		    	//reload_app('chosen');  reload_app('select2'); reload_app('number-format');
		    	//reload_app('switch-btn');reload_app('datepicker');
		      },
		      error : function(err, req) {}
		    });
	// }) ; 
	 
		
	 return false;
	  
}
function setDefaultCurrency($t){		 
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'setDefaultCurrency',id:jQuery($t).val()},
	      beforeSend:function(){
 
	      },
	      success: function (data) {	   
	    	  $d = JSON.parse(data);
	    	  jQuery('.input-currency-id').val($d.id);
	    	  jQuery('.input-currency-display').val($d.display);
	    	  jQuery('.input-currency-display-type').val($d.display_type);
	    	  jQuery('.input-currency-decimal-number').val($d.decimal_number);
	      },
	      error : function(err, req) { 
	           
				}
	    });
}
function get_decimal_number($t){
	var $this = jQuery($t);
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'get_decimal_number',id:jQuery($t).val()},
	      beforeSend:function(){
 
	      },
	      success: function (data) {	    	  
	    	  $this.attr('data-decimal',data);
	    	  if($this.attr('data-target')){
	    		jQuery($this.attr('data-target')).val(data) 
	    	  }
	    	  if(jQuery($t).attr('data-target-input')){
	    		  	var $x = jQuery(jQuery($t).attr('data-target-input')); 	    	
	    			$x.attr('data-decimal',data)
	    			.attr('data-currency',jQuery($t).val()).number(true,data);
	    			if($this.attr('data-action-change-after-save')){
	    				$x.blur();
	    			}
	    	  }
	    	  
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
}

function change_season_price_type($t){
	 var $this = jQuery($t); 
	 var $pr = $this.parent().parent().parent().parent().parent();
	 var $target = $pr.find('.input-incurred-season-price');
	 var $target1 = $pr.find('.input-incurred-season-parent-id');
	 var $target2 = $pr.find('.input-incurred-season-currency');
	 var $target3 = $pr.find('.input-change-location-append').parent().parent();
	 var $target5 = jQuery($this.attr('data-tab-target'));
	 var $i3 = $pr.find('.input-sub_id-change-price');
	 $target5.attr('data-tab',$this.attr('data-tab'));
	 switch(parseInt($this.val())){
	 case -1:
		 $target.val('').hide();
		 $target3.hide();
		 //$target4.hide();
		 break;
	 case 1:
		 $target1.val(0).show();
		 $target.val('').show().focus();
		 $target2.hide();
		 $i3.parent().parent().parent().show();
		 $target3.show();
		 //$target4.show(); 
		 break;
		 
	 case 2:
		 $target2.show();
		 $target1.val(0).hide();
		 $target.val('').show().focus();
		 $i3.parent().parent().parent().show();
		 $target3.show();
		// $target4.hide(); 
		 break;
	 default:
		 $target2.hide();
		 $target.val('').hide();
		 $target1.val(0).hide();
		 $i3.val(0);
		 load_select2(true);
		 $i3.parent().parent().parent().hide();
		 $target3.show();
		// $target4.hide();
		 break;
	 }
	 //
	 var $data = getAttributes($this);
	 $data['action'] = 'change_season_price_type';
	 $data['value'] = $this.val();
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  
	      },
	      success: function (data) {
	    	  //console.log(data)
	    	  $d = JSON.parse(data);
	    	   
	      },
	      complete: function(){
	    	//reload_app('chosen');  reload_app('select2'); reload_app('number-format');
	    	//reload_app('switch-btn');reload_app('datepicker');
	      },
	      error : function(err, req) {}
	 });
	 
	 //
	 reloadAutoPlayFunction();
 }
 function get_local_not_in_group($t){
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 if(!$this.is(':checked')){
		 $this.parent().parent().find('select').html('').trigger('chosen:updated')
	 }else{
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  
	      },
	      success: function (data) {
	    	  //console.log(data)
	    	  $d = JSON.parse(data);
	    	  $this.parent().parent().find('select').append($d.html).trigger('chosen:updated')
	      },
	      complete: function(){
	    	//reload_app('chosen');  reload_app('select2'); reload_app('number-format');
	    	//reload_app('switch-btn');reload_app('datepicker');
	      },
	      error : function(err, req) {}
	    });
	 }
 }
 function quick_delete_nationality_group_supplier($t){
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 if(confirm('Xác nhận.')){
		 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  showLoading();
	      },
	      success: function (data) {
	    	 // console.log(data)
	    	  hideLoading()
	    	  removeTrItem($t);
	      },
	      complete: function(){
	    	//reload_app('chosen');  reload_app('select2'); reload_app('number-format');
	    	//reload_app('switch-btn');reload_app('datepicker');
	      },
	      error : function(err, req) {}
	    });
	 }
 }
 function quick_delete_package_supplier($t){
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 if(confirm('Xác nhận.')){
		 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  showLoading();
	      },
	      success: function (data) {
	    	  //console.log(data)
	    	  hideLoading()
	    	  removeTrItem($t);
	      },
	      complete: function(){
	    	//reload_app('chosen');  reload_app('select2'); reload_app('number-format');
	    	//reload_app('switch-btn');reload_app('datepicker');
	      },
	      error : function(err, req) {}
	    });
	 }
 }
 function add_more_vehicles_categorys($t){
		var $this = jQuery($t); 
		$title =  'Chọn phương tiện' ;
		$html = '';
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
	    $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
	    $html += '<section class="boxInfo lbl-cl">';
	    $html += '<article class="boxForm uln fll w100 mb10">';

	   

	    $html += '<div class="form-group">';
	    //$html += '<label class="control-label col-sm-2" for="inputLoaithu">Kỳ nộp</label>';
	    $html += '<div class="col-sm-12">';        
	    $html += '<select id="select-chon-xe" data-type="'+($this.attr('data-type_id'))+'" data-type_id="'+($this.attr('data-type_id'))+'" data-existed="'+($this.attr('data-existed'))+'" data-index="'+($this.attr('data-count'))+'" data-target="#bang_list_chon_xe" onchange="get_list_vehicles_makers(this);"  data-role="select_vehicles_makers" class="form-control ajax-chosen-select-ajax"><option value="0">- Tất cả các hãng -</option></select>';
	    $html += '</div></div>';

	    $html += '<div class="form-group">';
	   // $html += '<label class="control-label col-sm-6 aleft bold" >Danh sách xe</label>';
	    $html += '<div class="col-sm-12">';        
	    $html += '<div id="bang_list_chon_xe"></div><div class="show_error_out"></div>';
	    $html += '<p class="help-block">Nếu chưa có trong danh sách phương tiện <a data-type_id="'+($this.attr('data-type_id'))+'" class="bold pointer" onclick="quick_add_more_vehicle_category(this);">click vào đây</a> để thêm mới.</p>';
	    $html += '</div> ';            
	    $html += '</div>';
	            
	    $html += '</article>';
	    $html += '</section>';
	 
	    $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="index" value="'+($this.attr('data-count'))+'">';
		$html += '<input type="hidden" name="existed_id" value="'+($this.attr('data-existed'))+'">';
		$html += '<input type="hidden" name="type_id" value="'+($this.attr('data-type_id'))+'">';
	    //$html += '<input type="hidden" name="f[quot]" value="'+$quot+'">'; 
	   // $html += '<input type="hidden" name="f[qtype]" value="'+$radio_quotation_type+'">';
		$html += '<input type="hidden" name="action" value="set_quantity_vehicles_categorys">';
		$html += '</form>';
		jQuery('.mymodal').html($html).modal('show');
		
		get_list_vehicles_makers('#select-chon-xe');
		reload_app('chosen');
		reload_app('number-format');
 }
 function get_list_vehicles_makers($t){
		var $this = jQuery($t);  
		var $target = jQuery($this.attr('data-target'));
		var $data = getAttributes($this);
		$data['action'] = 'get_list_vehicles_makers';
		$data['id'] = $this.val();
		jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.adminUrl  + '/ajax',						 		 
		      data: $data,
		      beforeSend:function(){
		    	  //showLoading();
		    	 // showFullLoading();
		      },
		      success: function (data) {
		    	  $target.html(data);
		    	  reload_app('number-format');
		      },
		      complete:function(){
		    	  //hideLoading();  
		    	  //$this.parent().append($er);
		      },
		      error : function(err, req) {
		           
					}
		});
		
 }
 function quick_add_more_vehicle_category($t){ 
		var $this = jQuery($t);
		$title =  'Thêm mới phương tiện' ;
		$html = '';
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
	    $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
	    $html += '<section class="boxInfo lbl-cl">';
	    $html += '<article class="boxForm uln fll w100 mb10">';
	 

	    $html += '<div class="form-group">';
	    $html += '<label class="control-label col-sm-12 aleft bold" >Tiêu đề</label>';
	    $html += '<div class="col-sm-12">';        
	    $html += '<div class="form-group"><div class="col-sm-12">';
	    $html += '<input data-target=".btn-save-data" onblur="check_vehicle_category_existed(this);" name="f[title]" class="form-control required" placeholder="Tiêu đề" value="" />'; 
	    $html += '<div class="show_error_out"></div></div>';
	    $html += '</div>';     
	    $html += '</div> ';            
	 
	     
	    $html += '<label class="control-label col-sm-12 aleft bold" >Hãng</label>';
	    $html += '<div class="col-sm-12">';        
	    $html += '<div class="form-group"><div class="col-sm-12">';
	    $html += '<select data-type="'+($this.attr('data-type_id'))+'" name="f[maker_id]" role="select_vehicles_makers" class="required form-control ajax-chosen-select-ajax"><option></option></select>'; 
	    $html += '</div>';
	    $html += '</div><div class="show_error_out"></div>';      
	    $html += '</div> ';  
	    
	    $html += '<label class="control-label col-sm-12 aleft bold" ></label>';
	    $html += '<div class="col-sm-12">';        
	    $html += '<div class="form-group"><div class="col-sm-12">';
	    $html += '<div class="browser_images"><div class="form-group col-sm-9"><input type="file" class="form-control input-sm" name="myfile" id="myfile-files-attach-images" accept=".jpeg,.jpg,.gif,.png,.ico" /></div>';  
	    $html += '<button type="button" data-input-name="image" data-name="old_icon" data-group="-files-attach-images" data-filetype="images" data-index="0" data-count="0" class="btn btn-default btn-sm btn-sd" onclick="return ajax_upload_files(this);" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';
	    $html += '<div class="col-sm-12"><div class="row">';
	    $html += '<div id="progress-group-files-attach-images" class="fl100" >'; 
	    $html += '</div><div class="" id="respon_image_uploaded-files-attach-images"></div></div></div></div>'; 
	    $html += '</div>';
	    $html += '</div><div class="show_error_out"></div>';      
	    $html += '</div> ';  
	    
	    
	    $html += '<label class="control-label col-sm-12 aleft bold" ></label>';
	    $html += '<div class="col-sm-12">';        
	    $html += '<div class="form-group"><div class="col-sm-12">';
	    $html += '<table class="table table-hover table-bordered vmiddle table-striped"> <thead><tr><th class="center mw100p" rowspan="2">Số ghế ngồi</th><th class="center" colspan="2">Khách Inbound</th><th class="center" colspan="2">Khách Nội Địa</th></tr><tr><th class="center mw100p">Tối thiểu</th><th class="center mw100p">Tối đa</th><th class="center mw100p">Tối thiểu</th><th class="center mw100p">Tối đa</th></tr></thead><tbody>';
	    $html += '<tr><td class="center"><input type="text" name="f[seats]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>'; 
	    $html += '<td class="center"><input type="text" name="x[pmin]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>';
	    $html += '<td class="center"><input type="text" name="x[pmax]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>';		
	    $html += '<td class="center"><input type="text" name="f[pmin]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>';
	    $html += '<td class="center"><input type="text" name="f[pmax]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td></tr>';
	    $html += '</tbody></table>';
	    $html += '</div>';
	    $html += '</div><div class="show_error_out"></div>';      
	    $html += '</div> ';  
	    
	    $html += '</div>';
	            
	    $html += '</article>';
	    $html += '</section>';
	 
	    $html += '</section>';
		$html += '</div>'; 
		$html += '<div class="modal-footer">';		
		$html += '<button disabled type="submit" class="btn btn-primary btn-save-data"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="index" value="'+($this.attr('data-count'))+'">';
		$html += '<input type="hidden" name="formSubmit" value="true">';
		$html += '<input type="hidden" name="type_id" value="'+($this.attr('data-type_id'))+'">';
	    //$html += '<input type="hidden" name="f[quot]" value="'+$quot+'">'; 
	   // $html += '<input type="hidden" name="f[qtype]" value="'+$radio_quotation_type+'">';
		$html += '<input type="hidden" name="action" value="quick_add_more_vehicle_category">';
		$html += '</form>';
		jQuery('.mymodal').modal('hide');
		if(jQuery('.mymodal1').length==0) jQuery('body').append('<div class="mymodal1 modal"></div>');
		jQuery('.mymodal1').html($html).modal('show');
		reload_app('chosen');
 }
 function check_vehicle_category_existed($t){
		var $this = jQuery($t);  
		var $target = jQuery($this.attr('data-target'));
		var $old = $this.attr('data-old');
		$id = $this.attr('data-id');
		if($old != $this.val()){
		jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.adminUrl  + '/ajax',						 		 
		      data: {action:'check_vehicle_category_existed',val:$this.val(),id:$id},
		      beforeSend:function(){
		    	  //showLoading();
		    	 // showFullLoading();
		      },
		      success: function (data) {
		    	  //console.log(data)
		    	  $d = JSON.parse(data);
		    	  if($d.state == true){
		    		  $this.parent().find('.show_error_out').html('<p class="help-block red italic">Dữ liệu đã tồn tại trong hệ thống</p>');
		    		  $target.attr('disabled','');
		    	  }else{
		    		  $this.parent().find('.show_error_out').html('');
		    		  $target.removeAttr('disabled');
		    	  }
		      },
		      complete:function(){
		    	  //hideLoading();  
		    	  //$this.parent().append($er);
		      },
		      error : function(err, req) {
		           
					}
		});
		}
 }
 function show_shield_grid($o){
	 jQuery($o.target).shieldGrid({
	        dataSource: {
	            data: $o.data
	        },
	        columns: $o.columns,
	        
	        altRows: false,
	        rowHover: false,
	         
	     }); 
	 reload_app('select2');reload_app('number-format');
 }
 function addNewCarCost(t){
		
		var $this = jQuery(t); $id = $this.attr('data-id');
		var $target = jQuery($this.attr('data-target'));
		$t2 = $this.attr('data-target');
		$name = ($this.attr('data-name'));
		
		$amount = parseInt($target.find('.add_cost_amount_row').val());
		$colspan = parseInt($this.attr('data-colspan'));
		$amount = $amount > 0 ? $amount : 1;
		$table_target = $target.find('.tableGrid > .sui-gridcontent > table > tbody > tr:last-child');
		$count = $c = parseInt($target.find('.data-count-cost-amount').val());
		$target.find('.data-count-cost-amount').val($count);
		$tb = '';
		for($i=0;$i<$amount;$i++){
		 
		$tb += '<tr class="sui-row"><td class="sui-cell">'+($c+$i+1)+'</td><td class="sui-cell"><div data-id="'+$id+'" class="auto_load_car_list auto_load_car_list_'+$i+'" data-name="'+$name+'['+($count)+'][item_id]" data-class="sui-input sui-input-focus w100 required sl-costs sl-cost-name"></div></td>';
		//$tb += '<td style="text-align: center;" class="sui-cell"><select name="'+$name+'['+$count+'][type]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-type"><option value="1">Inbound</option><option value="3">Nội địa</option></td>';
		$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][pmin]" class="sui-input sui-input-focus w100 ajax-number-format center sl-cost-pmin" value=""></td>';
		$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][pmax]" class="sui-input sui-input-focus w100 ajax-number-format center sl-cost-pmax" value=""></td>';
		$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price1]" data-decimal="0" class="aright sui-input sui-input-focus w100 ajax-number-format center sl-cost-price1 input-currency-price-ax-'+$count+'" value="" onblur="set_incurred_price(this);"></td>';
		//for($j = 0; $j<$colspan; $j++){
		//	$tb += '<td style="text-align: right;" class="sui-cell"><b data-decimal="2" data-index="'+$j+'" class="input-price-incurred sl-cost-price sl-cost-price-'+$j+'"></b></td>';
		//}
		
		$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price2]" data-decimal="0" class="aright sui-input sui-input-focus w100 ajax-number-format center sl-cost-price2 input-currency-price-ax-'+$count+'" value="" ></td>';
		$tb += '<td  class="sui-cell center"><select data-target-input=".input-currency-price-ax-'+$count+'" onchange="get_decimal_number(this);" name="'+$name+'['+$count+'][currency]" class="form-control input-sm select2" data-search="hidden">';
		jQuery.each($cfg.currency['list'],function(index,el){
			$tb += '<option id="'+(el['id'])+'">'+(el['code'])+'</option>';
		});
		$tb += '</select></td>';
		//$tb += '<td style="text-align: right;" class="sui-cell"><b class="sl-cost-price3 aright"></b></td>';
		$tb += '<td class="sui-cell"><p class="center"><i title="Xóa" onclick="removeItemCost(this);" class="pointer glyphicon glyphicon-trash"></i></p></td></tr>';
		 
		$target.find('.data-count-cost-amount').val(++$count);
		}
		$table_target.before($tb); 
		auto_load_car_list('.auto_load_car_list');
		//reload_app('select2');
		reload_app('chosen');reload_app('number-format');
		//reloadapp();
 }
 function auto_load_car_list($t){
		jQuery($t).each(function(i,e){
			var $this = jQuery(e);
			// alert(i)
		jQuery.ajax({
	        type: 'post',
	        datatype: 'json',
			url: $cfg.adminUrl  + '/ajax',						 		 
	        data: {action:'auto_load_car_list',id:$this.attr('data-id'),name:$this.attr('data-name'),classx : $this.attr('data-class')},
	        beforeSend:function(){
	               // showFullLoading();
	        },
	        success: function (data) {          	 
	            //hideFullLoading();
	        	jQuery('.auto_load_car_list_'+i).removeClass('auto_load_car_list auto_load_car_list_'+i).html(data);        
	        	reload_app('select2');
	        },
	        error : function(err, req) {
	            hideFullLoading();
	            //showModal('Đã xảy ra lỗi','Quá trình thực hiện đã xảy ra lỗi, vui lòng thử lại.');
				}
	      });
		});
	}	 
 function set_incurred_price($t){
		var $this = jQuery($t);
		var $val = $this.val();
		var $old = $this.attr('data-old');
		if($val != $old){
			$pr = $this.parent().parent();
			$pr.find('.input-price-incurred').each(function(i,e){
				$index = jQuery(e).attr('data-index');
				$d = jQuery(e).attr('data-decimal') ? jQuery(e).attr('data-decimal') : 0; 
				$incurred = parseFloat(jQuery('.sl-incurred-price-'+$index).val());
				$price = parseFloat($val) * (100+$incurred) / 100 ;
				jQuery(e).html(jQuery.number($price,$d))
			});
		}
 }
 function add_new_cost_distance($t){
		var $this = jQuery($t); 
		$title =  'Chọn chặng vận chuyển' ;
		$html = '';
		$html += '<form name="ajaxForm" action="/ajax" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">'+$title+'</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
	    $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
	    $html += '<section class="boxInfo lbl-cl">';
	    $html += '<article class="boxForm uln fll w100 mb10">';
	  
	    $html += '<div class="form-group">';
	    $html += '<div class="col-sm-12">';        
	    $html += '<select multiple name="f[distance_id][]" id="chosen-load-distances" data-place="'+(jQuery('.ajax_load_place_id').val())+'" data-type="'+($this.attr('data-type_id'))+'" data-type_id="'+($this.attr('data-type_id'))+'" data-existed="'+($this.attr('data-existed'))+'" data-index="'+($this.attr('data-count'))+'" data-target=".ajax-result-price-distance" role="load_distances" class="form-control ajax-chosen-select-ajax"><option></option></select>';
	    $html += '</div><p class="col-sm-12 help-block">*** Lưu ý: Danh sách chặng sẽ lấy theo địa danh đã chọn ở tab "Thông tin chung".</p></div>';
	        
	    
	    $html += '<div class="form-group quick-addnew-form">';
	    $html += '<div class="col-sm-12">';        
	    $html += '<label>Nếu chặng vận chuyển chưa tồn tại bạn có thể thêm nhanh tại đây:</label>';
	    $html += '<input name="fn[0][title]" type="text" class="form-control" value="" placeholder="Nhập tên chặng">';
	    $html += '<input name="fn[0][distance]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Khoảng cách (km)">';
	    $html += '<input name="fn[0][overnight]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Lưu đêm">';
	    $html += '</div></div>';
	    $html += '<div class="form-group quick-addnew-form">';
	    $html += '<div class="col-sm-12">';        
	    //$html += '<label>Tên chặng</label>';
	    $html += '<input name="fn[1][title]" type="text" class="form-control" value="" placeholder="Nhập tên chặng">';
	    $html += '<input name="fn[1][distance]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Khoảng cách (km)">';
	    $html += '<input name="fn[1][overnight]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Lưu đêm">';
	    $html += '</div></div>';
	    
	    
	    
	    $html += '</article>';
	    $html += '</section>';
	 
	    $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		$html += '<input type="hidden" name="index" value="'+($this.attr('data-count'))+'">';
		$html += '<input type="hidden" name="existed" value="'+($this.attr('data-existed'))+'">';
		$html += '<input type="hidden" name="f[id]" value="'+($this.attr('data-id'))+'">';
	    $html += '<input type="hidden" name="f[type_id]" value="'+($this.attr('data-type_id'))+'">'; 
	    $html += '<input type="hidden" name="f[place_id]" value="'+(jQuery('.ajax_load_place_id').val())+'">';
		$html += '<input type="hidden" name="action" value="add_new_cost_distance">';
		$html += '</form>';
		jQuery('.mymodal').html($html).modal('show');
		load_distance_to_element('#chosen-load-distances');
		
		
		 
	}
	function load_distance_to_element($t){
		var $this = jQuery($t);
		var $data = getAttributes($this);
		$data['action'] = 'load_distance_to_element';
		jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.adminUrl  + '/ajax',						 		 
		      data: $data,
		      beforeSend:function(){
		    	  //showLoading();
		    	 // showFullLoading();
		      },
		      success: function (data) {
		    	  //console.log(data)
		    	  $this.html(data);
		    	  reload_app('chosen');
		    	  $this.trigger('chosen:open');
		      },
		      complete:function(){
		    	  //hideLoading();  
		    	  //$this.parent().append($er);
		      },
		      error : function(err, req) {
		           
					}
		});
	}
	function remove_item_class($t){
		var $this = jQuery($t);
		$confirm = $this.attr('data-confirm') ? $this.attr('data-confirm') : false;
		var $target = jQuery($this.attr('data-target'));
		$state = true;
		if($confirm !== false){
			$state = confirm($confirm);
		}
		if($state){
			$target.remove();
		}
	}	
	
function changePermission ($t){
	var $this = jQuery($t);
	$checked = $this.is(':checked');
	$c = $this.attr('data-old') == 'checked' ? true : false;
	if($c != $checked){
		if($c){
			$this.removeAttr('data-old');
		}else{
			$this.attr('data-old','checked')
		}
		var $val = $this.val();
		$id = $this.attr('data-id');
		$permission = $this.attr('data-permission');
		var $type = $this.attr('data-type');
		$this.parent().find('input.change-'+$val).remove();
		$this.parent().append('<input type="hidden" value="'+$val+'" name="changed_permission[name][]" class="change-'+$val+'"/><input type="hidden" value="'+$checked+'" name="changed_permission[state][]" class="change-'+$val+'"/><input type="hidden" value="'+$id+'" name="changed_permission[id][]" class="change-'+$val+'"/><input type="hidden" value="'+$permission+'" name="changed_permission[permission][]" class="change-'+$val+'"/><input type="hidden" value="'+$type+'" name="changed_permission[type][]" class="change-'+$val+'"/>');
	}
}	
function get_notifications($t){
	 var $this = jQuery($t);
	 var $target = $this.find('ul.msg_list');
	 if(!$target.is(':visible')){
		 jQuery.get($cfg.cBaseUrl +'/ajax?action=getNotifis',{},function(r){
				
				// Setting a timeout for the next request,
				// depending on the chat activity:
				//alert(r)
				var nextRequest = 3000;
				$n = jQuery('.item-notifications');
				$badge = $n.find('.alert-count');
				$badge.hide();
				$target.html(r.html)
				$s = jQuery('.notification-scroll');
				if($s.attr('data-loaded') == undefined){
					$s.slimScroll({
				        height: '350px'
				    })
				    .bind('slimscroll', function(e, pos){
					  // console.log("Reached " + pos);
					})
				    .attr('data-loaded',true);
				}
				// 2 seconds
				//if(chat.data.noActivity > 3){
				//	nextRequest = 2000;
				//}
				
				//if(chat.data.noActivity > 10){
				//	nextRequest = 5000;
				//}
				
				// 15 seconds
				//if(chat.data.noActivity > 20){
				//	nextRequest = 15000;
				//}
			
				 
			},'json');
	 }
}
function changeOrderPrice($t){
	$discount = (jQuery('.input-order-discount').val());
	$vat = (jQuery('.input-order-vat').val());
	$price = (jQuery('.input-order-total-price-before-vat').val());
	$currency = (jQuery('.input-order-currency').val());
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'changeOrderPrice','discount':$discount,vat:$vat,price:$price,currency:$currency},
	      beforeSend:function(){},
	      success: function (data) {
	    	   $d = JSON.parse(data);
	    	   jQuery('.label-order-price-after').html($d.price_after);
	    	   jQuery('.input-order-total-price').val($d.price);
	    	   jQuery('.input-order-total-price-text').html($d.price_text);
	      },
	      complete:function(){},
	      error : function(err, req) {}
	});
}

function addrequired_input($t){
	var $this = jQuery($t);
	$parent = $this.parent().parent();
	$required = false;
	$parent.find('input').each(function(i,e){
		if(!$required && jQuery(e).val() != ""){
			$required = true; 			
		}
	});
	
	if($required){
		$parent.find('input').addClass('required');
	}else{
		$parent.find('input').removeClass('required');
	}
}

function changeNotificationState($t){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'changeNotificationState','id':jQuery($t).attr('data-id')},
	      beforeSend:function(){},
	      success: function (data) {
	    	    
	      },
	      complete:function(){},
	      error : function(err, req) {}
	});
	//return false;
}
function loadMenus($t){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadMenus','id':$t,'supplier_id':$t},
	      beforeSend:function(){},
	      success: function (data) {
	    	  var $d = parseJsonData(data);
	    	  jQuery('.ajax-result-load-menus').html($d.html)
	      },
	      complete:function(){
	    	  reloadTooltip();
	    	  reload_app('select2');
	    	  load_number_format();
	    	  
	      },
	      error : function(err, req) {}
	});
	//return false;
}
function loadGuidePrices($t){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadGuidePrices','id':$t},
	      beforeSend:function(){},
	      success: function (data) {
	    	    jQuery('.ajax-result-load-menus').html(data)
	      },
	      complete:function(){
	    	  reload_app('select2');
	    	  load_number_format()
	      },
	      error : function(err, req) {}
	});
	//return false;
}


function addToRemove($t){
	var $this = jQuery($t);
	$id = $this.attr('data-id');
	$name = $this.attr('data-name') ? $this.attr('data-name') : 'remove_item';
	$pr = $this.parent();
	$pr.parent().addClass('tr_temporary_delete');
	$pr.append('<input class="input-delete-item" type="hidden" name="'+$name+'[]" value="'+$id+'"/>');
	$class= $this.hasClass('btn-remove') ? 'btn-remove' : '';
	if($pr.find('.btn-restore-item').length == 0){
		$pr.append('<i onclick="restoreItem(this)" title="Khôi phục" class="btn-restore-item pointer fa fa-mail-reply underline '+$class+'"></i>'); 
	}else{
		//$pr.find('.btn-restore-item').css({'text-decoration':'none'}).show()
	}
	$pr.find('.btn-restore-item').css({'text-decoration':'none'}).show()
	$this.hide();
	
}
function restoreItem($t){
	var $this = jQuery($t);
	 
	$pr = $this.parent();
	$pr.parent().removeClass('tr_temporary_delete');
	$pr.find('.input-delete-item').remove();
	$pr.find('.btn-delete-item').css({'text-decoration':'none'}).show(); 
	$this.hide();
	
}
function addFormEditField($t){
	var $this = jQuery($t);
	$c = jQuery('.edited-field-form');
	if($c.length == 0) jQuery('body').append('<div class="edited-field-form"></div>');
	var $old = $this.attr('data-old') ? $this.attr('data-old').replace(/\.00/g,'') : '';
	$name  = $this.attr('name').replace(/\[\]|\[|\]/g,'_');
	 
	if($old != $this.val().replace(/\.00/g,'')){
		jQuery('.edited-field-form').append('<input type="hidden" class="field-required-save edited_field_'+$name+'" name="edited_field['+$this.attr('name')+']" value="'+$old+'"/>');
		jQuery('.btn-data-required-save').attr('data-required-save',true); 
	}else{
		jQuery('.edited-field-form').find('.edited_field_'+$name).remove();
	}
}
function remove_list_btn(){
	jQuery('.list-btn-hd').remove()
}
 
function checkExistedAuthItem(t){
	var $this = jQuery(t);
	$field = $this.attr('data-field') ? $this.attr('data-field') : 'code';
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	if($this.attr('data-old') != $this.val()){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'checkExistedAuthItem',val:$this.val(),id:$id,'field':$field},
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  //alert(data)
	    	  $d = JSON.parse(data);
	    	  
	    	  //$this.attr('data-old',$new);
	    	  // alert(data)
	    	  //$code.val(data)
	           if($d.state == false){
	        	   $this.removeClass('error');jQuery('.submitFormBtn').removeAttr('disabled');
	        	   $er.addClass('success').html('Bạn có thể sử dụng giá trị này.');
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          }else{
	        	  jQuery('.submitFormBtn').attr('disabled','disabled');
	        	  $this.addClass('error');
	        	  $add = '<p class="red"><b>'+$this.val()+'</b> không hợp lệ hoặc đã được sử dụng.</p>';
	        	  $add += '<p><b>'+$this.val()+': </b>'+$d.data['title']+'</p>';
	      
	        	  $er.removeClass('success').html($add);
	          }
	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  $this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
	}
}

 
function loadSupplierRoutes($t){
	var $this = jQuery($t);
}
function loadSupplierPrices($t){
	var $this = jQuery($t);
}


function loadSupplierRooms($t){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadSupplierRooms','id':$t},
	      beforeSend:function(){},
	      success: function (data) {
	    	    jQuery('.ajax-result-load-rooms').html(data)
	      },
	      complete:function(){
	    	  reload_app('select2');
	    	  load_number_format()
	      },
	      error : function(err, req) {}
	});
	//return false;
}
function quick_search_station_for_add_supplier($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'quick_search_station_for_add_supplier';
	$data['val'] = $this.val();
	$data['station_from'] = jQuery('.input-station-from').val();
	$data['station_to'] = jQuery('.input-station-to').val();
	
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){},
	      success: function (data) {
	    	  $d = JSON.parse(data);
	    	  //console.log($data)
	    	  jQuery('.quick-search-tr-item').hide();
	    	  jQuery.each($d,function(i,e){
	    		  jQuery('.quick-search-tr-item-'+e).show();
	    	  });
	      },
	      complete:function(){
	    	   
	      },
	      error : function(err, req) {}
	});
}

function quick_filter_text_value($t){ 
	var $this = jQuery($t);	 
	var $data = getAttributes($this);
	$data['q'] = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){},
	      success: function (data) {
	    	  $d = JSON.parse(data);
	    	  jQuery('.quick-search-tr-item').hide();
	    	  jQuery.each($d,function(i,e){
	    		  jQuery('.quick-search-tr-item-'+e).show();
	    	  });
	      },
	      complete:function(){
	    	   
	      },
	      error : function(err, req) {}
	});
}
function countTotalGuest(t){
	var $g1,$g2,$g3;
	$g1 = parseInt(jQuery('#input-tour-sokhach-nl').val());
	$g2 = parseInt(jQuery('#input-tour-sokhach-te').val());
	$g1 = $g1 > 0 ? $g1 : 0;$g2 = $g2 > 0 ? $g2 : 0;
	$g3 = Math.ceil($g2 / 2);
	//console.log($g1)
	//console.log($g2)
	//console.log($g3)
	jQuery('#input-tour-sokhach').val($g1 + $g3);
	changeTotalGuest(t); 
}
function changeTotalGuest(t){
	jQuery('#inputTotalGuest').val(jQuery(t).val());
	//calculationTourCost('#tab-genaral-costs-grid');
	//calculationTourCost('#tab-private-costs-grid');
	//calculationTourCost(t);
}
function changeTourType(t){
	var $this = jQuery(t);
	var $v = $this.val();
	switch ($v) {
	case 1: case '1':
		jQuery('#table_CTYPE').addClass('inbound');
		jQuery('.hotel_room_3').find('.radio-checked-hotel').prop('checked',false).change();
		break;
	default:
		jQuery('#table_CTYPE').removeClass('inbound');
		break;
	}
}
function changeNightPreview(t){
	var $this = jQuery(t);
	var $day = parseInt(jQuery('#input-day-amount').val());
	var $n = jQuery('#input-night-amount');
	if($this.val() != $this.attr('data-old')){
	if(parseInt($n.val()) < $day-1 ){
		$night = $day - 1;
		$n.val($night); 
	}else{
		if(parseInt($n.val()) > $day+1){
			$night = $day + 1;
			$n.val($night); 
		}else $night = parseInt($n.val());
	}
	$n.attr('data-old',$night);
	$this.attr('data-old',$this.val());
	jQuery('.ajax-auto-load-time-detail').attr('data-day',Math.max($day,$night));
	//loadTourProgramDetail('.ajax-auto-load-time-detail');
	
	}
}
function loadTourProgramDetail($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	// 
	$data['total_pax'] = jQuery('#input-tour-sokhach').val();
	$data['exchange_rate'] = jQuery('#inputXChangeRate').val();
	$data['nationality'] = jQuery('#inputNationality').val();
	 
	//
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){},
	      success: function (data) {
//	    	  console.log(data)
	    	  var $d = JSON.parse(data);
	    	  jQuery($d.target).html($d.html);
	    	  //console.log($d.target)
	    	  if($d.callback){
	    		  eval($d.callback_function);
	    	  }
	    	  reloadTooltip();
	      },
	      complete:function(){
	    	  reload_app('select2');
	    	  load_number_format()
	      },
	      error : function(err, req) {}
	});
}
function checkExistedTourProgramCode($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	// 
	$data['code'] = $this.val();
	//
	if($this.val() != $this.attr('data-old')){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){},
	      success: function (data) {	    	  
	    	  $d = JSON.parse(data);
	    	  $this.val($d.code);
	    	  if($d.state){
	    		  $this.removeClass('error').addClass('success');
	    		  hideAlertError($t);
	    	  } else{
	    		  $this.addClass('error').removeClass('success');
	    		  showAlertError($d.alert,$t);
	    	  }
	    	  $this.attr('data-old',$this.val());
	      },
	      complete:function(){
	    	   
	      },
	      error : function(err, req) {}
	});
	}
}
function showAlertError($content,$t){
	var $this = jQuery($t);
	$pr = $this.parent();
	if($pr.find('.error-alert').length == 0){
		$pr.append('<div class="alert alert-warning alert-dismissible error-alert" role="alert"></div>');
	}
	$alert = $pr.find('.error-alert');
	$alert.html($content).show();
}
function hideAlertError($t){
	var $this = jQuery($t);
	$pr = $this.parent();
	$alert = $pr.find('.error-alert');
	$alert.hide();
}

function Tour_quick_change_selected_tour_service_day($t){
	var $this = jQuery($t);
	var $pr = $this.parent();
	$pr.parent().find('.active').removeClass('active');
	$pr.addClass('active');
	var $data = getAttributes($this);
	$data.supplier_id = jQuery(".input-quick-search-supplier").val();
	$data.station_id = jQuery(".input-quick-search-station").val();
	var $x = jQuery("input.selected_value_"+($data['id'])+'_'+$data['day']+'_'+$data['time']).map(
	        function () {return this.value;}).get().join(",");

	$data['selected'] = $x;
	$data['place_id'] = jQuery('.input-quick-search-local').val();	
	sentAjaxData($data);	
}

function Tour_change_selected_tour_service_day($t){
	var $this = jQuery($t);
	var $pr = $this.parent();
	$pr.parent().find('.active').removeClass('active');
	$pr.addClass('active');
	var $data = getAttributes($this);
	var $x = jQuery("input.selected_value_"+($data['type_id'])+'_'+$data['day']+'_'+$data['time']).map(
	        function () {return this.value;}).get().join(",");

	$data['selected'] = $x;
	//log($x);
	$data['place_id'] = jQuery('.input-quick-search-local').val();	
	sentAjaxData($data);	
}
function change_selected_tour_service_group($t){
	var $this = jQuery($t);
	var $pr = $this.parent();
	$pr.parent().find('.active').removeClass('active');
	$pr.addClass('active');
	var $data = getAttributes($this);
	// 
	var $x = jQuery("input.selected_value_"+($data['id'])+'_'+$data['day']+'_'+$data['time']).map(
	        function () {return this.value;}).get().join(",");
	//console.log("input.selected_value_"+($data['id'])+'_'+$data['day']+'_'+$data['time']);
	$data['selected'] = $x;
	$data['place_id'] = jQuery('.input-quick-search-local').val();
	$data['action'] = 'change_selected_tour_service_group';
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){showFullLoading();},
	      success: function (data) {
	    	  
	    	  jQuery('.input-quick-search-service').attr('data-type_id',$data['id']) 	    	  
	    	  $d = JSON.parse(data);
	    	  jQuery('.available_services>ul').html($d.html);
	    	  if($d.changeDropdown){
	    		  var $dr = jQuery('.ex-service-select-dropdown');
	    		  $dr.html($d.dropdown);
	    		  $dr.find('select')
	    		  .change() ;
	    		  load_chosen_select();
	    	  }
	    	  if($d.callback){
	    		  eval($d.callback_function);
	    	  }
	    	  
	      },
	      complete:function(){
	    	  hideFullLoading();
	      },
	      error : function(err, req) {}
	});
	return false;
}


function quick_search_tour_service($t){
	//return true;
	var $this = jQuery($t);	 
	//console.log($this);
	var $type_id = $this.attr('data-type_id');		 	
	var $data = getAttributes($this);
	// 
	$segment_id = parseInt($this.attr('data-segment_id')) > 0 ? parseInt($this.attr('data-segment_id')) : 0;
	
	$selected_input = "input.selected_value_"+$type_id+'_'+$data['day']+'_'+$data['time'] + ($segment_id>0 ? '_'+$segment_id : '');
	
	if(jQuery($selected_input).length==0){
		$selected_input = jQuery('#sortable1').find('.selected_value_xc');
	}else{
		$selected_input = jQuery($selected_input);
	}
	///console.log($selected_input);
	$data['selected'] = $selected_input.map(
	        function(){return this.value;}).get().join(",");
	//console.log($data['selected']);
	//console.log($selected_input.length);
	$data['place_id'] = jQuery('.input-quick-search-local').val();
	$data['train_distance_id'] = jQuery('.input-quick-search-train-distance').val();
	$data['total_pax'] = jQuery('#input-tour-sokhach').val();
	$data['nationality'] = jQuery('#inputNationality').val();
	$data['action'] = 'quick_search_tour_service';
	if($this.attr('data-language')){
		$data['language'] = jQuery('.input-quick-search-language').val();
	}
	//console.log($data['language']);
	$data['value'] = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){},
	      success: function (data) {	
	    	// console.log(data);
	    	  $d = JSON.parse(data);
	    	  jQuery('#sortable2').html($d.html);
	    	  if($d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete:function(){
	    	  //hideFullLoading();
	      },
	      error : function(err, req) {}
	});
}

function generateSitemap($t){
	
	var $this = jQuery($t);
	var $parent = jQuery($this.attr('data-target'));
	var $data = getAttributes($this);
	$freq = $parent.find('.sitemap_freq').val();
	$last_mod = $parent.find('.sitemap_last_mod').val();
	$priority = $parent.find('.sitemap_priority:checked').val();
	
	$data['action'] = 'generateSitemap';
	$data['freq'] = $freq;
	$data['priority'] = $priority;
	$data['lastmod'] = $last_mod;
	
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){},
	      success: function (data) {
	    	  $d = JSON.parse(data);
	    	  var $input_sitemap = $parent.parent().find('.inputSitemap');
	    	  console.log($input_sitemap);
	    	  $input_sitemap.val($d.html); 
	    	   // jQuery('.input_codemirror').each(function(i, el){
	    	    var myInstance = $input_sitemap.data('CodeMirrorInstance');
	    	    //console.log(myInstance); 
	    	    myInstance.setValue($d.html);
	    	   // });
	    	    /*
	    	    CodeMirror.fromTextArea(document.getElementById('inpuSitemap'), { 
	      		  mode: "text/html",
	      		  lineNumbers: true
	      		});
	      		*/
	      },
	      complete:function(){
	    	  
	      },
	      error : function(err, req) {}
	});
	//return false;
}
function reloadDistanceServicePriceAuto($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'reloadDistanceServicePriceAuto';
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  $this.addClass('fa-spin fa-refresh').removeClass('green fa-check-square-o')
	      },
	      success: function (data) {
	    	  //console.log(data) 
	    	  $d = JSON.parse(data);
	    	  jQuery('.input-distance-service-price').val($d.price1).addClass('green');
	    	  jQuery('.input-distance-service-distance').val($d.quantity).addClass('green');
	      },
	      complete:function(){
	    	  $this.removeClass('fa-spin fa-refresh ').addClass('green fa-check-square-o')
	      },
	      error : function(err, req) {}
	});
	//return false;
}
function reloadServiceDayPriceAuto($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'reloadServiceDayPriceAuto';
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  $this.addClass('fa-spin fa-refresh').removeClass('green fa-check-square-o')
	      },
	      success: function (data) {
	    	  $d = JSON.parse(data);
	    	  //console.log($d) 
	    	  jQuery('.input-distance-service-price').val($d.price1).addClass('green');
	    	  jQuery('.input-service-day-price-quantity').val($d.quantity).addClass('green');
	    	  jQuery('.input-service-day-price-sub-item-id').val($d.sub_item_id).addClass('green');
	      },
	      complete:function(){
	    	  $this.removeClass('fa-spin fa-refresh ').addClass('green fa-check-square-o')
	      },
	      error : function(err, req) {}
	});
	//return false;
}
function getExchangeRateToday($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'getExchangeRateToday';
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  $this.addClass('fa-spin fa-refresh').removeClass('green fa-check-square-o')
	      },
	      success: function (data) {
	    	  $d = JSON.parse(data);
	    	  $this.parent().parent().find('.input-currency-exchange-rate').val($d.price).addClass('green');
	    	  
	      },
	      complete:function(){
	    	  $this.removeClass('fa-spin fa-refresh ').addClass('green fa-check-square-o')
	      },
	      error : function(err, req) {}
	});
	//return false;
}
function removeSupplierSeason($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'removeSupplierSeason';
	if(confirm('Xác nhận ?')){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	 // $this.addClass('fa-spin fa-refresh').removeClass('green fa-check-square-o')
	      },
	      success: function (data) {
	    	//  $d = JSON.parse(data);
	    	 // $this.parent().parent().find('.input-currency-exchange-rate').val($d.price).addClass('green');
	    	  removeTrItem($t);
	      },
	      complete:function(){
	    	//  $this.removeClass('fa-spin fa-refresh ').addClass('green fa-check-square-o')
	      },
	      error : function(err, req) {}
	});
	}
	//return false;
}
function read_date($number){
	$date = '';
	switch ($number){
		case 0:
			$date = 'Chủ nhật';
			break;
		default:
			$date = 'Thứ ' + ($number+1);
			break;
	}
	return $date;
}
function change_form_add_supplier_season_category($t){
	var $this = jQuery($t); var $html = '';
	var $i,$j;
	switch(parseInt($this.val())){
		case 5: 
			$html += '<tr><td class="pr" colspan="5"><input data-level="3" type="text" class="sui-input input-root-destination-required form-control w100 input-sm" value="" name="f[title]" placeholder="Tiêu đề"/></td></tr>';
			for($i=0; $i<3;$i++){
				
				$html += '<tr><td><select class="form-control input-sm select2" data-search="hidden"  name="new['+$i+'][from_date]">';
				for($j = 0;$j<7;$j++){
					$html += '<option value="'+$j+'">'+read_date($j)+'</option>';
				}
				$html += '</select></td>';
				//$html += '	<td><input type="text" onblur="check_input_required(this)" class="sui-input form-control input-condition-required input-sm ajax-timepicker" value="" name="new['+$i+'][from_time]" placeholder="Thời gian bắt đầu"/></td>';
				$html += '	<td><select class="form-control input-sm select2" data-search="hidden" name="new['+$i+'][to_date]">';
				for($j = 0;$j<7;$j++){
					$html += '<option value="'+$j+'">'+read_date($j)+'</option>';
				}
				$html += '</select></td>';
				$html += '	<td><select class="form-control input-sm select2" data-search="hidden" name="new['+$i+'][part_time]">';
				for($j = 0;$j<4;$j++){
					$html += '<option value="'+$j+'">'+showPartDay($j)+'</option>';
				}
				$html += '</select></td>';
				//$html += '	<td class="center "><input type="text" onblur="check_input_required(this)" class="sui-input w100 form-control input-condition-required input-sm ajax-timepicker" value="" name="new['+$i+'][to_time]" placeholder="Thời gian kết thúc"/></td>';
				$html += '	<td class=""><input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-destination-required input-condition-required" value="" name="new['+$i+'][title]" placeholder="Tiêu đề"/></td>';
				$html += '</tr>';
					
			}
			break;
		case 3: case 4:
			$html += '<tr><td class="pr" colspan="5"><input data-level="3" type="text" class="sui-input input-root-destination-required form-control w100 input-sm" value="" name="f[title]" placeholder="Tiêu đề"/></td></tr>';
			for($i=0; $i<3;$i++){
				
				$html += '<tr><td><select class="form-control input-sm select2" data-search="hidden" name="new['+$i+'][from_date]">';
				for($j = 0;$j<7;$j++){
					$html += '<option value="'+$j+'">'+read_date($j)+'</option>';
				}
				$html += '</select></td>';
				$html += '	<td><input type="text" onblur="check_input_required(this)" class="sui-input form-control input-condition-required input-sm ajax-timepicker" value="" name="new['+$i+'][from_time]" placeholder="Thời gian bắt đầu"/></td>';
				$html += '	<td><select class="form-control input-sm select2" data-search="hidden" name="new['+$i+'][to_date]">';
				for($j = 0;$j<7;$j++){
					$html += '<option value="'+$j+'">'+read_date($j)+'</option>';
				}
				$html += '</select></td>';
				$html += '	<td class="center "><input type="text" onblur="check_input_required(this)" class="sui-input w100 form-control input-condition-required input-sm ajax-timepicker" value="" name="new['+$i+'][to_time]" placeholder="Thời gian kết thúc"/></td>';
				$html += '	<td class=""><input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-destination-required" value="" name="new['+$i+'][title]" placeholder="Tiêu đề"/></td>';
				$html += '</tr>';
					
			}
		break;
		default:
			$html += '<tr><td class="pr" colspan="3"><input type="text" class="sui-input input-root-destination-required form-control w100 input-sm" value="" name="f[title]" placeholder="Tiêu đề"/></td></tr>';
			for($i=0; $i<3;$i++){
				
				$html += '<tr><td class="pr"><input onblur="check_input_required(this)" type="text" class="sui-input input-condition-required form-control w100 input-sm ajax-datepicker" value="" name="new['+$i+'][from_date]" placeholder="Thời gian bắt đầu"/></td>';
	    		$html += '<td class="center pr"><input onblur="check_input_required(this)" type="text" class="sui-input input-condition-required w100 form-control input-sm ajax-datepicker" value="" name="new['+$i+'][to_date]" placeholder="Thời gian kết thúc"/></td>';
	    		$html += '<td class="center "><input onblur="check_input_required(this);" type="text" class="sui-input input-destination-required w100 form-control input-sm" value="" name="new['+$i+'][title]" placeholder="Tiêu đề"/> </td>';
	    		$html += '</tr>';
					
			}
			break;
	}
	jQuery('.ajax-rs-0301').html($html);
	reload_app('date-time'); reload_app('select2');
}
function check_input_required($t){
	var $this = jQuery($t); 
	var $r = true;
	var $des = $this.parent().parent().find('.input-destination-required');
	var $root = $this.parent().parent().parent().find('.input-root-destination-required');
	if($this.val() != ""){
		$r = false;
		$des.addClass('required');
	}else{
		$r = true;
	}
	if(!$r){
	$this.parent().parent().find('.input-condition-required').each(function(i,e){		
		if(jQuery(e).val() != "") {
			$r = false;			 
		}
	});
	}
	if($r){
		$des.removeClass('required');
		$this.parent().parent().find('.input-condition-required').removeClass('required');
		$root.removeClass('required');
	}else{
		$this.parent().parent().find('.input-condition-required').addClass('required');
		$root.addClass('required');
		$des.addClass('required');
	}
}
function reloadAllTourProgramPrices(){
	
}

function quick_change_supplier_season($t){		 
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'quick_change_supplier_season';
	$data['new_value'] = $this.val();
	if($this.val() != $this.attr('data-old')){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  show_left_small_loading('show'); 
	      },
	      success: function (data) {	   
	    	 // console.log($this.val());
	    	  $this.attr('data-old',$this.val());
	    	  
	      },
	      complete:function(){
	    	  show_left_small_loading('hide');   
	      },
	      error : function(err, req) {
	           
				}
	    });
	}
}

function quick_change_supplier_list_vehicle($t){		 
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'quick_change_supplier_list_vehicle';
	$data['new_value'] = $this.val();
	//console.log($data)
	if($this.val() != $this.attr('data-old')){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  show_left_small_loading('show'); 
	      },
	      success: function (data) {	   
	    	  
	    	  $this.attr('data-old',$this.val());
	    	  
	      },
	      complete:function(){
	    	  show_left_small_loading('hide');   
	      },
	      error : function(err, req) {
	           
				}
	    });
	}
}

function change_location_append($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $target4 = $this.parent().parent().find('.add-more-nationality-group-to-supplier'); 
	$data['action'] =  'change_location_append';
	$data['value'] = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	 // show_left_small_loading('show'); 
	      },
	      success: function (data) {	    	  
	    	  //console.log(data)
	    	 // $this.attr('data-old',$this.val());
	    	  var $d = JSON.parse(data);
	    	  var $target = $this.parent().parent().find('.input-location-appended');
	    	  $target.attr('data-object_id',$this.val());
	    	  switch (parseInt($this.val())) {
				case 1:
					$target.parent().show();$target4.removeAttr('disabled');
					$target.attr('data-role','chosen-load-nationlity-group').html($d.html).trigger("chosen:updated");
					break;
				case 2:
					$target.parent().show();
					$target.attr('data-role','chosen-load-country').html($d.html).trigger("chosen:updated");
					$target4.attr('disabled','');
					break;
				default:
					$target.parent().hide();$target4.attr('disabled','');
					break;
				}
	      },
	      complete:function(){
	    	 // show_left_small_loading('hide');   
	      },
	      error : function(err, req) {
	           
				}
	});
}

function change_seasons_private_suppliers($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'change_seasons_private_suppliers';
	$data['value'] = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  show_left_small_loading('show'); 
	      },
	      success: function (data) {	    	  
	    	  //console.log(data)
	      },
	      complete:function(){
	    	  show_left_small_loading('hide');   
	      },
	      error : function(err, req) {
	           
				}
	});
}

function set_default_supplier_room($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'set_default_supplier_room';
	$data['value'] = $this.val();
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     //console.log(data)
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
}




 
function changeFormType($t){
	var $this = jQuery($t);
	var $target = jQuery($this.attr('data-target'));
	var $val = $this.val();
	switch ($val) {
	case 'link': case 'manual':
		jQuery('.input-form-group-tours').hide();
		$target.show();
		//$target.find('input').val('sss').focus(); 
		return false;
		break;
	case 'tours':
	jQuery('.input-form-group-tours').show();
	$target.hide();
	break;
	default: 
		jQuery('.input-form-group-tours').hide();
		$target.hide();
		break;
	}
}


function showPartDay($part){
	var $day;
	switch ($part){
		case 1: $day = 'trưa'; break;
		case 2: $day = 'chiều'; break;
		case 3: $day = 'tối'; break;
		default: $day = 'sáng'; break;
	}
	return $day;
}
function open_edit_mode($t){
	var $this = jQuery($t);
	if($this.attr('readonly')){
		$this.removeAttr('readonly');
	} 
} 

function quick_change_menu_price($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'quick_change_menu_price';
	$data['value'] = $this.val();
	if($this.val() != $this.attr('data-old').replace(/\.00/g,'')){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     //console.log(data)
	    	 $this.attr('data-old',$this.val()); 
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
	}
}
function quick_change_supplier_service_price($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'quick_change_supplier_service_price';
	$data['value'] = $this.val();
	var $old = $this.attr('data-old') ? $this.attr('data-old') : '';
	var $d = {};
	if($this.val() != $old.replace(/\.00/g,'')){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    	show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     		
	    	 $this.attr('data-old',$this.val()); 
	    	 $d = parseJsonData(data);
	    	 if($d.callback){
	    		 eval($d.callback_function);
	    	 }
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
	}
}
function quick_change_menu_price_currency($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $showLoading = $this.attr('data-show-loading') ? true : false;
	$data['action'] =  'quick_change_menu_price_currency';
	$data['value'] = $this.val();
	if($this.val() != $this.attr('data-old')){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    	if($showLoading) show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     //console.log(data)
	    	 $this.attr('data-old',$this.val()); 
	    },
	    complete:function(){
	    	if($showLoading) show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
	}
}
function quick_change_menus_price_default($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'quick_change_menus_price_default';
	$data['value'] = $this.val();
	if($this.val() != $this.attr('data-old')){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     //console.log(data)
	    	 $this.attr('data-old',$this.val()); 
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
	}
}

function change_multi_currency_price($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $target = jQuery($this.attr('data-target'));
	$target.val($this.val()).trigger('change');
}
function removeItemCost(t){
	var $this = jQuery(t);
	$this.parent().parent().parent().remove();
	calculationTourCost(t);
}
function removeItemCostDetail(t){
	var $this = jQuery(t);
	var $index = $this.attr('data-index');
	var $pindex = $this.attr('data-pindex') ? $this.attr('data-pindex') : false;
	//alert($pindex)
	var $pr = $this.parent().parent().parent().parent();
	
	$pr.find('.sui-detail-row-'+$index).remove();
	//$v = parseInt(jQuery('#numberOfHotel').val()) - 1;
	//jQuery('#numberOfHotel').val($v);
}

function setMainLogo($t){
	var $this = jQuery($t);
	var $image = $this.attr('data-image');
	jQuery('.rp-img-rs').find('img').attr('src',$image);
	jQuery('.rp-logo-main').val($image);
}

function removeItemTR($t){
	var $this = jQuery($t); $this.parent().parent().remove();
}
function reloadTooltip(){
	jQuery('[data-toggle="tooltip"]').tooltip({container: 'body'});
}

function nextFocus($t){
	var $this = jQuery($t);
	if($this.attr('data-next')){
		var $next = jQuery($this.attr('data-next'));
	}else{
		var $next = $this.next('input');
	}
	//console.log($next);
	$next.focus();
	return false;
}
function changeMenusType($t){
	
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'changeMenusType';
	$data['value'] = $this.val();
	$data['type_id'] = $this.val();
	if($this.val() != $this.attr('data-old')){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     	$d = parseJsonData(data);	     	
	    	$this.attr('data-old',$this.val());
	    	jQuery($this.attr('data-target2')).attr('data-type_id',$this.val());
	    	jQuery($this.attr('data-target')).html($d.html).trigger("chosen:updated");
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
	}
} 
  
function addSelectedMenusCategory($t){
	var $this = jQuery($t);
	jQuery($this.attr('data-target')).attr('data-existed',$this.val())
}
function change_date_range_from_day($t){
	var $this = jQuery($t);
	var $data = {};
	$data['action'] =  'change_date_range_from_day';
	$data['from_date'] = jQuery('#inpput_from_date').val();
	$data['day'] = jQuery('#input-day-amount').val();
	$data['night'] = jQuery('#input-night-amount').val();
	if($data['from_date'] != ""){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    //show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	     	$d = parseJsonData(data);	     	
	    	 jQuery('#input_todate').val($d.html)
	    },
	    complete:function(){
	    	//show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	});
	}
}


function Tourprogram_ReloadAllPrice($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'Tourprogram_ReloadAllPrice';
	$data['guest'] = jQuery('#input-tour-sokhach').val();
	$data['value'] = $this.val();
	if($this.attr('data-old') != $this.val()){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    show_left_small_loading('show'); 
	    },
	    success: function (data) {	    	  
	    	$d = parseJsonData(data);	
	     	$this.attr('data-old',$this.val());    	
	     	reloadAutoPlayFunction();
	     	//console.log(data);
	     	//jQuery('.ajax-load-time-detail').html($d.html);
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	}); 
	}
}

function genTourCode(t){
	$this = jQuery(t);
	//$target = $this.attr('data-target');
	$cusID = jQuery('#inputCustomerID').val();
	$start = jQuery('#inputStarttime').val();
	$end = jQuery('#inputEndtime').val();
	$in = jQuery('#inputDateIN').val();
	$out = jQuery('#inputDateOUT').val();
	$id = jQuery('#inputTourID').val();
	$code = jQuery('#inputTourCode');
	//alert($start);
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'genTourCode',cusID:$cusID,pIN:$in,pOUT:$out,start:$start,end:$end,id:$id},
	      beforeSend:function(){
 
	      },
	      success: function (data) {
	    	  $code.val(data)
	         // if(data != ""){
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          //}
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
} 
 
function quickGetAutoVehicleAjax($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'quickGetAutoVehicleAjax';
	var $target = $this.attr('data-target'); 
	if($this.attr('data-old') != $this.val()){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    show_left_small_loading('show'); 
	    },
	    success: function (data) {	   
	    	//console.log($target)
	    	$d = parseJsonData(data);	
	    	jQuery($d.remove_item).remove();
	     	jQuery($target).html($d.html);   
	     	
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	}); 
	}
}


function set_default_item_filter_value($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'set_default_item_filter_value';
	var $target = $this.attr('data-target'); 
	if($this.attr('data-old') != $this.val()){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){
	    	//show_left_small_loading('show'); 
	    },
	    success: function (data) {	   
	    	//console.log($target)
	    	$d = parseJsonData(data);
	    	if($d.callback){
	    		  eval($d.callback_function);
	    	  }  
	    	//jQuery($d.remove_item).remove();
	     	//jQuery($target).html($d.html);   
	     	
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {
	           
		}
	}); 
	}
}

function changeLiveChatVendor($t){
	var $this = jQuery($t);
	var $target = jQuery('.livechat-ajax-result');
	$html = '';
	switch($this.val()){
	case 'facebook':
		$html += '<div class="form-group"><div class="col-sm-12">';
		$html += '<input name="new[title]" class="form-control input-required-first input-sm required" placeholder="Tiêu đề" />';
		$html += '</div></div>';
		$html += '<div class="form-group"><div class="col-sm-12">';
		$html += '<input name="new[fanpage]" class="form-control input-sm required" placeholder="Link fanpage" />';
		$html += '</div></div>';
		//$html += '<div class="form-group"><div class="col-sm-12">';
		//$html += '<input name="new[title]" class="form-control input-required-first input-sm required" placeholder="Tiêu đề" />';
		//$html += '</div></div>';
		break;
	default:
		$html += '<div class="form-group"><div class="col-sm-12">';
		$html += '<input name="new[title]" class="form-control input-required-first input-sm required" placeholder="Tiêu đề" />';
		$html += '</div></div>';
		$html += '<div class="form-group"><div class="col-sm-12">';
		$html += '<textarea name="new[embed_code]" class="form-control input-seo required" id="inputlivechat" placeholder="Mã nhúng" ></textarea>';
		$html += '</div></div>';
		break;
	}
	$target.html($html);
	$target.find('.input-required-first').val($this.val()).focus();
	if($this.val() != ""){
		jQuery('.input-button-required-checked').removeAttr('disabled')
	}else{
		jQuery('.input-button-required-checked').attr('disabled','')
	}
	return false;
}



function selectTourCategory($t){
	var $this = jQuery($t);
	jQuery('.btn-tour-category').attr('data-category_id',$this.val())
}
function quick_search_filters_detail($t){
	var $this = jQuery($t);
}

function call_auto_upload_image_function($t){
	var $this = jQuery($t);
	$this.parent().parent().parent().find('.btn-submit-upload').click();
}


function loadCodeMirror(){	
	jQuery('.CodeMirror').each(function(i, el){
		if(!jQuery(el).attr('data-loaded')){
	    el.CodeMirror.refresh();
	    el.CodeMirror.focus();
	    jQuery(el).attr('data-loaded',true);
		}
	});
}

function set_tab_open($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $target = jQuery($data['target']);
	$target.find('.input-tab-open').remove();
	$target.append('<input type="hidden" value="'+$data['tab_id']+'" name="tab_open"/>');
	
}


function updateItemCode($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] =  'updateItemCode';
	var $target = $this.attr('data-target'); 
	if($this.attr('data-old') != $this.val()){
	jQuery.ajax({
	    type: 'post',
	    datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
	    data: $data,
	    beforeSend:function(){ 
	    },
	    success: function (data) {	   	    	
	    	$d = parseJsonData(data);
	    	if($d.callback){
	    		  eval($d.callback_function);
	    	}
	    },
	    complete:function(){
	    	show_left_small_loading('hide');   
	    },
	    error : function(err, req) {          
		}
	}); 
	}
}

function loadCkeditorFull(){
	jQuery('.ckeditor_full2').each(function(i,e){
		var $this = jQuery(e);
		var $id = $this.attr('id') ? $this.attr('id') : randString(8);
   	 	var $width = parseInt(jQuery(e).attr('data-width'));
   	 	var  $height = parseInt(jQuery(e).attr('data-height'));
   	 	var $expand = jQuery(e).attr('data-expand') ? jQuery(e).attr('data-expand') : true;
   	 	if($expand == 'false' || $expand == '0'){
   	 		$expand = false;
   	 	}
   	 		
       $expand = $expand == 'false' ? false : true;
       editor  = CKEDITOR.replace( $id, {
            width:$width, height:$height,
            toolbar:  'Full',
            toolbarStartupExpanded : $expand,
            filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
            filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
            filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
            filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
            filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
            filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
           });
    })
    .removeClass('ckeditor_full2');
}

function loadCkeditorBasic4(){
	jQuery('.ckeditor_basic4').each(function(i,e){
		var $this = jQuery(e);
   	 	var $id = $this.attr('id') ? $this.attr('id') : randString(8);
   	 	var $data = {};   	 	   	 
   	 	var  $width = parseInt($this.attr('data-width'));
   	 	var  $height = parseInt($this.attr('data-height'));
   	 	if($width > 0){
   	 		$data['width'] = $width;
   	 	}
   	 	if($height > 0){
	 		$data['height'] = $height;
	 	}
   	 	$data['toolbar'] = [
				 { name: 'document', items: [ 'Source'] },
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize'] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
				 { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },	
             ] ;
   	 	$data['toolbarStartupExpanded'] = true;
   	 
   	 	CKEDITOR.replace( $id, $data);
   	 	$this.removeClass('ckeditor_basic4');
    });
} 

function Ad_SubmitFormSearch($t){
	var $this = jQuery($t);
	var $advanced = jQuery('.dropdown-menu-quick-search-advanced');
	var $s = $advanced.is(':visible');
	if($s){
		var $form = jQuery('.quick-search-form-advanced');
	}else{
		var $form = jQuery('.form-quick-search-text');
	}
	//
	$form.find('input,textarea,select').each(function(i,e){
		var $e = jQuery(e);
		if($e.val() == ""){
			$e.attr('disabled','');
		}
	});
	//
	$form.submit();
}

function toggle_tr_hidden($t){
	var $this = jQuery($t);
	var $pr = $this.parent().parent().parent().parent();
	var $item = $pr.find('.tr-hidden');
	if($this.is(':checked')){
		$item.removeClass('hide').show();
	}else{
		$item.addClass('hide').hide();
	}
}

function Product_detail_change_price_type($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	jQuery('.tb-price-list-type').hide();
	jQuery('.tb-price-list-type-'+$this.val()).show();
	//console.log($data);
}
function Quick_search_menu_food($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $selected_input = jQuery('.selected_value_'+$data.supplier_id+'_'+$data.day_id+'_'+$data.time_id);
	$data['existed'] = $selected_input.map(
	        function(){return this.value;}).get().join(",");
	$data.value = ($this.val());
	sentAjaxData($data);
}

function Product_detail_add_price_range($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var index = parseInt($data.index);
	++index;
	$this.attr('data-index',index);
	var $from = 1;
	if(index>1){
		var j = jQuery('.tr-user-price-range-'+(index-1)).find('input[data-field=to_quantity]').val();
		
		if(parseInt(j)>0){
			$from = parseInt(j)+1;
		}else{
			j = jQuery('.tr-user-price-range-'+(index-1)).find('input[data-field=from_quantity]').val();
			$from = parseInt(j)+1;
		}
	}
	var $html = '';
	$html += '<tr class="tr-user-price-range tr-user-price-range-'+index+'"><td>Khoảng giá '+(index)+'</td>';
    $html += '<td class=""><input name="price_range['+(index-1)+'][from_quantity]" data-field="from_quantity" required '+(index == 1 ? '' : 'readonly')+' type="text" class="center required form-control input-sm bold number-format" value="'+$from+'" placeholder="Từ (SP)"/></td>\
         	<td class=" "><input name="price_range['+(index-1)+'][to_quantity]" onblur="Product_detail_add_price_range2(this);" onchange="Product_detail_add_price_range2(this);" data-field="to_quantity" required type="text" class="center form-control input-sm bold number-format required" value="" placeholder="Đến (SP)"/></td>	\
         	<td class="center "><input name="price_range['+(index-1)+'][price]" required type="text" class="form-control input-sm aright bold red required number-format bold input-currency-price-00" value="" placeholder="Giá sản phẩm"/></td>\
    		<th class="center"><i class="fa fa-trash hover-red pointer" onclick="removeTrItem(this);"></i></th> \
         	</tr>';
    jQuery($data.target).append($html);     	
    jQuery('.number-format').number(true);
}

function Product_detail_add_price_range2($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $target1 = $this.parent().parent().find('input[data-field=from_quantity]');	
	var $target = $this.parent().parent().next().find('input[data-field=from_quantity]');	
	var $val = parseInt($this.val());
	var $val1 = parseInt($target1.val());
	if($val1 < $val){
		$target.val($val+1);
	}else{
		$this.val($val1);
		$target.val($val1+1);
	}
	
}









































