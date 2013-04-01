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
        url: "http://mdjyi.com/index.php/Info/type",
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
            $Jason.dataListIns.arg = {typeid:typeidvalue,pager:1};
             $Jason.dataListIns.recentPage = 1;
            $Jason.dataListIns.load();
        })
    }
})
//

$Jason.dataList = $Class({
    _init:function(def){
        this.arg = $.extend({},def.arg||{});
        this.totalPage = "";
        this.recentPage = 1;//默认请求第一页
        this.data = {};
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
        url: "http://mdjyi.com/index.php/Info/info?"+urlParam,
        dataType:"jsonp",
        jsonpCallback:"datacbfunc",
        success:function(data){
          self.data = data;
          self._render();
        }});
    },
    _render:function(){
        var json = this.data.data;
        this.totalPage = this.data.pagenum;
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
        tmpl = this._templete(1,top_arr)+this._templete(2,sec_arr)+this._templete(3,thir_arr)+this._templete(4,four_arr);
        $(".add-list").html(tmpl);
        this._render_page();
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
    },
    _render_page:function(){
        var tmpl = "";
        var ellipsis = "<li class='ellipsis'>......</li>";
        tmpl += "<ul>"
        if(this.recentPage!=1){
            tmpl += "<li class='pre-page page-control'  val='"+(this.recentPage-1)+"'>上一页</li>";   
        }else{
            tmpl += "<li class='pre-page-disable'>上一页</li>";
        }
        var flag = "";
        if(this.totalPage>=7){
          if(this.recentPage<=4){
              for(var i=0;i<7;i++){
                 this.recentPage==(i+1) ? flag = "on" : flag = "";
                 tmpl+= "<li class='page-num page-control "+flag+"' val='"+(i+1)+"'>"+(i+1)+"</li>";
              }
              tmpl+=ellipsis;
              tmpl+="<li class='page-num page-control'  val='"+this.totalPage+"'>"+this.totalPage+"</li>";
          }else if(this.recentPage>this.totalPage-4){
              tmpl+="<li class='page-num page-control  val='1'>1</li>";
              tmpl+=ellipsis;
              for(var i=(this.totalPage-7);i<this.totalPage;i++){
                 this.recentPage==(i+1) ? flag = "on" : flag = "";
                 tmpl+= "<li class='page-num page-control "+flag+"' val='"+(i+1)+"'>"+(i+1)+"</li>";
              }
          }else{
              tmpl+="<li class='page-num page-control  val='1'>1</li>";
              tmpl+=ellipsis;
              for(var i=(this.recentPage-3);i<this.recentPage+4;i++){
                 this.recentPage==(i+1) ? flag = "on" : flag = "";
                 tmpl+= "<li class='page-num page-control "+flag+"' val='"+(i+1)+"'>"+(i+1)+"</li>";
              }
              tmpl+=ellipsis;
              tmpl+="<li class='page-num page-control'  val='"+this.totalPage+"'>"+this.totalPage+"</li>";
          }
        }else{
          for(var i=0;i<this.totalPage;i++){
             this.recentPage==(i+1) ? flag = "on" : flag = "";
             tmpl+= "<li class='page-num page-control "+flag+"' val='"+(i+1)+"'>"+(i+1)+"</li>";
          }
        }
        /*
        for(var i=0;i<this.totalPage;i++){
            this.recentPage==(i+1) ? flag = "on" : flag = "";
            tmpl+= "<li class='page-num page-control "+flag+"' val='"+(i+1)+"'>"+(i+1)+"</li>";
        }*/
        if(this.recentPage==this.totalPage){
             tmpl += "<li class='next-page-disable'>下一页</li>";
        }else{
             tmpl += "<li class='next-page page-control' val='"+(this.recentPage+1)+"'>下一页</li>";
        }
        tmpl += "</ul>"
        $(".pager").html(tmpl);
        this._bind_page();
    },
    _bind_page:function(){
        var self = this;
        $(".pager ul .page-control").bind("click",function(){
            if($(this).attr("val")){
              self.recentPage = parseInt($(this).attr("val"));
              $(".pager ul .pagenum").eq(self.recentPage).addClass("on");
              var typeid  = $(".type-list .on").children(0).attr("data-id");
              self.arg = {pager:$(this).attr("val"),typeid:typeid}
              self.load();
            }
        })
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
