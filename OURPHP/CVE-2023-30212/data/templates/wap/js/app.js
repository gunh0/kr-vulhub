
$(function(){
	miTouch();//系统交互：触屏效果
	nav();//侧边导航
	recomPro();//产品推荐
	banner();// banner
	searcher();
	menu();
	backtop();
	$("#goBack").click(function(){ 
		// history.back();
		history.go(-1);  
    }); 
	$("#topsearch").click(function(){
    	$(".btn_close_main").show();
   	 	$("#tsearch").slideDown(200);
	});

	$(".btn_close_main").click(function(){
	    $("#tsearch").slideUp(200);
	    $(".btn_close_main").hide();
	});
	$("#navwrap .tit").click(function () {
	    if ($(this).siblings('.sub-box').is(':hidden')) {
	        $(this).siblings('.sub-box').stop().slideDown().parent().siblings().find('.sub-box').slideUp();
	    } else {
	        $(this).siblings('.sub-box').stop().slideUp()
	    }
	});

});
// 系统交互：触屏效果
function miTouch(){
	$("#subNav .subNavCon li a,.reLink a,#toolbar .list a").bind({"touchstart":function(){
		$(this).addClass("hover");
	}, "touchmove":function(){
		$(this).removeClass("hover");
	}, "touchend":function(){
		$(this).removeClass("hover");
	}});
	
}
//侧边导航
function nav(){
	$("#nav").click(function () {
        if ($(this).hasClass("cur")) {
            $("#subNav").addClass("subNavShow2");
            $(this).removeClass("cur");
            $(this).html("<p class='close icon'></p>")
        } else {
            $(this).addClass("cur");
            $(this).html("<p class='nav icon'></p>");
            $("#subNav").removeClass("subNavShow2");
        }
    });
}
//三级导航
function menu(){
	var $a = $("#listBox ul");
$a.bind({"touchstart":function(){
		$(this).addClass("hover");
	}, "touchmove":function(){
//		$(this).removeClass("hover");
	}, "touchend":function(){
//		$(this).removeClass("hover");
	}});
}
function recomPro() {
	if (!$("#recomPro").length) {
		return false;
	}
	var $a = $("#recomPro .recomList"),
		length = $a.find("ul").length,
		vi = 0,
		wid, t, autoPlayTime = 8000,
		autoAnimateTime = 500,
		loop = true;
	// 克隆元素
	var clone = $a.find("ul").eq(0).clone().addClass("clone");
	$a.children(".list").append(clone);
	// 自适应宽度
	var _init = function() {
			wid = $a.width();
			$a.children(".list").width(wid * (length+1));
			$a.find("ul").width(wid);
//			$a.find("img").css({
//				"width": wid
//			});
		}
		// 滚动效果函数
	var _func = function() {
			if (vi >= length) {
				vi = 0;
				_func();
			} else {
				vi++;
				if (vi == length) {
					setTimeout(function() {
						$a.children(".list").css({
							"-webkit-transform": "translate3d(0px, 0px, 0px)",
							"-webkit-transition": "-webkit-transform 0ms linear"
						});
					}, autoAnimateTime);
				}
				$a.children(".list").css({
					"-webkit-transform": "translate3d(-" + wid * vi + "px, 0px, 0px)",
					"-webkit-transition": "-webkit-transform " + autoAnimateTime + "ms linear"
				});
			}
		}
		// 滑动触发效果
	var _touch = function() {
		var o_pagex = 0, // 接触记录值
			e_pagex = 0; // 离开记录值
		$a.bind({
			"touchstart": function(e) {
				clearInterval(t);
				o_pagex = e.originalEvent.targetTouches[0].pageX;
			},
			"touchmove": function(e) {
				e_pagex = e.originalEvent.changedTouches[0].pageX;
				var xpage = e_pagex - o_pagex; //::负数-向左边滑动::正数-向右边滑动 
				if (xpage >= 0) {
					if (vi <= 0) {
						$a.children(".list").css({
							"-webkit-transform": "translate3d(-" + (wid * length - xpage) + "px, 0px, 0px)",
							"-webkit-transition": "-webkit-transform 0ms linear"
						});
						vi = length;
					}
				} else {
					if (vi >= length) {
						$a.children(".list").css({
							"-webkit-transform": "translate3d(0px, 0px, 0px)",
							"-webkit-transition": "-webkit-transform 0ms linear"
						});
						vi = 0;
					}
				}
				$a.children(".list").css({
					"-webkit-transform": "translate3d(-" + (wid * vi - xpage) + "px, 0px, 0px)",
					"-webkit-transition": "-webkit-transform 0ms linear"
				});
				e.preventDefault();
			},
			"touchend": function(e) {
				$a.children(".list").css({
					"-webkit-transition": "-webkit-transform " + autoAnimateTime + "ms linear"
				});
				e_pagex = e.originalEvent.changedTouches[0].pageX
				if (e_pagex - o_pagex > 0) { // 手指向右边滑动
					vi -= 2;
					_func();
				} else if (e_pagex - o_pagex < 0) { // 手指向左边滑动
					_func();
				}
				t = setInterval(_func, autoPlayTime);
			}
		});
	}
	$("#recomPro .left").click(function(){ 
			clearInterval(t);
			if (vi <= 0 || vi == length) {
				$a.children(".list").css({
					"-webkit-transform": "translate3d(-" + (wid * length) + "px, 0px, 0px)",
					"-webkit-transition": "-webkit-transform 0ms linear"
				});
				vi = length;
			}
			_func();
	})
	$("#recomPro .right").click(function(){ 
			clearInterval(t);
			if (vi >= length) {
				$a.children(".list").css({
					"-webkit-transform": "translate3d(0px, 0px, 0px)",
					"-webkit-transition": "-webkit-transform 0ms linear"
				});
				vi = 0;
			}
			_func();
	});
	_touch(); // 手指滑动触发
	_init(); // 自适应宽度
//	t = setInterval(_func, autoPlayTime);
	$(window).resize(_init); // 改变浏览器宽度
	window.onorientationchange = function() {
		_init();
	};
}
/* banner */
function banner(){
    if(!$("#banner").length){ return false; }
	var $a = $("#banner"), length = $a.find("li").length, vi = 0, wid, t, autoPlayTime = 5000, autoAnimateTime = 500, loop = true;
	// 克隆元素
	var clone = $a.find("li").eq(0).clone().addClass("clone"), tipHtml = "";;
	$a.children("ul").append(clone);
	// 生成Tip
	if(length > 1){
		for(var i=0; i<length; i++){
			i == 0 ? tipHtml += "<span class='cur icon'></span>" : tipHtml += "<span class='icon'></span>";
		}
		$(".tip").show();
	}
	$(".tip").html(tipHtml);
	// 自适应宽度
	var _init = function(){
		wid = $a.width();
		$a.children("ul").width(wid*(length+1));
		$a.find("li").width(wid);
		$a.find("img").css({"width":wid});
		$a.css({"opacity":1});
	}
	// 滚动效果函数
	var _func = function(){
		if(vi >= length){
			vi = 0;
			_func();
		}else{
			vi++;
			$a.children("ul").css({"-webkit-transform":"translate3d(-" + wid*vi + "px, 0px, 0px)", "-webkit-transition":"-webkit-transform " + autoAnimateTime + "ms linear"});
			if(vi == length){
				$(".tip").children("span").eq(0).addClass("cur").siblings().removeClass("cur");
				setTimeout(function(){
					$a.children("ul").css({"-webkit-transform":"translate3d(0px, 0px, 0px)", "-webkit-transition":"-webkit-transform 0ms linear"});
				}, autoAnimateTime);
			}else{
				$(".tip").children("span").eq(vi).addClass("cur").siblings().removeClass("cur");
			}
		}
	}
	// 滑动触发效果
	var _touch = function(){
		var o_pagex = 0,   // 接触记录值
		    e_pagex = 0;   // 离开记录值
		$a.bind({
			"touchstart":function(e){
				clearInterval(t);
				o_pagex = e.originalEvent.targetTouches[0].pageX;
			},
			"touchmove":function(e){
				e_pagex = e.originalEvent.changedTouches[0].pageX;
				var xpage = e_pagex - o_pagex;   //::负数-向左边滑动::正数-向右边滑动
				if(xpage >= 0){
					if(vi <= 0){
						$a.children("ul").css({"-webkit-transform":"translate3d(-" + (wid*length - xpage) + "px, 0px, 0px)", "-webkit-transition":"-webkit-transform 0ms linear"});
						vi = length;
					}
				}else{
					if(vi >= length){
						$a.children("ul").css({"-webkit-transform":"translate3d(0px, 0px, 0px)", "-webkit-transition":"-webkit-transform 0ms linear"});
						vi = 0;
					}
				}
				$a.children("ul").css({"-webkit-transform":"translate3d(-" + (wid*vi - xpage) + "px, 0px, 0px)", "-webkit-transition":"-webkit-transform 0ms linear"});
				e.preventDefault();
			},
			"touchend":function(e){
				$a.children("ul").css({"-webkit-transition":"-webkit-transform " + autoAnimateTime + "ms linear"});
				e_pagex = e.originalEvent.changedTouches[0].pageX
				if(e_pagex - o_pagex > 0){  // 手指向右边滑动
					vi-=2;
					_func();
				}else if(e_pagex - o_pagex < 0){  // 手指向左边滑动
					_func();
				}
				t = setInterval(_func, autoPlayTime);
			}
		});
	}
	_touch();  // 手指滑动触发
	_init();  // 自适应宽度
	t = setInterval(_func, autoPlayTime);
	$(window).resize(_init);  // 改变浏览器宽度
	window.onorientationchange = function() {
        _init();
    };
}

