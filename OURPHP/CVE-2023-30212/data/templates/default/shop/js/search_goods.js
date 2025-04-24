$(function(){
    /* 筛选事件  */
    $('span[dstype="span_filter"]').click(function(){
    	_i = $(this).find('i');
    	location.assign($(this).find('i').attr('data-uri'));
        return false;
    });
    
    $("#search_by_price").click(function(){
        replaceParam('price', $(this).siblings("input:first").val() + '-' + $(this).siblings("input:last").val());
        return false;
    });
    
    // 筛选的下拉展开
    $(".select").hover(function () {
        $(this).addClass("over").next().css("display", "block");
    }, function () {
        $(this).removeClass("over").next().css("display", "none");
    });

    $(".option").hover(function () {
        $(this).css("display", "block");
    }, function () {
        $(this).css("display", "none");
    });

    $('.list_pic').find('dl').on('mouseout', function () {
        $(this).find('.slide-show').hide();
    });
    $('.slide_tiny').on('mouseover', function () {
        small_image = $(this).attr('dstype');
        $(this).parents('.slide-show').find('img:first').attr('src', small_image);
    });


    // 加入购物车
    $('a[dstype="add_cart"]').click(function() {
        var _parent = $(this).parent(), thisTop = _parent.offset().top, thisLeft = _parent.offset().left;
        animatenTop(thisTop, thisLeft), !1;
        eval('var data_str = ' + $(this).attr('data-param'));
        addcart(data_str.goods_id, 1, '');
    });
    // 立即购买
    $('a[dstype="buy_now"]').click(function(){
        eval('var data_str = ' + $(this).attr('data-param'));
        $("#goods_id").val(data_str.goods_id+'|1');
        $("#buynow_form").submit();
    });
    // 图片切换效果
    $('.goods-pic-scroll-show').find('a').mouseover(function(){
        $(this).parents('li:first').addClass('selected').siblings().removeClass('selected');
        var _src = $(this).find('img').attr('src');
        _src = _src.replace('_60.', '_240.');
        $(this).parents('.goods-content').find('.goods-pic').find('img').attr('src', _src);
    });
// 品牌按首字母切换
    $('ul[dstype="ul_initial"] > li').mouseover(function(){
        $(this).addClass('current').siblings().removeClass('current');
        if ($(this).attr('data-initial') == 'all') {
            $('ul[dstype="ul_brand"] > li').show();
            return;
        }
        $('ul[dstype="ul_brand"] > li').hide();
        $('ul[dstype="ul_brand"] > li[data-initial="'+$(this).attr('data-initial')+'"]').show();
    });
    // 品牌显示筛选
    $('span[dstype="brand_show"]').click(
        function(){
            if(!$('ul[dstype="ul_initial"]').is(':visible')){
            $('ul[dstype="ul_initial"]').show();
            $('ul[dstype="ul_brand"] > li').show();
            $(this).html('<i class="iconfont">&#xe738;</i>收起');
       }else{
            $('ul[dstype="ul_initial"]').hide();
            $('ul[dstype="ul_brand"] > li:gt(13)').hide();
            $('ul[dstype="ul_brand"] > li:lt(14)').show();
            $(this).html('<i class="iconfont">&#xe73a;</i>更多');
        }
                }
    );
});
function animatenTop(thisTop, thisLeft) {
    var CopyDiv = '<div id="box" style="top:' + thisTop + "px;left:" + thisLeft + 'px" ></div>', topLength = $("#rtoolbar_cart").offset().top, leftLength = $("#rtoolbar_cart").offset().left;
    $("body").append(CopyDiv), $("body").children("#box").animate({
        "width": "0",
        "height": "0",
        "margin-top":"0",
        "top": topLength,
        "left": leftLength,
        "opacity": 0
    }, 1000, function() {
        $(this).remove();
    });
}

function setcookie(name,value){
    var Days = 30;   
    var exp  = new Date();   
    exp.setTime(exp.getTime() + Days*24*60*60*1000);   
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();   
}



