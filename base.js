var $_GET = {}, $device = {};
jQuery(document).ready(function(){
	var $body = jQuery('body');	
	var $_GET = {};
	var $device = {};
	$device.width = getDeviceWidth();
	///$body.addClass('dw'+getDeviceWidth());
	//
	if(document.location.toString().indexOf('?') !== -1) {
	    var query = document.location
	                   .toString()	                   
	                   .replace(/^.*?\?/, '')
	                   .replace(/#.*$/, '')
	                   .split('&');
	    for(var i=0, l=query.length; i<l; i++) {
	       var aux = decodeURIComponent(query[i]).split('=');
	       $_GET[aux[0]] = aux[1];
	    }
	}
	var $config = $cfg;
	//
	$config['action'] = 'system_init';
	jQuery.post($cfg.baseUrl +'/sajax',$config,function($r){
		if($r.callback){ 
			eval($r.callback_function);
		}
	},'json');
	 
	setAutoHeightElement();
	reloadAutoPlayFunction();
	
	jQuery('.bootstrp-file-inputs').each(function(i,e){
		jQuery(e).bootstrapFileInput();
	});
	
	load_datetimepicker();load_datetimepicker2();
	load_chosen_select();
	//izi.loadChosenAjax2 ();
	
	if(jQuery('.s-superfish').length>0){
	    var ex = jQuery('.s-superfish').superfish({
	    //add options here if required
	    });
	 }
	
	if($device.width<768){
		jQuery('.tab-menu ul.nav-tabs>li').each(function(i,e){
			var $t = jQuery(e);
			var $id = $t.find('a').attr('href');	
			var $target = jQuery($id);
			if($target.find('.mobile-only.tab-detail-heading').length==0 ){
				var $text = '<p class="program-no-content mobile-only tab-detail-heading">' +  $t.find('a').html() + '</p>';
				$target.prepend($text);				
			}
		});
	}
	
	$('input.numberOnly,input.numeric').keyup(function(e){
		if (/\D/g.test(this.value))
		{
		// Filter non-digits from input value.
		this.value = this.value.replace(/\D/g, '');
		}
	});
	
	
	jQuery('.s2-slick-slider-vertical').each(function(i,e){
		var $this = jQuery(e);
        var $show =$this.attr('data-show') ?$this.attr('data-show') : 1;
        var $scroll =$this.attr('data-scroll') ?$this.attr('data-scroll') : 1;
        var $dots =$this.attr('data-dots') ?$this.attr('data-dots') : false;
        var $rows =$this.attr('data-rows') ?$this.attr('data-rows') : 1;
        $this.slick({
          vertical: true,
          slidesToShow: $show,
          slidesToScroll: $scroll,
          dots:$dots,
          //rows:$rows,
          //infinite: false,
          //lazyLoad: 'ondemand',
          //speed: 1000,
          autoplay:true,
          prevArrow: '<div class="ca-nav"><span class="ca-nav-prev">Previous</span></div>',
          nextArrow: '<div class="ca-nav"><span class="ca-nav-next">Next</span></div>'
        });
    }); 
	
	if(jQuery('.default-product-info .zoomimg').length>0) {
		jQuery('.default-product-info .zoomimg').zoom();
	
		var imagezoom = function(zoomimage) {
			jQuery("#zoomlinks").children().hide();
			document.getElementById(zoomimage).style.display = "inline"; 	
		};
	
	}
	
	if($body.has('#gridTable').length==0){jQuery('body').append('<div id="gridTable"></div>')}
	if($body.has('#gridTableExportExcel').length==0){jQuery('body').append('<table id="gridTableExportExcel"></table>')}
	if(jQuery('body').has('.mymodal').length==0){jQuery('body').append('<div class="modal mymodal" tabindex="-1" role="dialog" aria-labelledby=""></div>')}if(jQuery('body').has('.mymodal1').length==0){jQuery('body').append('<div class="modal mymodal2" tabindex="-1" role="dialog" aria-labelledby=""></div>')}if(jQuery('body').has('.mymodal2').length==0){jQuery('body').append('<div class="modal mymodal2" tabindex="-1" role="dialog" aria-labelledby=""></div>')}jQuery(window).scroll(function(){if($cfg.is_admin==false&&jQuery(this).scrollTop()>500){if(jQuery('.btn-scroll-to-top').length==0){jQuery('body').append('<a class="btn-scroll-to-top" onclick="return scrollToTop();" href="javascript:void(0);"></a>');}jQuery('.btn-scroll-to-top').show();}else{jQuery('.btn-scroll-to-top').hide();}})
jQuery(window).resize(function(){
	var $w=jQuery(window).width();
	var $h=jQuery(window).height();

	setItemRatio();
	resizeItem4x3();
	setAutoHeightElement();

});
	loadDropDownCheckbox();
	//
	jQuery(window).bind('beforeunload', function () {
	    if($body.hasClass('confirm-reload')){
	    	return 'Dữ liệu chưa được lưu. Bạn có chắc chắn tải lại trang ?';
	    }    
	});
	jQuery("#slivechat .chat_fb_header").click(function() {
		jQuery('#slivechat .fchat').toggle('slow');
		return false;
	});
	//
	if(jQuery('.system-style.bottom_nav').length>0){var $h=0;jQuery('.system-style.bottom_nav li.li-level-1').each(function(){if((jQuery(this).height())>$h){$h=jQuery(this).height();}});jQuery('.system-style.bottom_nav li.li-level-1').css({"min-height":$h+'px'});}jQuery('.system.btnPaging').click(function(){var $this=jQuery(this);$role=($this.attr('role'));$loading=$this.parent().find('.img_loading');$loading.removeClass('hide');$.ajax({type:'post',datatype:'json',url:'/ajax',data:{role:$role,action:'loadingItem'},success:function(data){$loading.addClass('hide');var $d=JSON.parse(data);$this.attr('role',$d.role);jQuery('ul.ajax_result').append($d.r);var $d=JSON.parse($d.role);if($d.end==true){$this.parent().addClass('hide');}},error:function(err,req){console.log("Error");}});});jQuery('.sys_lang .sys_flag').click(function(){$role=jQuery(this).attr('role');__system_set_language($role);});setItemRatio();
	jQuery('.sdatetimepicker').each(function(i,e){
		$maxDate=jQuery(e).attr('data-maxDate')?jQuery(e).attr('data-maxDate'):false;
		jQuery(e).datetimepicker({format:'DD/MM/YYYY HH:mm',maxDate:$maxDate});});
		jQuery('.sdatepicker').each(function(i,e){
			var $maxDate=jQuery(e).attr('data-maxDate')?jQuery(e).attr('data-maxDate'):false;
			var $locale=jQuery(e).attr('data-locale')?jQuery(e).attr('data-locale'):false;
			jQuery(e).datetimepicker({format:'DD/MM/YYYY',maxDate:$maxDate});});
resizeItem4x3();
jQuery('.s2-slick-slider').each(function(i,e){
	var $this=jQuery(e); var $data = {};
	$data['rows'] = $this.attr('data-rows')?parseInt($this.attr('data-rows')):1;
	if($this.attr('data-items')){
		$data['items'] = $this.attr('data-items')?parseInt($this.attr('data-items')):1;
	}
	if($this.attr('data-infinite')){
		$data['infinite'] = $this.attr('data-infinite') && $this.attr('data-infinite') == 0 ? false : true;
	}
	$data['slidesToShow'] = $this.attr('data-slidesToShow') ? parseInt($this.attr('data-slidesToShow')): 1;
	$data['slidesToScroll'] = $this.attr('data-slidesToScroll') ? parseInt($this.attr('data-slidesToScroll')): 1;
	$data['slidesPerRow'] = $this.attr('data-slidesPerRow') ? parseInt($this.attr('data-slidesPerRow')): 1;
	
	$data['arrows'] = $this.attr('data-arrows')?($this.attr('data-arrows')=='false' ? false:true):true;
	$data['dots'] = $this.attr('data-dots') ? true : false;
	$data['autoplay'] = $this.attr('data-autoplay') ? true : false;
	
	$this.slick($data);
});

jQuery('.popup_colorbox').each(function(i,e){jQuery(e).colorbox({rel:jQuery(e).attr('rel')});});
jQuery('.s-slick-slider').each(function(i,e){
	var $this=jQuery(e);
	var $rows=$this.attr('data-rows')?parseInt($this.attr('data-rows')):1;
	var $items=$this.attr('data-items')?parseInt($this.attr('data-items')):1;
	var $category=$this.attr('data-category')?parseInt($this.attr('data-category')):-1;
	var $arrows=$this.attr('data-arrows')?($this.attr('data-arrows')=='false'?false:true):true;
	$this.slick({slidesToShow:$items,slidesToScroll:$items, rows:$rows,arrows:$arrows,dots:true, customPaging:function(slider,i){return'<button class="btn-custom-paging index-'+(i+1)+'" data-index="'+(i+1)+'" type="button" data-role="none" role="button" aria-required="false" tabindex="0">'+(i+1)+'</button>';}}).on('beforeChange',function(event,slick,currentSlide,nextSlide){$index=Math.ceil(nextSlide/$items)+1;$paging=$this.parent().parent().find('.paging');$paging.find('.active').removeClass('active');$paging.find('.page.page-'+$index).addClass('active');$it=$this.find('.item.item-page-'+$index);$next=$index+1;$prev=$index-1;$prev=$prev<1?1:$prev;$total=parseInt($paging.attr('data-total'));$next=$next>$total?$total:$next;$paging.find('.first').attr('data-page',$prev);$paging.find('.last').attr('data-page',$next);if($it.attr('data-loaded')=='false'){jQuery.ajax({type:'post',datatype:'jsonp',url:$cfg.baseUrl+'/ajax',data:{action:'get_paging_slick',p:$index,limit:$items,category:$category},beforeSend:function(){},success:function(data){$d=JSON.parse(data);jQuery.each($d.data,function(key,value){$start=(($index-1)*$items)+key;jQuery('.item.item-page-'+$index+'[data-slick-index='+$start+']').html(value).attr('data-loaded','true');});},error:function(err,req){},complete:function(){}});}});});
 
if(jQuery('.showmore_text').length>0){
jQuery('.showmore_text').showmore({

	  // text for Read more link
	  moreText: "Xem thêm",

	  // text for Read less link
	  lessText: "Thu gọn",

	  // Height of the collapsed content area
	  // numeric (pixels), px's ('100px'), em's ('6em'), or 'max-height'.
	  collapsedHeight: 'max-height',

	  // open / close animation duration
	  duration: 200, // consider migrating to animate

	  // callbacks
	  expand: undefined,
	  collapse: undefined,

	  // Built-in jQuery UI icon support
	  // requires jQuery UI CSS
	  showIcon: true
	  
	});
}
$( ".ui2-sortable" ).sortable();


$('.ui-sortable').sortable({
    items: "li:not(.ui-state-disabled)",
    start: function(event, ui) {
        var start_pos = ui.item.index();
        ui.item.data('start_pos', start_pos);
    },
    change: function(event, ui){
    	var start_pos = ui.item.data('start_pos');
        var index = ui.placeholder.index();
        console.log(start_pos + ' -> ' + index);
    },
    
});

});
function setItemRatio(){jQuery('[data-toggle="setRatio"]').each(function(i,e){$ratio=jQuery(e).attr('data-ratio')?parseFloat(jQuery(e).attr('data-ratio')):false;if($ratio!=false){$w=$ratio*jQuery(e).width();}});}
	
	function clearInput($t){var $this=jQuery($t);$this.find('input[text]').val('')}

function reloadAutoPlayFunction($t){
	jQuery('.auto_play_script_function').each(function(i,e){
		var $e = jQuery(e);
		var $showLoading = $e.attr('data-show-loading') ? true : false;
		if($t == true || !$e.hasClass('loaded')){	   
	    eval($e.val());
	    if($e.attr('data-remove')){ 
		   $e.remove();
	    }
	    if($e.attr('data-changed')){
		   $e.removeClass('auto_play_script_function').addClass('auto_play_script_function1');
	    }
	    $e.addClass('loaded');
	   
		}
	})
}

function reloadAutoPlayFunction1(){
	jQuery('.auto_play_script_function1').each(function(i,e){
	   var $e = jQuery(e);
	   eval($e.val());
	   if($e.attr('data-remove')){
		   $e.remove();
	   }
	  
	})
}

function chosen_select_init($t){
	jQuery('.chosen-select,.chosen-select-no-single,.chosen-select-no-search').each(function(i,e){
		var $this = jQuery(e);
		var $config = {search_contains:true,case_sensitive_search:true}
		$config['search_contains'] = $this.attr('data-search_contains') && $this.attr('data-search_contains') == 'false' ? false : true;
		$config['case_sensitive_search'] = $this.attr('data-case_sensitive_search') && $this.attr('data-case_sensitive_search') == 'false' ? false : true;
		$config['allow_single_deselect'] = $this.attr('data-allow_single_deselect') ? true : false;
		$config['disable_search'] = $this.attr('data-disable_search') ? true : false;
		$config['disable_search_threshold'] = $this.attr('data-disable_search_threshold') ? $this.attr('data-disable_search_threshold') : 10;
		$config['no_results_text'] = $this.attr('data-no_results_text') ? $this.attr('data-no_results_text') : 'Không tìm thấy kết quả phù hợp.';
		
		//console.log($config);
		
		if($this.attr('data-width')){
			$config['width'] = $this.attr('data-width');
		}
				
		if($t==true || $this.attr('data-loaded') == undefined){
			$this.chosen($config).attr('data-loaded',true);
		}
	});
}

function load_chosen_select($t){
	chosen_select_init($t);
	jQuery('select.ajax-chosen-select-ajax').each(function(index,element){
		if($t==true || jQuery(element).attr('data-loaded') == undefined){
		var $data = getAttributes(jQuery(element));
		$data['action'] = 'CHOSEN_AJAX';
		$data['role'] = $data.role ? $data.role : jQuery(element).attr('role');
		$data['dtype'] = $data.dtype ? $data.dtype : jQuery(element).attr('data-type');
		$data['data'] = $data;
		jQuery(element).ajaxChosen({
	     		 dataType: 'json',
	       		 type: 'POST',
	       		 data:$data,
	       		 url: $cfg.adminUrl + '/ajax/chosen_ajax',
	       		 search_contains:true,
	       		 allow_single_deselect:true,
	        		 
	          },{
	      		   loadingImg: $cfg.baseUrl+'/loading.gif'
	           })
	           //.removeClass('ajax-chosen-select-ajax'); 
		}
	            
	 	});
}

 
function __system_set_language($lang){}function showFullLoading(t){switch(t){case true:break;default:jQuery('body').append('<div class="fixed-loading-modal"></div>');break;}}
function changeLanguage($lang,$t){var $this=jQuery($t);
var $redirect=$this.attr('data-redirect');
var $cLang=$this.attr('data-lang');
var $r=false;if(!$redirect){}else{$r=true;window.location=$redirect+'/ajax?action=setLanguage&lang='+$lang;}
if(!$r&&$lang!=$cLang){
	jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{lang:$lang,action:'changeLanguage'},beforeSend:function(){showFullLoading();},success:function(data){window.location=$cfg.cBaseUrl;hideFullLoading();},error:function(err,req){}});}}function checkUserExisted($t){var $this=jQuery($t);$val=$this.val();var re=/^[\w]+$/;jQuery('.btn-submit').attr('disabled','');jQuery('.error-alert').html('<i class="loading"></i>').show();
	if(!re.test($val)){jQuery('.error-alert').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu cũ không đúng.').addClass('bg-danger').removeClass('bg-success');jQuery('.btn-submit').attr('disabled','');$this.focus();return false;}jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{val:$val,action:'checkUserExisted'},beforeSend:function(){jQuery('.error-alert').addClass('loading_ajax').html('Vui lòng chờ...').show();},success:function(data){var $d=JSON.parse(data);if($d.state){jQuery('.error-alert').html('<i class="glyphicon glyphicon-ok text-success"></i> Bạn có thể sử dụng tên này').addClass('bg-success').removeClass('bg-danger loading_ajax');jQuery('.btn-submit').removeAttr('disabled');}else{jQuery('.btn-submit').attr('disabled','');jQuery('.error-alert').html('<i class="glyphicon glyphicon-remove text-danger"></i> Tên đăng nhập không hợp lệ, hoặc đã được sử dụng.').addClass('bg-danger').removeClass('bg-success loading_ajax');}},error:function(err,req){}});}
	function checkOldPassword($t){var $this=jQuery($t);
	var $val=$this.val();
	var re=/^[\w]+$/;jQuery('.btn-submit').attr('disabled','');
	jQuery('.error-alert').html('<i class="loading"></i>').show();
	jQuery.ajax({type:'post',datatype:'json',url:$cfg.cBaseUrl+($cfg.is_admin?'/':'')+'ajax',data:{val:$val,action:'checkOldPassword'},beforeSend:function(){jQuery('.error-alert').addClass('loading_ajax').html('Vui lòng chờ...').show();},success:function(data){hideLoading3($t); var $d=JSON.parse(data);
	
	if($d.state){jQuery('.error-alert')
		.html('<i class="glyphicon glyphicon-ok text-success"></i> Nhập mật khẩu mới ở ô bên dưới.')
		.addClass('bg-success')
		.removeClass('bg-danger loading_ajax');
	$this.attr('disabled','');
	jQuery('.new_pass,.re_new_pass').removeAttr('readonly');jQuery('.new_pass').focus();
	jQuery("#password_old_status").html('');
	}
	else{jQuery('.btn-submit').attr('disabled','');
	jQuery('.error-alert').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu cũ không đúng.')
	.addClass('bg-danger').removeClass('bg-success loading_ajax');
	jQuery("#password_old_status").html('<span class="short_pass">Mật khẩu cũ không đúng.</span>');
	}},error:function(err,req){}});}
	
	function checkPassword(){
		var $p1=jQuery('.password1').val();
		var $p2=jQuery('.password2').val();
		var $p3=jQuery('.password3').val();
		//console.log($p1 + $p2 + $p3);
		if($p2==$p1){
			jQuery('.error-alert1').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu không được trùng với mật khẩu cũ.')
			.addClass('bg-danger')
			.removeClass('bg-success loading_ajax')
			.show();
			jQuery("#password_new_status").html('<span class="short_pass">Mật khẩu mới không được trùng với mật khẩu cũ.</span>');
		}else{
			if($p2!=$p3){if($p3.length==0){jQuery('.password3').focus();}else{
				jQuery('.error-alert1').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu mới không khớp.')
				.addClass('bg-danger')
				.removeClass('bg-success loading_ajax').show();	
				jQuery("#password_confirm_status").html('<span class="short_pass">Mật khẩu xác nhận không khớp.</span>');
			}
			}else{
				if($p2.length<5){
					jQuery('.error-alert1').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu phải có ít nhất 6 ký tự.')
					.addClass('bg-danger')
					.removeClass('bg-success loading_ajax').show();
				}else{
					jQuery('.btn-submit').removeAttr('disabled')
					.parent().removeClass('uiButtonDisabled'); 
					jQuery("#password_confirm_status").html('');
					jQuery('.error-alert1').remove();
				}
			}
		}
	}
	
	function hideFullLoading(){jQuery('.fixed-loading-modal').remove();}
		
	function popup_youtube(e){jQuery(e).colorbox({iframe:true,innerWidth:640,innerHeight:390});}function popup_colorbox(e){jQuery(e).colorbox({rel:jQuery(e).attr('data-rel')});}function setLocation($location){window.location=$location;}
	
	function goBack(){window.history.back();}var ChuSo=new Array(" không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín ");var Tien=new Array(""," nghìn"," triệu"," tỷ"," nghìn tỷ"," triệu tỷ");function DocSo3ChuSo(baso){var tram;var chuc;var donvi;var KetQua="";tram=parseInt(baso/100);chuc=parseInt((baso%100)/10);donvi=baso%10;if(tram==0&&chuc==0&&donvi==0)return"";if(tram!=0){KetQua+=ChuSo[tram]+" trăm ";if((chuc==0)&&(donvi!=0))KetQua+=" linh ";}if((chuc!=0)&&(chuc!=1)){KetQua+=ChuSo[chuc]+" mươi";if((chuc==0)&&(donvi!=0))KetQua=KetQua+" linh ";}if(chuc==1)KetQua+=" mười ";switch(donvi){case 1:if((chuc!=0)&&(chuc!=1)){KetQua+=" mốt ";}else{KetQua+=ChuSo[donvi];}break;case 5:if(chuc==0){KetQua+=ChuSo[donvi];}else{KetQua+=" lăm ";}break;default:if(donvi!=0){KetQua+=ChuSo[donvi];}break;}return KetQua;}function docso(SoTien){var lan=0;var i=0;var so=0;var KetQua="";var tmp="";var ViTri=new Array();if(SoTien<0)return"Số tiền âm !";if(SoTien==0)return"Không đồng !";if(SoTien>0){so=SoTien;}else{so=-SoTien;}if(SoTien>8999999999999999){return"Số quá lớn!";}ViTri[5]=Math.floor(so/1000000000000000);if(isNaN(ViTri[5]))ViTri[5]="0";so=so-parseFloat(ViTri[5].toString())*1000000000000000;ViTri[4]=Math.floor(so/1000000000000);if(isNaN(ViTri[4]))ViTri[4]="0";so=so-parseFloat(ViTri[4].toString())*1000000000000;ViTri[3]=Math.floor(so/1000000000);if(isNaN(ViTri[3]))ViTri[3]="0";so=so-parseFloat(ViTri[3].toString())*1000000000;ViTri[2]=parseInt(so/1000000);if(isNaN(ViTri[2]))ViTri[2]="0";ViTri[1]=parseInt((so%1000000)/1000);if(isNaN(ViTri[1]))ViTri[1]="0";ViTri[0]=parseInt(so%1000);if(isNaN(ViTri[0]))ViTri[0]="0";if(ViTri[5]>0){lan=5;}else if(ViTri[4]>0){lan=4;}else if(ViTri[3]>0){lan=3;}else if(ViTri[2]>0){lan=2;}else if(ViTri[1]>0){lan=1;}else{lan=0;}for(i=lan;i>=0;i--){tmp=DocSo3ChuSo(ViTri[i]);KetQua+=tmp;if(ViTri[i]>0)KetQua+=Tien[i];if((i>0)&&(tmp.length>0))KetQua+=',';}if(KetQua.substring(KetQua.length-1)==','){KetQua=KetQua.substring(0,KetQua.length-1);}KetQua=KetQua.substring(1,2).toUpperCase()+KetQua.substring(2);return KetQua;}function changeTourStyle(t){var $this=jQuery(t);$val=parseInt($this.val());$text=jQuery('.tour_type_text_'+$val).val();jQuery('.tour_start_text').val($text);switch($val){case 1:jQuery('.tour_start_multi').hide();jQuery('.table_depart_scheduler').show();jQuery('.tour_start_text').hide();jQuery('.group-item-price').hide();break;case 5:jQuery('.table_depart_scheduler').hide();jQuery('.tour_start_text').hide();jQuery('.tour_start_multi').show();jQuery('.group-item-price').show();break;default:jQuery('.table_depart_scheduler').hide();jQuery('.tour_start_text').show();jQuery('.tour_start_multi').hide();jQuery('.group-item-price').show();break;}}function reEnableInput($t){var $this=jQuery($t);$target=$this.attr('data-target');jQuery($target).find('input,textarea,button').removeAttr('disabled');jQuery('.btn-submit').html('<i class="glyphicon glyphicon-floppy-save"></i> Lưu lại');}function scrollToTop(){jQuery('html,body').animate({scrollTop:0},500);return false;}function scrollToDiv($div,$offset){if(jQuery($div).length>0){$offset=$offset>0?$offset:0;var $o=jQuery($div).offset().top-$offset;jQuery('html,body').animate({scrollTop:$o},500);return false;}}function scrollToDivX($div){if(jQuery($div).length>0){var $o=jQuery($div).offset().top-60;jQuery('html,body').animate({scrollTop:$o},500);return false;}else{}}
	function showModal($title,$content){
		 
		var $html='<div class="modal-dialog modal-sm">';
	$html+='<div class="modal-content">';
	$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';$html+='<div class="modal-body f12e">'+$content+'</div>'
$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button></div>';
	$html+='</div></div>';
	if(jQuery('.mymodal').length==0){
		jQuery('body').append('<div class="modal mymodal" tabindex="-1" role="dialog" aria-labelledby=""></div>');
	}
	jQuery('.mymodal').html($html).modal('show');
	}
	
	function showMModal($title,$content){
		var $html='<div class="modal-dialog ">';
		$html+='<div class="modal-content">';
		$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';
		$html+='<div class="modal-body f12e">'+$content+'</div>'
		$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button></div>';
		$html+='</div></div>';
		jQuery('.mymodal').html($html).modal('show');}
	function showXModal($content){
		var $html='<div class="modal-dialog ">';
		$html+='<div class="modal-content">';
		//$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';
		$html+='<div class="modal-body f12e">'+$content+'</div>'
		//$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
		$html+='</div></div>';
		jQuery('.mymodal').html($html).modal({'show':true,backdrop: 'static', keyboard: true});
		 
		}
	function showZModal($title,$content){ 
		var $html='<div class="modal-dialog ">';
		$html+='<div class="modal-content">';
		$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e bold" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';
		$html+='<div class="modal-body f12e">'+$content+'</div>'
		//$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
		$html+='</div></div>';
		jQuery('.mymodal').html($html).modal({'show':true,backdrop: 'static', keyboard: true});
		 
		}
	function showFModal($content,t){$html='';$this=jQuery(t);$today=jQuery.format.date(new Date(),"dd/MM/yyyy H:m");switch($content){case'changeAvatar':$html='<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjaxs(this);">';$html+='<input type="hidden" name="action" value="changeAvatar" />';$html+='<div class="modal-dialog" role="document">';$html+='<div class="modal-content">';$html+='<div class="modal-header">';$html+='<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';$html+='<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">Thay đổi hình đại diện</h4>';$html+='</div>';$html+='<div class="modal-body">';$html+='<section class="addCustomer addCashflow showAnimate uln control-poup">';$html+='<section class="boxInfo lbl-cl">';$html+='<article class="boxForm uln fll w100 mb10">';$html+='<div class="form-group">';$html+='<div class="col-sm-10">';$html+='<div class="browser_images"><div class="form-group col-sm-9">';$html+='<input type="file" class="form-control input-sm " name="myfile" id="myfile" />';$html+='</div>';$html+='<button data-name="biz" type="button" data-index="0" class="btn btn-default btn-sm btn-sd" onclick="return ajaxUploadFiles(this);" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';$html+='<div class="col-sm-12"><div id="progress-group" class="" ></div><div class="" id="respon_image_uploaded"></div></div></div>';$html+='</div>';$html+='</div>';$html+='</article>';$html+='</section>';$html+='</section>';$html+='</div>';$html+='<div class="modal-footer">';$html+='<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Cập nhật</button>';$html+='<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';$html+='</div>';$html+='</div>';$html+='</div>';$html+='</form>';break;default:break;}jQuery('.mymodal').html($html).modal('show');}function randomStr($l){var text="";var possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";for(var i=0;i<$l;i++)text+=possible.charAt(Math.floor(Math.random()*possible.length));return text;}function getLocalByParent(t){var $this=jQuery(t);$new=$this.val();jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'getLocalByParent',val:$new},beforeSend:function(){},success:function(data){$target=jQuery($this.attr('data-target'));$target.html(data);if($target.hasClass('select2')){$target.trigger('change');}},complete:function(){},error:function(err,req){}});}function removeErrorField($t){jQuery('form .error_alert').remove();}function showErrorField($t){var $this=jQuery($t);jQuery('form .error_alert').remove();$text=$this.attr('data-alert')?$this.attr('data-alert'):$cfg.text[210];$p='<p class="error_alert bg-danger pd15 mgt10"><i class="glyphicon glyphicon-remove"></i> '+$text+'</p>';$this.parent().append($p);}function showSuccessField($t){var $this=jQuery($t);jQuery('form .error_alert').remove();$text=$this.attr('data-success')?$this.attr('data-success'):$cfg.text[211];$p='<p class="error_alert bg-success pd15 mgt10"><i class="glyphicon glyphicon-ok"></i> '+$text+'</p>';$this.parent().append($p);}function refreshCaptcha($t){document.getElementById('captcha_image').src=$cfg.libsDir+'/captcha/?'+Math.random();document.getElementById('input-captcha-code').value='';document.getElementById('input-captcha-code').focus();return false;}function checkMemberExisteds($t){$j=jQuery;$this=$j($t);$val=$this.val();$field=$this.attr('data-field');$id=$this.attr('data-id');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'checkMemberExisteds',val:$val,field:$field,id:$id},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state>0){$j('.submitFormBtn').attr('disabled','');showErrorField($t);}else{$j('.submitFormBtn').removeAttr('disabled');showSuccessField($t);}},complete:function(){},error:function(err,req){}});}function checkCaptcha($t){$j=jQuery;$this=$j($t);$val=$this.val();$field=$this.attr('data-field');$id=$this.attr('data-id');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'checkCaptcha',val:$val},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state>0){$j('.submitFormBtn').attr('disabled','');$j($t).addClass('error');return false;}else{$j('.submitFormBtn').removeAttr('disabled');jQuery('form .error_alert').remove();$j($t).removeClass('error').addClass('success');}},complete:function(){},error:function(err,req){}});}function checkCurrentPassword($t){$j=jQuery;$this=$j($t);$type=$this.attr('data-type');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'checkCurrentPassword',val:$this.val()},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state>0){removeErrorField($t);$j('.inputFormDisabled').removeAttr('disabled');}else{showErrorField($t);}},complete:function(){$j('.inputFormFocus').focus();},error:function(err,req){}});}function parseValue($t){var $this=jQuery($t);$target=jQuery($this.attr('data-target'));jQuery('form .error_alert').remove();if($this.val()!=$target.val()){showErrorField($t);return false;}return true;}
function removeTrItem($t,$x){var $this=jQuery($t);
var $c = $this.attr('data-count') ? parseInt($this.attr('data-count')) : $x;switch($c){case 4:$this.parent().parent().parent().parent().remove();break;case 3:$this.parent().parent().parent().remove();break;case 1:$this.parent().remove();break;default:$this.parent().parent().remove();break;}}function change_departure_place_book($t){var $this=jQuery($t);$price=($this.attr('data-price'));$id=parseInt($this.attr('data-id'));$target=jQuery($this.attr('data-target'));$target2=jQuery($this.attr('data-xtarget'));$date=$this.attr('data-date');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'change_departure_place_book',depart:$this.val(),id:$id,date:$date,price:$price},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state){$target.html($d.price);$target2.attr('data-depart',$this.val())}},complete:function(){},error:function(err,req){}});return true;}