// 搜索
function searcher(){
    var bool = true, deValue = "请输入关键词搜索！", _search = $("#search"), _searcher = $("#searcher");
    $("#SearchSubmit").click(function(){
        if($("#SearchTxt").val() == "" || $("#SearchTxt").val() == deValue){
        	alert(deValue);
        }else{
        	search();
        }
    });
    $("#SearchTxt").val(deValue).focus(function(){
    	$(this).val("");
    }).blur(function(){
        if($(this).val() == ""){
        	$(this).val(deValue);
        }
    });
  if(!$("#skeyLoading").length){  return false }
  var bool = true, aut = 100;
  $(window).scroll(function(){ 
		if($(window).scrollTop()+$(window).height()>=$(document).height()-aut && bool == true){
             
  		     $("#skeyLoading").find(".loadingData").show();
        }
    else{
         $("#skeyLoading").find(".loadingData").hide();
    }
  });
}

function AutoScroll(obj){ 
$(obj).find("ul:first").animate({ 
marginTop:"-25px" 
},500,function(){ 
$(this).css({marginTop:"0px"}).find("li:first").appendTo(this); 
}); 
} 
$(document).ready(function(){ 
setInterval('AutoScroll("#annut")',3000); 
}); 


function backtop(){
    $("#returntop").bind("click",function() {
        $("body, html").stop().animate({"scrollTop":"0px"});
    });
    $(window).scroll(function () {
        var i = $(window).scrollTop();
        if (i > 80) {
            $("#returntop").show();
        }
        else if (i < 60) {
            $("#returntop").hide();
        }
    });

}
//layout
function layout(u){
	var $obj = $('<div class="dialog-layout"></div>');
	if(u == 0){
		$('.dialog-layout').remove();
	}else{
		if(!$('.dialog-layout').length){
			$obj.appendTo('body').show();
		}
	}
}
    if (!$(".cateList .box").find("a").length) {
        $(".cateList .box").remove();
        //		$(".cateList h3 s").remove();
    } else {
        $(".cateList h3").click(function () {
            $(".cateList h3 s").toggleClass('rotate1', "");
        });
    }
	
	if($('.box > *').length){
     $('.iconn').show();
}

$('.cateList h3').bind('click', function(){
    if($('.box').is(':hidden')){
        $('.box').slideDown();
    }else{
        $('.box').slideUp();
    }
})
/* 微信提示 */
	var btn=document.getElementById('btn');
	var clipboard=new Clipboard(btn);
	clipboard.on('success', function(e){
		$('#weixin').slideDown().delay(1500).slideUp(500);
		console.log(e);
	});
	clipboard.on('error', function(e){
		$('#weixin').slideDown().delay(1500).slideUp(500);
		console.log(e);
	});
/* 微信弹窗 */
function dkcf(){$('#wxnr').fadeIn("fast");$('#fdwx').fadeOut("fast");}
function gbcf(){$('#fdwx').fadeIn("slow");$('#wxnr').fadeOut("fast");}