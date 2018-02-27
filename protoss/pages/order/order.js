// pages/order/order.js
// import { Order } from 'order/order-model.js';
import { Cart } from '../cart/cart-model.js';
import { Address } from '../../utils/address.js';

// var order = new Order();
var cart = new Cart();
var address = new Address();
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
    var productsArr;//商品的信息,读取本地的缓存
    this.data.account=options.account;//从别的页面接收的参数
    productsArr = cart.getCartDataFromLocal(true);//订单下面的所有的信息
    //数据绑定
    this.setData({
      productsArr: productsArr,
      account:options.account,
      orderStatus:0
    });
      //显示收货的地址
    address.getAddress((res) => {
       this._bindAddressInfo(res);
    });
  },


 


//添加收货的地址

editAddress:function(event){
  var that=this;
  wx.chooseAddress({
    success:function(res){
   console.log(res);
       var addressInfo={
         name:res.userName,
         mobile:res.telNumber,
         totalDetail:address.setAddressInfo(res)
       }
       //调用数据绑定的函数
       that._bindAddressInfo(addressInfo);
       //调用address模型类中的方法
       address.submitAddress(res,(flag)=>{
         if(!flag){//接收服务器返回的结果
           that.showTips('操作提示','地址信息更新失败！');
         }
       });
    }
  })
},
/*
       * 提示窗口
       * params:
       * title - {string}标题
       * content - {string}内容
       * flag - {bool}是否跳转到 "我的页面"
       */
showTips: function (title, content, flag) {
  wx.showModal({
    title: title,
    content: content,
    showCancel: false,
    success: function (res) {
      if (flag) {
        wx.switchTab({
          url: '/pages/my/my'
        });
      }
    }
  });
},
// 绑定地址的信息,私有函数
_bindAddressInfo:function(addressInfo){
  this.setData({
   addressInfo:addressInfo
  });
},
/*下单和付款*/
// pay: function () {
//   if (this.data.addressInfo) {
//     this.showTips('下单成功');
//     return;
//   }
// if (this.data.orderStatus == 0) {
  //   this._firstTimePay();
  // } else {
  //   this._oneMoresTimePay();
  // }
// },
  pay() {
    wx.showModal({
      title: '提示',
      content: '下单成功',
      complete() {
        wx.switchTab({
          url: '/pages/my/my'
        })
      }
    })
  },



  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})