function btn_book_tour($t){ 
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $token_string=jQuery('meta[name="csrf-token"]').attr('content');
	
	
	var $href=$cfg.baseUrl+'/booking?token='+$token_string;
	$href+='&id='+$data['id'];
	$href+='&tour_type='+$data['tour_type'];
	$href+='&tour_start='+$data['tour_start'];
	$href+='&tour_hotel='+$data['tour_hotel'];
	$href+='&tour_date_time='+$data['tour_date_time'];
	
	window.location=$href;
}

function get_item_price_from_depart($t,$departure,$date){var $this=jQuery($t);$date=jQuery('#date_departure').length>0?jQuery('#date_departure').val():$date;$departure=jQuery('#departure_local').length>0?jQuery('#departure_local').val():$departure;$rating_service=jQuery('#rating_service').length>0?jQuery('#rating_service').val():-1;$filter_tour_type=jQuery('#filters_tour_type').length>0?jQuery('#filters_tour_type').val():-1;$filter_tour_group=jQuery('#filter_tour_group').length>0?jQuery('#filter_tour_group').val():-1;$id=parseInt($this.attr('data-id'));$target=jQuery($this.attr('data-target'));jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'get_item_price_from_depart',departure:$departure,id:$id,date:$date,rating_service:$rating_service,filter_tour_type:$filter_tour_type,filter_tour_group:$filter_tour_group},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state){if(parseInt($d.xprice)<=0){$target.html('<button type="submit" class="btn btn-link f19px"><i class="glyphicon glyphicon-hand-right "></i> '+$cfg.text[2]+'</button>');jQuery('.t_price_change_currency,.t_price_change_prec').hide();jQuery('.submit-booktour').html($cfg.text[198]);}else{$target.html($d.price);jQuery('.t_price_change_currency,.t_price_change_prec').show();jQuery('.submit-booktour').html($cfg.text[35]);}if(parseInt($filter_tour_type)==3){jQuery('#departure_scheduler_from_item_price').slideUp();jQuery('.date_available_select').hide();jQuery('.group_filter_tour_group_depart_select').show();jQuery('.group_filter_tour_group_depart_select_none').hide();}else{jQuery('.date_available_select').show();jQuery('.group_filter_tour_group_depart_select').hide();$idd=jQuery('.group_filter_tour_group_depart_select_none').attr('data-id');jQuery('#departure_local').val($idd).change();jQuery('.group_filter_tour_group_depart_select_none').show();jQuery('#departure_scheduler_from_item_price').slideDown();}}else{$target.html('');}},complete:function(){},error:function(err,req){}});return true;}
function validate_seo_preview($t){
	var $this=jQuery($t);
	var $min=parseInt($this.attr('data-min'));
	var $max=parseInt($this.attr('data-max'));
	var $role=($this.attr('data-role'));
	var $target=jQuery($this.attr('data-target')).find('.progress-bar');
	var $prev=jQuery('.seo_preview').find('.preview-'+$role);
	var $val=$this.val();
	var $len=$val.length;
	var $du=0; var $cl;
	//console.log($len)
	if($len<$min){$cl='progress-bar-warning';$c1='';}else{if($len<$max+1){$cl='progress-bar-success';$c1='';}else{$cl='progress-bar-danger';$c1='danger';$du=$len-$max;}}$w=$len/$max*80;$w=$w>100?100:$w;if($role=='url'){jQuery.ajax({type:'post',datatype:'json',url:$cfg.cBaseUrl+'/ajax',data:{action:'get_item_link',url:$val,},beforeSend:function(){},success:function(data){$prev.html(data);},complete:function(){},error:function(err,req){}});}else{$prev.html($val);$target.html($len+' ký tự '+($du>0?'<i>('+($du*-1)+')</i>':'')).css({"width":$w+"%"}).removeClass('progress-bar-warning progress-bar-success progress-bar-danger').addClass($cl);}}function show_datepicker_by_item($t){var $this=jQuery($t);$id=$this.attr('data-id');$target=jQuery('#respon_date_ch_'+$id);$mindate=$this.attr('data-date');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'show_datepicker_by_item',id:$id,},beforeSend:function(){},success:function(data){$d=JSON.parse(data);$target.datepicker({dateFormat:'dd/mm/yy',beforeShowDay:function(date){dmy=date.getDate()+"/"+(date.getMonth()+1)+'/'+date.getFullYear();return[($.inArray(dmy,$d.availableDates)!=-1),""];},minDate:$mindate,maxDate:'+1y',regional:"vi",onSelect:function(){get_price_from_task_scheduler(this);}}).focus();},complete:function(){},error:function(err,req){}});}function get_price_from_task_scheduler($t){var $this=jQuery($t);$id=$this.attr('data-id');$date=$this.val();$target_price=$this.parent().parent().find('.tour_pricex_rp');$target_book=$this.parent().parent().find('.btn-book-tour-rp');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'get_price_from_task_scheduler',id:$id,date:$date},beforeSend:function(){},success:function(data){$d=JSON.parse(data);$target_price.html($d.price)
$target_book.attr('data-date',$d.date)},complete:function(){},error:function(err,req){}});}function getText($id){alert($cfg.text[210])
$text='a';jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'get_text',id:$id},beforeSend:function(){},success:function(data){},complete:function(){},error:function(err,req){}});return $text;}function set_checked_bool($t){if(jQuery($t).is(':checked')){jQuery($t).val(1);}else{jQuery($t).val(0);}}function view_obj(obj){var propValue;for(var propName in obj){propValue=obj[propName]
console.log(propName,propValue);}}
function add_to_cart($t){
	var $this=jQuery($t);
	var $data = getAttributes($this);
	var $id=$this.attr('data-id')?$this.attr('data-id'):false;
	var $role=$this.attr('data-role')?$this.attr('data-role'):'push';
	var $amount=$this.attr('data-amount')?
			$this.attr('data-amount'):(jQuery('.cart-item-quantity-'+$id).length>0?
					jQuery('.cart-item-quantity-'+$id):$this.parent().find('.cart-item-quantity').val());
			$amount=parseInt($amount)>0?parseInt($amount):1;
			if($id!==false){update_cart('add',$id,$amount,$role);}
	show_popup_to_cart();
	return false;
	}
