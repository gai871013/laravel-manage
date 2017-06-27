+function ($) {
	var layerModal = function (dom,callback) {
		var dialog = $(dom).appendTo(document.body);
		dialog.find('.btn , .close').click(function(){
			$(this).off();
			dialog.removeClass("active");
			if(typeof callback=="function") callback();
			setTimeout(function () {
				dialog.remove();
				dialog=null;
			}, 350);
		});
		setTimeout(function () {
			dialog.addClass('active');
		}, 100);
	}
	$.alert = function (text,callback) {
		var dom = '<div class="layer" id="layer"><div><div class="layer-content">\
		 <div class="layer-title">\
                <h2>温馨提示</h2>\
                <div class="close"><i>X</i></div>\
         </div>\
         <div class="layer-text"><h1></h1><p style="text-align:center;font-size:1.05rem;color:#f05136">'+text+'</p></div>\
       <div class="layer_button"> <button class="btn" >确定</button></div></div></div></div>';
		layerModal(dom,callback);
	}
	$.closeAll = function () {
		var dialog = $("#layer.active").removeClass("active");
		setTimeout(function () {
			dialog.remove();
		}, 350);
	};
}($);

