var $tree = false; 
var $config = $cfg;
$config.action = 'admin_init';
jQuery.post($cfg.cBaseUrl +'/ajax?initcss',$config,function($r){
	if($r.callback){
		eval($r.callback_function);
	}
},'json');
//
console.log($config);
$config.action = 'admin_init_js';
jQuery.post($cfg.cBaseUrl +'/ajax?initjs',$config,function($r){
	
	LazyLoad.js($r.script, function(){

		if($r.callback){
			eval($r.callback_function);
		}
	
	// Common js	
	var $body = $('body'); $window = $(window);
	izi.init();
	$('.unhide-after-load').removeClass('hide display-none').show();
	//$('#demo-htmlselect').ddslick(); 
	/*
	var date = new Date();
	var minutes = 0.6;
	date.setTime(date.getTime() + (minutes * 60 * 1000));
	Cookies.set('expried_alert_time_left', 'value', { expires: date });
	console.log(Cookies.get('expried_alert_time_left'));
	*/
	$('.full-row-click').find('td').click(function(i,e){
		var $this = $(this);
		var $link = $this.parent().attr('data-link');
		if(!$this.hasClass('igrone-this')){
			window.location = $link;
		}
	});
	/*
	 * Fixed header
	 * 
	 */
	var tra1 = $('.tree-list-a1 > li > a, .tree-list-a1 > .mg-nav-item > a');
	var tra2 = $('.tree-list-a1 > li, .tree-list-a1 > .mg-nav-item');
	tra1.hover(function(){
		var $this = $(this).parent();
		var hoverDiv = $this.find('.show-on-hover');
		var icon = $this.find('.arrow-icon');
		var tw = parseInt($this.css('width'));
		var hw = parseInt(hoverDiv.css('width'));
		var hh = parseInt(hoverDiv.find('.hla-result').css('height'));
		var iw = parseInt($this.find('.i-icon').css('width'));		
		var ih = parseInt($this.find('.i-icon').css('height'));
		var pos = $this.position();
		var left = (((tw - iw)/2) + iw +10);
		//hoverDiv.show();
		if(pos.left + tw + hw > $(window).width()){
			left = (left + 30) * (-1);
			icon.removeClass('arrow-left')
			.addClass('arrow-right')
			.css({'right':-10});
		}else{
			left = (((tw - iw)/2) + iw +10);
			icon.removeClass('arrow-right')
			.addClass('arrow-left')
			.css({'left':-10});
		}
		if(pos.top > (hh/2)-(ih/2)){
			hoverDiv.css({'top':(-1)*((hh/2)-(ih/2))});
			icon.css({'top':((hh/2>15 ? hh/2 : 15)-10)});
		}
		
		
		if(!($this.hasClass('loaded'))){			
			$data = getAttributes($this);
			$data.action = 'menu-hover-load-child2';
			hoverDiv
			.css({
				//"background":"#fff",
				"left" : left
			});
			
			$.ajax({
				url: $cfg.cBaseUrl + '/ajax',
				type: 'POST',
				data: $data
				
			})			
			.done(function(data){
				$this.addClass('loaded');
				$d = parseJsonData(data);
				if($d.html != ""){
					hoverDiv
					.find('.hla-result').show()
					.find('.ct-rs-aj').html($d.html);
					
					p1 = $this.parent(); 
					p2 = p1.parent();
					var hh = parseInt(hoverDiv.find('.hla-result').css('height'));
					var ph = p1.height();
					hoverDiv.removeClass('loading3')
					.css({"height":hh,"z-index":1});
					
					if(p2.hasClass('sf-mega')){
						
						 
						//p1.css({
							//'height':(hh + ph),
							//'min-height': '80%',
						//})
						//;
						 
						p1.animate({
						//	'height': '85%',
						},500);
						
						if(pos.left + tw + hw > $(window).width()){
							left = (left + 30) * (-1);
							icon.removeClass('arrow-left')
							.addClass('arrow-right')
							.css({'right':-10});
						}else{
							left = (((tw - iw)/2) + iw +10);
							icon.removeClass('arrow-right')
							.addClass('arrow-left')
							.css({'left':-10});
						}
						if(pos.top > (hh/2)-(ih/2)){
							hoverDiv.css({'top':(-1)*((hh/2)-(ih/2))});
							icon.css({'top':(hh/2-10) > 0 ? (hh/2-10) : 10});
						}
					}
					
					
					
				}else{
					hoverDiv.remove();
				}
			});
						
		}
		hoverDiv.show();
	},function(){
		
	});
	tra2.hover(function(){},function(){
		jQuery('.show-on-hover').hide();
	});
	
	/* jQueryKnob */

    $(".knob").knob();
    $(".knob").removeClass('hide');
    /* END JQUERY KNOB */
    //console.log($('.bs-docs-example').text());
    if($('.bs-docs-example').text()==""){
    	$('.bs-docs-example').remove();
    }
	
    var headerHeight = $("#header").height(); 
	var $avHeight = Math.min(($(window).height() - (headerHeight + 82)), ($('.fixedHeader').height()+10)); 
	$avHeight = $avHeight<90 ? 90 :$avHeight;
	$('table.fixedHeader').fixedHeader({
		height:$avHeight + 'px'
	});	
	$(window).resize(function(){
		var headerHeight = $("#header").height();
		var $avHeight = Math.min(($(window).height() - (headerHeight + 82)), ($('.fixedHeader').height()+10)); 
		$avHeight = $avHeight<90 ? 90 :$avHeight;
		$('.tableFixedHeaderScollBody').height($avHeight);
		setMegaMenuHeight();
		setMegaMenuClick();
	});
	
	//$(window).load(function(){
		setMegaMenuHeight();
		setMegaMenuClick();
	//});
	
	var $input = $(".checkall_box");
	$input.clone().removeAttr("id").appendTo("table>thead>tr>th.ccheckitem");
	var $uniformed = $("input.uniform, textarea.uniform, select.uniform, button.uniform, a.uniform").not(".skipThese");
	$uniformed.uniform();
	
	var $_progress = jQuery('.progcess-bar-life-time');
	//
	var SHOP_TIME_LEFT = parseInt($_progress.attr('data-time'));
	var $s = SHOP_TIME_LEFT * 100 / 365;
	var $w = (15 * 100 / 365) + SHOP_TIME_LEFT;	 
	var $d = 100 - $s - $w;
	//
	var $progress = '<div data-toggle="tooltip" title="Bạn còn '+SHOP_TIME_LEFT+' ngày sử dụng" data-placement="bottom" class="progress life-time"><div class="progress-bar progress-bar-success" style="width: '+$s+'%"><span class="sr-only">'+$s+'% Complete (success)</span></div>';
	$progress += '<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: '+$w+'%"><span class="sr-only">'+$w+'% Complete (warning)</span></div>';
	$progress += '<div class="progress-bar progress-bar-danger" style="width: '+$d+'%"><span class="sr-only">'+$d+'% Complete (danger)</span></div></div>';
	jQuery('.progcess-bar-life-time').html($progress);
	if(!Cookies.get('expried_alert_time_left')){
		var $tlf = jQuery('.sys-alert-time-left');
		
		//console.log(SHOP_TIME_LEFT < 31);
		//var $px = SHOP_TIME_LEFT * 100 / 365; 
		var $alert = '';
		if(SHOP_TIME_LEFT < 31){
			
			$alert += '<div class="alert life-time alert-danger alert-dismissible fade in" role="alert">'; 
			$alert += SHOP_TIME_LEFT > 0 ? '<button title="Đóng" type="button" onclick="setCookieExpried(this)" data-time="60" data-name="expried_alert_time_left" data-value="1" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' : ''; 
			$alert += '<strong>Gấp gấp gấp!</strong> ';
			$alert += 'Tài khoản của bạn sắp hết hạn sử dụng. Hệ thống sẽ tự động tạm ngưng dịch vụ sau '; 
			$alert += '<b>'+SHOP_TIME_LEFT+'</b> ngày nữa ('+$tlf.attr('data-alert')+')'; 
			$alert += '. Vui lòng liên hệ hotline: <b class="underline"><a class="red " href="tel:0985527788">098 552 77 88</a> - Mr. Trường</b> để được hỗ trợ gia hạn dịch vụ.';
			$alert += '<br/><i>Sau <b>15</b> ngày kể từ ngày tạm ngưng dịch vụ, tài khoản của bạn sẽ bị khóa toàn bộ các dịch vụ.</i>';
			$alert += '</div>';
			if(SHOP_TIME_LEFT > 0 && SHOP_TIME_LEFT < 7){
				showZModal('Cảnh báo !!!',$alert);
			}else{
				if(SHOP_TIME_LEFT<1) {
					$alert = '<div class="alert life-time alert-danger alert-dismissible fade in" role="alert">'; 
					$alert += SHOP_TIME_LEFT > 0 ? '<button title="Đóng" type="button" onclick="setCookieExpried(this)" data-time="60" data-name="expried_alert_time_left" data-value="1" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' : ''; 
					$alert += '<strong>Gấp gấp gấp!</strong> ';
					$alert += 'Tài khoản của bạn đã hết hạn <b>'+(-1*SHOP_TIME_LEFT)+'</b> ngày.<br/>'; 
					//$alert += '<b>'+SHOP_TIME_LEFT+'</b> ngày nữa '+$tlf.attr('data-alert'); 
					$alert += 'Vui lòng liên hệ hotline: <b class="underline"><a class="red " href="tel:0985527788">098 552 77 88</a> - Mr. Trường</b> để được hỗ trợ gia hạn dịch vụ.';
					$alert += '<br/><i>Sau <b>15</b> ngày kể từ ngày tạm ngưng dịch vụ, tài khoản của bạn sẽ bị khóa toàn bộ các dịch vụ.</i>';
					$alert += '</div>';
					showXModal($alert);
				}
			}
		}else{
			if(SHOP_TIME_LEFT < 60){
				$alert += '<div class="alert life-time alert-warning alert-dismissible fade in" role="alert">'; 
				$alert += '<button title="Đóng" type="button" onclick="setCookieExpried(this)" data-time="480" data-name="expried_alert_time_left" data-value="1" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'; 
				$alert += '<strong>Cảnh báo!</strong> ';
				$alert += 'Tài khoản của bạn sắp hết hạn sử dụng. Hệ thống sẽ tự động tạm ngưng dịch vụ sau '; 
				$alert += '<b>'+SHOP_TIME_LEFT+'</b> ngày nữa ('+$tlf.attr('data-alert')+')'; 
				$alert += '. Vui lòng liên hệ hotline: <b class="underline"><a class="red " href="tel:0985527788">098 552 77 88</a> - Mr. Trường</b> để được hỗ trợ gia hạn dịch vụ.';
				$alert += '<br/><i>Sau <b>15</b> ngày kể từ ngày tạm ngưng dịch vụ, tài khoản của bạn sẽ bị khóa toàn bộ các dịch vụ.</i>';
				$alert += '</div>';
			}
		}
	 
		$tlf.html($alert);
	}
	var $xHeight = ($(window).height()-$('.main-menu-mega').height());
	$('.sf-mega').css({"height":$xHeight});
	
	$('.superfishx').superfish({
		delay: 0,
		//delay: 600,
	    autoArrows: false,
	    speed: 'slow',
	    disableHI: true,
	    speedOut: 350,
		onShow: function(){
			var $this = $(this);
			
			var $n = $this.find('.tree-list-a1');
			$n.css({'overflow-y':'scroll !important'});
			var $currentHeight = parseInt($n.css('height'));
			var $xHeight = ($(window).height()-$('.main-menu-mega').height());
			$this.css({"height":$xHeight});
			$xHeight -= 50;
			
			if($currentHeight>$xHeight){
				$n.css({"height":$xHeight});
			}else{
				$n.css({"height":$currentHeight});
			}
			/*
			$n.animate({
				height: '70%'
			},200);
			*/
			$this.find('.tree-list-a1.hasChid').hover(function(){
				$this.stop().fadeIn(200);
			},function(){
				$this.stop().fadeOut(500);
			});
		}
	});
	
	 
	
	
	load_select2();load_chosen_select();load_number_format();load_datetimepicker();
    $href = window.location.href.split('#');if($href.length > 1){$href = '#' + $href[1];if($($href).length > 0){if($($href).length>0) $('.nav-tabs a[href="'+$href+'"]').tab('show')  ;}}
	$('.form-edit-tab>li>a').click(function(){
		$('input.currentTab').val($(this).attr('href'));
	});
    $("input[type=checkbox].switchBtn").each(function(i,e){
		$(e).switchButton({
	        labels_placement: "left"
	    });
	});
 
    if($('[data-toggle="popover"]').length > 0){
        $('[data-toggle="popover"]').popover()  ;
    }
    reloadTooltip();
    $('.auto_height_price_list').each(function(i,e){
    	var $h = $(e).find('.table-prices').height()+65;    	
    })
    if($('a[rel=popover]').length > 0){
        $('a[rel=popover]').popover({
        html: true,
           trigger: 'hover',
           content: function () {
           return '<img src="'+$(this).data('img') + '" alt="" style="max-width: 600px;max-height:600px" />';
          }
        });
    }
    $('.Ccolorpicker').each(function(i,e){
   	 $format = $(e).attr('data-format') ? $(e).attr('data-format') : false;      
        $(e).colorpicker({
       	 format:$format
        });
    });
    $("input[type=checkbox].switch-btn").each(function(i,e){
    	var $this = $(e);
    	var $parent = $this.parent();
    	var node = document.createElement("div");
    	node.className = 'onoff-button-div';
    	$parent.append(node);
    	$this.appendTo(node).removeClass('switch-btn').addClass('switchBtn')    	
		.switchButton({
	        labels_placement: "left"
	    });
		
	});
    
    $('.checkall_box').change(function(event) {  //on click
        if(this.checked) { // check select status
        	$('.checkall_box').prop('checked',true);
            $('.checked_item').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
        	$('.checkall_box').prop('checked',false);
            $('.checked_item').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }        
        $.uniform.update("input.uniform");
    });
    // popout
    $popout =  $('[data-toggle="confirmation-popout"]');
    $('[data-toggle="confirmation"]').confirmation();
    $('[data-toggle="confirmation-singleton"]').confirmation({singleton:true});
    $popout.confirmation({
	      btnOkLabel:'<i class="fa fa-check-square-o"></i> Có',
	      btnCancelLabel:'<i class="fa fa-remove"></i> Không',
	      popout: true,
	      onConfirm:function(element){
	    	  $this = jQuery(this);	    	
	    	  if($this.attr('data-type') == 'multiple'){
	    		  $v = jQuery("input.checked_item:checked").map(function () {return this.value;}).get().join(",");
	    		  $this.attr('data-id',$v);
	    	  }
	    	  $data = getAttributes($this);	    	   
	      jQuery.ajax({	  			
	    	  type: 'post',	  		 	
	    	  datatype: 'json',	  			
	    	  url: $cfg.cBaseUrl +'/ajax/delete',	  			
	    	  data:$data,	          
	    	  beforeSend: function() {
	    		  show_left_small_loading('show');             
	    	  },	  			
	    	  success: function(data) {	                  
	    		  $popout.confirmation('hide');	              
	              var $d = JSON.parse(data);
	              if($d.state == true){ 
	              jQuery.each($d.hide_class,function(index,value){   
	            	  jQuery('.tr_item_'+value).remove();
	              });
	              show_left_small_loading('hide');
	              }
	    	  },	  				  			
	    	  error : function(err, req) {	  		
	    		  show_left_small_loading('show');
	    	  },	  				            
	    	  complete: function() {
	    		  
	    	  }
	      });	        
	      return false	      
	      },

	});
    ///////
	$('.Breadcrumb li:last-child').prev().addClass('SecondLast');
	if($('.fixed-bottom-left').length==0){
		$('body').append('<div class="fixed-bottom-left alert done alert-success alert-dismissible fade hide" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> <strong class="alert-content"></strong></div>');
	}
	// Customize js
	 
	if($r.load_js) $.getScript($cfg.assets + "/js/actions/"+$cfg.controller+".js");
	// Scroll window 
	var $list_btn = $('.list-btn');	
	var $list_btn_tab = $('.v2-product-tab');	
	var $Offset = $list_btn.length>0 ? $list_btn.offset().top : 0;
	$window.scroll(function(){        
       $this = $(this);       
       if($list_btn.length>0){    	   
    	   if ($window.scrollTop() > $Offset) {
    		   $list_btn.addClass('header-sticky'); 
    	   }else{
    		   $list_btn.removeClass('header-sticky');
    	   }
       }
       
       if($list_btn_tab.length>0){    	   
    	   if ($window.scrollTop() > $Offset) {
    		   $list_btn_tab.addClass('header-sticky navbar-fixed-top'); 
    	   }else{
    		   $list_btn_tab.removeClass('header-sticky navbar-fixed-top');
    	   }
       }
       
       
	})
	/// Load editor
	$('.ckeditor_basic1').each(function(i,e){
       var $id = $(e).attr('id');
       var $width = parseInt($(e).attr('data-width'));
       var  $height = parseInt($(e).attr('data-height'));
        CKEDITOR.replace( $id, {
            width:$width, height:$height,
             toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
             ] 
             });
     })
     .removeClass('ckeditor_basic1');
     $('.ckeditor_basic2').each(function(i,e){
    	 var $id = $(e).attr('id');
    	 var  $width = parseInt($(e).attr('data-width'));
    	 var $height = parseInt($(e).attr('data-height'));
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
     })
     .removeClass('ckeditor_basic2');
     $('.ckeditor_basic3').each(function(i,e){
    	 var $id = $(e).attr('id');
    	 var  $width = parseInt($(e).attr('data-width'));
    	 var  $height = parseInt($(e).attr('data-height'));
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
     })
     .removeClass('ckeditor_basic3');
     //
     loadCkeditorBasic4();
     //
     $('.ckeditor_full').each(function(i,e){
    	 var $id = $(e).attr('id');
    	 var $width = parseInt($(e).attr('data-width'));
    	 var  $height = parseInt($(e).attr('data-height'));
    	 var $expand = $(e).attr('data-expand') ? $(e).attr('data-expand') : true;
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
     .removeClass('ckeditor_full');
     $('.used_editor_for_info').each(function(i,e){
    	 var $role = $(e).attr('role');
    	 var $checked = $(e).is(':checked') ;
    	 var $width = parseInt($(e).attr('data-width'));
    	 var $height = parseInt($(e).attr('data-height'));
         if($checked){
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
         
     })
     .removeClass('used_editor_for_info');
     $('.used_editor_for_info').change(function(){
    	 var  $this = $(this); $role = $this.attr('role');
    	 var  $checked = $this.is(':checked') ;
    	 var  $width = parseInt($this.attr('data-width'));
    	 var  $height = parseInt($this.attr('data-height'));
        if($checked){
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
     })
     .removeClass('used_editor_for_info');
     $('.file-inputs').bootstrapFileInput();
     $('.auto_play_script_function').each(function(i,e){
  	   eval($(e).val());
     })
     
     notification.init();
     // autocomplete
     loadTagsInput(); 
     loadTagsInput1(); 
     loadAutocomplete();
     /////////////////////////////
     loadSelectTagsinput();
     //reloadTooltip(); 
     /////////////////////////////
     
     $('form input, form textarea, form select').change(function(){
    	 $('body').addClass('confirm-reload');
     });
     
     for (var i in CKEDITOR.instances) {
         CKEDITOR.instances[i].on('change', function() {
        	 $('body').addClass('confirm-reload');
         });
     }
     
     $('form').submit(function(){
    	 $('body').removeClass('confirm-reload');
     });
     
     $.datetimepicker.setLocale($cfg.locale);
     $(".datetimepicker3").datetimepicker({
    		mask:'39/19/2299',
    		format: 'd/m/Y',
    		lang:'vi',
    		timepicker:false,
    		minDate: $(this).attr('data-minDate') ? $(this).attr('data-minDate') : false,
     });
     
     //  $.mask.definitions['3']='[0-3]';
     //   console.log($.default_mask.definitions);
     //   $.mask.definitions['~']='[+-]';
     //   $("#datesss").mask("(999) 999-9999? x99999");
     // THIS EVENT MAKES SURE THAT THE BACK/FORWARD BUTTONS WORK AS WELL
		window.onpopstate = function(event) {
			//$("#loading").show();
			//console.log("pathname: "+location.pathname);
			//loadContent(location.pathname);
		};

     // End load script
})},'json');

//}
var notification = {
		 init:function(){
			 
			 //
			 (function getNotisTimeoutFunction(){
				 notification.getNotis(getNotisTimeoutFunction);
			})();
		 },
		 getNotis : function(callback){
			
			 jQuery.post($cfg.cBaseUrl +'/ajax',{action:"countNotifis"},function(r){
				   
					min = 10000; max = 25000;
					var nextRequest = Math.floor(Math.random()*(max-min+1)+min);
					$n = jQuery('.item-notifications');
					$badge = $n.find('.alert-count');
					if(r.unview > 0){
						$badge.html(r.unview).show();
						$n.find('.badge-0').removeClass('badge-0').addClass('badge-1');
					} else{
						$badge.html('').hide();
						$n.find('.badge-1').removeClass('badge-1').addClass('badge-0');
					}		
					
					
					$n = jQuery('.item-notifications-mail');
					$badge = $n.find('.alert-count');
					if(r.new_mail > 0){
						$badge.html(r.new_mail).show();
						$n.find('.badge-0').removeClass('badge-0').addClass('badge-1');
					} else{
						$badge.html('').hide();
						$n.find('.badge-1').removeClass('badge-1').addClass('badge-0');
					}				
					setTimeout(callback,nextRequest);
					 
				},'json');
				
			},
		 
}
function load_css(urls,target) {
	var node;	 
	head = target == 1 ? document.getElementsByTagName("link")[0] : document.getElementsByTagName("head")[0];	
	urls = typeof urls === 'string' ? [urls] : urls.concat();
	for (i = 0, len = urls.length; i < len; ++i) {
	  node = document.createElement("link");
	  node.type = "text/css";
	  node.rel = "stylesheet";
	  node.className = 'lazyload';
	  node.href = urls[i];
	  if(target == 1){
		  head.parentNode.insertBefore(node, head);	  
	  }else{
		  head.appendChild(node); 
	  }
	}
}

function setMegaMenuHeight(){
	var $this = jQuery('.sf-mega.sf-mega-overflow-y');
	
	var $n = $this.find('.tree-list-a1');
	$n.css({'overflow-y':'scroll !important'});
	var $currentHeight = parseInt($n.css('height'));
	var $xHeight = (jQuery(window).height()-jQuery('.main-menu-mega').height());
	$this.css({"height":$xHeight});
	///$xHeight -= 50;
	
	if($currentHeight>$xHeight){
		$n.css({"height":$xHeight});
	}else{
		$n.css({"height":$xHeight});
	}
}
function setMegaMenuClick(){
	
	if($(window).width()<768){
		jQuery('body').addClass('mobile-mode');
		var $this = jQuery('.mobile-mode .main-menu-mega');		
		var li = $this.find('li.dropdown');
		li.addClass('onClick');
		li.click(function(){
			if(jQuery(this).hasClass('onClick')){
				var href = jQuery(this).find('>a').attr('href');
				window.location.href = href;
			}			
		});
	}else{
		/*
		jQuery('body').removeClass('mobile-mode');
		var $this = jQuery('.main-menu-mega');	
		var li = $this.find('>li');
		li.removeClass('onClick');
		var href = li.find('>a').attr('href');
		li.click(function(){
			if(!jQuery(this).hasClass('onClick')){
				var a = jQuery(this).find('>a');
				var href = a.attr('href');				
				changeUrl($cfg,href);
				loadContent(location.pathname); 
			}			
		}); 
		*/
		
	}
}





