function show_popup_to_cart(){jQuery('.modal').remove();
var $html='<div class="modal cart_alert fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content">';
$html+='<div class="alert f12e alert-default alert-dismissible fade in success" role="alert"><i class="glyphicon glyphicon-ok success"></i> Thêm vào giỏ hàng thành công.</div>';
$html+='</div></div></div>';
jQuery('body').append($html);
var $modal=jQuery('.modal').modal('show');
window.setTimeout(function(){jQuery('.modal').modal('hide');},1500);}


function gotoUrl($location,delay){
	//window.location=$location;
	delay = delay > 0 ? delay : 0;
	window.setTimeout(function(){
		window.location=$location;
	},delay);
}
function reload(delay){
	delay = delay > 0 ? delay : 0;
	window.setTimeout(function(){
		window.location=window.location;
	},delay);
}

function cart_update_item_quantity($t){ 
	var $this=jQuery($t);
	var $id=$this.attr('data-id')?$this.attr('data-id'):false;
	var $role=$this.attr('data-role')?$this.attr('data-role'):'add';
	var $item=jQuery('.item-quantity-'+$id);
	var $val=parseInt($item.val());
	if($role=='sub'){$val--;}else{$val++;}$val=$val>1?$val:1;$item.val($val);update_cart('update',$id,$val,'update');}
function cart_remove_item($t){
	var $this=jQuery($t);
	var $id=$this.attr('data-id')?$this.attr('data-id'):false;
	update_cart('delete',$id,0,'delete');}
function cart_reload($d){
	
	jQuery('.cart-total-price').html($d.cart['totalPrice']);
	jQuery('.cart-total-item').html($d.cart['totalItem']);
	//jQuery('.cart-total-item-'+$id).html($d.cart['changeSubTotal']);
	jQuery('.cart-total-price-text').html($d.cart['totalPriceText']);}
