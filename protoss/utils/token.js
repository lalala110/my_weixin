import { Config } from 'config.js';
class Token{
constructor(){
  //通过拼接地址的方法
  this.verifyUrl = Config.restUrl + 'token/verify';
  this.tokenUrl = Config.restUrl + 'token/user';
}
//1检测令牌是否有效
verify(){
  var token=wx.getStorageSync('token');//从缓存中读取令牌
  if(!token){//如果令牌不存在，从服务端获取令牌
    this.getTokenFromServer();
  }
  else{
    this._verifyFromServer(token);//当令牌存在，也要从服务端检测令牌是否有效
  }
}
//从服务器校验令牌
_verifyFromServer(token) {
  var that = this;
  wx.request({
    url: that.verifyUrl,
    method: 'POST',
    data: {
      token: token
    },
    success: function (res) {
      var valid = res.data.isValid;
      if (!valid) {
        that.getTokenFromServer();
      }
    }
  })
}
//从服务器获取token
getTokenFromServer(callBack) {
  var that = this;
  //获取code码就要调用login的接口
  wx.login({
    success: function (res) {
      wx.request({
        url: that.tokenUrl,
        method: 'POST',
        data: {
          code: res.code
        },
        success: function (res) {
          wx.setStorageSync('token', res.data.token);
          callBack && callBack(res.data.token);
        }
      })
    }
  })
}
}
export{Token};