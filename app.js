// App main config
require.config({
	baseUrl: $cfg.baseUrl+'themes/admin/js',
	//map: {
	      // '*' means all modules will get 'jquery-private'
	//      // for their 'jquery' dependency.
	//      '*': { 'jquery': 'jquery-private' },
	 
	      // 'jquery-private' wants the real jQuery module
	      // though. If this line was not here, there would
	      // be an unresolvable cyclic dependency.
	 //     'jquery-private': { 'jquery': 'jquery' }
	 //   },
	shim: {
		
		underscore: {
            exports: '_'
        },
        backbone: {
			deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        raphael: {
        	deps: ['jquery', 'backbone', 'underscore' ]
        },
                
		switchButton: {
			deps: [
				
				'jquery','jui',
			]
		},
		btag: {
			deps: [
				'jquery',		
			]
		},
		bdatetime: {
			deps: [
				'blocal',
			]
		},
		shielui: {
			deps: [
				'jquery',
			]
		},
		bconfirm: {
			deps: [
				'btooltip',
			]
		},
		btooltip: {
			deps: [
				'jquery',
			]
		},
		superfish: {
			deps: [
			      'jquery', 
				'superfishHover',
			]
		},
		lazyload: {
			deps: [
				'jquery',
			]
		},
		jnumber: {
			deps: [
				'jquery',
			]
		},
		chosenjs: {
			deps: [
				'jquery',
			]
		},
		jmg: {
			deps: [
				'jquery',
			]
		},
		smartresize: {
			deps: [
				'jquery',
			]
		},
		chosenajax: {
			deps: [
				'chosenjs',
			]
		},	
 
		flot: {
            deps: ['jquery','Chart'],
            exports: '$.plot'
        },
		curvedLines: {
			deps: [
			       	'flot',
 
			]
		},
		morris: {
			deps: [ 
				'raphael','sparkline' 				
			]
		},
		bapp: {
			deps: [ 
				'bconfirm', 				
			]
		},
		
		
	},
	paths: {
		jquery: 'https://code.jquery.com/jquery-3.1.1.min',
		//jmg:"https://code.jquery.com/jquery-migrate-git.min",
		jstree: $cfg.libsDir+'/jstree/dist/jstree.min',
		//jquery:'//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min',
		//eve: $cfg.libsDir+'/vendors/eve/eve',		
		backbone: 'backbone/backbone-min',
		//application:'application', 
		underscore: 'underscore/underscore-min',
		
		//jnumber:'jquery.number.min',
		//jui:$cfg.libsDir+"/themes/js/jquery-ui.min",
		//switchButton: $cfg.libsDir+"/onoff/jquery.switchButton",
		//superfishHover:$cfg.libsDir+"/menu/superfish-1.7.4/src/js/hoverIntent",
	    //superfish:$cfg.libsDir+"/menu/superfish-1.7.4/src/js/superfish",
	    scrolls:$cfg.libsDir+"/scrolls/simplyscroll2.0.5/jquery.simplyscroll",
	    //bjs:$cfg.libsDir+"/bootstrap/3.3.6/js/bootstrap.min",
	    btag:$cfg.libsDir+"/bootstrap/tagsinput/dist/bootstrap-tagsinput.min",
	    bapp : $cfg.libsDir+'/bootstrap/assets/application',
	    btooltip : $cfg.libsDir+"/bootstrap/assets/js/bootstrap-tooltip",
	    bconfirm : $cfg.libsDir+"/bootstrap/assets/js/bootstrap-confirmation",
	    //bmoment : $cfg.libsDir+"/bootstrap/assets/js/moment.min",	       
	    //dateFormat : $cfg.libsDir+"/themes/js/dateFormat",
	    bdatetime : $cfg.libsDir+"/bootstrap/datetime/bootstrap-datetimepicker",
	    blocal : $cfg.libsDir+"/bootstrap/datetime/moment-with-locales",
	    bcolor : $cfg.libsDir+"/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.min",
	   // bbug : $cfg.libsDir+"/bootstrap/3.2.0/js/ie10-viewport-bug-workaround",
	    lazyload:$cfg.libsDir+"/lazyload",
	    modernizr:$cfg.libsDir+"/modernizr",	    
	    cjs : $cfg.libsDir+"/themes/js/cjs",
	    modal : $cfg.libsDir+"/themes/js/modal",
	    select2:$cfg.libsDir+"/select2/dist/js/select2.full", 
	    chosenjs:$cfg.libsDir+"/chosen/chosen.jquery",
	    chosenajax:$cfg.libsDir+"/chosen/chosen.ajaxaddition.jquery",
	    ckeditor:$cfg.libsDir+"/ckeditor/ckeditor",
	    shielui:$cfg.libsDir+"/shieldui/js/shieldui-all.min", 
	    //base:$cfg.libsDir+"/themes/js/base",
	    // new
	    fastclick:$cfg.libsDir+"/vendors/fastclick/lib/fastclick",
	    nprogress:$cfg.libsDir+"/vendors/nprogress/nprogress",
	    Chart:$cfg.libsDir+"/vendors/Chart.js/dist/Chart.min",
	    sparkline:$cfg.libsDir+"/vendors/jquery-sparkline/dist/jquery.sparkline.min",
	    raphael:$cfg.libsDir+"/vendors/raphael/raphael.min",
	    //raphael:"http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min",
	    morris: $cfg.libsDir+"/vendors/morris.js/morris.min",
	    colorbox: $cfg.libsDir+"/popup/colorbox/jquery.colorbox_w_h",
	    clipboard: $cfg.libsDir+"/clipboard.js/dist/clipboard.min",
	    contextMenu: $cfg.libsDir+"/contextMenu/dist/jquery.contextMenu.min",
	    gauge:$cfg.libsDir+"/vendors/gauge.js/dist/gauge.min",
	    progressbar:$cfg.libsDir+"/vendors/bootstrap-progressbar/bootstrap-progressbar.min",
	    skycons:$cfg.libsDir+"/vendors/skycons/skycons",
	    flot:$cfg.libsDir+"/vendors/Flot/jquery.flot",
	    flot_pie:$cfg.libsDir+"/vendors/Flot/jquery.flot.pie",
	    flot_time:$cfg.libsDir+"/vendors/Flot/jquery.flot.time",
	    flot_stack:$cfg.libsDir+"/vendors/Flot/jquery.flot.stack",
	    flot_resize:$cfg.libsDir+"/vendors/Flot/jquery.flot.resize",
	    flotorderBars:"flot/jquery.flot.orderBars",
	    date:"flot/date",
	    flotspline:"flot/jquery.flot.spline",
	    curvedLines:"flot/curvedLines",
	   // momentmin:"moment/moment.min",
	    daterangepicker:"datepicker/daterangepicker",
	    smartresize:'smartresize'
	},
	priority: ['jquery','eve', 'raphael', 'underscore', 'backbone']
});

// Require main app viewer
require([
	//'order!jquery','order!raphael','order!underscore','order!backbone',
	'jquery','select2','jui',
	'base' ,'raphael','underscore','backbone', 'jstree',
	'jmg',	'jnumber',
	'bconfirm',
	'switchButton','superfishHover','superfish','scrolls',
	//'bjs',
	'bapp', 'btag', 'btooltip','colorbox',
	'contextMenu','clipboard',
	//'bmoment',
	'bdatetime','blocal','bcolor','bbug',
	'lazyload','modernizr',
	//'dateFormat',
	'cjs','modal','chosenjs','chosenajax',
	'ckeditor','shielui' ,'application','common',
	'fastclick','nprogress','Chart','sparkline','morris','gauge','progressbar','skycons',
	'flot',
	//'flot_pie','flot_time','flot_stack','flot_resize','flotorderBars',
	//'date',
	//'flotspline',
	'curvedLines',
	//'momentmin',
	'daterangepicker' ,'smartresize'
	 
	//'btooltip',
	//'app/todo/app'
], function($, Backbone, _, AppView){
	var editor;
	var CURRENT_URL = window.location.href.split('?')[0],
    $BODY = jQuery('body'),
    $MENU_TOGGLE = jQuery('#menu_toggle'),
    $SIDEBAR_MENU = jQuery('#sidebar-menu'),
    $SIDEBAR_FOOTER = jQuery('.sidebar-footer'),
    $LEFT_COL = jQuery('.left_col'),
    $RIGHT_COL = jQuery('.right_col'),
    $NAV_MENU = jQuery('.nav_menu'),
    $FOOTER = jQuery('footer');
	
    $href = window.location + "";
    $href = $href.split('#');
    if($href.length > 1){
      $href = '#' + $href[1];
      if(jQuery($href).length > 0){
        jQuery('.nav-tabs a[href="'+$href+'"]').tab('show')  ;
      }
    }

    jQuery('#resultsForm_checkall').click(function(event) {  //on click
        if(this.checked) { // check select status
            jQuery('.checked_item').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            jQuery('.checked_item').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });
    jQuery('a[role=tab]').click(function(){
      $href = jQuery(this).attr('href');
      //alert($href);
      jQuery('input.currentTab').val($href);

    });
    
    jQuery('[data-toggle="tooltip"]').tooltip({
    	delay: { "show": 600, "hide": 100 }
    });
 
    if(jQuery('.superfish').length>0){
        var ex = jQuery('.superfish').superfish({
        //add options here if required
        });
    }
    if(jQuery("input[type=checkbox].switchBtn").length>0){
    jQuery("input[type=checkbox].switchBtn").switchButton({
        labels_placement: "left"
    });
    }
    var $i=0;
    jQuery("input[type=checkbox].ckcAjaxChangeStatus").change(function(){
       if(jQuery(this).is(':checked')==true){
         $val = 1;
       }else{
         $val = 0;
       }
       $role = jQuery(this).attr('role');
       $href = $cfg.adminUrl + '/ajax'  ;
       //$table =jQuery(this).attr('data-table'); 
      //alert($role);
       $.ajax({
   			type: 'post',
   		 	datatype: 'json',
   			url: $href,
   			data:{action:'ckcAjaxChange',data:$role,val:$val},
             beforeSend: function() {
                 // setting a timeout
                jQuery(".alert-success").removeClass('hide').addClass('in loading');
                //jQuery(".alert-success").removeClass('hide ').addClass('in error alert-danger alert-dismissible') ;

                $i++;

             },
   			success: function(data) {
   				//alert(data);
   			     //jQuery('html').html(data)
                  // var $d = JSON.parse(data);
                  // if($d.status == true){

                 //  }

   				
   			},
   			error : function(err, req) {
   				jQuery(".alert-success").removeClass('loading').addClass('error alert-danger alert-dismissible') ;
                       window.setTimeout(function() {
                       jQuery(".alert-success").addClass('hide'); }, 1000);
   			},
             complete: function() {

                 $i--;
                 if ($i <= 0) {
                     jQuery(".alert-success").removeClass('loading') ;
                       window.setTimeout(function() {
                       jQuery(".alert-success").addClass('hide'); }, 1000);
                 }
             }
   		});
    });
    
    

    if(jQuery('[data-toggle="popover"]').length > 0){
     jQuery('[data-toggle="popover"]').popover()  ;
    }

    if(jQuery('a[rel=popover]').length > 0){
       jQuery('a[rel=popover]').popover({
         html: true,
         trigger: 'hover',
         content: function () {
           return '<img src="'+jQuery(this).data('img') + '" alt="" style="max-width: 600px;max-height:600px" />';
         }
       });
    }

   

   if(jQuery('.datetimepicker').length>0){
     jQuery('.datetimepicker').datetimepicker({
      //language:'vi',//
    	 format:'DD/MM/YYYY HH:mm',
     });
   }
   if(jQuery('.datepickeronly').length>0){
     jQuery('.datepickeronly').datetimepicker({
      //language:'vi',//dateFormat:'DD/MM/YYYY',
    	 format:'DD/MM/YYYY',
         //pickTime:false
     });
   }
   if(jQuery('.datepicker').length>0){
	     jQuery('.datepicker').datetimepicker({
	      //language:'vi',
	    	 format:'DD/MM/YYYY',
	      //pickTime:false,
	      //dateFormat:'DD/MM/YYYY'
	     });
	   }
   jQuery('.Ccolorpicker').each(function(i,e){
	 $format = jQuery(e).attr('data-format') ? jQuery(e).attr('data-format') : false;      
     jQuery(e).colorpicker({
    	 format:$format
     });
   });
        
	  
    var chosen_config = {
       '.chosen-select'           : {search_contains:true,case_sensitive_search:true},
       '.chosen-select-deselect'  : {allow_single_deselect:true,search_contains:true,case_sensitive_search:true},
       '.chosen-select-no-single' : {disable_search_threshold:10,search_contains:true,case_sensitive_search:true},
       '.chosen-select-no-results': {no_results_text:'Oops, nothing found!',search_contains:true,case_sensitive_search:true},
       '.chosen-select-width'     : {width:"95%",search_contains:true,case_sensitive_search:true}
     }
     for (var selector in chosen_config) {
       if(jQuery(selector).length>0){
         jQuery(selector).chosen(chosen_config[selector]);
       }
     }
     
     jQuery('select.ajax-chosen-select').each(function(index,element){
 	       
 	       jQuery(element).ajaxChosen({
         		   dataType: 'json',
         		   type: 'POST',
         		   data:{dtype:jQuery(element).attr('data-type'),action:'CHOSEN_AJAX',role:jQuery(element).attr('role') },
         		   url: $cfg.adminUrl + '/ajax/chosen_ajax' 
            },{
         		   loadingImg: $cfg.baseUrl+'/loading.gif'
            }); 
            
 	});
    
     jQuery('.numberFormat,.number-format').each(function(i,e){
    	 $x = jQuery(e).attr('data-decimal') ? jQuery(e).attr('data-decimal') : 0;
    	 jQuery(e).number( true,$x);
     });
         
          

      
     if(jQuery('.numberFormatx').length>0){
         jQuery('.numberFormatx').number( true,1);         
     }
     if(jQuery("img.lazy").length>0){
       jQuery("img.lazy").each(function(){
       jQuery(this).attr("data-original", jQuery(this).attr("src"));
     		jQuery(this).attr("src", $cfg.baseUrl+"/libs/pro_loading.gif");
       });
       jQuery("img.lazy").lazyload();

     }
     jQuery('a.addTab').click(function(){
       $this= jQuery(this);
       $c_type = $this.attr('data-c_type') ? true : false;
       $role = parseInt($this.attr('role')) + 1;
       $tab = 'detail-tab-'+$role;
       $this.parent().before('<li class="pr" role="presentation"><a href="#" class="delTab" onclick="delTab(\'#'+$tab+'\');">x</a><a href="#'+$tab+'"  role="tab" data-toggle="tab">Tab '+($role)+'</a></li>');
       $html = '<div role="tabpanel" class="tab-pane" id="'+$tab+'"><div class="p-content"><div class="row"><div class="col-sm-6"><div class="form-group"><label   class="col-sm-2 control-label">Tiêu đề</label><div class="col-sm-10"><input type="text" name="c_title[]" class="form-control" id="inputTitleTab'+$role+'" placeholder="Title" value="Tab '+$role+'" />  </div> </div>';
       
       $html += $c_type ? '<div class="form-group">'+(jQuery('.group-form-style').html())+'</div>' : '';
       
       $html += '</div><div class="col-sm-6"></div><div class="col-sm-10"><div class="form-group"><div class="col-sm-10"><textarea  name="c_detail[]" class="form-control" id="ckc_'+$tab+'"  ></textarea>  </div> </div></div></div></div></div>';
     jQuery('#append-tabs').append($html);
     jQuery('a[href="#'+$tab+'"]').tab('show');
       $this.attr('role',$role);
       editor = CKEDITOR.replace('ckc_'+$tab,{
         height:400,
         filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
         filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
         filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
         filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
         filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
         filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

       });
       jQuery('#inputTitleTab'+$role).select();

       return false;
     }) ;
     
     jQuery('.Breadcrumb li:last-child').prev().addClass('SecondLast');
     
     jQuery('.delete_image_article').click(function(){
        $this = jQuery(this);
        $target = $this.attr('data-target');
        //alert($target);
        jQuery($target).remove();
     });
     
     jQuery(".select2").select2({
     	language: "vi"
     });
     jQuery(".select2-hide-search").select2({
     	  minimumResultsForSearch: Infinity
     });
     $.fn.select2.amd.require([
                               "select2/core",
                               "select2/utils",
                               "select2/compat/matcher"
                             ], function (Select2, Utils, oldMatcher) {
                              

                               //var $ajax = jQuery(".js-select-data-ajax");
                              
                                

                               function formatRepo (repo) {
                                 if (repo.loading) return repo.text;

                                 var markup = "<div class='select2-result-repository clearfix'>" +
                                  // "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
                                   "<div class='select2-result-repository__meta'>" +
                                     "<div class='select2-result-repository__title'>" + repo.text + "</div>";

                                 if (repo.description) {
                                   markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                                 }

                                 markup += '';//+ //"<div class='select2-result-repository__statistics'>" +
                                   //"<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
                                   //"<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
                                   //"<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                                // "</div>" +
                                 markup += "</div></div>";

                                 return markup;
                               }

                               function formatRepoSelection (repo) {
                                 return repo.full_name || repo.text;
                               }
                               jQuery('select.js-select-data-ajax').each(function(index,element){
                               jQuery(element).select2({
                             	  language: "vi",
                                 ajax: {
                                   url: $cfg.adminUrl + '/ajax/select2_ajax' ,
                                   dataType: 'json',
                                   delay: 250,
                                   type:'POST',
                                   data: function (params) {
                                     return {
                                       q: params.term, // search term
                                       role:jQuery(element).attr('data-role') ? jQuery(element).attr('data-role') : jQuery(element).attr('role'),
                                       page: params.page,
                                       groupID:jQuery(element).attr('data-groupID'),
                                       type:jQuery(element).attr('data-type'),
                                       filterID:jQuery(element).attr('data-filterID'),
                                     };
                                   },
                                   processResults: function (data) {
                                	   //alert(data)
                                       // parse the results into the format expected by Select2.
                                       // since we are using custom formatting functions we do not need to
                                       // alter the remote JSON data
                                       return {
                                           results: data
                                       };
                                   },
                                   cache: true
                                 },
                                // tags: true,
                                 cache: true,
                                 escapeMarkup: function (markup) { return markup; },
                                 minimumInputLength: 1,
                                 templateResult: formatRepo,
                                // templateSelection: formatRepoSelection
                               });
                               });
                            
                                
                             });
     $h = jQuery(window).height()-205;
     jQuery('.cxd_tabs_auto_height').height($h);
     jQuery('.tab-content>.tab-panel').hide();
     jQuery('.tab-content>.tab-panel.active').show();
     
     jQuery('.nav-tabs li > a').on('click',function(){
    	$this = jQuery(this);
     	$id = $this.attr('href');
     	$link_target = $this.attr('rel') ? $this.attr('rel') : false;
     	if($link_target != 'link_target' && jQuery($id).length>0){
     		$pr = $this.parent().parent().parent();
     		$pr.find('.tab-content>.tab-panel').hide();
     		jQuery($id).show();
     	}
     	
     });
      
     jQuery(".autocomplete_cost").each(function(i,e){
     	$type = jQuery(e).attr('data-type') ? jQuery(e).attr('data-type') : 1;
     	$ctype = jQuery(e).attr('data-ctype') ? jQuery(e).attr('data-ctype') : 'NOR';
     	
     	jQuery(e).autocomplete({
 	        source: $cfg.cBaseUrl+"/ajax?action=autocomplete&type="+$type+"&ctype="+$ctype,
 	        minLength: 2,
 	        width			: 481,
 			delay			: 150,
 			scroll		: false,
 			max			: 13,
 			selectFirst	: false,
 	 
 	        select: function(event, ui) {
 	        	$this = jQuery(e);
 	            var $i = ui.item.item;
 	            $tr = $this.parent().parent();
 	            $c = parseInt($tr.find('.sl-cost-count').val());
 	            $a = parseInt($tr.find('.sl-cost-amount').val());
 	            $p = $tr.find('.sl-cost-price');
 	            $t = $tr.find('.sl-cost-total');
 	            //alert($a)
 	            $p.val($c*$a*$i.price);
 	            calculationTourCost();
 	        },
 	        appendTo:'.ac_results',
 	        html: true, // optional (jquery.ui.autocomplete.html.js required)
 	
 		    // optional (if other layers overlap autocomplete list)
 	        open: function(event, ui) {
 	            jQuery(".ui-autocomplete").css("z-index", 1000);
 	        }
     	});
     });
     
     

     var proto = $.ui.autocomplete.prototype,
     	initSource = proto._initSource;

     function filter( array, term ) {
     	var matcher = new RegExp( $.ui.autocomplete.escapeRegex(term), "i" );
     	return $.grep( array, function(value) {
     		return matcher.test( jQuery( "<div>" ).html( value.label || value.value || value ).text() );
     	});
     }



     $.extend( proto, {
     	_initSource: function() {
     		if ( this.options.html && $.isArray(this.options.source) ) {
     			this.source = function( request, response ) {
     				response( filter( this.options.source, request.term ) );
     			};
     		} else {
     			initSource.call( this );
     		}
     	},

     	_renderItem: function( ul, item) {
     		return jQuery( "<li></li>" )
     			.data( "item.autocomplete", item )
     			.append( jQuery('<a></a>')[ this.options.html ? "html" : "text" ]( item.label ) )
     			.appendTo( ul );
     	}
     });
    // jQuery('.priceTooltip').popover('hide');
     jQuery('.priceTooltip').each(function(i,e){
     	//alert(jQuery(e).attr('data-role'));
     	
     	$r = JSON.parse(jQuery(e).attr('data-role'));
     	$content = '<table class="table table-bordered">';
     	$content += '<tr><td>Phí tổ chức:</td><td class="aright">'+($r.exCost)+'</td></tr>';
     	$content += '<tr><td>Giá NET:</td><td class="aright">'+($r.netPrice)+'</td></tr>';
     	$content += '</table>';
     	jQuery(e).popover({
 	    	html:true, // trigger:'click',
 	    	content:$content,
 	    	title:'Chi phí bao gồm',
 	    	template:'<div class="popover popover-lager" role="tooltip"><div class="arrow"></div><div class="pr"><h3 class="popover-title"></h3><span class="close_popover" onclick="jQuery(\'.priceTooltip\').popover(\'hide\');">x</span></div><div class="popover-content">'+$content+'</div></div>'
     	});
     	
     })
     
    // alert('s');
     $x1 = '';$ar = [0,0,0];$x2='';$x3='';
     jQuery('#localxxx').find('tr').each(function(i,e){
     	
     	jQuery(e).find('p').each(function($i,$e){
     		 $t = (jQuery($e).html() );
     		 switch($i){
     		 case 0:
     			 $x1 += $t;
     			 break;
     		 case 1:
     			 $x2 += $t+';';
     			 break;
     		 case 2:
     			 $x3 += $t+';';
     			 break;
     		 }
     	});
     	//alert($tr.html);
     	
     });
     jQuery('.ajaxLoadGrid').each(function(i,e){
    	 //
    	 $this = jQuery(e);
    	 $target = $this.attr('data-target');
    	 $data = $this.attr('data-data');
     });
     
     jQuery('.hotelPriceGrid').each(function(i,e){
    	 //
    	 $this = jQuery(e);
    	 $target = $this.attr('id');
    	 $data = $this.attr('data-data');
    	// alert($target)
    	 jQuery('#'+$target).shieldGrid({
    	        dataSource: {
    	            data: $hotelPriceGrid
    	        },
    	        
    	        columns: [
    	            { field: "id", width: "40px", title: "STT" },
    	            { field: "room_id", title: "Phòng" },
    	            
    	            { field: "type",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Loại khách" , width: "100px" },
    	            { field: "pmin",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Từ (khách)" , width: "150px" }, 
    	            { field: "pmax",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Đến (khách)" , width: "150px" }, 
    	            { field: "price1",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Giá ngày thường" , width: "150px" },
    	            { field: "price2",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Giá cuối tuần" , width: "150px" },
    	            { field: "price3",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Giá ngày lễ" , width: "150px" },
    	            { field: "action", title: "Thao tác", width: "80px",headerAttributes: { style: "text-align: center;" } }
    	        ],
    	        altRows: false,
    	        rowHover: false,
    	         
    	    }); 
    	 jQuery('.numberFormat,.number-format').each(function(i,e){
    		 $d = jQuery(e).attr('data-decimal') ? jQuery(e).attr('data-decimal') : 0; 
    		 jQuery(e).number( true,$d); 
    	 });
    	 reload_app('select2');
     });
     jQuery('.carsPriceGrid').each(function(i,e){
    	 //
    	 $this = jQuery(e);
    	 $target = $this.attr('id');
    	 $data = $this.attr('data-data');
    	// alert($target)
    	 jQuery('#'+$target).shieldGrid({
    	        dataSource: {
    	            data: $carsPriceGrid
    	        },
    	        
    	        columns: [
    	            { field: "id", width: "40px", title: "STT" },
    	            { field: "vehicle_id", title: "Xe" },    	            	                	           
    	            
    	            { field: "pmin",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Từ (km)" , width: "150px" }, 
    	            { field: "pmax",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Đến (km)" , width: "150px" }, 
    	            { field: "price1",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Giá ngày thường /km" , width: "200px" },
    	            { field: "price2",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Giá cuối tuần /km" , width: "200px" },
    	            { field: "price3",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Giá ngày lễ /km" , width: "200px" },
    	            { field: "action", title: "Thao tác", width: "80px",headerAttributes: { style: "text-align: center;" } }
    	        ],
    	        altRows: false,
    	        rowHover: false,
    	         
    	    }); 
    	 reload_app('select2');reload_app('number-format');
    	  
     });
     $w = jQuery(window).width(); 
     if($w<1024){
    	 jQuery('.col8respon').removeClass('col-sm-6')
    	 .removeClass('col-sm-8')
    	 .removeClass('col-sm-10')
    	 .addClass('col-sm-12');
     }

     jQuery('.ckeditor_basic1').each(function(i,e){
         $id = jQuery(e).attr('id');
         $width = parseInt(jQuery(e).attr('data-width'));
         $height = parseInt(jQuery(e).attr('data-height'));
         CKEDITOR.replace( $id, {
             width:$width, height:$height,
              toolbar: [
                  { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                  { name: 'styles', items : [ 'Font','FontSize' ] },	 
                  { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                  { name: 'links', items : [ 'Link','Unlink' ] },
              ] 
              });
      });
      jQuery('.ckeditor_basic2').each(function(i,e){
         $id = jQuery(e).attr('id');
         $width = parseInt(jQuery(e).attr('data-width'));
         $height = parseInt(jQuery(e).attr('data-height'));
         CKEDITOR.replace( $id, {
             width:$width, height:$height,
              toolbar: [
                  { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                  { name: 'styles', items : [ 'Font','FontSize' ] },	 
                  { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                  { name: 'links', items : [ 'Link','Unlink' ] },
              ],
              toolbarStartupExpanded : false
              });
      });
      jQuery('.ckeditor_basic3').each(function(i,e){
         $id = jQuery(e).attr('id');
         $width = parseInt(jQuery(e).attr('data-width'));
         $height = parseInt(jQuery(e).attr('data-height'));
         CKEDITOR.replace( $id, {
             width:$width, height:$height,
              toolbar: [
                  { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                  { name: 'styles', items : [ 'Font','FontSize' ] },	 
                  { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                  { name: 'links', items : [ 'Link','Unlink' ] },
                  { name: 'insert', items: [ 'Image',  'SpecialChar' ] },
              ],
              toolbarStartupExpanded : false
              });
      });
      jQuery('.ckeditor_full').each(function(i,e){
         $id = jQuery(e).attr('id');
         $width = parseInt(jQuery(e).attr('data-width'));
         $height = parseInt(jQuery(e).attr('data-height'));
         $expand = jQuery(e).attr('data-expand') ? jQuery(e).attr('data-expand') : true;
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
      });
      jQuery('input.used_editor_for_info').each(function(i,e){
          $role = jQuery(e).attr('role');
          $checked = jQuery(e).is(':checked') ;
          $width = parseInt(jQuery(e).attr('data-width'));
          $height = parseInt(jQuery(e).attr('data-height'));
          if($checked){
             //alert($role);
             editor = CKEDITOR.replace( $role, {
              width:$width, height:$height,
              toolbar: [
                  { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                  { name: 'styles', items : [ 'Font','FontSize' ] },	 
                  { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                  { name: 'links', items : [ 'Link','Unlink' ] },
                  { name: 'insert', items: [ 'Image',  'SpecialChar' ] },
              ],
               
              filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
              filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
		filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
		filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
		filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
		filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
             });
          } 
          
      });
      jQuery('input.used_editor_for_info').change(function(){
         $this = jQuery(this); $role = $this.attr('role');
         $checked = $this.is(':checked') ;
         $width = parseInt($this.attr('data-width'));
         $height = parseInt($this.attr('data-height'));
         if($checked){
             //alert($role);
             editor = CKEDITOR.replace( $role, {
              width:$width, height:$height,
              toolbar: [
                  { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                  { name: 'styles', items : [ 'Font','FontSize' ] },	 
                  { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                  { name: 'links', items : [ 'Link','Unlink' ] },
                  { name: 'insert', items: [ 'Image',  'SpecialChar' ] },
              ],
               
              filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
              filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
		filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
		filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
		filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
		filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
             });
         }else{
             editor.destroy();

         }
      });
      
      
     $header = jQuery('.list-btn');
  	if($header.length>0){
  	 $body = jQuery('body');
  	 var headerOffset = $header.offset().top;
      jQuery(window).scroll(function(){
          
          $window = jQuery(this);
         
          if ($window.scrollTop() > headerOffset) {
          	// jQuery('.titlexpro').html($window.scrollTop() + ' / ' + headerOffset)
              //if ($body.hasClass('device-lg') || $body.hasClass('device-md')) {

                  //if (!$header.hasClass("header-no-sticky")) {
                      $header.addClass('header-sticky'); 
                 // }
                   
              //} else {
              //    $header.removeClass('header-sticky');
              //}
          } else {
              $header.removeClass('header-sticky');
          }
      });
      
  	}
  	
  	jQuery('.tagsinput').each(function(i,e){
  		jQuery(e).tagsinput();
  		
  	});
  	
    ////////////////////////////////////////////////////////////

    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', jQuery(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? 0 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    $SIDEBAR_MENU.find('a').on('click', function(ev) {
        var $li = jQuery(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            jQuery('ul:first', $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').slideUp();
            }
            
            $li.addClass('active');

            jQuery('ul:first', $li).slideDown(function() {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu
    $MENU_TOGGLE.on('click', function() {
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }

        $BODY.toggleClass('nav-md nav-sm');

        setContentHeight();
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    jQuery(window).smartresize(function(){  
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        jQuery('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel:{ preventDefault: true }
        });
    }
    ////

    jQuery('.collapse-link').on('click', function() {
        var $BOX_PANEL = jQuery(this).closest('.x_panel'),
            $ICON = jQuery(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content');
        
        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function(){
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200); 
            $BOX_PANEL.css('height', 'auto');  
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    jQuery('.close-link').click(function () {
        var $BOX_PANEL = jQuery(this).closest('.x_panel');

        $BOX_PANEL.remove();
    });
    if (jQuery(".progress .progress-bar")[0]) {
        jQuery('.progress .progress-bar').progressbar();
    }
    if (jQuery(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }
    if (jQuery("input.flat")[0]) {
        jQuery(document).ready(function () {
            jQuery('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
    ///
    
    jQuery('table input').on('ifChecked', function () {
        checkState = '';
        jQuery(this).parent().parent().parent().addClass('selected');
        countChecked();
    });
    jQuery('table input').on('ifUnchecked', function () {
        checkState = '';
        jQuery(this).parent().parent().parent().removeClass('selected');
        countChecked();
    });

    var checkState = '';

    jQuery('.bulk_action input').on('ifChecked', function () {
        checkState = '';
        jQuery(this).parent().parent().parent().addClass('selected');
        countChecked();
    });
    jQuery('.bulk_action input').on('ifUnchecked', function () {
        checkState = '';
        jQuery(this).parent().parent().parent().removeClass('selected');
        countChecked();
    });
    jQuery('.bulk_action input#check-all').on('ifChecked', function () {
        checkState = 'all';
        countChecked();
    });
    jQuery('.bulk_action input#check-all').on('ifUnchecked', function () {
        checkState = 'none';
        countChecked();
    });

    function countChecked() {
        if (checkState === 'all') {
            jQuery(".bulk_action input[name='table_records']").iCheck('check');
        }
        if (checkState === 'none') {
            jQuery(".bulk_action input[name='table_records']").iCheck('uncheck');
        }

        var checkCount = jQuery(".bulk_action input[name='table_records']:checked").length;

        if (checkCount) {
            jQuery('.column-title').hide();
            jQuery('.bulk-actions').show();
            jQuery('.action-cnt').html(checkCount + ' Records Selected');
        } else {
            jQuery('.column-title').show();
            jQuery('.bulk-actions').hide();
        }
    }

    jQuery(".expand").on("click", function () {
        jQuery(this).next().slideToggle(200);
        $expand = jQuery(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });

 // NProgress
 if (typeof NProgress != 'undefined') {
     jQuery(document).ready(function () {
         NProgress.start();
     });

     jQuery(window).load(function () {
         NProgress.done();
     });
 }
    
 //////////////////////////////////
 if(jQuery("#placeholder3xx3").length>0){
 var d1 = [
 
           [1, 9],
           [2, 6],
           [3, 10],
           [4, 5],
           [5, 17],
           [6, 6],
           [7, 10],
         
           
          
           
         ];

         //flot options
         var options = {
           series: {
             curvedLines: {
               apply: true,
               active: true,
               monotonicFit: true
             }
           },
           colors: ["#26B99A"],
           grid: {
             borderWidth: {
               top: 0,
               right: 0,
               bottom: 1,
               left: 1
             },
             borderColor: {
               bottom: "#7F8790",
               left: "#7F8790"
             }
           }
         };
         var plot = $.plot(jQuery("#placeholder3xx3"), [{
           label: "Registrations",
           data: d1,
           lines: {
             fillColor: "rgba(150, 202, 89, 0.12)"
           }, //#96CA59 rgba(150, 202, 89, 0.42)
           points: {
             fillColor: "#fff"
           }
         }], options);
 }
 if(jQuery(".sparkline_one").length>0){
	 jQuery(".sparkline_one").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
         type: 'bar',
         height: '40',
         barWidth: 9,
         colorMap: {
           '7': '#a1a1a1'
         },
         barSpacing: 2,
         barColor: '#26B99A'
       });
 }
if(jQuery(".sparkline_two").length>0){
	jQuery(".sparkline_two").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
        type: 'line',
        width: '200',
        height: '40',
        lineColor: '#26B99A',
        fillColor: 'rgba(223, 223, 223, 0.57)',
        lineWidth: 2,
        spotColor: '#26B99A',
        minSpotColor: '#26B99A'
      });
 }

if(jQuery("#canvas1").length>0){
	var options = {
	          legend: false,
	          responsive: false
	        };

	        new Chart(document.getElementById("canvas1"), {
	          type: 'doughnut',
	          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
	          data: {
	            labels: [
	              "Symbian",
	              "Blackberry",
	              "Other",
	              "Android",
	              "IOS"
	            ],
	            datasets: [{
	              data: [15, 20, 30, 10, 30],
	              backgroundColor: [
	                "#BDC3C7",
	                "#9B59B6",
	                "#E74C3C",
	                "#26B99A",
	                "#3498DB"
	              ],
	              hoverBackgroundColor: [
	                "#CFD4D8",
	                "#B370CF",
	                "#E95E4F",
	                "#36CAAB",
	                "#49A9EA"
	              ]
	            }]
	          },
	          options: options
	        });
	
}
if(jQuery("#reportrangea").length>0){
	var cb = function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        jQuery('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      };

      var optionSet1 = {
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        minDate: '01/01/2015',
        maxDate: '12/31/2016',
        dateLimit: {
          days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
          applyLabel: 'Submit',
          cancelLabel: 'Clear',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom',
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          firstDay: 1
        }
      };
      jQuery('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
      jQuery('#reportrange').daterangepicker(optionSet1, cb);
      jQuery('#reportrange').on('show.daterangepicker', function() {
        console.log("show event fired");
      });
      jQuery('#reportrange').on('hide.daterangepicker', function() {
        console.log("hide event fired");
      });
      jQuery('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
      });
      jQuery('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
        console.log("cancel event fired");
      });
      jQuery('#options1').click(function() {
        jQuery('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
      });
      jQuery('#options2').click(function() {
        jQuery('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
      });
      jQuery('#destroy').click(function() {
        jQuery('#reportrange').data('daterangepicker').remove();
      });
}
if(jQuery("#graph_barxx").length>0){

    Morris.Bar({
      element: 'graph_bar',
      data: [
        { "period": "Jan", "Hours worked": 80 }, 
        { "period": "Feb", "Hours worked": 125 }, 
        { "period": "Mar", "Hours worked": 176 }, 
        { "period": "Apr", "Hours worked": 224 }, 
        { "period": "May", "Hours worked": 265 }, 
        { "period": "Jun", "Hours worked": 314 }, 
        { "period": "Jul", "Hours worked": 347 }, 
        { "period": "Aug", "Hours worked": 287 }, 
        { "period": "Sep", "Hours worked": 240 }, 
        { "period": "Oct", "Hours worked": 211 }
      ],
      xkey: 'period',
      hideHover: 'auto',
      barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
      ykeys: ['Hours worked', 'sorned'],
      labels: ['Hours worked', 'SORN'],
      xLabelAngle: 60,
      resize: true
    });

    $MENU_TOGGLE.on('click', function() {
      jQuery(window).resize();
    });
}
///
if(jQuery("#foo").length>0){ 
	var opts = {
	        lines: 12,
	        angle: 0,
	        lineWidth: 0.4,
	        pointer: {
	          length: 0.75,
	          strokeWidth: 0.042,
	          color: '#1D212A'
	        },
	        limitMax: 'false',
	        colorStart: '#1ABC9C',
	        colorStop: '#1ABC9C',
	        strokeColor: '#F0F3F3',
	        generateGradient: true
	      };
	      var target = document.getElementById('foo'),
	          gauge = new Gauge(target).setOptions(opts);

	      gauge.maxValue = 100;
	      gauge.animationSpeed = 32;
	      gauge.set(80);
	      gauge.setTextField(document.getElementById("gauge-text"));

	      var target = document.getElementById('foo2'),
	          gauge = new Gauge(target).setOptions(opts);

	      gauge.maxValue = 5000;
	      gauge.animationSpeed = 32;
	      gauge.set(4200);
	      gauge.setTextField(document.getElementById("gauge-text2"));
}
	$tree = false;
    jQuery('.jstree_item_helps').each(function(i,e){
    	$c = window.location.href;
    	$tree = true; $ajax_data = jQuery(e).attr('data-ajax') ? jQuery(e).attr('data-ajax') : false;
    	jQuery(e).jstree({
			'core' : {
				'multiple':false,
				'data' : $ajax_data == false ? false : ({
					'url' : $ajax_data,
					'data' : function (node) {
						return { 'id' : node.id };
					}
				}),
				'check_callback' : function(o, n, p, i, m) {
					if(m && m.dnd && m.pos !== 'i') { return false; }
					if(o === "move_node" || o === "copy_node") {
						if(this.get_node(n).parent === this.get_node(p).id) { return false; }
					}
					return true;
				},
				'force_text' : true,
				'themes' : {
					'responsive' : true,
					'variant' : 'small',
					'stripes' : true
				}
			},
			'sort' : function(a, b) {
				return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
			},
			 
			'types' : {
				'default' : { 'icon' : 'folder' },
				'file' : { 'valid_children' : [], 'icon' : 'file' }
			},
			'unique' : {
				'duplicate' : function (name, counter) {
					return name + ' ' + counter;
				}
			},
			'plugins' : ['state','dnd','sort','types','unique'] 
		}).on('changed.jstree', function (e, data) {
			
			window.history.pushState({"html":'',"pageTitle":'(2)'},"", '#viewed');
		    if(data.selected.length > 0){
			    $.post($cfg.cBaseUrl + '/ajax/helps', {
					'action':'get_helps',				
					'type' : data.node.type, 
					'id' : data.node.li_attr['data-id'], 
					'text' : data.node.text,
					'url': $c,
					
				},function(r){},'json').done(function (d) {
					 
					jQuery('.jstree_right_panel_list').html(d.text);
					if(d.state){
						jQuery('.jstree_right_panel').removeClass('js-helps-panel');
					}else{
						jQuery('.jstree_right_panel').addClass('js-helps-panel');
					}
				})
				.fail(function () {
					//data.instance.refresh();
				});
		    //jQuery('#event_result').html('Selected: ' + r.join(', '));
		    }
		  }).on('create_node.jstree', function (e, data) {
			  $pr = data.instance.get_node(data.node.parent);
			  //view_obj($pr)
				$.post($cfg.cBaseUrl + '/ajax/files_manages', {
					'action':'create_folder',				
					'type' : data.node.type, 
					'id' : data.node.parent, 
					'text' : data.node.text,
					'parent_id': $pr.data.id ,
					
				}).done(function (d) {
					//jQuery('body').html(d)
					data.instance.refresh();
				})
				.fail(function () {
					data.instance.refresh();
				});
		}).on('rename_node.jstree', function (e, data) {
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'rename_folder',				
				'type' : data.node.type, 
				'id' : data.node.data.id, 
				'text' : data.node.text,
				//'parent_id': $pr.data.id ,
				
			})
			.done(function (d) {
				data.instance.refresh();
			})
			.fail(function () {
				data.instance.refresh();
			});
		}).on('delete_node.jstree', function (e, data) {
			if(confirm ('Xác nhận ?')){
				
			
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'delete_folder',				
				//'type' : data.node.type, 
				'id' : data.node.data.id, 
				//'text' : data.node.text,
				//'parent_id': $pr.data.id ,
				
			})
			.done(function (d) {
				//alert(d); 
				data.instance.refresh();
			}) 
			.fail(function (d) { 								
				data.instance.refresh();
			});
			}else{
				data.instance.refresh();
			}
		}).on('move_node.jstree', function (e, data) {
			$pr = data.instance.get_node(data.node.parent);
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'move_folder',				
				//'type' : data.node.type, 
				'id' : data.node.data.id, 
				//'text' : data.node.text,
				'parent_id': $pr.data.id ,
				
			})
			.done(function (d) {
				//data.instance.load_node(data.parent);
				//alert(d)
				data.instance.refresh();
			})
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('copy_node.jstree', function (e, data) {
		 
		//$.get('?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
			$pr = data.instance.get_node(data.parent);
			 
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'copy_folder',				
				//'type' : data.node.type, 
				'id' : data.original.data.id, 
				//'text' : data.node.text,
				'parent_id': $pr.data.id ,
				
			})
			.done(function (d) { 
				//data.instance.load_node(data.parent);
				data.instance.refresh();
			})
			.fail(function () {
				data.instance.refresh();
			});
	})
		;
    	
    });
    jQuery('.jstree_item_ftp').each(function(i,e){
    	$tree = true; $ajax_data = jQuery(e).attr('data-ajax') ? jQuery(e).attr('data-ajax') : false;
    	jQuery(e).jstree({
			'core' : {
				'data' : $ajax_data == false ? false : ({
					'url' : $ajax_data,
					'data' : function (node) {
						return { 'id' : node.id };
					}
				}),
				'check_callback' : function(o, n, p, i, m) {
					if(m && m.dnd && m.pos !== 'i') { return false; }
					if(o === "move_node" || o === "copy_node") {
						if(this.get_node(n).parent === this.get_node(p).id) { return false; }
					}
					return true;
				},
				'force_text' : true,
				'themes' : {
					'responsive' : true,
					'variant' : 'small',
					'stripes' : true
				}
			},
			'sort' : function(a, b) {
				return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
			},
			'contextmenu' : {
				'items' : function(node) {
					var tmp = $.jstree.defaults.contextmenu.items();
					delete tmp.create.action;
					delete tmp.ccp;
					tmp.create.label = "Tạo mới";
					tmp.create.submenu = {
						"create_folder" : {
							"separator_after"	: true,
							"label"				: "Thư mục",
							"action"			: function (data) {
								var inst = $.jstree.reference(data.reference),
									obj = inst.get_node(data.reference);
								inst.create_node(obj, { type : "default" }, "last", function (new_node) {
									setTimeout(function () { inst.edit(new_node); },0);
								});
							}
						},
						/*"create_file" : {
							"label"				: "File",
							"action"			: function (data) {
								var inst = $.jstree.reference(data.reference),
									obj = inst.get_node(data.reference);
								inst.create_node(obj, { type : "file" }, "last", function (new_node) {
									setTimeout(function () { inst.edit(new_node); },0);
								});
							}
						}*/
					};
					if(this.get_type(node) === "file") {
						delete tmp.create;
					}
					return tmp;
				}
			},
			'types' : {
				'default' : { 'icon' : 'folder' },
				'file' : { 'valid_children' : [], 'icon' : 'file' }
			},
			'unique' : {
				'duplicate' : function (name, counter) {
					return name + ' ' + counter;
				}
			},
			'plugins' : ['state','dnd','sort','types','contextmenu','unique']
		}).on('changed.jstree', function (e, data) {
		    var i, j, r = [];
		    for(i = 0, j = data.selected.length; i < j; i++) {
		    	
		      r.push(data.instance.get_node(data.selected[i]).li_attr['data-id']);
		    }
		    //jQuery('#event_result').html(r.join(', '));
		    get_list_ftp_file(r.join(', '));
		  }).on('create_node.jstree', function (e, data) {
			  $pr = data.instance.get_node(data.node.parent);
			  //view_obj($pr)
				$.post($cfg.cBaseUrl + '/ajax/files_manages', {
					'action':'create_folder',				
					'type' : data.node.type, 
					'id' : data.node.parent, 
					'text' : data.node.text,
					'parent_id': $pr.data.id ,
					
				}).done(function (d) {
					//jQuery('body').html(d)
					data.instance.refresh();
				})
				.fail(function () {
					data.instance.refresh();
				});
		}).on('rename_node.jstree', function (e, data) {
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'rename_folder',				
				'type' : data.node.type, 
				'id' : data.node.data.id, 
				'text' : data.node.text,
				//'parent_id': $pr.data.id ,
				
			})
			.done(function (d) {
				data.instance.refresh();
			})
			.fail(function () {
				data.instance.refresh();
			});
		}).on('delete_node.jstree', function (e, data) {
			if(confirm ('Xác nhận ?')){
				
			//alert(data.node.data.id);
			$.post($cfg.cBaseUrl + '/ajax/ftp', {
				'action':'delete_file',				
				//'type' : data.node.type, 
				'id' : data.node.data.id, 
				//'text' : data.node.text,
				//'parent_id': $pr.data.id ,
				
			})
			.done(function (d) {
				//alert(d); 
				data.instance.refresh();
			}) 
			.fail(function (d) { 								
				data.instance.refresh();
			});
			}else{
				data.instance.refresh();
			}
		}).on('move_node.jstree', function (e, data) {
			$pr = data.instance.get_node(data.node.parent);
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'move_folder',				
				//'type' : data.node.type, 
				'id' : data.node.data.id, 
				//'text' : data.node.text,
				'parent_id': $pr.data.id ,
				
			})
			.done(function (d) {
				//data.instance.load_node(data.parent);
				//alert(d)
				data.instance.refresh();
			})
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('copy_node.jstree', function (e, data) {
		 
		//$.get('?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
			$pr = data.instance.get_node(data.parent);
			 
			$.post($cfg.cBaseUrl + '/ajax/files_manages', {
				'action':'copy_folder',				
				//'type' : data.node.type, 
				'id' : data.original.data.id, 
				//'text' : data.node.text,
				'parent_id': $pr.data.id ,
				
			})
			.done(function (d) { 
				//data.instance.load_node(data.parent);
				data.instance.refresh();
			})
			.fail(function () {
				data.instance.refresh();
			});
	})
		;
    	
    });
    if($tree){
	    jQuery(window).resize(function () {
			var h = Math.max(jQuery(window).height() - 140, 420);
			jQuery('.jstree_item,.jstree_item_ftp,.jstree_item_helps').height(h).filter('.default').css('lineHeight', h + 'px');
			jQuery('.jstree_right_panel').height(h+46).filter('.default').css('lineHeight', h + 'px');
		}).resize();
    }
    jQuery('.top_nav .nav > li.notification').click(function(){
    	jQuery('[data-toggle="tooltip"]').tooltip('hide');
    	if(jQuery(this).find('ul li').length>0){
    		return true;
    	}return false
    });
    // context menu
    $.contextMenu({
        selector: '.context-menu',
        callback:function(){
        	set_selected(this)
        },
        items: {
            zoom: {
                name: "Xem ảnh lớn",
                callback: function(key, opt){
                	jQuery(this).colorbox({rel:'group1'});
                },
			    disabled: function(key, opt){  
			    	return true;
			        // Disable this item if the menu was triggered on a div
			        if(opt.$trigger.nodeName === 'div'){
			            return true;
			        }            
			    },
                visible: function(key, opt){ 
                	$this = jQuery(this);
                    // Hide this item if the menu was triggered on a div
                    if($this.attr('data-type') == 'image'){
                        return true;
                    }else{
                    	return false;
                    }
                }
            },
            copy: {
                name: "Sao chép đường dẫn",
                callback: function(key, opt){
                	// clipboard
                    //$clipboard = new Clipboard('.btn-clipboard');
                	$im = jQuery(this).find('.image-link').attr('id');
                	//alert($im)
                	copyToClipboard(document.getElementById($im))
                },
                visible: function(key, opt){ 
                	$this = jQuery(this);
                    // Hide this item if the menu was triggered on a div
                    if($this.attr('data-type') == 'image'){
                        return true;
                    }else{
                    	return false;
                    }
                }
			     
            },
            delete_item: {
                name: "Xóa",
                callback: function(key, opt){
                	if(confirm ('Xác nhận ?')){
                	$this = jQuery(this);
                	$id = jQuery(this).attr('data-id');
                	$.post($cfg.cBaseUrl + '/ajax/ftp', {
        				'action':'delete_file',				
        				'id':$id         				
        			}).done(function (d) { 
        				$this.remove() 
        			})
        			.fail(function () {
        				 
        			});}
                },
			     
            },
            
        }
    });
    //notification.init();
   jQuery('.auto_play_script_function').each(function(i,e){
	   eval(jQuery(e).val());
   })
});
var notification = {
		 init:function(){
			 
			 //
			 (function getNotisTimeoutFunction(){
				 notification.getNotis(getNotisTimeoutFunction);
				})();
		 },
		 getNotis : function(callback){
				jQuery.get($cfg.cBaseUrl +'/ajax/notifis?action=countNotifis',{},function(r){
					
					// Setting a timeout for the next request,
					// depending on the chat activity:
					//alert(r)
					var nextRequest = 3000;
					$n = jQuery('.item-notifications');
					$badge = $n.find('.alert-count');
					if(r.unview > 0){
						$badge.html(r.unview).show();
						$n.find('.badge-0').removeClass('badge-0').addClass('badge-1');
					} else{
						$badge.html('').hide();
						$n.find('.badge-1').removeClass('badge-1').addClass('badge-0');
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
				
					setTimeout(callback,nextRequest);
				},'json');
			},
		 
}