function update_cart($behavior,$id,$amount,$role){
	jQuery.ajax({type:'post',datatype:'json',url:$cfg.absoluteUrl+'/sajax',data:{role:$role,id:$id,amount:$amount,behavior:$behavior,action:'update_cart'},beforeSend:function(){console.log($cfg.absoluteUrl+'/sajax');showFullLoading();},success:function(data){console.log(data);var $d=JSON.parse(data);cart_reload($d);},error:function(err,req){},complete:function(){switch($behavior){case'add':break;case'delete':jQuery('.cart-item-id-'+$id).remove();break;default:break;}switch($role){case'buynow':case'buy':window.location='/cart';break;}hideFullLoading();}});}function cart_show_company($t){var $this=jQuery($t);$ck=$this.is(':checked');$c=jQuery('.cart-company-information');if($ck){$c.show();$c.find('.srequired').addClass('required');$c.find('.focus').focus();}else{$c.hide();$c.find('.required').removeClass('required');}}function cart_show_receiver_information($t){var $this=jQuery($t);$ck=$this.is(':checked');$c=jQuery('.cart-receiver-information');if(!$ck){$c.show();$c.find('.srequired').addClass('required');$c.find('.focus').focus();}else{$c.hide();$c.find('.required').removeClass('required');}}function show_paging($t){var $this=jQuery($t);if(!$this.hasClass('active')){$loading=jQuery('<div class="ajax-loading-paging-data"></div>');$option=$this.attr('data-option')?$this.attr('data-option'):'';$p=$this.attr('data-page')?$this.attr('data-page'):($this.attr('data-p')?$this.attr('data-p'):1);$limit=$this.attr('data-limit')?$this.attr('data-limit'):10;$role=$this.attr('data-role')?$this.attr('data-role'):'';$category=$this.attr('data-category')?$this.attr('data-category'):-1;$box_id=$this.attr('data-box_id')?$this.attr('data-box_id'):0;$p=parseInt($p)>1?parseInt($p):1;$this.parent().find('.active').removeClass('active');$this.parent().find('.page-'+$p).addClass('active');$target=$this.parent().parent().find('.ajax-data-result');switch($role){case'slick-goto':$parent=jQuery($this.attr('data-parent'));$dots=$parent.find('.btn-custom-paging.index-'+$p);if($dots.length>0){$dots.click();}else{}break;case'normal':break;default:if($target.find('.item-page-'+$p).length>0){$target.find('.item').slideUp();$target.find('.item-page-'+$p).slideDown()}else{jQuery.ajax({type:'post',datatype:'jsonp',url:$cfg.baseUrl+'/ajax',data:{action:'get_paging',option:$option,p:$p,limit:$limit,box_id:$box_id},beforeSend:function(){if($target.find('.ajax-loading-paging-data').length>0){}else{$loading.appendTo($target)}},success:function(data){$d=JSON.parse(data);$target.find('.item').hide();$target.append($d.html)},error:function(err,req){$loading.remove();},complete:function(){$loading.remove();}});}break;}}}function goto_google_search($t){var $this=jQuery($t);var $key=$this.find('.search-keyword');if($key.val()==""){$key.focus();return false;}key=$key.val().split(" ");var q=key[0];for(i=1;i<key.length;i++){q=q+"+"+key[i];}var href='https://www.google.com.vn/#hl=vi&sclient=psy-ab&q=site:'+$cfg.domain+' '+q+'&oq='+q;window.open(href,'_blank');return false;}function change_device($t){var $this=jQuery($t);jQuery.post($cfg.baseUrl+'/sajax/change_device',function(r){window.location=window.location.href},'json');}
function checkRequiredField($t){
	var $this=jQuery($t);
	var $submit=true;
	if($submit){
		$this.find('input.required,textarea.required,email.required').each(function(i,e){
			var $e=jQuery(e);	
			if($e.val().trim()==""){
				$e.focus();
				$submit = false;
				return false;
			}
		})
	}
	return $submit;
}
function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}
function ajaxSubmitForm(t){
	var $this=jQuery(t); var $href; 
	if($this.attr('data-action')=='current'){
		var $href=$this.attr('action')!=""?$this.attr('action'):window.location.href;}else{
		var	$href=$cfg.cBaseUrl+'/ajax';}
	switch($this.attr('data-action')){ case'current':$href=$this.attr('action')!=""?$this.attr('action'):window.location.href;break;case'sajax':$href=$cfg.baseUrl+'/sajax';break;default:$href=$cfg.cBaseUrl+'/ajax';break;}
	$submit=true;
	 
	jQuery('.er').remove();
	$ckc=true;jQuery('.error.check_error').each(function(i,e){$submit=false;jQuery(e).focus();
	$er=jQuery(e).parent().find('.error_field');if($er.length==0){$er=jQuery('<div class="error_field"></div>');
	jQuery(e).parent().append($er);}$erText=jQuery(e).attr('data-alert')?jQuery(e).attr('data-alert'):'';
	$erText=$erText.replace(/{VAL}/g,jQuery(e).val());$er.html($erText);return false;});
	if($submit){$this.find('input.required,textarea.required,email.required').each(function(i,e){$e=jQuery(e);
	if($e.val().trim()==""){$e.focus();
	if($e.attr('data-select')=='select2'){$e.parent().find('.select2-selection').addClass('error');}$ckc=false;return false;}});
	
	if($this.find('.cke').length>0)CKupdate();
	if($this.attr('data-confirm')){
		$ckc = confirm($this.attr('data-confirm'));
	}
	
	
	if(!$ckc)return false;
	var $d = {};
	jQuery.ajax({type:'post',datatype:'json',url:$href,data:$this.serialize(),
		beforeSend:function(){
			//console.log($href);
			showFullLoading();},
		success:function(data){
	
		hideFullLoading();
		if(data!=""){ 
			$d=JSON.parse(data);
			if($d.error==true){showModal('Thông báo',$d.error_content)}else{if($d.modal==true){showModal('Thông báo',$d.modal_content)
$timeout=$d.delay!=undefined?$d.delay:0;if($timeout>0){window.setTimeout(function(){$modal=jQuery('.mymodal');$modal.modal('hide');},$timeout);}}}
		if($d.redirect==true){window.location=$d.target;}
		if($d.callback){eval($d.callback_function);}
		if($d.event!=undefined){switch($d.event){case'preview_order':jQuery('.preview_order').html($d.text);break;  case'quick-add-more-nationality-group-to-tickets':$target=jQuery('.ajax-load-group-nationality');$modal=jQuery('.mymodal1');$modal.modal('hide');$target.append($d.html);jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed);reload_app('number-format');break;case'quick_update_seo':jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');$this.find('input,textarea').attr('disabled','disabled');break;case'edit_user_success':jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');$this.find('input').attr('disabled','disabled');break;case'submit-controller-form':show_left_small_loading('show');show_left_small_loading('hide');break;case'hide-modal':$modal=jQuery('.mymodal');$modal.modal('hide');break;case'relogin':jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');$this.find('input').attr('disabled','disabled');window.location=$d.target;break;case'forgot':if(!$d.state){$r='<p class="text-danger bg-danger pd15"><i class="glyphicon glyphicon-remove"></i> Rất tiếc! Hệ thống không tìm thấy thông tin tài khoản của bạn, vui lòng kiểm tra lại.</p>';jQuery('.error_respon').html($r);}else{$r='<p class="text-success bg-success pd15"><i class="glyphicon glyphicon-ok"></i> Thông tin khôi phục đã được gửi tới email <b>'+$d.email+'</b>.<br/>Vui lòng kiểm tra email và làm theo hướng dẫn.</p>';jQuery('.error_respon').html($r);jQuery('.remove-after-submit').remove();}break;case'quick-add-more-season-to-supplier':$target=jQuery($d.target);$target.append($d.html);$modal=jQuery('.mymodal');$modal.modal('hide');jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
reload_app('switch-btn');break;
case'quick-add-more-room-to-hotel':$target=jQuery('.ajax-result-quick-add-more-before');$target.before($d.html);
$modal=jQuery('.mymodal');$modal.modal('hide');jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
reload_app('switch-btn');break;
case'quick-add-more-hight-way':$target=jQuery('.ajax-result-more-hight-way');
$target.append($d.html);$modal=jQuery('.mymodal');$modal.modal('hide');
jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
reload_app('switch-btn');break;
case'add_new_cost_distance':jQuery.each($d.r,function($i,$e){jQuery('.'+$d.target_class+$i).append($e);});jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed).attr('data-count',$d.index);jQuery('.mymodal').modal('hide');reload_app('chosen');reload_app('select2');reload_app('number-format');reload_app('switch-btn');break;case'set_quantity_vehicles_categorys':case'set_quantity_currency':jQuery('.'+$d.target_class).before($d.html);jQuery('.'+$d.target_class).find('.btn-add-more').attr('data-existed',$d.existed_id).attr('data-count',$d.index);jQuery('.mymodal').modal('hide');break;case'quick_add_more_vehicle_category':jQuery('.mymodal1').modal('hide');jQuery('.mymodal').modal('show');get_list_vehicles_makers('#select-chon-xe');break;case'_tour_program_add_service':$pr=jQuery($d.parent);$target=$pr.find($d.target);$target.before($d.html);$c=$target.find('.btn-option .btn-count-array');$cx=parseInt($c.attr('data-count'))>0?parseInt($c.attr('data-count')):0;$c.attr('data-count',$cx+1);tour_program_calculation_price();jQuery('.mymodal').modal('hide');reload_app('number-format');reload_app('chosen');break;case'_tour_program_edit_service':$pr=jQuery($d.parent);$target=$pr.find($d.target);$target.replaceWith($d.html);tour_program_calculation_price();jQuery('.mymodal').modal('hide');reload_app('number-format');reload_app('chosen');break;case'quick_edit_field':jQuery($d.target).html($d.title);jQuery('.mymodal').modal('hide');break;case'redirect_link':$timeout=$d.delay!=undefined?$d.delay:0;window.setTimeout(function(){window.location=$d.target;},$timeout);break;case'clearInput':jQuery($d.target).val('');break;case'reload':$timeout=$d.delay!=undefined?$d.delay:0;window.setTimeout(function(){window.location=window.location;},$timeout);break;case'add_loai_thu_chi':jQuery('.mymodal1').modal('hide');jQuery($d.target).html($d.select).trigger("chosen:updated").change();break;case'chon_khach_san':$action=$d.action;switch($action){case'add':$tbody=jQuery('.select_hotel_option_'+$d.option).find('.private-row-hotel-'+$d.pindex);$v=parseInt(jQuery('#numberOfHotel').val())+1;jQuery('#numberOfHotel').val($v);$target=jQuery($d.target);$target.attr('data-index',$d.index);$tbody.before($d.price);break;default:$input_name=jQuery('.input-hotel-name-'+$d.pindex+'-'+$d.index);$input_star=jQuery('.input-hotel-star-'+$d.pindex+'-'+$d.index);$input_name.val($d.hotel['name']);$input_star.val($d.hotel['star']);$tbody=jQuery('.hotel-detail-body-index-'+$d.option+'-'+$d.pindex+'-'+$d.index);$tbody.html($d.price);break;}jQuery('.mymodal').modal('hide');reload_app('number_format');changeHotelCost(jQuery('.sl-hotel-cost-amount'));reloadCost();break;case'chon_xe':$action=$d.action;switch($action){case'add':$tbody=jQuery('.public-row-car-0');$v=parseInt(jQuery('#numberOfCar').val())+1;jQuery('#numberOfCar').val($v);$tbody.before($d.price);jQuery('.btn-add-more-transport').attr('data-index',$d.index);break;default:$input_name=jQuery('.input-car-name-'+$d.index);$input_name.val($d.item['name']);$tbody=jQuery('.car-detail-body-index-'+$d.index);$tbody.html($d.price);break;}jQuery('.mymodal').modal('hide');reload_app('number-format');reloadCost();break;case'checkInError':$e=jQuery('.cError');switch($d.error_code){case'SUCCESS':$e.html('<p>Điểm danh thành công.</p>');break;case'CHECKED':$e.html('<p>Bạn đã điểm danh rồi.</p>');break;case'USER_NOT_EXIST':$e.html('<p>Không tìm thấy tài khoản.</p>');break;case'NOT_FOUND':$e.html('<p>Không tìm thấy lớp học.</p>');break;}break;case'them_danh_muc_chiphi':jQuery('#addCostCateID').append('<option ="'+$d.data['id']+'" selected>'+$d.data['name']+'</option>').trigger("chosen:updated");;jQuery('.mymodal1').modal('hide');break;}}}},
complete: function(){
	 if($d !== false && $d != null && $d.complete){
		 eval($d.complete_function);	    		 
	 }
	//// console.log($d)
 },
error:function(err,req){hideFullLoading();}

	});}return false;}
