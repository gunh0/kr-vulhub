/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/

KindEditor.plugin('autoheight', function(K) {
	var self = this;

	if (!self.autoHeightMode) {
		return;
	}

	var minHeight;

	function hideScroll() {
		var edit = self.edit;
		var body = edit.doc.body;
		edit.iframe[0].scroll = 'no';
		body.style.overflowY = 'hidden';
	}

	function resetHeight() {
		var edit = self.edit;
		var body = edit.doc.body;
		edit.iframe.height(minHeight);
		self.resize(null, Math.max((K.IE ? body.scrollHeight : body.offsetHeight) + 76, minHeight));
	}

	//浮动工具栏
	function fixToolBar() {
		var ln=K('.ke-toolbar');	//工具栏筛选
		var lnPar=K('.ke-container');	//编辑器外框
		var ln_len=ln.length;	//工具栏数量
		var originW=ln.width()-10+'px';	//工具栏宽度	
        	K(window).bind('scroll', function () {	//卷轴事件函数
            		var scrollT = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
			var RealTime_H=[],RealTime_P=[],RealTime_Y=[];
            		for (var i=0; i<ln_len; i++) {
				RealTime_H[i]=lnPar.eq(i).height();	//实时高度
				RealTime_P[i]=lnPar.eq(i)[0].getBoundingClientRect().top;	//编辑器外框距窗口实时TOP
				RealTime_Y[i]=RealTime_P[i]+scrollT;	//编辑器外框距页面实时TOP
			}
			for (var i=0; i<ln_len; i++) {
				if (scrollT>RealTime_Y[i]+27 && scrollT<RealTime_Y[i]+RealTime_H[i]-108){	//设置浮动
					ln.eq(i).css('position', 'fixed');
				        ln.eq(i).css('top', '0px');
				        ln.eq(i).css('width', originW);
				        ln.eq(i).css('box-shadow', '0 0 10px #999');
				        ln.eq(i).css('index', '99999');
				} else {	//清除浮动
					ln.eq(i).css('position', 'relative');
				        ln.eq(i).css('top', 'auto');
				        ln.eq(i).css('box-shadow', 'none');
				}
			}
        	});
	}
	
	function init() {
		minHeight = K.removeUnit(self.height);
		self.edit.afterChange(function(){
			resetHeight();	//自动高度
			fixToolBar();	//工具栏浮动
		});
		hideScroll();	//隐藏卷轴
	}

	if (self.isCreated) {
		init();
	} else {
		self.afterCreate(init);
	}
});

/*
* 如何实现真正的自动高度？
* 修改编辑器高度之后，再次获取body内容高度时，最小值只会是当前iframe的设置高度，这样就导致高度只增不减。
* 所以每次获取body内容高度之前，先将iframe的高度重置为最小高度，这样就能获取body的实际高度。
* 由此就实现了真正的自动高度
* 测试：chrome、firefox、IE9、IE8
* */
