 import {Home} from 'home-model.js';
var home = new Home();
Page({


 
  data: {
    
  },
onLoad:function(){
  this._loadData();
},
_loadData:function(){
  var id=1;
  //轮播图部分
  home.getBannerData(id,(res)=>{
    // console.log(res);
    this.setData({
      'bannerArr':res
    })
  });
  //主题部分
  home.getThemeData((res)=>{
    this.setData({
      'themeArr': res
    })
  });
  //商品列表
  home.getProductsData((data) => {
    this.setData({
      'productsArr': data
    })
  });
},
//页面跳转
onProductsItemTap:function(event){
  // var id=event.currentTarget.dataset.id;
  var id = home.getDataSet(event, 'id');
  wx.navigateTo({
    url:'../product/ptoduct?id='+id
  });
},
//主题页面跳转的部分
onThemesItemTap: function (event) {
  // var id=event.currentTarget.dataset.id;
  var id = home.getDataSet(event, 'id');
  var name = home.getDataSet(event, 'name');
  wx.navigateTo({
    url: '../theme/theme?id=' + id+'&name='+name
  });
},


 
})