function openPrint(){window.print();window.onfocus=function(){ window.close();}}
function changeLayoutProductView($t){
	var $this = jQuery($t);
	$layout = $this.attr('data-layout');
	$target = $this.attr('data-target');
	$tx = jQuery('.'+$target);
	switch($layout){
	case 'list': 
		$tx.css({"height":''});
		$tx.find('.item-name-fix-height').css({"height":''});
		break;
	case 'grid': 
		//$tx.addClass($target) ;
		//setAutoHeightElement();
		break;
	}
}
function setAutoHeightElement(){
	$c = {};
	jQuery('.auto-height-element').each(function(i,e){
		var $e = jQuery(e);
		var $g = $e.attr('data-group');
		$h = $e.height();
		if($c[$g] == undefined){
			$c[$g] = $h;
		}else{
			if($h > $c[$g]){
				$c[$g] = $h;
			}
		}
		
	});
	jQuery.each($c,function(i,e){
		//console.log(i + '/' + e)
		jQuery('.auto-height-element-'+i).height(e)
	}); 
}
function parseJsonData(msg){
	//var IS_JSON = true;
    try
    {
    	//console.log(jQuery.parseJSON(msg).name);
        return jQuery.parseJSON(msg);
             
    }
    catch(err)
    {
        //  IS_JSON = false;
        return false;
    }         
}
function getAttributes ( $node ) {
    var attrs = {};
    if(!$node.jquery){
    	$node = jQuery($node);
    }
    //if($node[0].attributes){
    jQuery.each( $node[0].attributes, function ( index, attribute ) {
    	var res =  attribute.name.substr(0, 5);
    	if(res== 'data-'){
    		var $name = attribute.name.replace(/data-/g,'');
        	attrs[$name] = attribute.value;
    	}
    } );
    //}
    return attrs;
}
function loadChildsProvinces($t){
	var $this = jQuery($t);
	var $val = $this.val();
	var $data = getAttributes($this);
	var $level = parseInt($this.attr('data-level'));
	$data['action'] = 'loadChildsProvinces';
	$data['parent_id'] = $val;
	jQuery.ajax({
		type:'post',
		datatype:'json',
		url:$cfg.baseUrl+'/sajax',
		data:$data,
		beforeSend:function(){
			hideFullLoading();
		},
		success:function(data){
			var $d = parseJsonData(data);
			var $target_input = jQuery($d.target);
			var $selected_value = parseInt($target_input.attr('data-selected'));
			//console.log($selected_value);
			$target_input
			.html($d.html)
			.attr('data-parent_id',$val);
			if($selected_value>0){
				$target_input.val($selected_value);
			}
			 
			jQuery($d.target_input).val($d.local_id);
			if($level != 2){
				
				$target_input.trigger("chosen:updated").change();
			}else{
				$target_input.trigger("chosen:updated");
				if(parseInt($target_input.val()) > 0){
					$target_input.change();
				}
			}
			$target_input.chosen("destroy");
			load_chosen_select(true);
			//re_load_chosen_select();
		},
		error:function(err,req){}
	});
	
}
function changeDayOfYear($t){
	var $this = jQuery($t);
	var $pr = $this.parent().parent().parent().parent();
	var $role = parseInt($this.attr('data-role'));
	//console.log($role);
	switch ($role) {
	case 0: // nam
		jQuery($this.attr('data-target')).attr('data-year',$this.val()).trigger("chosen:updated").change();
		break;
	case 2:
		jQuery($this.attr('data-target')).val($this.attr('data-year')+'-'+$this.attr('data-month')+'-'+$this.val());
		break;
	default: // thang
		var $year = parseInt($this.attr('data-year'));
		var $month = parseInt($this.val());
		var $day = 31;
		switch ($month){
		case 1:case 3:case 5:case 7:case 8:case 10:case 12:
			$day = 31;
			break;
		case 4:case 6:case 9:case 11:
			$day = 30;
			break;
		case 2:
			if($year % 400 == 0 ){
				$day = 29;
			}else{
				if($year % 4 == 0){
					$day = 29;
				}else{
					$day = 28;
				}
			}
			break;
		}
		var $target = jQuery($this.attr('data-target'));
		$target.find('option').removeAttr('disabled');
		var $vt = parseInt($target.val());
		if($vt>$day){
			$target.val($day)
		}
		for($i = $day+1; $i<32;$i++){
			$target.find('option[value="'+$i+'"]').attr('disabled','');
		}
		$target.attr({'data-year':$this.attr('data-year'),'data-month':$this.val()}).trigger("chosen:updated").change();
		break;
	}
}
function resizeItem4x3(){
	jQuery('.auto_rz_4x3').each(function(i,e){
		var $this=jQuery(e);
		var $w=$this.width();
		var $h=$w*3/4;
		$this.addClass('autow'+$w).height($h);
		//$this.find('img').addClass('mw100p');
	});
}
function setCookieExpried($t){
	var $this=jQuery($t);
	var $time = parseInt($this.attr('data-time'));
	var $value = ($this.attr('data-value'));
	var $name = ($this.attr('data-name'));
	var date = new Date();
	//var minutes = 0.1;
	date.setTime(date.getTime() + ($time * 60 * 1000));
	Cookies.set($name, $value, { expires: date });
	//console.log(Cookies.get('name') + date);
	
}
function getDeviceWidth() {
	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	//var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;	
	return width;
} 
function getDeviceHeight() {
	//var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;	
	return height;
}

function showCalendar2($t){
	var $this=jQuery($t);
	var $data = getAttributes($this);
	
	jQuery($this.attr('data-target')).datetimepicker('show');
	
}

function load_datetimepicker2(){
	
	
	jQuery('.datetimepicker2,.datepicker2').each(function(i,e){
		
		var $e = jQuery(e);
		
		if($e.attr('data-loaded') == undefined){
			//jQuery.datetimepicker.setLocale('vi');
			if ($e.attr('data-lang')){
				jQuery.datetimepicker.setLocale($e.attr('data-lang'));
			}
			var $format = $e.attr('data-format') ? $e.attr('data-format') : 'd/m/Y H:i:s';
			var $time = $e.attr('data-time') ? true : false;
			var $mask = $e.attr('data-mask') ? true : false;
			var $month = $e.attr('data-month') ? $e.attr('data-month') : 1;
			var $minDate = $e.attr('data-minDate') ? $e.attr('data-minDate') : false;
			var $maxDate = $e.attr('data-maxDate') ? $e.attr('data-maxDate') : false;
			var $day_of_week = $e.attr('data-day_of_week') ? $e.attr('data-day_of_week') : false;
			
			var $disabledDates = $e.attr('data-disabledDates') ? $e.attr('data-disabledDates') : '';
			var $disabledWeekDays = $e.attr('data-disabledWeekDays') ? $e.attr('data-disabledWeekDays') : '';
			if($disabledDates != ''){
				$disabledDates = $disabledDates.split(',');
			}else{
				$disabledDates = [];
			}
			if($disabledWeekDays != ''){
				$disabledWeekDays = $disabledWeekDays.split(',').map(parseFloat);;
			}else{
				$disabledWeekDays = [];
			}
			var $data = {};
			$data['timepicker'] = $time;
			$data['format'] = $format;
			$data['numberOfMonths'] = $month;
			$data['mask'] = $mask;
			$data['lang'] = 'vi';
			$data['minDate'] = $minDate;
			$data['maxDate'] = $maxDate;
			$data['disabledDates'] = $disabledDates;
			$data['disabledWeekDays'] = $disabledWeekDays;
			//$data['lang'] = 'vi'; 
			$data['parentID'] = $e.attr('data-parentID') ? $e.attr('data-parentID') : 'body';
			
			if($day_of_week !== false && $day_of_week != ""){
				$day_of_week = $day_of_week.split(',').map(Number);
				var $b = {};
				
				$data['beforeShowDay'] = function(date){
					
					return [(jQuery.inArray((date.getDay()), $day_of_week) != -1),""];
				}
			}
			//$data['onGenerate'] = function(current_time,$input){
				//console.log(current_time)
				//console.log($input); 
				//jQuery($input).find('input').val(current_time.dateFormat('d/m/Y'))
			//}
			 
			
			$e.datetimepicker($data).attr('data-loaded',true); 
		}
	});
}
function load_datetimepicker(){
	jQuery('.ajax-datetimepicker,ajax-timepicker,.ajax-datepicker,.datetimepicker,.datepicker,timepicker').each(function(i,e){
		var $e = jQuery(e);
		if($e.attr('data-loaded') == undefined){
			var $format = $e.attr('data-format') ? $e.attr('data-format') : 'd/m/Y H:i:s';
			$e.datetimepicker({
			//language:'vi',//dateFormat:'DD/MM/YYYY',
				format:$format,
			//pickTime:false
			}).attr('data-loaded',true); 
		}
	})
}
function load_number_format(){
	jQuery('.ajax-number-format,.number-format,.numberFormat').each(function(i,e){
		var $e = jQuery(e);
		if($e.attr('data-loaded') == undefined){
			$d = $e.attr('data-decimal') ? $e.attr('data-decimal') : 0; 
			$e.number(true,$d).attr('data-loaded',true); 
		}
	})
}
function disabledFnKey($t){
	if (event.keyCode==13){return false;}
}
function split( val ) {
    return val.split( /,\s*/ );
}
function extractLast( term ) {
    return split( term ).pop();
}
function loadTagsInput(){
	jQuery('.tagsinput').each(function(i,e){
		var $e = jQuery(e);
		//console.log(e);
		jQuery(e).tagsinput({
			
		})
		if($e.hasClass('autocomplete')){
			$e.parent().find('.bootstrap-tagsinput input').addClass('autocomplete').attr('data-action',$e.attr('data-action'))
			.attr('onkeypress','if (event.keyCode==13){return false;}')
			.attr('data-delimiter', $e.attr('data-delimiter') ? $e.attr('data-delimiter') : ';') 
		//if($e.attr('data-loaded') == undefined){
		//	jQuery(e).tagsinput('items').attr('data-loaded',true);
		//}
		$e.removeClass('autocomplete');
		}
	});
}
function sentEmailTo($t){
	var $this=jQuery($t);
	var $data = getAttributes($this);
	
	return false;
}
function showHistory($t){
	var $this=jQuery($t);
	var $data = getAttributes($this);
	showModal('Thông báo','Chức năng đang xây dựng');
	
	return false;
}
function log(e){
	console.log(e);
}
function showLoading($loading){
	switch($loading){
	case 'fb2':
		var div = jQuery('.fb-loading-wraper');
		if(div.length == 0){
			var html = '<div class="fb-loading-wraper">\
				<div class="fb-loading-container">\
				<span class="fb-loading"></span>\
				</div></div>';
			jQuery('body').append(html);
			div = jQuery('.fb-loading-wraper');
		}
		div.show();
		break;
	}
}
function hideLoading($loading){
	switch($loading){
	case 'fb2':
		var div = jQuery('.fb-loading-wraper');		
		div.hide();
		break;
	}
}
function call_ajax_function($t){	  
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 $data.web = {}; 
	 $data.web.controller_text = $cfg.controller_text;
	 $data.web.controller = $cfg.controller;
	 $data.web.action = $cfg.controller_action;
	 var $loading = $this.attr('data-loading') ? $this.attr('data-loading') : false;
	 var $showLoading = $this.attr('data-show-loading') ? true : false;
	 var $d;
	 switch($this.attr('type')){
	 	case 'checkbox':
	 	case 'radio':
	 		if($this.is(':checked')){
	 			$data['value'] = $this.val();
	 		}else{
	 			$data['value'] = '';
	 		}	 		 
	 		break;
		 default:
			 if($this.val() != undefined){
				 $data['value'] = $this.val();
			 }
			 break;
	 }
	 
	 var $ajax_action = $this.attr('data-ajax-action') ? $this.attr('data-ajax-action') : '/ajax';
	 var $state = true;	 
	 if($cfg.cBaseUrl.slice(-1) != '/' && $ajax_action.slice(0,1) != "/"){
		 $ajax_action = '/' + $ajax_action;
	 }
	// console.log($cfg.cBaseUrl.slice(0,1));
	 
	 switch($this.attr('type')){
	 case 'checkbox': case 'radio':
		 $data['checked'] = $this.is(':checked') ? 1 : 0;
		 break;
	 }
	 
	 if($this.attr('data-old')){
		 if($this.val() != $this.attr('data-old')){
			 $this.attr('data-old',$this.val());
		 }else{
			 $state = false;
		 }
	 }
	 
	 if($state){
		 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + $ajax_action,						 		 
	      data: $data,
	      beforeSend:function(){
	    	  if($showLoading) showFullLoading();
	    	  showLoading($loading);
	      },
	      success: function (data) {
 
	    	  hideFullLoading();
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	  if($d !== false && $d != null && $d.complete){
	    		  eval($d.complete_function);	    		 
	    	  }
	    	  if($showLoading) hideFullLoading();
	    	  hideLoading($loading);
	      },
	      error : function(err, req) { 
	    	  console.log('Error: '+ $cfg.cBaseUrl  + $ajax_action);
	    	  
	    	  if($showLoading) hideFullLoading();}
	 });
	 }
	 return false;
}

function change_item_price_detail($t){	 
	 var $this = jQuery($t);
	 var $data = getAttributes($this);	 
	 $data['action'] = 'change_item_price_detail';
	 // tour_type
	 $data['tour_type'] = parseInt(jQuery('.input-tour-detail-select-tour-type').val());
	 $data['tour_guest_group'] = parseInt(jQuery('.input-tour-detail-select-tour-guest-group').val());
	 $data['tour_start'] = parseInt(jQuery('.input-tour-detail-select-tour-start').val());
	 $data['tour_hotel'] = parseInt(jQuery('.input-tour-detail-select-tour-hotel').val());
	 $data['tour_date_time'] = jQuery('.input-tour-detail-select-tour-date-time').val();
	// console.log($data);
	 if($this.val() != undefined){
		 $data['value'] = $this.val();
	 }
	 $ajax_action = $this.attr('data-ajax-action') ? $this.attr('data-ajax-action') : '/sajax';
	 if($cfg.cBaseUrl.slice(-1) != '/' && $ajax_action.slice(0,1) != "/"){
		 $ajax_action = '/' + $ajax_action;
	 }
	 $state = true;	 
	 
	 switch($this.attr('type')){
	 case 'checkbox': case 'radio':
		 $data['checked'] = $this.is(':checked') ? 1 : 0;
		 break;
	 }
	 
	 if($this.attr('data-old')){
		 if($this.val() != $this.attr('data-old')){
			 $this.attr('data-old',$this.val());
		 }else{
			 $state = false;
		 }
	 }
	 
	// console.log($data)
	 if($state){
		 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + $ajax_action,						 		 
	      data: $data,
	      beforeSend:function(){
	    	  showFullLoading();
	      },
	      success: function (data) {
 
	    	  hideFullLoading();
	    	  //$d = JSON.parse(data);
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	  hideFullLoading();
	      },
	      error : function(err, req) { 
	    	 console.log('Error: ' + $cfg.cBaseUrl  + $ajax_action);
	    	 // console.log(err + req)
	    	 
	    	 hideFullLoading();}
	 });
	 }
	 return false;
}

function change_item_price_detail2($t){	 
	 var $this = jQuery($t);
	 var $data = getAttributes($this);	 
	 $data['action'] = 'change_item_price_detail2';
	 switch($data['field']){
	 case 'tour_date_time':
		 $data['tour_date_time'] = $this.val();
		 break;
	 default:
		 $data['tour_start'] = $this.val();
		 break;
	 }
	 	 
	 if($this.val() != undefined){
		 $data['value'] = $this.val();
	 }
	 $ajax_action = $this.attr('data-ajax-action') ? $this.attr('data-ajax-action') : '/sajax';
	 if($cfg.cBaseUrl.slice(-1) != '/' && $ajax_action.slice(0,1) != "/"){
		 $ajax_action = '/' + $ajax_action;
	 }
	 $state = true;	 
	 
	 switch($this.attr('type')){
	 case 'checkbox': case 'radio':
		 $data['checked'] = $this.is(':checked') ? 1 : 0;
		 break;
	 }
	 
	 if($this.attr('data-old')){
		 if($this.val() != $this.attr('data-old')){
			 $this.attr('data-old',$this.val());
		 }else{
			 $state = false;
		 }
	 }
	 
	 if($state){
		 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + $ajax_action,						 		 
	      data: $data,
	      beforeSend:function(){
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  hideFullLoading();
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	  hideFullLoading();
	      },
	      error : function(err, req) { 
	    	  console.log( 'Đã xảy ra lỗi, vui lòng thử lại.'); 
	    	  hideFullLoading();}
	 });
	 }
	 return false;
}

