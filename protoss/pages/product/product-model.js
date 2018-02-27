import { Base } from '../../utils/base.js';


class Product extends Base {
  constructor() {
    super();
  }
  //加载商品详情数据部分
  getDetailInfo(id, callback) {
    var param = {
      url: 'product/' + id,
      sCallback: function (data) {
        callback && callback(data);
      }
    };
    this.request(param);
  }
};

export { Product }
