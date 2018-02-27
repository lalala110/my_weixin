// pages/my/my.js

import { Address } from '../../utils/address.js';
// import { Order } from '../order/order-model.js';
import { My } from '../my/my-model.js';

var address = new Address();
// var order = new Order();
var my = new My();

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

    this._loadData();
    this._getAddressInfo();

  },
  /**地址信息**/
  _getAddressInfo: function () {
    var that = this;
    address.getAddress((addressInfo) => {
      that._bindAddressInfo(addressInfo);
    });
  },
  /**绑定地址的信息 */
  _bindAddressInfo: function (addressInfo) {

    this.setData({
      addressInfo: addressInfo
    });
  },
  //添加收货的地址
  editAddress: function (event) {
    var that = this;
    wx.chooseAddress({
      success: function (res) {
        console.log(res);
        var addressInfo = {
          name: res.userName,
          mobile: res.telNumber,
          totalDetail: address.setAddressInfo(res)
        }
        //调用数据绑定的函数
        that._bindAddressInfo(addressInfo);
        //调用address模型类中的方法
        address.submitAddress(res, (flag) => {
          if (!flag) {//接收服务器返回的结果
            that.showTips('操作提示', '地址信息更新失败！');
          }
        });
      }
    })
  },



  _loadData: function () {
    var that = this;
    my.getUserInfo((data) => {
      that.setData({//用户信息绑定
        userInfo: data
      });

    });








  },







})