function downloadFile($t){
	var $this = jQuery($t);
	var $src = $this.attr('data-src');
	jQuery.fileDownload($this.prop('href'))
    .done(function () { alert('File download a success!'); })
    .fail(function () { alert('File download failed!'); });
    return false;	
}

function hideModal($timeout){
	$timeout = $timeout > 0 ? $timeout : 0;
	window.setTimeout(function(){jQuery('.modal').modal('hide');},$timeout);
}

function showQuickReplyForm($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'showQuickReplyForm';
	
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + '/sajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  hideFullLoading();
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	  hideFullLoading();
	      },
	      error : function(err, req) { 
	    	  //console.log($cfg.cBaseUrl  + $ajax_action);
	    	  console.log('Đã xảy ra lỗi, vui lòng thử lại.'); 
	    	  hideFullLoading();}
	 });
	
}
function likeComment($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data['action'] = 'likeComment';
	
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + '/sajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  //showFullLoading();
	      },
	      success: function (data) {
	    	  //hideFullLoading();
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	  //hideFullLoading();
	      },
	      error : function(err, req) { 
	    	  //console.log($cfg.cBaseUrl  + $ajax_action);
	    	  console.log('Đã xảy ra lỗi, vui lòng thử lại.');
	    	  hideFullLoading();}
	 });
	
}

function changeClassTestType($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	switch($this.val()){
		case 'audio':case 'mp3':
		if(jQuery('.input-respon-upload-mp3-files').val()==""){
			jQuery('.btn-submit-upload-form').attr('disabled','');
		}
		break;
		default:
			jQuery('.btn-submit-upload-form').removeAttr('disabled');
			break;
		
	}	
	//
	jQuery('.panels .panel_detail').hide();
	jQuery('.panels .panel_detail').find('input,textarea').removeClass('required');
	jQuery('.panels .panel_ans_'+$this.val()).find('input[type=text],textarea').addClass('required');
	jQuery('.panels .panel_ans_'+$this.val()).show();
}

function getHtmlData($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$url = $this.attr('data-url') ? $this.attr('data-url') : '/ajax';
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + $url,						 		 
	      data: getAttributes($this),
	      beforeSend:function(){},
	      success: function (data) {
	    	  //console.log(data)
	    	  $d = parseJsonData(data); 	    	  
	    	  jQuery($d.target).html($d.html);
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete:function(){
	    	 // reload_app('select2');
	    	 // load_number_format()
	      },
	      error : function(err, req) {}
	});
}

function changeSaveAction($t){
	var $this = jQuery($t);
	$this.parent().find('.submit-action').val($this.attr('data-action'));
}

function open_sajax_modal($t){
	 
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 var $m = $this.attr('data-modal-target') ? $this.attr('data-modal-target') : '.mymodal';
	 if(jQuery($m).length == 0) jQuery('body').append('<div class="modal '+($m.replace('.',''))+'"></div>'); 
	 var $modal = jQuery($m);
	 var $state = true;
	 var $check_required_save = $this.attr('data-required-save') ? true : false;
	 if($check_required_save && jQuery('.field-required-save').length>0){
		 $state = confirm('Dữ liệu chưa đc lưu !!! Bạn có muốn tiếp tục ?');
	 }
	 if(!$this.attr('data-title')){
		 $data['title'] = $this.attr('title');
	 }
	 
	 var $ajax_action = $this.attr('data-ajax-action') ? $this.attr('data-ajax-action') : 'sajax';
	 
	 if($state){
		 $this.removeAttr('data-required-save');
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.baseUrl  + '/' + $ajax_action,						 		 
	      data: $data,
	      beforeSend:function(){
	    	  $html = '<form data-action="'+$ajax_action+'" name="sajaxForm" action="/'+$ajax_action+'" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return ajaxSubmitForm(this);">';
	    	  $html += '<div class="modal-dialog '+$this.attr('data-class')+'" role="document">';
	    	  $html += '<div class="modal-content">';
	    	  $html += '<div class="modal-header">';
	    	  $html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	    	  $html += '<h4 class="modal-title f12e upper bold" style="font-size:14px">'+$data.title+'</h4>';
	    	  $html += '</div>';
	    	  $html += '<div class="modal-body ajax-modal-body">';
	    	  $html += '<p class="ajax-loading-data">Đang tải dữ liệu.</p>';
	    	  $html += '</div></div></div></form>';
	    	  $modal.html($html).modal({'show':true,backdrop: 'static', keyboard: true});
	      },
	      success: function (data) {
	    	  $d = parseJsonData(data); 	    
	    	  $modal.find('.ajax-modal-body').html($d.html);
	    	  $modal.draggable({
	    		    handle: ".modal-header"
	    	  });
	    	  if($d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	 
	      },
	      error : function(err, req) {}
	 });
	 }
	 return false;
}

function goto(){
	
}
;



var http_arr = new Array();
function sajax_upload_image_files($t) {
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$o = $this.attr('data-option') ? $this.attr('data-option') : '';
	$token = $this.attr('data-token') ? $this.attr('data-token') : jQuery('meta[name=csrf-token]').attr('content');
	$group = $this.attr('data-group') ? $this.attr('data-group') : '';
	$index = $this.attr('data-index') ? parseInt($this.attr('data-index')) : 0;
	$count = $this.attr('data-count') ? parseInt($this.attr('data-count')) : 0;
	$temp_file = $this.attr('data-name') ? $this.attr('data-name') : 'files_attach';
	$multiple = $this.attr('multiple') ? true : false;
	$filetype = $this.attr('data-filetype') ? parseInt($this.attr('data-filetype')) : 'files';
	$input_name = $this.attr('data-input-name') ? ($this.attr('data-input-name')) : 'file';
	//document.getElementById('progress-group'+$group).innerHTML = ''; //Reset láº¡i Progress-group

	var files = document.getElementById($this.attr('id')).files;  
	//console.log(files);
	for (i=0;i<files.length;i++) {
		if(!$multiple){
			_sajax_upload_files(files[i], ($count+i),$group,$temp_file+ '['+$input_name+']',$filetype,$token, $data);
		}else{			
			_sajax_upload_files(files[i], ($count+i),$group,$temp_file+ '['+(i + $count)+']['+$input_name+']',$filetype,$token, $data);
		}
		
	}
	$this.attr('data-count',files.length + $count) ;
	return false;
}

function _sajax_upload_files(file, $count,$group,$temp_file,$filetype,$token,$upload_option) {
	
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khá»Ÿi táº¡o vÃ¹ng tiáº¿n trÃ¬nh **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$group);
	
	var $ffx = document.getElementById('myfile'+$group);

	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//ThÃªm Div.Progress-bar vÃ  Div.Progress-text vÃ o Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//ThÃªm Div.Progress vÃ  Div.Progress-bar vÃ o Div.Progress-group	
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
		var speed = sSpeedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
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
    data.append('_csrf-frontend',$token);
   // data.append('upload_option',$upload_option);
    if($upload_option != undefined){
    jQuery.each($upload_option,function(i1,e1){
    	data.append(i1,e1);
    });
    }
	http.open('POST', $cfg.adminUrl +'/ajax', true);
	http.send(data);
 

	//Nháº­n dá»¯ liá»‡u tráº£ vá»�
	http.onreadystatechange = function(event) {
		//Kiá»ƒm tra Ä‘iá»�u kiá»‡n
		//console.log(http.status);
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bá»� hÃ¬nh áº£nh xá»­ lÃ½
		
			try { //Báº«y lá»—i JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
        
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //ThÃªm class Success
					ProgressBar.innerHTML = server.message; //ThÃ´ng bÃ¡o	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    //console.log(server.image)
                   //var InputRsx = document.createElement('input');
                   //  InputRsx.value = server.image;
                   // / InputRsx.className   = 'form-control inputPreview';
                   //  Respon_image_uploaded.appendChild(InputRs);	
                   // Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$group);
                    var respon_btable = document.getElementById('respon-btable'+$group);
                    switch ($group) {
                    case '-upload-avatar':
                    	jQuery('.input-respon-upload-avatar').val(server.image);
                    	jQuery('.input-image-respon-upload-avatar').attr('src', server.image);
                    	ProgressGroup.innerHTML = '';
                    	//ProgressGroup.style.display = "none";
                    break;
                    case '-upload-files':
                    	jQuery('.input-respon'+$group).val(server.image);
                    	
                    	var InputRs = document.createElement('input');
                        InputRs.name = 'f['+$count+'][file]';
                        InputRs.type = 'hidden';
                        InputRs.value = server.image;
                        
                        var InputRs1 = document.createElement('input');
                        InputRs1.name = 'f['+$count+'][title]';
                        InputRs1.type = 'hidden';
                        InputRs1.value = file.name;
                        
                        Respon_image_uploaded.appendChild(InputRs);
                        Respon_image_uploaded.appendChild(InputRs1);
                        
                        
                        
                        
                    	break;
                    case '-xls-import-class':
                    	 
                        
                      //  Respon_image_uploaded.innerHTML = '';
                      // console.log(server) 
                        
                        
                        
                        
                    	break;	

					default:
						jQuery('.input-respon'+$group).val(server.image);
						jQuery('.btn-submit-upload-form').removeAttr('disabled');
						break;
					}
                    
                     
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                    if(server.callback){eval(server.callback_function);}
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //ThÃªm class Danger
					ProgressBar.innerHTML = server.message; //ThÃ´ng bÃ¡o
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //ThÃªm class Danger
			
				ProgressBar.innerHTML = e ; //'CÃ³ lá»—i xáº£y ra :('; //ThÃ´ng bÃ¡o
			}
		}
		http.removeEventListener('progress',function(x){}); //Bá»� báº¯t sá»± kiá»‡n
	}
}


 
function sSpeedRate(oldTime, newTime, oldLoaded, newLoaded) {
		var timeProcess = newTime - oldTime; //Ä�á»™ trá»… giá»¯a 2 láº§n gá»�i sá»± kiá»‡n
		if (timeProcess != 0) {
			var currentLoadedPerMilisecond = (newLoaded - oldLoaded)/timeProcess; // Sá»‘ byte chuyá»ƒn Ä‘Æ°á»£c 1 Mili giÃ¢y
			return parseInt((currentLoadedPerMilisecond * 1000)/1024); //Tráº£ vá»� giÃ¡ trá»‹ tá»‘c Ä‘á»™ KB/s
		} else {
			return parseInt(newLoaded/1024); //Tráº£ vá»� giÃ¡ trá»‹ tá»‘c Ä‘á»™ KB/s
		}
}


function checkMemberoldPassword($t){
	var $this = jQuery($t);
	var $val = $this.val();
	var $data = getAttributes($this);	 
	$data['action'] = 'checkMemberoldPassword';
	$data['value'] = $val;
	if($val.length>0){
	jQuery.ajax({
		type:'post',
		datatype:'json',
		url:$cfg.baseUrl+'/sajax',
		data:$data,
		beforeSend:function(){
			showFullLoading();
		},
		success:function(data){
			var $d = parseJsonData(data);
			hideFullLoading();
			if($d.state){
				$this.attr('readonly','');
				jQuery('.error-alert1').remove();
				jQuery('.input-submit-check').removeAttr('disabled');
				jQuery('.password2').focus();
			}else{
				jQuery('.error-alert1').addClass('bg-danger').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu cũ không đúng').show(); 
				jQuery('.input-submit-check').attr('disabled','')
			}
		},
		error:function(err,req){}
	});
	}
}


function validateUsernameRegister($t){
	var $this = jQuery($t);
	var $val = $this.val();
	var $data = getAttributes($this);	 
	$data['action'] = 'validateUsernameRegister';
	$data['value'] = $val;
	if($val != ''){
	jQuery.ajax({
		type:'post',
		datatype:'json',
		url:$cfg.baseUrl+'/sajax',
		data:$data,
		beforeSend:function(){
			showFullLoading();
		},
		success:function(data){
			var $d = parseJsonData(data);
			//console.log($d);
			hideFullLoading();
			var $e = $this.parent();
			$e.find('.help-block-error2').remove();
			if($d.state){
				$e.find('.help-block-error2').html('');
				jQuery('.btn-submit-register').removeAttr('disabled');
			}else{
				$e.addClass('has-error2');
				$e.append('<p class="help-block-error2 red">Tài khoản <b>'+$d.username+'</b> đã được sử dụng.</p>');
				jQuery('.btn-submit-register').attr('disabled','');
			}
		},
		error:function(err,req){}
	});
	}
}


function open_ajax_smodal($t){
	 
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
	 $form_action = $this.attr('data-form-action') ? $this.attr('data-form-action') : '/sajax';
	 if($state){
		 $this.removeAttr('data-required-save');
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.baseUrl  + '/sajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	  $html = '<form name="ajaxForm" data-action="current" action="'+$form_action+'" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return ajaxSubmitForm(this);">';
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
	    	 
	      },
	      error : function(err, req) {}
	 });
	 }
	 return false;
}


