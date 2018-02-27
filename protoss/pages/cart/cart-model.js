import { Base } from '../../utils/base.js';

class Cart extends Base {
  constructor() {
    super();
    this._storageKeyName = 'cart';
  };

add(item,counts){
  //获取购物车的数据
  var cartData = this.getCartDataFromLocal();
  var isHasInfo = this._isHasThatOne(item.id, cartData);
  if (isHasInfo.index==-1 ){
    //没有商品,是新的商品，添加到缓存当中去
    item.counts=counts;
    item.selectStatus=true;//商品是否被选中，添加的时候，默认选中
    cartData.push(item);
  }
  else{
    //就表明商品是存在的，就要增加
    cartData[isHasInfo.index].counts += counts;
  }
  //更新缓存,存取缓存
  wx.setStorageSync(this._storageKeyName, cartData);
}
//从本地中读取购物车
getCartDataFromLocal(flag){
    var res = wx.getStorageSync(this._storageKeyName);//读取缓存的方法
        if(!res){
          res=[];
         }
         //在下单的时候过滤不下单的商品

        if (flag) {
          var newRes = [];
          for (let i = 0; i < res.length; i++) {
            if (res[i].selectStatus) {
              newRes.push(res[i]);
            }
          }
          res = newRes;
        }
   return res;
      }
      //获取商品的总的数量
      //flag:
/*
*获得购物车商品总数目,包括分类和不分类
* param:
* flag - {bool} 是否区分选中和不选中
* return
* counts1 - {int} 不分类
* counts2 -{int} 分类
*/
getCartTotalCounts(flag) {
  var data = this.getCartDataFromLocal();
  var counts=0;
  for (let i = 0; i < data.length; i++) {
     if(data[i].selectStatus){
       counts += data[i].counts;
     }
     else{
       counts += data[i].counts;
     }
       }
 return counts;

};

   _isHasThatOne(id,arr){
      //判断是否有相应的商品的信息,是否被添加到购物车当中，返回数组的序号
      var item,
      result={index:-1}
      for(let i=0;i<arr.length;i++){
        item=arr[i];
        if(item.id==id){
          result={
            index:i,
            data:item
          };
          break;
        }

      }
       return result;
  
    }
//实现商品修改数目
_changeCounts(id,counts){
  var cartData=this.getCartDataFromLocal();
   var hasInfo=this._isHasThatOne(id,cartData);
  if(hasInfo.index!=-1){
  if(hasInfo.data.counts>1){
   cartData[hasInfo.index].counts+=counts;
     }
}
  //更新缓存,存取缓存
  wx.setStorageSync(this._storageKeyName, cartData);
};

//增加商品的数目的方法默认是每次只能能增加1个
addCounts(id){
  this._changeCounts(id,1);
}
//当购物车是减的时候，默认减1
  cutCounts(id) {
    this._changeCounts(id, -1);
  };
  //删除商品的时候,可以选择删除多种
  delete(ids){
    if (!(ids instanceof Array)){
      ids=[ids];
    }
    var cartData = this.getCartDataFromLocal();
   for(let i=0;i<cartData.length;i++){
     var hasInfo = this._isHasThatOne(ids[i], cartData);
     if(hasInfo.index=-1){
       cartData.splice(hasInfo.index, 1);
     }
   }
   //更新缓存,存取缓存
   wx.setStorageSync(this._storageKeyName, cartData);
 }

}



export{Cart};