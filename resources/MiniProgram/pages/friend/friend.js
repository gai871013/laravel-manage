let common = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    let that = this;
    that.setData({
      id: options.id,
    });
    wx.getStorage({
      key: 'token',
      success: function(res) {
        that.getData();
      }
    })
  },
  // 获取数据
  getData: function() {

    let that = this;
    let data = {
      id: that.data.id
    };
    let method = 'GET';
    let add_cancel = '';
    let success = function(res) {
      data = res.data.data;
      add_cancel = data.is_friend ? '取消收藏' : '添加收藏';
      that.setData({
        user: data,
        id: that.data.id,
        add_cancel: add_cancel,
        mobile: data.mobile.substr(0, 3) + '-' + data.mobile.substr(3, 4) + '-' + data.mobile.substr(7),
      });
      wx.setNavigationBarTitle({
        title: data.name,
      })
    };
    common.request(common.urls.userInfo, data, method, success);

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
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

  },
  // 添加收藏&&取消收藏
  add_cancel: function() {
    let that = this;
    let type = '';
    let add_cancel = '';
    if (that.data.user.is_friend) {
      // 取消好友关系
      type = 'cancel';
    } else {
      // 添加为好友
      type = 'add';
    }
    let success = function(res) {
      wx.showModal({
        title: '温馨提示',
        content: res.data.message,
        showCancel: false
      });
      that.getData();
    }
    common.request(common.urls.card, {
      type: type,
      id: that.data.user.id
    }, 'GET', success);
  },
  go2index: function() {
    wx.switchTab({
      url: '../index/index',
    })
  },
  // 拨打电话
  makeFhoneCall: function() {
    wx.makePhoneCall({
      phoneNumber: this.data.mobile,
    });
  },
})