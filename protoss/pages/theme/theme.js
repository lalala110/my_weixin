import { Theme } from 'theme-model.js';
var theme = new Theme();
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //获取当点击主题的时候的页面跳转的Id以及名字
    var id=options.id;
    var name=options.name;
    this.data.id=id;
    this.data.name=name;
  
   this._loadData();
  },
  onReady:function(){
    wx.setNavigationBarTitle({
      title: this.data.name,//动态设置标题栏
    })
  },
_loadData:function(){
  theme.getProductorData(this.data.id,(data)=>{
    this.setData({
      themeInfo:data
    });
  })
},
 
//   /*跳转到商品详情*/
  onProductsItemTap: function (event) {
    var id = theme.getDataSet(event, 'id');
  wx.navigateTo({
    url: '../product/ptoduct?id=' + id
  })
},
})