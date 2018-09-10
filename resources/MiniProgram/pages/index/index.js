//index.js
//获取应用实例
const app = getApp();
let common = require('../../utils/util.js');

Page({
  data: {
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },
  onLoad: function() {
    let that = this;
    if (app.globalData.userInfo) {
      this.setData({
        user: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        that.saveUserInfo(res);
        this.setData({
          user: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          that.saveUserInfo(res);
          app.globalData.userInfo = res.userInfo
          this.setData({
            user: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  onShow: function() {
    let that = this;
    wx.getStorage({
      key: 'user',
      success: function(res) {
        that.showData(res.data);
      }
    })
  },
  // 展示信息
  showData: function(data) {
    console.log(data, new Date());
    let hasUserInfo = data.nickName == null ? false : true;
    // let mobile = 
    this.setData({
      user: data,
      mobile: data.mobile.substr(0, 3) + '-' + data.mobile.substr(3, 4) + '-' + data.mobile.substr(7),
      hasUserInfo: hasUserInfo
    });
  },
  // 获取用户信息
  getUserInfo: function(e) {
    if (!e.detail.userInfo) {
      return;
    }
    app.globalData.userInfo = e.detail.userInfo;
    this.saveUserInfo(e.detail);
    this.setData({
      user: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  // 保存用户信息
  saveUserInfo: function(data) {
    //console.log(data);
    let self = wx.getStorageSync('user');
    if (self.nickName == null && data.rawData) {
      common.request(common.urls.saveUserInfo, {
        info: data.rawData
      }, 'POST', function(res) {
        if (res.data.status_code == 20002) {
          self.nickName = data.userInfo.nickName;
          self.avatarUrl = data.userInfo.avatarUrl;
          wx.setStorageSync('user', self);
        }
      });
    }

    this.setData({
      'user': wx.getStorageSync('user')
    })
  },
  // 创建名片
  create: function(e) {
    let formId = e.detail.formId;
    common.dealFormIds(formId);
    let self = wx.getStorageSync('user');
    if (self.nickName == null) {
      wx.showModal({
        title: '温馨提示',
        content: '请先点击上面按钮获取您的头像~_~',
        showCancel: false
      });
      return;
    }
    wx.navigateTo({
      url: '../user/user',
    });
    console.log(new Date());
  },
  // 拨打电话
  makeFhoneCall: function() {
    wx.makePhoneCall({
      phoneNumber: this.data.mobile,
    });
  },
  onShareAppMessage: function() {
    let self = wx.getStorageSync('user');
    if (self.name) {
      return {
        title: '添加我为好友，联系更方便',
        path: '/pages/friend/friend?id=' + self.id
      }
    } else {
      return {
        title: '添加我为好友，联系更方便',
        path: '/pages/index/index'
      }
    }
  }
})