let common = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    date: [],
    lists: [],
    all: [],
    page: 0,
    keyword: '',
  },
  // 获取数据
  getData: function() {
    // 设置我的信息
    this.setData({
      user: wx.getStorageSync('user')
    });
    let that = this;
    let data = {
      page: ++this.data.page,
      keyword: this.data.keyword
    };
    let succcess = function(res) {
      console.log(res);
      that.setData({
        all: that.data.all.concat(res.data.data)
      });
      that.dealData();
    }
    common.request(common.urls.friendLists, data, 'GET', succcess);
  },
  // 处理数据
  dealData: function() {
    let that = this;
    let data = that.data.all;
    let lists = [];
    let date = [];
    for (let i in data) {
      if (that.inArray(data[i].date, date) < 0) {
        date.push(data[i].date);
        lists[data[i].date + ' '] = [];
      }
      lists[data[i].date + ' '].push(data[i].detail);
    }

    console.log(lists);
    that.setData({
      date: date,
      lists: lists
    })
    console.log(date);
    console.log(lists);
  },
  // 判断是否在数组内
  inArray: function(elem, array, i) {
    var len;

    if (array) {
      // if (indexOf) {
      //   return indexOf.call(array, elem, i);
      // }

      len = array.length;
      i = i ? i < 0 ? Math.max(0, len + i) : i : 0;

      for (; i < len; i++) {
        // Skip accessing in sparse arrays
        if (i in array && array[i] === elem) {
          return i;
        }
      }
    }

    return -1;
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    this.getData();
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
    this.setData({
      page: 0,
      keyword: '',
      all: [],
      date: []
    });
    this.getData();
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {
    console.log(new Date());
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
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
  // 搜索
  search: function(e) {
    console.log(1234);
    this.setData({
      page: 0,
      keyword: e.detail.value,
      all: [],
      date: []
    });
    this.getData();
  },
  // 回到首页
  index: function() {
    wx.switchTab({
      url: '../index/index',
    })
  },
  friend: function(e) {
    console.log(e);
    wx.navigateTo({
      url: '../friend/friend?id=' + e.currentTarget.dataset.id,
    })
  }
})