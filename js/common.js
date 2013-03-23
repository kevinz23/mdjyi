// namespace
window.$Jason = window.$Jason || {};

// class define
$Class = function (oDef) {
    function typeClass() {
        if (typeof this._init == "function") 
		this._init.apply(this, arguments);
    }

    typeClass.prototype = oDef;
    typeClass.prototype.constructor = typeClass;

    return typeClass;
};
//布局配置器
var CONSTANT_COLUMNS = [{num:1,width:"98.2%"},{num:2,width:"48.6%"},{num:3,width:"32.05%"},{num:4,width:"23.8%"}];

$Jason.typeList = $Class({
    _init:function(def){
        this.load();
    },
    load:function(){
        this._getData();
        this._bind();
    },
    _getData:function(){
      $(".type-list").html('<li class="loading">分类读取中...</li>');
      var self = this;
      $.ajax({
        url: "http://mdjyi.com/offlinetype.php",
        dataType:"jsonp",
        jsonpCallback:"typecbfunc",
        success: self._render        
        });
    },
    _render:function(res){
        var tmpl = "";
        var json = res.type;
        for(var i=0;i<json.length;i++){
          tmpl+="<li><a href='javascript:void(0)' data-id='"+json[i].id+"'>"+json[i].type_name+"</li>";
        }
        $(".type-list").html(tmpl);
    },
    _bind:function(){
        $(".type-list li a").live("click",function(){
            var typeidvalue = $(this).attr("data-id");
            $(this).parent().siblings().removeClass("on");
            $(this).parent().addClass("on");
            $Jason.dataListIns.arg = {typeid:typeidvalue};
            $Jason.dataListIns.load();
        })
    }
})
//

$Jason.dataList = $Class({
    _init:function(def){
        this.arg = $.extend({},def.arg||{});
        this.load();
    },
    load:function(){
        this._getData();
    },
    _getData:function(){
      $(".add-list").html('<li class="loading">列表读取中...</li>');
      var self = this;
      var urlParam = $.param(this.arg);
      $.ajax({
        url: "http://mdjyi.com/offlinedata.php?"+urlParam,
        dataType:"jsonp",
        jsonpCallback:"datacbfunc",
        success: self._render          
        });
    },
    _render:function(res){
        var json = res.data;
        var tmpl = "";
        var top_arr = [];
        var sec_arr = [];
        var thir_arr = [];
        var four_arr = [];
        for(var i=0;i<json.length;i++){ 
            switch(parseInt(json[i].info_level)){
               case 1:
                  top_arr.push(json[i]);
               break;

               case 2:
                  sec_arr.push(json[i]);
               break;

               case 3:
                  thir_arr.push(json[i]);
               break;
                  
               case 4:
                 four_arr.push(json[i]);
               break;
            }
        }
        tmpl = $Jason.dataListIns._templete(1,top_arr)+$Jason.dataListIns._templete(2,sec_arr)+$Jason.dataListIns._templete(3,thir_arr)+$Jason.dataListIns._templete(4,four_arr);
        $(".add-list").html(tmpl);
    },
    _templete:function(count,arr){
        var tmpl = "";
        var static_num = Math.floor(arr.length/count);
        if(arr.length===0){tmp="";return tmpl;}
        for(var i=0;i<arr.length;i++){
          if(i%static_num==0){
            tmpl+="<div class='steam' style='width:"+CONSTANT_COLUMNS[count-1].width+"'>";
          }
          tmpl += "<div class='item'>";
          tmpl += "<p class='title'>"+arr[i].title+"</p>"
          tmpl += "<p class='content'>"+arr[i].content+"</p>"
          tmpl += "<p class='time'>发布于："+arr[i].insert_time+"</p>"
          tmpl += "</div>";
          if(i==(arr.length-1)||(i+1)%static_num==0){
            tmpl+="</div>";
          }
          if(i==(arr.length-1)){
            tmpl+="<div class='cl'></div>";
          }
        }
        return tmpl;
    }
})

$(function(){
    $Jason.typeListIns = new $Jason.typeList({});
    $Jason.dataListIns = new $Jason.dataList({});
    //首先将#back-to-top隐藏
    $("#back-to-top").hide();
    //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消
    $(window).scroll(function(){
      if ($(window).scrollTop()>100){
       $("#back-to-top").fadeIn(1500);
      }
      else
      {
       $("#back-to-top").fadeOut(1500);
      }
    });
     //当点击跳转链接后，回到页面顶部位置
    $("#back-to-top").click(function(){
      $('body,html').animate({scrollTop:0},1000);
      return false;
    });
})
