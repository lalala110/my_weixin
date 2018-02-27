            import { Config } from 'config.js';
              import { Token } from 'token.js';
              class Base {
                constructor() {
                  "use strict";
                  this.baseRequestUrl = Config.restUrl;

                }
                //norefech 为真，不再重复授权的机制
              request(params,noRefetch) {
                var that=this;
                var url = this.baseRequestUrl + params.url;
                  if (!params.type){
                    params.type="GET";
                  }
                  wx.request({
                    url: url,
                    data: params.data,
                    method: params.type,
                    header: {
                      'content-type': 'application/json',
                      'token': wx.getStorageSync('token')
                    },
                    success: function (res) {
                     var code=res.statusCode.toString();
                     var startChar=code.charAt(0);//获取code码的第一个字母
                     if (startChar=="2") { params.sCallback && params.sCallback(res.data);}
                     else{
                       //处理错误的情况
                       if(code=='401'){
                         //从新向服务器发送请求，再次调用getToken的接口，wx.request的接口              
                         if (!noRefetch)
                          { that._refetch(params);}
                        
                       }
                      
                     }
                     if(noRefetch){
                       params.eCallback && params.eCallback(res.data);
                     }
                    },
                    //此次调用是不成功的
                    fail:function(err){
                      console.log(err);
                    }
                  })
                }
              _refetch(param) {
                var token = new Token();
                token.getTokenFromServer((token) => {
                  this.request(param, true);
                });
              }
              /*获得元素上的绑定的值*/
              getDataSet(event, key) {
                return event.currentTarget.dataset[key];
              };
              }


              export { Base};
       