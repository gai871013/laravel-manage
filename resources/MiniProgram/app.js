//app.js
let common = require('utils/util.js');
App({
  onLaunch: function () {
    // 控制流程
    let promise = new Promise(function (resolve, reject) {
      wx.getStorage({
        key: 'token',
        success: function (res) {
          resolve("token获取成功!");
        },
        fail: function () {
          common.refreshToken();
          resolve("token刷新成功!");
        }
      })
    });
    promise.then(function (successMessage) {
      console.log(successMessage);
    });
    
    // 获取用户信息
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 可以将 res 发送给后台解码出 unionId
              this.globalData.userInfo = res.userInfo
              // console.log(res);

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        }
      }
    })
  },
  globalData: {
    userInfo: null
  }
});