function chosen_select_init2($t){
	jQuery('.chosen-select,.chosen-select-no-single,.chosen-select-no-search').each(function(i,e){
		var $this = jQuery(e);
		var $config = {search_contains:true,case_sensitive_search:true}
		$config['search_contains'] = $this.attr('data-search_contains') && $this.attr('data-search_contains') == 'false' ? false : true;
		$config['case_sensitive_search'] = $this.attr('data-case_sensitive_search') && $this.attr('data-case_sensitive_search') == 'false' ? false : true;
		$config['allow_single_deselect'] = $this.attr('data-allow_single_deselect') ? true : false;
		$config['disable_search'] = $this.attr('data-disable_search') ? true : false;
		$config['disable_search_threshold'] = $this.attr('data-disable_search_threshold') ? $this.attr('data-disable_search_threshold') : 0;
		$config['no_results_text'] = $this.attr('data-no_results_text') ? $this.attr('data-no_results_text') : 'Không tìm thấy kết quả phù hợp.';
		
		
		if($this.attr('data-width')){
			$config['width'] = $this.attr('data-width');
		}

		if($this.attr('data-loaded') == undefined){
			$this.chosen($config).attr('data-loaded',true);
		}
	});
}


function cimagezoom($href){
	jQuery('.zoomimg').find('img').attr('src',$href);
	jQuery('#zoomlinks').find('a').attr('href',$href)
}

function submitFormTarget($t){
	var $this = jQuery($t);
	var $form = jQuery($this.attr('data-form-submit'));
	if($this.val() != $this.attr('data-old')){
		$form.submit();
	}
}

function PHP_export_excel($t){
	
}

function read_date($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$data.value = $this.val();
	$data.action = 'read_date';
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.baseUrl  + '/sajax',						 		 
	      data: $data,
	      beforeSend:function(){
	    	   
	      },
	      success: function (data) {
	    	  
	    	  var $d = parseJsonData(data);
	    	  var $target_input = jQuery($d.target);	    	   
	    	  if($d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	 
	      },
	      error : function(err, req) {}
	 });
}

function close_this($t){
	var $this = jQuery($t);
	jQuery($this.attr('data-target')).slideUp();
}




function registerCss(urls,pos,target) {
	var node;	 
	var $target = document.getElementById(target);
	if($target != null && $target != undefined ){
		var head = $target;
	}else{
		var head = pos == 1 ? document.getElementsByTagName("link")[0] : document.getElementsByTagName("head")[0];
	}
	///var element = document.querySelector('[rel="stylesheet"]');
	////console.log(element);
	//var head = pos == 1 ? document.getElementsByTagName("link")[0] : document.getElementsByTagName("head")[0];
	urls = typeof urls === 'string' ? [urls] : urls.concat();
	for (i = 0, len = urls.length; i < len; ++i) {
	  node = document.createElement("link");
	  node.type = "text/css";
	  node.rel = "stylesheet";
	  node.className = 'lazyload';
	  node.href = urls[i];
	  if(pos == 1){
		  head.parentNode.insertBefore(node, head);	  
	  }else{
		  head.appendChild(node); 
	  }
	}
}

function markDelete($t){
	var $this = jQuery($t);
	var $p = $this.parent().parent(); 
	if($this.is(':checked')){
		$p.css({"background":"#ffc"});
	}else{
		$p.css({"background":"none"});
	};
}
function checkAllChild($t){
	var $this = jQuery($t);
	var $role = $this.attr('data-role');
	$checkboxes = jQuery('.list-child-item').find('input[data-role='+$role+']');
	$checkboxes.prop('checked', $this.is(':checked')).change();
	var pr = $checkboxes.parent().parent();
	if($this.is(':checked')){
		pr.addClass('info');
	}else{
		pr.removeClass('info');
	}
}

function getParam($param){
	return $_GET[$param] ? $_GET[$param] : '';
}

function buildQuery( obj ) {
	  return '?'+Object.keys(obj).reduce(function(a,k){a.push(k+'='+encodeURIComponent(obj[k]));return a},[]).join('&')
}
function randString($t) {
	$t = $t > 0 ? $t : 6;
	  var text = "";
	  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	  for (var i = 0; i < $t; i++)
	    text += possible.charAt(Math.floor(Math.random() * possible.length));

	  return text;
}
if(!((typeof izi)== 'object')){
	var izi = {};
}
izi.preloadAjax = function (){
	var $html = '<div class="_10 uiLayer _4-hy _31e _3qw preload_ajax_dialog">';
	
	$html += '<div class="_3ixn"></div>';
	$html += '<div class="_59s7" role="dialog" aria-label="Nội dung hội thoại">';
	$html += '<div class="_4t2a"><div><div><div></div><div class="_57-x">';
	$html += '<span class="img _55ym _55yq _55yo" role="progressbar" aria-valuetext="Đang tải..." aria-busy="true" aria-valuemin="0" aria-valuemax="100"></span>';
	$html += '</div></div></div></div></div>';
	
	$html +='</div>';
	
	var $popup = jQuery('.preload_ajax_dialog');
	if($popup.length>0){
		//
		$popup.show();
	}else{
		//
		
		jQuery('body').append($html);
		jQuery('.preload_ajax_dialog ._3ixn').on('click',function(){
			izi.closePreload();
		});
	}
	
	
}
izi.closePreload = function (){
	jQuery('.preload_ajax_dialog').remove();
}

izi.callAjax = function ($t){
	 var $this = jQuery($t);
	 var $data = getAttributes($this);	 
	 if($this.val() != undefined){
		 $data['value'] = $this.val();
	 }
	 $ajax_action = $this.attr('data-ajax-action') ? $this.attr('data-ajax-action') : '/ajax';
	 if($cfg.cBaseUrl.slice(-1) != '/' && $ajax_action.slice(0,1) != "/"){
		 $ajax_action = '/' + $ajax_action;
	 }
	 $state = true;	 
	 
	 switch($this.attr('type')){
	 case 'checkbox': case 'radio':
		 $data['checked'] = $this.is(':checked') ? 1 : 0;
		 break;
	 }
	 
	 if($this.attr('data-old')){
		 if($this.val() != $this.attr('data-old')){
			 $this.attr('data-old',$this.val());
		 }else{
			 $state = false;
		 }
	 }
	 
	 if($state){
		 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + $ajax_action,						 		 
	      data: $data,
	      beforeSend:function(){
	    	  if($this.attr('data-preload')){
	    		  this.preloadAjax();
	    	  }
	      },
	      success: function (data) {
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	 if($d !== false && $d != null && $d.complete){
	    		 eval($d.complete_function);
	    		 if($this.attr('data-preload')){
		    		  this.closePreload();
		    	 }
	    	 }
	      },
	      error : function(err, req) { 
	    	  console.log('Error: '+ $cfg.cBaseUrl  + $ajax_action);
	    	  
	    	 /// hideFullLoading();
	      }
	 });
	 }
	 return false;
}



izi.callAjaxPopup = function ($t){
	 var $this = jQuery($t);
	 var $data = getAttributes($this);
	 if($this.val() != undefined){
		 $data['value'] = $this.val();
	 }
	 $ajax_action = $this.attr('data-ajax-action') ? $this.attr('data-ajax-action') : '/ajax';
	 if($cfg.cBaseUrl.slice(-1) != '/' && $ajax_action.slice(0,1) != "/"){
		 $ajax_action = '/' + $ajax_action;
	 }
	 $state = true;	 
	 
	 switch($this.attr('type')){
	 case 'checkbox': case 'radio':
		 $data['checked'] = $this.is(':checked') ? 1 : 0;
		 break;
	 }
	 
	 if($this.attr('data-old')){
		 if($this.val() != $this.attr('data-old')){
			 $this.attr('data-old',$this.val());
		 }else{
			 $state = false;
		 }
	 }
	 
	 if($state){
	 var $d = {};	 
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl  + $ajax_action,						 		 
	      data: $data,
	      beforeSend:function(){
	    	  izi.preloadAjax();
	      },
	      success: function (data) {
	    	  $d = parseJsonData(data); 
	    	  if($d !== false && $d != null && $d.callback){
	    		  eval($d.callback_function);
	    	  }
	      },
	      complete: function(){
	    	 if($d !== false && $d != null && $d.complete){
	    		 eval($d.complete_function);	    		 
	    	 }
	    	 izi.closePreload();
	      },
	      error : function(err, req) { 
	    	  console.log('Error: '+ $cfg.cBaseUrl  + $ajax_action);	    	  
	      }
	 });
	 }
	 return false;
}

izi.Bill_update_quantity = function ($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	$c = true;
	if($this.attr('data-old')){
		if($this.attr('data-old') == $this.val()){
			$c = false;
		}else{
			$this.attr('data-old',$this.val());
		}
	}
	if($c){
		var $item_id = $data.item_id;
		var $input = jQuery('.bill-input-item-quantity-'+$item_id);
		var $val = parseInt($input.val());
		if(!($val>0)){
			$val = 1;
			
		}
		switch($data.role){
		case 'desc':
			$val = $val > 1 ? $val-1 : 1;
			break;
		case 'asc':
			$val++;
			break;
		case 'set':
			
			break;
		}
		$input.val($val).attr('data-old',$val);
		izi.Bill_update_item_price($item_id);
	}
}
izi.Bill_update_item_price = function ($item_id){
	var $input = jQuery('.bill-input-item-quantity-'+$item_id);
	var $quantity = parseInt($input.val());
	var $input_price = jQuery('.bill-input-item-price-'+$item_id);
	var $decimal = $input_price.attr('data-decimal') ? $input_price['data-decimal'] : 0;
	var $price = parseFloat($input_price.val());	
	var $subtotal = $quantity * $price;
	jQuery('.bill-label-item-sub-total-'+$item_id).html(jQuery.number($subtotal,$decimal));
	jQuery('.bill-input-item-sub-total-'+$item_id).val($subtotal);
	izi.Bill_refresh();
}

izi.Bill_remove_item = function ($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	jQuery('.bill-item-'+$data.item_id).remove();
	$this.parent().parent().remove();
	izi.Bill_refresh();
}

izi.Bill_refresh = function (){
	var $input = ['sub-total','total-item','discount-total','ship-total','grand-total','total-owed','guest-total'];
	var $currency = jQuery('.bill-detail-infomation').attr('data-currency');
	// Tính tổng tiền hàng
	// bill-input-item-sub-total
	var $subtotal = 0, $total_item = 0, $grand_total = 0;
	var $input_subtotal = jQuery('.bill-input-item-sub-total');
	
	$input_subtotal.each(function(i,e){
		$subtotal += parseFloat(jQuery(e).val());
	});
	jQuery('.bill-input-item-quantity').each(function(i,e){
		$total_item += parseFloat(jQuery(e).val());
	});
	jQuery('.bill-input-sub-total').val($subtotal);
	jQuery('.bill-label-sub-total').html(getCurrencyText($subtotal,$currency));
	jQuery('.bill-label-total-item').html(jQuery.number($total_item));
	jQuery('.bill-input-total-item').html($total_item);
	// Discount
	
	var $discount_type = jQuery('.bill-input-all-item-discount-type').val();
	var $discount_value = parseFloat(jQuery('.bill-input-all-item-discount-value').val());
	if($discount_type == '%'){
		var $discount = $discount_value * $subtotal / 100;
		jQuery('.bill-input-discount-total').val($discount);
		jQuery('.bill-label-discount-total').html(getCurrencyText($discount,$currency));
		jQuery('.btn-set-discount-all-item').attr({
			'data-discount_total':$discount,
			'data-sub_total':$subtotal
		});
	}else{
		var $discount = parseFloat(jQuery('.bill-input-discount-total').val());
	}
	
	
	
	
	
	var $ship = parseFloat(jQuery('.bill-input-ship-total').val());
	//
	$discount = $discount > 0 ? $discount : 0;
	$ship = $ship > 0 ? $ship : 0;
	//
	$grand_total = $subtotal - $discount + $ship ;
	
	//console.log($discount);
	jQuery('.bill-input-grand-total').val($grand_total); 
	jQuery('.bill-label-grand-total').html(getCurrencyText($grand_total,$currency));
	//
	var $tt = parseFloat(jQuery('.bill-input-guest-total').val());
	var $ow = parseFloat(jQuery('.bill-input-total-owed').val());
	//$tt = $grand_total;
	//if($tt > $grand_total){
		$tt = $grand_total;
		jQuery('.bill-label-guest-total').html(getCurrencyText($tt,$currency));
		jQuery('.bill-input-guest-total').val($tt);
	//}
	//
	var $owed = $grand_total - $tt;
	
	jQuery('.bill-label-total-owed').html(getCurrencyText($owed,$currency));
	jQuery('.bill-input-total-owed').val($owed);
	
	
}

function setBtnClick($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	jQuery('.btnSubmit').val($data.role);
}

function showLoading3($t){
	var $this = jQuery($t);
	$this.parent().find('.loading3').show();
}

function hideLoading3($t){
	var $this = jQuery($t);
	$this.parent().find('.loading3').hide();
}


