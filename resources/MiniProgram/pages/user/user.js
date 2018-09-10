let common = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    user: null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    this.setData({
      user: wx.getStorageSync('user')
    })
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
        title:'添加我为好友，联系更方便',
        path: '/pages/friend/friend?id=' + self.id
      }
    } else {
      return {
        title: '添加我为好友，联系更方便',
        path: '/pages/index/index'
      }
    }
  },
  saveInfo: function(e) {
    let formId = e.detail.formId;
    common.dealFormIds(formId);
    console.log(e);
    let data = e.detail.value;
    if (!/^[\u4E00-\u9FA5]{2}/.test(data.name)) {
      return this.alert('请输入您的姓名(至少两个汉字)');
    }
    if (!/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(data.mobile)) {
      return this.alert('请输入正确的手机号码');
    }
    if (data.company_name == '') {
      return this.alert('请输入您的公司名称');
    }

    common.request(common.urls.saveUserDetail, {
      info: JSON.stringify(data)
    }, 'POST', function(res) {
      console.log(res);
      if (res.data.status_code == 20002) {
        wx.setStorageSync('user', res.data.data);
        wx.showModal({
          title: '提示',
          content: res.data.message,
          showCancel: false,
          success: function(res) {
            wx.navigateBack();
          }
        });
      }
    });
  },
  // 提示框
  alert: function(txt) {
    wx.showModal({
      title: '提示',
      content: txt,
      showCancel: false
    });
    return false;
  },

})