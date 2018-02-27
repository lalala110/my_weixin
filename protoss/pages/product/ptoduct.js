// pages/product/ptoduct.js
import { Product } from 'product-model.js';
import { Cart } from '../cart/cart-model.js';
var product = new Product();
var cart = new Cart();
Page({

  /**
   * 页面的初始数据
   */
  data: {
  id:null,
  countsArray:[1,2,3,4,5,6,7,8,9,10],
    productCounts:1,//默认初始值是1
    currentTabsIndex:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  //获取id 的参数(点击轮播图的时候获取到对应的id的值)
  var id=options.id;
this.data.id=id;
  this._loadData();
  },
  _loadData:function(){
    product.getDetailInfo(this.data.id,(data)=>{
     this.setData({
       cartTotalCounts: cart.getCartTotalCounts(),//获取商品的数量
       product:data//从服务器加载数据

     })
    });
  },
  bindPickerChange:function(event){
    var index=event.detail.value;
    var selectedCount=this.data.countsArray[index]
    this.setData({
      productCounts: selectedCount
    })
  },
  //实现切换tab栏的效果
  onTabsItemTap: function (event) {
    var index = product.getDataSet(event,'index');

    this.setData({
      currentTabsIndex:index
    });
  },
  /*添加到购物车*/
  onAddingToCartTap: function (event) {
   this.addToCart();
   var counts = this.data.cartTotalCounts+ this.data.productCounts;//动态的展示购物车的数量，将原来有的和现在的一并加起来
   //响应用户的操作,数据的绑定
   this.setData({
     cartTotalCounts:cart.getCartTotalCounts(),
   });

  },

  /*将商品数据添加到内存中*/
  addToCart: function () {
    var tempObj = {}, keys = ['id', 'name', 'main_img_url', 'price'];
    for (var key in this.data.product) {
      if (keys.indexOf(key) >= 0) {
        tempObj[key] = this.data.product[key];
      }
    }

    cart.add(tempObj, this.data.productCounts);
  },
  onCartTap: function (event) {
    wx.switchTab({
      url: '/pages/cart/cart',
    });
  }
})