function checkPasswordStrength($t) {
    //var password_strength = document.getElementById("password_strength");
	var $this = jQuery($t);
	var password = $this.val();

      //if textBox is empty
      if(password.length==0){
          
      }

      //Regular Expressions
      var regex = new Array();
      regex.push("[A-Z]"); //For Uppercase Alphabet
      regex.push("[a-z]"); //For Lowercase Alphabet
      regex.push("[0-9]"); //For Numeric Digits
      regex.push("[$@$!%*#?&]"); //For Special Characters

      var passed = 0;

      //Validation for each Regular Expression
      for (var i = 0; i < regex.length; i++) {
          if((new RegExp (regex[i])).test(password)){
              passed++;
          }
      }

      //Validation for Length of Password
      if(passed > 2 && password.length > 8){
          passed++;
      }
var $tg = jQuery('#password_new_status');
var $msg = '';
if(password.length>0 && password.length<6){
	$msg = '<span class="short_pass">Quá ngắn</span>';
}else{
      switch (passed) {
	case 1:		 
	case 2:
		$msg = '<span class="short_pass">Yếu</span>';
		break;
	case 3:
		$msg = '<span class="medium_pass">Trung bình</span>';
		break;
	case 4:
		$msg = '<span class="strong_pass">Mạnh</span>';
		break;
	case 5:
		$msg = '<span class="strong_pass">Rất mạnh</span>';
		break;
	 
	}
      if(passed>0)      $msg = "Độ dài mật khẩu: " + $msg;
}
      
      $tg.html($msg);
      return passed;
  }

izi.openModal = function (content,id){
	if(id == undefined || id == null){
		id = '.mymodal';
		//$modal = jQuery('.mymodal');
	}else{
		
	}	
	$modal = jQuery(id);
	if($modal.length>0){
		$modal.remove();
	}
	 
	jQuery('body').append(content);
	$modal = jQuery(id);
	$modal.modal({'show':true,backdrop: 'static', keyboard: true});
}

izi.closeModal = function(id){
	if(id == undefined || id == null){
		id = '.mymodal';		
	}else{
		
	}	
	$modal = jQuery(id);
	$modal.modal('hide');
}

izi.init = function (t){
	jQuery("input.checkboxradio").each(function(i,e){
		var $this = jQuery(e);
		var $d1 = {};
		$d1.disabled = $this.attr('data-disabled') ? true : false;
		$d1.icon = $this.attr('data-icon') == '0' ? false : true;
		if($this.attr('data-label')){
			$d1.label = $this.attr('data-label');
		}
		$this.checkboxradio($d1).attr('data-loaded',1);
	});
	
	jQuery("fieldset.controlgroup").each(function(i,e){
		var $this = jQuery(e);
		 
		var $d1 = {};
		if($this.attr('data-direction')){
			$d1.direction = $this.attr('data-direction');
		}		
		$d1.disabled = $this.attr('data-disabled') ? true : false;		
		$d1.onlyVisible = $this.attr('data-onlyVisible') == '0' ? false : true;
		$this.controlgroup($d1).attr('data-loaded',1);
	});
	    
	jQuery("input.spinner").spinner();
	//
	izi.loadChosenAjax2();
}

izi.loadChosenAjax2 = function (){
	jQuery('select.ajax-chosen-select2').each(function(index,element){
		var $this = jQuery(element);
		var $data = $data2 = getAttributes($this);
		
		$data['action'] = 'CHOSEN_AJAX';
		 
		var $config = {search_contains:true,case_sensitive_search:true}
		$config['search_contains'] = $this.attr('data-search_contains') && $this.attr('data-search_contains') == 'false' ? false : true;
		$config['case_sensitive_search'] = $this.attr('data-case_sensitive_search') && $this.attr('data-case_sensitive_search') == 'false' ? false : true;
		$config['allow_single_deselect'] = $this.attr('data-allow_single_deselect') ? true : false;
		$config['disable_search'] = $this.attr('data-disable_search') ? true : false;
		$config['disable_search_threshold'] = $this.attr('data-disable_search_threshold') ? $this.attr('data-disable_search_threshold') : 10;
		$config['no_results_text'] = $this.attr('data-no_results_text') ? $this.attr('data-no_results_text') : 'Không tìm thấy kết quả phù hợp.';
		
		console.log($config);
		
		if($this.attr('data-width')){
			$config['width'] = $this.attr('data-width');
		}
				
		//if($t==true || $this.attr('data-loaded') == undefined){
		var $s = $this.chosen($config).attr('data-loaded',true);
		/*/}
		var $s = $this
		.ajaxChosen({
         		   dataType: 'json',
         		   type: 'POST',
         		   data:$data,
         		   url: $cfg.adminUrl + '/ajax/chosen_ajax',
         		  search_contains:true
            },{
         		   loadingImg: $cfg.absoluteUrl+'/loading.gif'
            });
		/*/
        $s.removeClass('ajax-chosen-select2');
        if(($s.attr('data-action2'))){
	    $s.on('chosen:showing_dropdown', function(evt, params) {
	        if(!($s.attr('data-loaded-data') == 1)){
	        	$data2['action'] = $s.attr('data-action2') ;
	        	//$data2['respon'] = '.dastydtyas';
	        	sentAjaxData($data2);
	        }
	    });
        }
 	});
}

izi.Active_product_attr = function (t){
	var $this = jQuery(t);
	var $data = getAttributes($this);
	var $lb = jQuery('.glo_label_detailsp_'+$data.type_id);
	var $target = jQuery('.btn-add-to-cart-'+$data.item_id);
	
	console.log($target);
	
	$lb.removeClass('active');
	$this.addClass('active');
	switch(parseInt( $data.type_id)){
	case 1: // Color
		$target.attr('data-color',$data.text_id);
		break;
	case 2: // Size
		$target.attr('data-size',$data.text_id);
		break;
	}

}

izi.AddToCart = function (t){
	var $this = jQuery(t);
	var $data = getAttributes($this);
	$data.action = 'Izi_Update_Cart';
	$data.quantity = $data.quantity ? $data.quantity : jQuery('.cart-item-quantity-'+$data.item_id).val();
	
	sentSAjaxData($data);
	if($data.role == 'buynow'){
		window.location = $cfg.baseUrl + '/cart';
	}
}

izi.Cart_UpdateItem = function (t){
	var $this = jQuery(t);
	var $data = getAttributes($this);
	$data.cart_action = $data.action ? $data.action : $data.cart_action;
	$data.action = 'Izi_Update_Cart';	
	$data.quantity = $data.quantity ? $data.quantity : jQuery('.cart-item-quantity-'+$data.item_id).val();
	$ajax = true;
	if($data.old){
		if($data.old == $this.value){
			$ajax =false;
		}
	}
	if($data.number){
		if(parseInt($this.val()) > 0){
			$this.val(parseInt($this.val()));
			$data.value = parseInt($this.val());
		}else{
			$this.val('');
			$this.focus();
			$ajax =false;
			return false;
		}
	}
	
	
	if($ajax)	sentSAjaxData($data);
	
	if($data.role == 'buynow'){
		window.location = $cfg.baseUrl + '/cart';
	}
}

izi.Cart_RemoveItem = function (t){
	var $this = jQuery(t);
	var $data = getAttributes($this);
	$data.action = 'Izi_Update_Cart';
	$data.cart_action = 'delete';
	$data.quantity = $data.quantity ? $data.quantity : jQuery('.cart-item-quantity-'+$data.item_id).val();
	
	sentSAjaxData($data);
	if($data.role == 'buynow'){
		window.location = $cfg.baseUrl + '/cart';
	}
}


function changeTitle(title){
	if (document.title != title) {
	    document.title = title;
	}
}

function changeUrl(title, url) {
    if (typeof (history.pushState) != "undefined") {
    	switch(typeof title){    	
    		case 'object':
    			var obj = title;
    			if(!obj.url) obj.Url = url;    			
    			break;
    		default:
    			var obj = { Title: title, Url: url };
    			break;
    		
    	}
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        console.log("Browser does not support HTML5.");
    }
}
function html2text(html) {
    var tag = document.createElement('div');
    tag.innerHTML = html;
    
    return tag.innerText;
}

function loadContent(url){
	// USES JQUERY TO LOAD THE CONTENT
	jQuery.getJSON($cfg.baseUrl + url, {format: 'json'}, function(json) {		
		jQuery.each(json, function(key, value){
			switch(key){
				case 'callback': case 'callback_function':break;
				default:
					jQuery(key).html(value);
					break;
			}
			
		});
		if(json != null && json.callback){
			eval(json.callback_function);
		}
	});		 
}

function loadJsonContent(url){
	// USES JQUERY TO LOAD THE CONTENT
	jQuery.getJSON($cfg.cBaseUrl +'/ajax/json_page', {page:url,format: 'json'}, function(json) {		
		jQuery.each(json, function(key, value){
			switch(key){
				case 'callback': case 'callback_function':break;
				default:
					jQuery(key).html(value);
					break;
			}
			
		});
		if(json != null && json.callback){
			eval(json.callback_function);
		}
	});		 
}

function loadElementJsonContent(element){
	// USES JQUERY TO LOAD THE CONTENT
	var $this = jQuery(element);
	var $data = getAttributes($this);
	$data.format = 'json';
	jQuery.getJSON($cfg.cBaseUrl +'/ajax/json_page', $data, function(json) {		
		if(json != null){
			$this.html(json.html);
		}
		if(json != null && json.callback){
			eval(json.callback_function);
		}
	});		 
}

function loadDropDownCheckbox(){
	var options = [];

	jQuery( '.dropdown-checkbox a' ).on( 'click', function( event ) {

	   var $target = jQuery( event.currentTarget ),
	       val = $target.attr( 'data-value' ),
	       $inp = $target.find( 'input' ),
	       idx;

	   if ( ( idx = options.indexOf( val ) ) > -1 ) {
	      options.splice( idx, 1 );
	      $inp.prop( 'checked', false );
	      $target.removeClass('active');
	   } else {
	      options.push( val );
	      $inp.prop( 'checked', true );
	      $target.addClass('active');
	   }
	   $inp.val(val);
	   jQuery( event.target ).blur();
	   $inp.change();
	   ///console.log( $inp.is(':checked'));
	   return false;
	});
}

function closeAllModal(){
	///jQuery('.modal').modal('hide');
	jQuery('.modal').modal('hide');
}

function gotoMessage(){
	
}

function showCalendar($t){
	var $this = jQuery($t);
	if($this.attr('data-target')){
		
	}else{
		$this.parent().parent().find('input[type=text]').focus();
	}
}

function selectCurrentCheckbox($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var checkBoxes = $this.find("input[type=checkbox]");
	checkBoxes.parent()
	.addClass('pr amlsx')
	//.append('<span class="ps w100 h100 l0 t0 jskdjaks"></span>')
	;
	//$this.addClass('active');
	if(!checkBoxes.prop("checked")) {
		$this.addClass('info');
	}else{
		$this.removeClass('info');
		
	}
	//	checkBoxes = $this.find("input[type=checkbox]");
	checkBoxes.prop("checked", !checkBoxes.prop("checked"));
	//}
    
}

function sentAjaxData($data){
	$ajax_url = $data.ajax_url ? $data.ajax_url : $cfg.cBaseUrl +'/ajax';
	jQuery.ajax({
	  	  type: 'post',	  		 	
	  	  datatype: 'text',	  			
	  	  url: $ajax_url,	  			
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

function sentSAjaxData($data){
	jQuery.ajax({
	  	  type: 'post',	  		 	
	  	  datatype: 'text',	  			
	  	  url: $cfg.baseUrl +'/sajax',	  			
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


function loadHtmlData($t){
	var $this = jQuery($t);
	var $data = getAttributes($this);
	var $d;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.cBaseUrl + '/ajax',						 		 
	      data: getAttributes($this),
	      beforeSend:function(){
	    	  if($this.attr('data-loading')){
	    		  jQuery($data['target']).html('<div class="ajax-loading-data">Đang tải dữ liệu</div>');
	    	  }
	      },
	      success: function (data) {
	    	  $d = parseJsonData(data);
	    	  if(typeof $d == 'object'){
	    		  jQuery($d.target).html($d.html);
	    		  if($d.callback){
	    			  eval($d.callback_function);
	    		  }
	    	  }
	      },
	      complete:function(data){
	    	  if(typeof $d == 'object'){
	    		  if($d !== false && $d != null && $d.complete){
	    			  eval($d.complete_function);	    		 
	    		  }
	    	  }
	    	  //reload_app('select2');
	    	  //load_number_format();
	      },
	      error : function(err, req) {}
	});
}
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function getCurrency($id){
	var $c = {};
	jQuery.each($cfg.currencies, function(i,e){
		//console.log(i);
//		console.log(e.id)
		if(parseInt(e.id) == $id){
			$c = e;		
			return e;
			
		}
	});
	return $c;
}

function getCurrencyText($number, $currency,$o){
	if((typeof $currency) != 'object'){
		$currency = getCurrency($currency);
	}
	//
	// console.log($currency);
	$preText = $afterText = '';
	
	$priceText = jQuery.number($number, $currency['decimal_number']);
	
	switch (parseInt($currency['display_type'])){
		case 1: $preText = ''; $afterText = $currency['symbol']; break;
		case 2: $preText = ''; $afterText = $currency['code']; break;
		case 3: $preText = $currency['symbol']; $afterText = ''; break;
		case 4: $preText = $currency['code']; $afterText = ''; break;
		case 5: $preText = ''; $afterText = $currency['symbol2']; break;
		case 6: $preText = $currency['symbol2']; $afterText = ''; break;
		case 7: $preText = ''; $afterText = ' ' + $currency['symbol2']; break;
		case 8: $preText = ''; $afterText = ' ' + $currency['code']; break;
	}
	
	if((typeof $o) == 'object'  && $o['show_symbol']===false){
		$preText = $afterText = '';
	}
	
	return $preText + $priceText + $afterText;
}
function copyTrElement($t){
	var $this = jQuery($t);
	var $tr = $this.parent().parent();
	var $tbody = $tr.parent();
	$tr.clone().appendTo($tbody);
}