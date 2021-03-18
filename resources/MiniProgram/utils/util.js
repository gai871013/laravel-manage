const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()
  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}
// 年月日
const formatDate = date => {
  let year = date.getFullYear();
  let month = date.getMonth() + 1;
  let day = date.getDate();
  return [year, month, day].map(formatNumber).join('/')
}
const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}
// 配置本次请求的信息 S
let request_url = '';
let request_data = {};
let request_method = '';
let request_success = function() {};
let request_fail = function() {};
// 配置本次请求信息 E
// 请求路径
var base_url = 'https://micro-card.wc87.com/api/';
var urls = {
  // 获取Token
  token: base_url + "token",
  // formIds
  saveFormIds: base_url + "saveFormIds",
  // 保存用户信息
  saveUserInfo: base_url + "saveUserInfo",
  // 保存用户详情
  saveUserDetail: base_url + "saveUserDetail",
  // 获取好友列表
  friendLists: base_url + "friendLists",
  // 获取好友详情
  userInfo: base_url + 'userInfo',
  // 收藏&取消
  card: base_url + 'card'
};

// 封装微信request
function getRequest(url, data, method, success, fail) {
  // 设置本次请求数据/方法
  request_data = data;
  request_method = method;
  request_success = success;
  request_fail = fail;
  // E

  wx.showLoading({
    title: '正在加载...',
  });
  wx.showNavigationBarLoading();
  var data = typeof data == 'undefined' ? [] : data;
  var method = typeof method == 'undefined' ? 'GET' : method;
  wx.request({
    url: url,
    method: method,
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      token: wx.getStorageSync('token')
    },
    data: data,
    success: function(res) {
      wx.hideLoading();
      var data = res.data;
      if (data.status_code == 401) {
        // 如果授权失效，记录本次请求路径，为下次请求做准备
        request_url = url;
        wx.showModal({
          title: '提示',
          content: res.data.message,
          showCancel: false,
          success: function(res) {
            if (res.confirm) {
              wx.clearStorage();
              refreshToken();
            }
          }
        });
        return;
      } else {
        request_url = '';
      }
      typeof success == 'function' && success(res);
    },
    complete: function(res) {
      wx.hideLoading();
      wx.hideNavigationBarLoading();
      typeof complete == 'function' && fail(res);
    },
    fail: function(res) {
      wx.hideLoading();
      typeof fail == 'function' && fail(res);
    }
  });
  // 发送formIds到服务器
  var formIds = wx.getStorageSync('formIds');
  if (formIds.length) {
    wx.request({
      url: urls.saveFormIds,
      method: 'POST',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
        token: wx.getStorageSync('token')
      },
      // header: { 'content-type': 'application/x-www-form-urlencoded', token: 123456 },
      data: {
        formIds: JSON.stringify(formIds)
      },
      success: function(res) {
        if (res.data.status_code == 20002) {
          wx.setStorageSync('formIds', []);
        }
      }
    })
  }
}
// 刷新token
function refreshToken(fun) {
  wx.showLoading({
    title: '正在获取数据...'
  });
  var that = this;
  wx.login({
    success: function(res) {
      if (res.code) {
        //发起网络请求
        wx.request({
          url: urls.token,
          data: {
            code: res.code
          },
          success: function(res) {
            var data = res.data.data;
            if (res.data.errcode == 40029) {
              wx.clearStorage();
              refreshToken();
              return;
            }
            wx.setStorageSync('token', data.token);
            if (data.user) {
              wx.setStorageSync('user', data.user);
              let index = getPage('pages/index/index');
              if (index) {
                index.showData(data.user);
              }
              let friend = getPage('pages/friend/friend');
              console.log(friend);
              if (friend) {
                friend.getData();
              }
              typeof fun == 'function' && fun();
            }
            // 如果上次失败路径不为空
            if (request_url != '') {
              console.log(request_url);
              getRequest(request_url, request_data, request_method, request_success, request_fail);
              request_url = '';
            }
            wx.showToast({
              title: 'Token已刷新'
            })
            wx.hideLoading();
          },
          fail: function() {
            wx.showModal({
              title: '提示',
              content: '网络连接错误，请重试...',
              showCancel: false
            });
          }
        })
      } else {
        wx.showModal({
          title: '提示',
          content: '获取用户登录态失败！' + res.errMsg,
          showCancel: false
        })
        // console.log('获取用户登录态失败！' + res.errMsg);
      }
    }
  });
}
let getPage = function(pageName) {
  var pages = getCurrentPages();
  return pages.find(function(page) {
    return page.__route__ == pageName
  })
};

// 处理formId
function dealFormIds(formId) {
  let formIds = wx.getStorageSync('formIds'); // 获取全局数据中的推送码 globalFormIds数组
  if (!formIds) formIds = [];
  let data = {
    formId: formId,
    expire: (parseInt(new Date().getTime() / 1000)) + 604000 // 计算7天后的过期时间戳（减去800秒，防止formId过期）
  }
  formIds.push(data);
  wx.setStorageSync('formIds', formIds);
  //app.globalData.globalFormIds = formIds; // 保存推送码并赋值给全局变量
  //  console.log(app);
}
// 是否在数组内
function in_array(search, array) {
  for (var i in array) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

module.exports = {
  formatTime: formatTime, //年月日  时分秒
  formatDate: formatDate, //年月日
  urls: urls,
  request: getRequest,
  refreshToken: refreshToken,
  dealFormIds: dealFormIds,
  in_array: in_array
};
// let formId = e.detail.formId;
// common.dealFormIds(formId);