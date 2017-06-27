/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/axios/index.js":
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./node_modules/axios/lib/axios.js");

/***/ }),

/***/ "./node_modules/axios/lib/adapters/xhr.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var settle = __webpack_require__("./node_modules/axios/lib/core/settle.js");
var buildURL = __webpack_require__("./node_modules/axios/lib/helpers/buildURL.js");
var parseHeaders = __webpack_require__("./node_modules/axios/lib/helpers/parseHeaders.js");
var isURLSameOrigin = __webpack_require__("./node_modules/axios/lib/helpers/isURLSameOrigin.js");
var createError = __webpack_require__("./node_modules/axios/lib/core/createError.js");
var btoa = (typeof window !== 'undefined' && window.btoa && window.btoa.bind(window)) || __webpack_require__("./node_modules/axios/lib/helpers/btoa.js");

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();
    var loadEvent = 'onreadystatechange';
    var xDomain = false;

    // For IE 8/9 CORS support
    // Only supports POST and GET calls and doesn't returns the response headers.
    // DON'T do this for testing b/c XMLHttpRequest is mocked, not XDomainRequest.
    if ("development" !== 'test' &&
        typeof window !== 'undefined' &&
        window.XDomainRequest && !('withCredentials' in request) &&
        !isURLSameOrigin(config.url)) {
      request = new window.XDomainRequest();
      loadEvent = 'onload';
      xDomain = true;
      request.onprogress = function handleProgress() {};
      request.ontimeout = function handleTimeout() {};
    }

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password || '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    request.open(config.method.toUpperCase(), buildURL(config.url, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request[loadEvent] = function handleLoad() {
      if (!request || (request.readyState !== 4 && !xDomain)) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        // IE sends 1223 instead of 204 (https://github.com/mzabriskie/axios/issues/201)
        status: request.status === 1223 ? 204 : request.status,
        statusText: request.status === 1223 ? 'No Content' : request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      reject(createError('timeout of ' + config.timeout + 'ms exceeded', config, 'ECONNABORTED'));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__("./node_modules/axios/lib/helpers/cookies.js");

      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(config.url)) && config.xsrfCookieName ?
          cookies.read(config.xsrfCookieName) :
          undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (config.withCredentials) {
      request.withCredentials = true;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        if (request.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (requestData === undefined) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/axios.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var bind = __webpack_require__("./node_modules/axios/lib/helpers/bind.js");
var Axios = __webpack_require__("./node_modules/axios/lib/core/Axios.js");
var defaults = __webpack_require__("./node_modules/axios/lib/defaults.js");

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(utils.merge(defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__("./node_modules/axios/lib/cancel/Cancel.js");
axios.CancelToken = __webpack_require__("./node_modules/axios/lib/cancel/CancelToken.js");
axios.isCancel = __webpack_require__("./node_modules/axios/lib/cancel/isCancel.js");

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__("./node_modules/axios/lib/helpers/spread.js");

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/Cancel.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CancelToken.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__("./node_modules/axios/lib/cancel/Cancel.js");

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/isCancel.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/Axios.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var defaults = __webpack_require__("./node_modules/axios/lib/defaults.js");
var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var InterceptorManager = __webpack_require__("./node_modules/axios/lib/core/InterceptorManager.js");
var dispatchRequest = __webpack_require__("./node_modules/axios/lib/core/dispatchRequest.js");
var isAbsoluteURL = __webpack_require__("./node_modules/axios/lib/helpers/isAbsoluteURL.js");
var combineURLs = __webpack_require__("./node_modules/axios/lib/helpers/combineURLs.js");

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = utils.merge({
      url: arguments[0]
    }, arguments[1]);
  }

  config = utils.merge(defaults, this.defaults, { method: 'get' }, config);

  // Support baseURL config
  if (config.baseURL && !isAbsoluteURL(config.url)) {
    config.url = combineURLs(config.baseURL, config.url);
  }

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),

/***/ "./node_modules/axios/lib/core/InterceptorManager.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),

/***/ "./node_modules/axios/lib/core/createError.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__("./node_modules/axios/lib/core/enhanceError.js");

/**
 * Create an Error with the specified message, config, error code, and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 @ @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, response);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/dispatchRequest.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var transformData = __webpack_require__("./node_modules/axios/lib/core/transformData.js");
var isCancel = __webpack_require__("./node_modules/axios/lib/cancel/isCancel.js");
var defaults = __webpack_require__("./node_modules/axios/lib/defaults.js");

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers || {}
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/core/enhanceError.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 @ @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }
  error.response = response;
  return error;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/settle.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__("./node_modules/axios/lib/core/createError.js");

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  // Note: status is not exposed by XDomainRequest
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response
    ));
  }
};


/***/ }),

/***/ "./node_modules/axios/lib/core/transformData.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),

/***/ "./node_modules/axios/lib/defaults.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var normalizeHeaderName = __webpack_require__("./node_modules/axios/lib/helpers/normalizeHeaderName.js");

var PROTECTION_PREFIX = /^\)\]\}',?\n/;
var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__("./node_modules/axios/lib/adapters/xhr.js");
  } else if (typeof process !== 'undefined') {
    // For node use HTTP adapter
    adapter = __webpack_require__("./node_modules/axios/lib/adapters/xhr.js");
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      data = data.replace(PROTECTION_PREFIX, '');
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMehtodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__("./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/axios/lib/helpers/bind.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/btoa.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// btoa polyfill for IE<10 courtesy https://github.com/davidchambers/Base64.js

var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

function E() {
  this.message = 'String contains an invalid character';
}
E.prototype = new Error;
E.prototype.code = 5;
E.prototype.name = 'InvalidCharacterError';

function btoa(input) {
  var str = String(input);
  var output = '';
  for (
    // initialize result and counter
    var block, charCode, idx = 0, map = chars;
    // if the next str index does not exist:
    //   change the mapping table to "="
    //   check if d has no fractional digits
    str.charAt(idx | 0) || (map = '=', idx % 1);
    // "8 - idx % 1 * 8" generates the sequence 2, 4, 6, 8
    output += map.charAt(63 & block >> 8 - idx % 1 * 8)
  ) {
    charCode = str.charCodeAt(idx += 3 / 4);
    if (charCode > 0xFF) {
      throw new E();
    }
    block = block << 8 | charCode;
  }
  return output;
}

module.exports = btoa;


/***/ }),

/***/ "./node_modules/axios/lib/helpers/buildURL.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

function encode(val) {
  return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      }

      if (!utils.isArray(val)) {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/combineURLs.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '');
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/cookies.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
  (function standardBrowserEnv() {
    return {
      write: function write(name, value, expires, path, domain, secure) {
        var cookie = [];
        cookie.push(name + '=' + encodeURIComponent(value));

        if (utils.isNumber(expires)) {
          cookie.push('expires=' + new Date(expires).toGMTString());
        }

        if (utils.isString(path)) {
          cookie.push('path=' + path);
        }

        if (utils.isString(domain)) {
          cookie.push('domain=' + domain);
        }

        if (secure === true) {
          cookie.push('secure');
        }

        document.cookie = cookie.join('; ');
      },

      read: function read(name) {
        var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
        return (match ? decodeURIComponent(match[3]) : null);
      },

      remove: function remove(name) {
        this.write(name, '', Date.now() - 86400000);
      }
    };
  })() :

  // Non standard browser env (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return {
      write: function write() {},
      read: function read() { return null; },
      remove: function remove() {}
    };
  })()
);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAbsoluteURL.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isURLSameOrigin.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
  (function standardBrowserEnv() {
    var msie = /(msie|trident)/i.test(navigator.userAgent);
    var urlParsingNode = document.createElement('a');
    var originURL;

    /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
    function resolveURL(url) {
      var href = url;

      if (msie) {
        // IE needs attribute set twice to normalize properties
        urlParsingNode.setAttribute('href', href);
        href = urlParsingNode.href;
      }

      urlParsingNode.setAttribute('href', href);

      // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
      return {
        href: urlParsingNode.href,
        protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
        host: urlParsingNode.host,
        search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
        hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
        hostname: urlParsingNode.hostname,
        port: urlParsingNode.port,
        pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
                  urlParsingNode.pathname :
                  '/' + urlParsingNode.pathname
      };
    }

    originURL = resolveURL(window.location.href);

    /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
    return function isURLSameOrigin(requestURL) {
      var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
      return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
    };
  })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return function isURLSameOrigin() {
      return true;
    };
  })()
);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/normalizeHeaderName.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseHeaders.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/spread.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),

/***/ "./node_modules/axios/lib/utils.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__("./node_modules/axios/lib/helpers/bind.js");

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  typeof document.createElement -> undefined
 */
function isStandardBrowserEnv() {
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined' &&
    typeof document.createElement === 'function'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object' && !isArray(obj)) {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = merge(result[key], val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  extend: extend,
  trim: trim
};


/***/ }),

/***/ "./node_modules/fastclick/lib/fastclick.js":
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_RESULT__;;(function () {
	'use strict';

	/**
	 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
	 *
	 * @codingstandard ftlabs-jsv2
	 * @copyright The Financial Times Limited [All Rights Reserved]
	 * @license MIT License (see LICENSE.txt)
	 */

	/*jslint browser:true, node:true*/
	/*global define, Event, Node*/


	/**
	 * Instantiate fast-clicking listeners on the specified layer.
	 *
	 * @constructor
	 * @param {Element} layer The layer to listen on
	 * @param {Object} [options={}] The options to override the defaults
	 */
	function FastClick(layer, options) {
		var oldOnClick;

		options = options || {};

		/**
		 * Whether a click is currently being tracked.
		 *
		 * @type boolean
		 */
		this.trackingClick = false;


		/**
		 * Timestamp for when click tracking started.
		 *
		 * @type number
		 */
		this.trackingClickStart = 0;


		/**
		 * The element being tracked for a click.
		 *
		 * @type EventTarget
		 */
		this.targetElement = null;


		/**
		 * X-coordinate of touch start event.
		 *
		 * @type number
		 */
		this.touchStartX = 0;


		/**
		 * Y-coordinate of touch start event.
		 *
		 * @type number
		 */
		this.touchStartY = 0;


		/**
		 * ID of the last touch, retrieved from Touch.identifier.
		 *
		 * @type number
		 */
		this.lastTouchIdentifier = 0;


		/**
		 * Touchmove boundary, beyond which a click will be cancelled.
		 *
		 * @type number
		 */
		this.touchBoundary = options.touchBoundary || 10;


		/**
		 * The FastClick layer.
		 *
		 * @type Element
		 */
		this.layer = layer;

		/**
		 * The minimum time between tap(touchstart and touchend) events
		 *
		 * @type number
		 */
		this.tapDelay = options.tapDelay || 200;

		/**
		 * The maximum time for a tap
		 *
		 * @type number
		 */
		this.tapTimeout = options.tapTimeout || 700;

		if (FastClick.notNeeded(layer)) {
			return;
		}

		// Some old versions of Android don't have Function.prototype.bind
		function bind(method, context) {
			return function() { return method.apply(context, arguments); };
		}


		var methods = ['onMouse', 'onClick', 'onTouchStart', 'onTouchMove', 'onTouchEnd', 'onTouchCancel'];
		var context = this;
		for (var i = 0, l = methods.length; i < l; i++) {
			context[methods[i]] = bind(context[methods[i]], context);
		}

		// Set up event handlers as required
		if (deviceIsAndroid) {
			layer.addEventListener('mouseover', this.onMouse, true);
			layer.addEventListener('mousedown', this.onMouse, true);
			layer.addEventListener('mouseup', this.onMouse, true);
		}

		layer.addEventListener('click', this.onClick, true);
		layer.addEventListener('touchstart', this.onTouchStart, false);
		layer.addEventListener('touchmove', this.onTouchMove, false);
		layer.addEventListener('touchend', this.onTouchEnd, false);
		layer.addEventListener('touchcancel', this.onTouchCancel, false);

		// Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
		// which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
		// layer when they are cancelled.
		if (!Event.prototype.stopImmediatePropagation) {
			layer.removeEventListener = function(type, callback, capture) {
				var rmv = Node.prototype.removeEventListener;
				if (type === 'click') {
					rmv.call(layer, type, callback.hijacked || callback, capture);
				} else {
					rmv.call(layer, type, callback, capture);
				}
			};

			layer.addEventListener = function(type, callback, capture) {
				var adv = Node.prototype.addEventListener;
				if (type === 'click') {
					adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
						if (!event.propagationStopped) {
							callback(event);
						}
					}), capture);
				} else {
					adv.call(layer, type, callback, capture);
				}
			};
		}

		// If a handler is already declared in the element's onclick attribute, it will be fired before
		// FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
		// adding it as listener.
		if (typeof layer.onclick === 'function') {

			// Android browser on at least 3.2 requires a new reference to the function in layer.onclick
			// - the old one won't work if passed to addEventListener directly.
			oldOnClick = layer.onclick;
			layer.addEventListener('click', function(event) {
				oldOnClick(event);
			}, false);
			layer.onclick = null;
		}
	}

	/**
	* Windows Phone 8.1 fakes user agent string to look like Android and iPhone.
	*
	* @type boolean
	*/
	var deviceIsWindowsPhone = navigator.userAgent.indexOf("Windows Phone") >= 0;

	/**
	 * Android requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0 && !deviceIsWindowsPhone;


	/**
	 * iOS requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent) && !deviceIsWindowsPhone;


	/**
	 * iOS 4 requires an exception for select elements.
	 *
	 * @type boolean
	 */
	var deviceIsIOS4 = deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


	/**
	 * iOS 6.0-7.* requires the target element to be manually derived
	 *
	 * @type boolean
	 */
	var deviceIsIOSWithBadTarget = deviceIsIOS && (/OS [6-7]_\d/).test(navigator.userAgent);

	/**
	 * BlackBerry requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsBlackBerry10 = navigator.userAgent.indexOf('BB10') > 0;

	/**
	 * Determine whether a given element requires a native click.
	 *
	 * @param {EventTarget|Element} target Target DOM element
	 * @returns {boolean} Returns true if the element needs a native click
	 */
	FastClick.prototype.needsClick = function(target) {
		switch (target.nodeName.toLowerCase()) {

		// Don't send a synthetic click to disabled inputs (issue #62)
		case 'button':
		case 'select':
		case 'textarea':
			if (target.disabled) {
				return true;
			}

			break;
		case 'input':

			// File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
			if ((deviceIsIOS && target.type === 'file') || target.disabled) {
				return true;
			}

			break;
		case 'label':
		case 'iframe': // iOS8 homescreen apps can prevent events bubbling into frames
		case 'video':
			return true;
		}

		return (/\bneedsclick\b/).test(target.className);
	};


	/**
	 * Determine whether a given element requires a call to focus to simulate click into element.
	 *
	 * @param {EventTarget|Element} target Target DOM element
	 * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
	 */
	FastClick.prototype.needsFocus = function(target) {
		switch (target.nodeName.toLowerCase()) {
		case 'textarea':
			return true;
		case 'select':
			return !deviceIsAndroid;
		case 'input':
			switch (target.type) {
			case 'button':
			case 'checkbox':
			case 'file':
			case 'image':
			case 'radio':
			case 'submit':
				return false;
			}

			// No point in attempting to focus disabled inputs
			return !target.disabled && !target.readOnly;
		default:
			return (/\bneedsfocus\b/).test(target.className);
		}
	};


	/**
	 * Send a click event to the specified element.
	 *
	 * @param {EventTarget|Element} targetElement
	 * @param {Event} event
	 */
	FastClick.prototype.sendClick = function(targetElement, event) {
		var clickEvent, touch;

		// On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
		if (document.activeElement && document.activeElement !== targetElement) {
			document.activeElement.blur();
		}

		touch = event.changedTouches[0];

		// Synthesise a click event, with an extra attribute so it can be tracked
		clickEvent = document.createEvent('MouseEvents');
		clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
		clickEvent.forwardedTouchEvent = true;
		targetElement.dispatchEvent(clickEvent);
	};

	FastClick.prototype.determineEventType = function(targetElement) {

		//Issue #159: Android Chrome Select Box does not open with a synthetic click event
		if (deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
			return 'mousedown';
		}

		return 'click';
	};


	/**
	 * @param {EventTarget|Element} targetElement
	 */
	FastClick.prototype.focus = function(targetElement) {
		var length;

		// Issue #160: on iOS 7, some input elements (e.g. date datetime month) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
		if (deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time' && targetElement.type !== 'month') {
			length = targetElement.value.length;
			targetElement.setSelectionRange(length, length);
		} else {
			targetElement.focus();
		}
	};


	/**
	 * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
	 *
	 * @param {EventTarget|Element} targetElement
	 */
	FastClick.prototype.updateScrollParent = function(targetElement) {
		var scrollParent, parentElement;

		scrollParent = targetElement.fastClickScrollParent;

		// Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
		// target element was moved to another parent.
		if (!scrollParent || !scrollParent.contains(targetElement)) {
			parentElement = targetElement;
			do {
				if (parentElement.scrollHeight > parentElement.offsetHeight) {
					scrollParent = parentElement;
					targetElement.fastClickScrollParent = parentElement;
					break;
				}

				parentElement = parentElement.parentElement;
			} while (parentElement);
		}

		// Always update the scroll top tracker if possible.
		if (scrollParent) {
			scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
		}
	};


	/**
	 * @param {EventTarget} targetElement
	 * @returns {Element|EventTarget}
	 */
	FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {

		// On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
		if (eventTarget.nodeType === Node.TEXT_NODE) {
			return eventTarget.parentNode;
		}

		return eventTarget;
	};


	/**
	 * On touch start, record the position and scroll offset.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchStart = function(event) {
		var targetElement, touch, selection;

		// Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
		if (event.targetTouches.length > 1) {
			return true;
		}

		targetElement = this.getTargetElementFromEventTarget(event.target);
		touch = event.targetTouches[0];

		if (deviceIsIOS) {

			// Only trusted events will deselect text on iOS (issue #49)
			selection = window.getSelection();
			if (selection.rangeCount && !selection.isCollapsed) {
				return true;
			}

			if (!deviceIsIOS4) {

				// Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
				// when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
				// with the same identifier as the touch event that previously triggered the click that triggered the alert.
				// Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
				// immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
				// Issue 120: touch.identifier is 0 when Chrome dev tools 'Emulate touch events' is set with an iOS device UA string,
				// which causes all touch events to be ignored. As this block only applies to iOS, and iOS identifiers are always long,
				// random integers, it's safe to to continue if the identifier is 0 here.
				if (touch.identifier && touch.identifier === this.lastTouchIdentifier) {
					event.preventDefault();
					return false;
				}

				this.lastTouchIdentifier = touch.identifier;

				// If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
				// 1) the user does a fling scroll on the scrollable layer
				// 2) the user stops the fling scroll with another tap
				// then the event.target of the last 'touchend' event will be the element that was under the user's finger
				// when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
				// is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
				this.updateScrollParent(targetElement);
			}
		}

		this.trackingClick = true;
		this.trackingClickStart = event.timeStamp;
		this.targetElement = targetElement;

		this.touchStartX = touch.pageX;
		this.touchStartY = touch.pageY;

		// Prevent phantom clicks on fast double-tap (issue #36)
		if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
			event.preventDefault();
		}

		return true;
	};


	/**
	 * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.touchHasMoved = function(event) {
		var touch = event.changedTouches[0], boundary = this.touchBoundary;

		if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
			return true;
		}

		return false;
	};


	/**
	 * Update the last position.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchMove = function(event) {
		if (!this.trackingClick) {
			return true;
		}

		// If the touch has moved, cancel the click tracking
		if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
			this.trackingClick = false;
			this.targetElement = null;
		}

		return true;
	};


	/**
	 * Attempt to find the labelled control for the given label element.
	 *
	 * @param {EventTarget|HTMLLabelElement} labelElement
	 * @returns {Element|null}
	 */
	FastClick.prototype.findControl = function(labelElement) {

		// Fast path for newer browsers supporting the HTML5 control attribute
		if (labelElement.control !== undefined) {
			return labelElement.control;
		}

		// All browsers under test that support touch events also support the HTML5 htmlFor attribute
		if (labelElement.htmlFor) {
			return document.getElementById(labelElement.htmlFor);
		}

		// If no for attribute exists, attempt to retrieve the first labellable descendant element
		// the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
		return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
	};


	/**
	 * On touch end, determine whether to send a click event at once.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchEnd = function(event) {
		var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

		if (!this.trackingClick) {
			return true;
		}

		// Prevent phantom clicks on fast double-tap (issue #36)
		if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
			this.cancelNextClick = true;
			return true;
		}

		if ((event.timeStamp - this.trackingClickStart) > this.tapTimeout) {
			return true;
		}

		// Reset to prevent wrong click cancel on input (issue #156).
		this.cancelNextClick = false;

		this.lastClickTime = event.timeStamp;

		trackingClickStart = this.trackingClickStart;
		this.trackingClick = false;
		this.trackingClickStart = 0;

		// On some iOS devices, the targetElement supplied with the event is invalid if the layer
		// is performing a transition or scroll, and has to be re-detected manually. Note that
		// for this to function correctly, it must be called *after* the event target is checked!
		// See issue #57; also filed as rdar://13048589 .
		if (deviceIsIOSWithBadTarget) {
			touch = event.changedTouches[0];

			// In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
			targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
			targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
		}

		targetTagName = targetElement.tagName.toLowerCase();
		if (targetTagName === 'label') {
			forElement = this.findControl(targetElement);
			if (forElement) {
				this.focus(targetElement);
				if (deviceIsAndroid) {
					return false;
				}

				targetElement = forElement;
			}
		} else if (this.needsFocus(targetElement)) {

			// Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
			// Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
			if ((event.timeStamp - trackingClickStart) > 100 || (deviceIsIOS && window.top !== window && targetTagName === 'input')) {
				this.targetElement = null;
				return false;
			}

			this.focus(targetElement);
			this.sendClick(targetElement, event);

			// Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
			// Also this breaks opening selects when VoiceOver is active on iOS6, iOS7 (and possibly others)
			if (!deviceIsIOS || targetTagName !== 'select') {
				this.targetElement = null;
				event.preventDefault();
			}

			return false;
		}

		if (deviceIsIOS && !deviceIsIOS4) {

			// Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
			// and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
			scrollParent = targetElement.fastClickScrollParent;
			if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
				return true;
			}
		}

		// Prevent the actual click from going though - unless the target node is marked as requiring
		// real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
		if (!this.needsClick(targetElement)) {
			event.preventDefault();
			this.sendClick(targetElement, event);
		}

		return false;
	};


	/**
	 * On touch cancel, stop tracking the click.
	 *
	 * @returns {void}
	 */
	FastClick.prototype.onTouchCancel = function() {
		this.trackingClick = false;
		this.targetElement = null;
	};


	/**
	 * Determine mouse events which should be permitted.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onMouse = function(event) {

		// If a target element was never set (because a touch event was never fired) allow the event
		if (!this.targetElement) {
			return true;
		}

		if (event.forwardedTouchEvent) {
			return true;
		}

		// Programmatically generated events targeting a specific element should be permitted
		if (!event.cancelable) {
			return true;
		}

		// Derive and check the target element to see whether the mouse event needs to be permitted;
		// unless explicitly enabled, prevent non-touch click events from triggering actions,
		// to prevent ghost/doubleclicks.
		if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

			// Prevent any user-added listeners declared on FastClick element from being fired.
			if (event.stopImmediatePropagation) {
				event.stopImmediatePropagation();
			} else {

				// Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
				event.propagationStopped = true;
			}

			// Cancel the event
			event.stopPropagation();
			event.preventDefault();

			return false;
		}

		// If the mouse event is permitted, return true for the action to go through.
		return true;
	};


	/**
	 * On actual clicks, determine whether this is a touch-generated click, a click action occurring
	 * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
	 * an actual click which should be permitted.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onClick = function(event) {
		var permitted;

		// It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
		if (this.trackingClick) {
			this.targetElement = null;
			this.trackingClick = false;
			return true;
		}

		// Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
		if (event.target.type === 'submit' && event.detail === 0) {
			return true;
		}

		permitted = this.onMouse(event);

		// Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
		if (!permitted) {
			this.targetElement = null;
		}

		// If clicks are permitted, return true for the action to go through.
		return permitted;
	};


	/**
	 * Remove all FastClick's event listeners.
	 *
	 * @returns {void}
	 */
	FastClick.prototype.destroy = function() {
		var layer = this.layer;

		if (deviceIsAndroid) {
			layer.removeEventListener('mouseover', this.onMouse, true);
			layer.removeEventListener('mousedown', this.onMouse, true);
			layer.removeEventListener('mouseup', this.onMouse, true);
		}

		layer.removeEventListener('click', this.onClick, true);
		layer.removeEventListener('touchstart', this.onTouchStart, false);
		layer.removeEventListener('touchmove', this.onTouchMove, false);
		layer.removeEventListener('touchend', this.onTouchEnd, false);
		layer.removeEventListener('touchcancel', this.onTouchCancel, false);
	};


	/**
	 * Check whether FastClick is needed.
	 *
	 * @param {Element} layer The layer to listen on
	 */
	FastClick.notNeeded = function(layer) {
		var metaViewport;
		var chromeVersion;
		var blackberryVersion;
		var firefoxVersion;

		// Devices that don't support touch don't need FastClick
		if (typeof window.ontouchstart === 'undefined') {
			return true;
		}

		// Chrome version - zero for other browsers
		chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

		if (chromeVersion) {

			if (deviceIsAndroid) {
				metaViewport = document.querySelector('meta[name=viewport]');

				if (metaViewport) {
					// Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
					if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
						return true;
					}
					// Chrome 32 and above with width=device-width or less don't need FastClick
					if (chromeVersion > 31 && document.documentElement.scrollWidth <= window.outerWidth) {
						return true;
					}
				}

			// Chrome desktop doesn't need FastClick (issue #15)
			} else {
				return true;
			}
		}

		if (deviceIsBlackBerry10) {
			blackberryVersion = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);

			// BlackBerry 10.3+ does not require Fastclick library.
			// https://github.com/ftlabs/fastclick/issues/251
			if (blackberryVersion[1] >= 10 && blackberryVersion[2] >= 3) {
				metaViewport = document.querySelector('meta[name=viewport]');

				if (metaViewport) {
					// user-scalable=no eliminates click delay.
					if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
						return true;
					}
					// width=device-width (or less than device-width) eliminates click delay.
					if (document.documentElement.scrollWidth <= window.outerWidth) {
						return true;
					}
				}
			}
		}

		// IE10 with -ms-touch-action: none or manipulation, which disables double-tap-to-zoom (issue #97)
		if (layer.style.msTouchAction === 'none' || layer.style.touchAction === 'manipulation') {
			return true;
		}

		// Firefox version - zero for other browsers
		firefoxVersion = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

		if (firefoxVersion >= 27) {
			// Firefox 27+ does not have tap delay if the content is not zoomable - https://bugzilla.mozilla.org/show_bug.cgi?id=922896

			metaViewport = document.querySelector('meta[name=viewport]');
			if (metaViewport && (metaViewport.content.indexOf('user-scalable=no') !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) {
				return true;
			}
		}

		// IE11: prefixed -ms-touch-action is no longer supported and it's recomended to use non-prefixed version
		// http://msdn.microsoft.com/en-us/library/windows/apps/Hh767313.aspx
		if (layer.style.touchAction === 'none' || layer.style.touchAction === 'manipulation') {
			return true;
		}

		return false;
	};


	/**
	 * Factory method for creating a FastClick object
	 *
	 * @param {Element} layer The layer to listen on
	 * @param {Object} [options={}] The options to override the defaults
	 */
	FastClick.attach = function(layer, options) {
		return new FastClick(layer, options);
	};


	if (true) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_RESULT__ = function() {
			return FastClick;
		}.call(exports, __webpack_require__, exports, module),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (typeof module !== 'undefined' && module.exports) {
		module.exports = FastClick.attach;
		module.exports.FastClick = FastClick;
	} else {
		window.FastClick = FastClick;
	}
}());


/***/ }),

/***/ "./node_modules/process/browser.js":
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/zepto/dist/zepto.js":
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_RESULT__;/* Zepto v1.2.0 - zepto event ajax form ie - zeptojs.com/license */
(function(global, factory) {
  if (true)
    !(__WEBPACK_AMD_DEFINE_RESULT__ = function() { return factory(global) }.call(exports, __webpack_require__, exports, module),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__))
  else
    factory(global)
}(this, function(window) {
  var Zepto = (function() {
  var undefined, key, $, classList, emptyArray = [], concat = emptyArray.concat, filter = emptyArray.filter, slice = emptyArray.slice,
    document = window.document,
    elementDisplay = {}, classCache = {},
    cssNumber = { 'column-count': 1, 'columns': 1, 'font-weight': 1, 'line-height': 1,'opacity': 1, 'z-index': 1, 'zoom': 1 },
    fragmentRE = /^\s*<(\w+|!)[^>]*>/,
    singleTagRE = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
    tagExpanderRE = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,
    rootNodeRE = /^(?:body|html)$/i,
    capitalRE = /([A-Z])/g,

    // special attributes that should be get/set via method calls
    methodAttributes = ['val', 'css', 'html', 'text', 'data', 'width', 'height', 'offset'],

    adjacencyOperators = [ 'after', 'prepend', 'before', 'append' ],
    table = document.createElement('table'),
    tableRow = document.createElement('tr'),
    containers = {
      'tr': document.createElement('tbody'),
      'tbody': table, 'thead': table, 'tfoot': table,
      'td': tableRow, 'th': tableRow,
      '*': document.createElement('div')
    },
    readyRE = /complete|loaded|interactive/,
    simpleSelectorRE = /^[\w-]*$/,
    class2type = {},
    toString = class2type.toString,
    zepto = {},
    camelize, uniq,
    tempParent = document.createElement('div'),
    propMap = {
      'tabindex': 'tabIndex',
      'readonly': 'readOnly',
      'for': 'htmlFor',
      'class': 'className',
      'maxlength': 'maxLength',
      'cellspacing': 'cellSpacing',
      'cellpadding': 'cellPadding',
      'rowspan': 'rowSpan',
      'colspan': 'colSpan',
      'usemap': 'useMap',
      'frameborder': 'frameBorder',
      'contenteditable': 'contentEditable'
    },
    isArray = Array.isArray ||
      function(object){ return object instanceof Array }

  zepto.matches = function(element, selector) {
    if (!selector || !element || element.nodeType !== 1) return false
    var matchesSelector = element.matches || element.webkitMatchesSelector ||
                          element.mozMatchesSelector || element.oMatchesSelector ||
                          element.matchesSelector
    if (matchesSelector) return matchesSelector.call(element, selector)
    // fall back to performing a selector:
    var match, parent = element.parentNode, temp = !parent
    if (temp) (parent = tempParent).appendChild(element)
    match = ~zepto.qsa(parent, selector).indexOf(element)
    temp && tempParent.removeChild(element)
    return match
  }

  function type(obj) {
    return obj == null ? String(obj) :
      class2type[toString.call(obj)] || "object"
  }

  function isFunction(value) { return type(value) == "function" }
  function isWindow(obj)     { return obj != null && obj == obj.window }
  function isDocument(obj)   { return obj != null && obj.nodeType == obj.DOCUMENT_NODE }
  function isObject(obj)     { return type(obj) == "object" }
  function isPlainObject(obj) {
    return isObject(obj) && !isWindow(obj) && Object.getPrototypeOf(obj) == Object.prototype
  }

  function likeArray(obj) {
    var length = !!obj && 'length' in obj && obj.length,
      type = $.type(obj)

    return 'function' != type && !isWindow(obj) && (
      'array' == type || length === 0 ||
        (typeof length == 'number' && length > 0 && (length - 1) in obj)
    )
  }

  function compact(array) { return filter.call(array, function(item){ return item != null }) }
  function flatten(array) { return array.length > 0 ? $.fn.concat.apply([], array) : array }
  camelize = function(str){ return str.replace(/-+(.)?/g, function(match, chr){ return chr ? chr.toUpperCase() : '' }) }
  function dasherize(str) {
    return str.replace(/::/g, '/')
           .replace(/([A-Z]+)([A-Z][a-z])/g, '$1_$2')
           .replace(/([a-z\d])([A-Z])/g, '$1_$2')
           .replace(/_/g, '-')
           .toLowerCase()
  }
  uniq = function(array){ return filter.call(array, function(item, idx){ return array.indexOf(item) == idx }) }

  function classRE(name) {
    return name in classCache ?
      classCache[name] : (classCache[name] = new RegExp('(^|\\s)' + name + '(\\s|$)'))
  }

  function maybeAddPx(name, value) {
    return (typeof value == "number" && !cssNumber[dasherize(name)]) ? value + "px" : value
  }

  function defaultDisplay(nodeName) {
    var element, display
    if (!elementDisplay[nodeName]) {
      element = document.createElement(nodeName)
      document.body.appendChild(element)
      display = getComputedStyle(element, '').getPropertyValue("display")
      element.parentNode.removeChild(element)
      display == "none" && (display = "block")
      elementDisplay[nodeName] = display
    }
    return elementDisplay[nodeName]
  }

  function children(element) {
    return 'children' in element ?
      slice.call(element.children) :
      $.map(element.childNodes, function(node){ if (node.nodeType == 1) return node })
  }

  function Z(dom, selector) {
    var i, len = dom ? dom.length : 0
    for (i = 0; i < len; i++) this[i] = dom[i]
    this.length = len
    this.selector = selector || ''
  }

  // `$.zepto.fragment` takes a html string and an optional tag name
  // to generate DOM nodes from the given html string.
  // The generated DOM nodes are returned as an array.
  // This function can be overridden in plugins for example to make
  // it compatible with browsers that don't support the DOM fully.
  zepto.fragment = function(html, name, properties) {
    var dom, nodes, container

    // A special case optimization for a single tag
    if (singleTagRE.test(html)) dom = $(document.createElement(RegExp.$1))

    if (!dom) {
      if (html.replace) html = html.replace(tagExpanderRE, "<$1></$2>")
      if (name === undefined) name = fragmentRE.test(html) && RegExp.$1
      if (!(name in containers)) name = '*'

      container = containers[name]
      container.innerHTML = '' + html
      dom = $.each(slice.call(container.childNodes), function(){
        container.removeChild(this)
      })
    }

    if (isPlainObject(properties)) {
      nodes = $(dom)
      $.each(properties, function(key, value) {
        if (methodAttributes.indexOf(key) > -1) nodes[key](value)
        else nodes.attr(key, value)
      })
    }

    return dom
  }

  // `$.zepto.Z` swaps out the prototype of the given `dom` array
  // of nodes with `$.fn` and thus supplying all the Zepto functions
  // to the array. This method can be overridden in plugins.
  zepto.Z = function(dom, selector) {
    return new Z(dom, selector)
  }

  // `$.zepto.isZ` should return `true` if the given object is a Zepto
  // collection. This method can be overridden in plugins.
  zepto.isZ = function(object) {
    return object instanceof zepto.Z
  }

  // `$.zepto.init` is Zepto's counterpart to jQuery's `$.fn.init` and
  // takes a CSS selector and an optional context (and handles various
  // special cases).
  // This method can be overridden in plugins.
  zepto.init = function(selector, context) {
    var dom
    // If nothing given, return an empty Zepto collection
    if (!selector) return zepto.Z()
    // Optimize for string selectors
    else if (typeof selector == 'string') {
      selector = selector.trim()
      // If it's a html fragment, create nodes from it
      // Note: In both Chrome 21 and Firefox 15, DOM error 12
      // is thrown if the fragment doesn't begin with <
      if (selector[0] == '<' && fragmentRE.test(selector))
        dom = zepto.fragment(selector, RegExp.$1, context), selector = null
      // If there's a context, create a collection on that context first, and select
      // nodes from there
      else if (context !== undefined) return $(context).find(selector)
      // If it's a CSS selector, use it to select nodes.
      else dom = zepto.qsa(document, selector)
    }
    // If a function is given, call it when the DOM is ready
    else if (isFunction(selector)) return $(document).ready(selector)
    // If a Zepto collection is given, just return it
    else if (zepto.isZ(selector)) return selector
    else {
      // normalize array if an array of nodes is given
      if (isArray(selector)) dom = compact(selector)
      // Wrap DOM nodes.
      else if (isObject(selector))
        dom = [selector], selector = null
      // If it's a html fragment, create nodes from it
      else if (fragmentRE.test(selector))
        dom = zepto.fragment(selector.trim(), RegExp.$1, context), selector = null
      // If there's a context, create a collection on that context first, and select
      // nodes from there
      else if (context !== undefined) return $(context).find(selector)
      // And last but no least, if it's a CSS selector, use it to select nodes.
      else dom = zepto.qsa(document, selector)
    }
    // create a new Zepto collection from the nodes found
    return zepto.Z(dom, selector)
  }

  // `$` will be the base `Zepto` object. When calling this
  // function just call `$.zepto.init, which makes the implementation
  // details of selecting nodes and creating Zepto collections
  // patchable in plugins.
  $ = function(selector, context){
    return zepto.init(selector, context)
  }

  function extend(target, source, deep) {
    for (key in source)
      if (deep && (isPlainObject(source[key]) || isArray(source[key]))) {
        if (isPlainObject(source[key]) && !isPlainObject(target[key]))
          target[key] = {}
        if (isArray(source[key]) && !isArray(target[key]))
          target[key] = []
        extend(target[key], source[key], deep)
      }
      else if (source[key] !== undefined) target[key] = source[key]
  }

  // Copy all but undefined properties from one or more
  // objects to the `target` object.
  $.extend = function(target){
    var deep, args = slice.call(arguments, 1)
    if (typeof target == 'boolean') {
      deep = target
      target = args.shift()
    }
    args.forEach(function(arg){ extend(target, arg, deep) })
    return target
  }

  // `$.zepto.qsa` is Zepto's CSS selector implementation which
  // uses `document.querySelectorAll` and optimizes for some special cases, like `#id`.
  // This method can be overridden in plugins.
  zepto.qsa = function(element, selector){
    var found,
        maybeID = selector[0] == '#',
        maybeClass = !maybeID && selector[0] == '.',
        nameOnly = maybeID || maybeClass ? selector.slice(1) : selector, // Ensure that a 1 char tag name still gets checked
        isSimple = simpleSelectorRE.test(nameOnly)
    return (element.getElementById && isSimple && maybeID) ? // Safari DocumentFragment doesn't have getElementById
      ( (found = element.getElementById(nameOnly)) ? [found] : [] ) :
      (element.nodeType !== 1 && element.nodeType !== 9 && element.nodeType !== 11) ? [] :
      slice.call(
        isSimple && !maybeID && element.getElementsByClassName ? // DocumentFragment doesn't have getElementsByClassName/TagName
          maybeClass ? element.getElementsByClassName(nameOnly) : // If it's simple, it could be a class
          element.getElementsByTagName(selector) : // Or a tag
          element.querySelectorAll(selector) // Or it's not simple, and we need to query all
      )
  }

  function filtered(nodes, selector) {
    return selector == null ? $(nodes) : $(nodes).filter(selector)
  }

  $.contains = document.documentElement.contains ?
    function(parent, node) {
      return parent !== node && parent.contains(node)
    } :
    function(parent, node) {
      while (node && (node = node.parentNode))
        if (node === parent) return true
      return false
    }

  function funcArg(context, arg, idx, payload) {
    return isFunction(arg) ? arg.call(context, idx, payload) : arg
  }

  function setAttribute(node, name, value) {
    value == null ? node.removeAttribute(name) : node.setAttribute(name, value)
  }

  // access className property while respecting SVGAnimatedString
  function className(node, value){
    var klass = node.className || '',
        svg   = klass && klass.baseVal !== undefined

    if (value === undefined) return svg ? klass.baseVal : klass
    svg ? (klass.baseVal = value) : (node.className = value)
  }

  // "true"  => true
  // "false" => false
  // "null"  => null
  // "42"    => 42
  // "42.5"  => 42.5
  // "08"    => "08"
  // JSON    => parse if valid
  // String  => self
  function deserializeValue(value) {
    try {
      return value ?
        value == "true" ||
        ( value == "false" ? false :
          value == "null" ? null :
          +value + "" == value ? +value :
          /^[\[\{]/.test(value) ? $.parseJSON(value) :
          value )
        : value
    } catch(e) {
      return value
    }
  }

  $.type = type
  $.isFunction = isFunction
  $.isWindow = isWindow
  $.isArray = isArray
  $.isPlainObject = isPlainObject

  $.isEmptyObject = function(obj) {
    var name
    for (name in obj) return false
    return true
  }

  $.isNumeric = function(val) {
    var num = Number(val), type = typeof val
    return val != null && type != 'boolean' &&
      (type != 'string' || val.length) &&
      !isNaN(num) && isFinite(num) || false
  }

  $.inArray = function(elem, array, i){
    return emptyArray.indexOf.call(array, elem, i)
  }

  $.camelCase = camelize
  $.trim = function(str) {
    return str == null ? "" : String.prototype.trim.call(str)
  }

  // plugin compatibility
  $.uuid = 0
  $.support = { }
  $.expr = { }
  $.noop = function() {}

  $.map = function(elements, callback){
    var value, values = [], i, key
    if (likeArray(elements))
      for (i = 0; i < elements.length; i++) {
        value = callback(elements[i], i)
        if (value != null) values.push(value)
      }
    else
      for (key in elements) {
        value = callback(elements[key], key)
        if (value != null) values.push(value)
      }
    return flatten(values)
  }

  $.each = function(elements, callback){
    var i, key
    if (likeArray(elements)) {
      for (i = 0; i < elements.length; i++)
        if (callback.call(elements[i], i, elements[i]) === false) return elements
    } else {
      for (key in elements)
        if (callback.call(elements[key], key, elements[key]) === false) return elements
    }

    return elements
  }

  $.grep = function(elements, callback){
    return filter.call(elements, callback)
  }

  if (window.JSON) $.parseJSON = JSON.parse

  // Populate the class2type map
  $.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(i, name) {
    class2type[ "[object " + name + "]" ] = name.toLowerCase()
  })

  // Define methods that will be available on all
  // Zepto collections
  $.fn = {
    constructor: zepto.Z,
    length: 0,

    // Because a collection acts like an array
    // copy over these useful array functions.
    forEach: emptyArray.forEach,
    reduce: emptyArray.reduce,
    push: emptyArray.push,
    sort: emptyArray.sort,
    splice: emptyArray.splice,
    indexOf: emptyArray.indexOf,
    concat: function(){
      var i, value, args = []
      for (i = 0; i < arguments.length; i++) {
        value = arguments[i]
        args[i] = zepto.isZ(value) ? value.toArray() : value
      }
      return concat.apply(zepto.isZ(this) ? this.toArray() : this, args)
    },

    // `map` and `slice` in the jQuery API work differently
    // from their array counterparts
    map: function(fn){
      return $($.map(this, function(el, i){ return fn.call(el, i, el) }))
    },
    slice: function(){
      return $(slice.apply(this, arguments))
    },

    ready: function(callback){
      // need to check if document.body exists for IE as that browser reports
      // document ready when it hasn't yet created the body element
      if (readyRE.test(document.readyState) && document.body) callback($)
      else document.addEventListener('DOMContentLoaded', function(){ callback($) }, false)
      return this
    },
    get: function(idx){
      return idx === undefined ? slice.call(this) : this[idx >= 0 ? idx : idx + this.length]
    },
    toArray: function(){ return this.get() },
    size: function(){
      return this.length
    },
    remove: function(){
      return this.each(function(){
        if (this.parentNode != null)
          this.parentNode.removeChild(this)
      })
    },
    each: function(callback){
      emptyArray.every.call(this, function(el, idx){
        return callback.call(el, idx, el) !== false
      })
      return this
    },
    filter: function(selector){
      if (isFunction(selector)) return this.not(this.not(selector))
      return $(filter.call(this, function(element){
        return zepto.matches(element, selector)
      }))
    },
    add: function(selector,context){
      return $(uniq(this.concat($(selector,context))))
    },
    is: function(selector){
      return this.length > 0 && zepto.matches(this[0], selector)
    },
    not: function(selector){
      var nodes=[]
      if (isFunction(selector) && selector.call !== undefined)
        this.each(function(idx){
          if (!selector.call(this,idx)) nodes.push(this)
        })
      else {
        var excludes = typeof selector == 'string' ? this.filter(selector) :
          (likeArray(selector) && isFunction(selector.item)) ? slice.call(selector) : $(selector)
        this.forEach(function(el){
          if (excludes.indexOf(el) < 0) nodes.push(el)
        })
      }
      return $(nodes)
    },
    has: function(selector){
      return this.filter(function(){
        return isObject(selector) ?
          $.contains(this, selector) :
          $(this).find(selector).size()
      })
    },
    eq: function(idx){
      return idx === -1 ? this.slice(idx) : this.slice(idx, + idx + 1)
    },
    first: function(){
      var el = this[0]
      return el && !isObject(el) ? el : $(el)
    },
    last: function(){
      var el = this[this.length - 1]
      return el && !isObject(el) ? el : $(el)
    },
    find: function(selector){
      var result, $this = this
      if (!selector) result = $()
      else if (typeof selector == 'object')
        result = $(selector).filter(function(){
          var node = this
          return emptyArray.some.call($this, function(parent){
            return $.contains(parent, node)
          })
        })
      else if (this.length == 1) result = $(zepto.qsa(this[0], selector))
      else result = this.map(function(){ return zepto.qsa(this, selector) })
      return result
    },
    closest: function(selector, context){
      var nodes = [], collection = typeof selector == 'object' && $(selector)
      this.each(function(_, node){
        while (node && !(collection ? collection.indexOf(node) >= 0 : zepto.matches(node, selector)))
          node = node !== context && !isDocument(node) && node.parentNode
        if (node && nodes.indexOf(node) < 0) nodes.push(node)
      })
      return $(nodes)
    },
    parents: function(selector){
      var ancestors = [], nodes = this
      while (nodes.length > 0)
        nodes = $.map(nodes, function(node){
          if ((node = node.parentNode) && !isDocument(node) && ancestors.indexOf(node) < 0) {
            ancestors.push(node)
            return node
          }
        })
      return filtered(ancestors, selector)
    },
    parent: function(selector){
      return filtered(uniq(this.pluck('parentNode')), selector)
    },
    children: function(selector){
      return filtered(this.map(function(){ return children(this) }), selector)
    },
    contents: function() {
      return this.map(function() { return this.contentDocument || slice.call(this.childNodes) })
    },
    siblings: function(selector){
      return filtered(this.map(function(i, el){
        return filter.call(children(el.parentNode), function(child){ return child!==el })
      }), selector)
    },
    empty: function(){
      return this.each(function(){ this.innerHTML = '' })
    },
    // `pluck` is borrowed from Prototype.js
    pluck: function(property){
      return $.map(this, function(el){ return el[property] })
    },
    show: function(){
      return this.each(function(){
        this.style.display == "none" && (this.style.display = '')
        if (getComputedStyle(this, '').getPropertyValue("display") == "none")
          this.style.display = defaultDisplay(this.nodeName)
      })
    },
    replaceWith: function(newContent){
      return this.before(newContent).remove()
    },
    wrap: function(structure){
      var func = isFunction(structure)
      if (this[0] && !func)
        var dom   = $(structure).get(0),
            clone = dom.parentNode || this.length > 1

      return this.each(function(index){
        $(this).wrapAll(
          func ? structure.call(this, index) :
            clone ? dom.cloneNode(true) : dom
        )
      })
    },
    wrapAll: function(structure){
      if (this[0]) {
        $(this[0]).before(structure = $(structure))
        var children
        // drill down to the inmost element
        while ((children = structure.children()).length) structure = children.first()
        $(structure).append(this)
      }
      return this
    },
    wrapInner: function(structure){
      var func = isFunction(structure)
      return this.each(function(index){
        var self = $(this), contents = self.contents(),
            dom  = func ? structure.call(this, index) : structure
        contents.length ? contents.wrapAll(dom) : self.append(dom)
      })
    },
    unwrap: function(){
      this.parent().each(function(){
        $(this).replaceWith($(this).children())
      })
      return this
    },
    clone: function(){
      return this.map(function(){ return this.cloneNode(true) })
    },
    hide: function(){
      return this.css("display", "none")
    },
    toggle: function(setting){
      return this.each(function(){
        var el = $(this)
        ;(setting === undefined ? el.css("display") == "none" : setting) ? el.show() : el.hide()
      })
    },
    prev: function(selector){ return $(this.pluck('previousElementSibling')).filter(selector || '*') },
    next: function(selector){ return $(this.pluck('nextElementSibling')).filter(selector || '*') },
    html: function(html){
      return 0 in arguments ?
        this.each(function(idx){
          var originHtml = this.innerHTML
          $(this).empty().append( funcArg(this, html, idx, originHtml) )
        }) :
        (0 in this ? this[0].innerHTML : null)
    },
    text: function(text){
      return 0 in arguments ?
        this.each(function(idx){
          var newText = funcArg(this, text, idx, this.textContent)
          this.textContent = newText == null ? '' : ''+newText
        }) :
        (0 in this ? this.pluck('textContent').join("") : null)
    },
    attr: function(name, value){
      var result
      return (typeof name == 'string' && !(1 in arguments)) ?
        (0 in this && this[0].nodeType == 1 && (result = this[0].getAttribute(name)) != null ? result : undefined) :
        this.each(function(idx){
          if (this.nodeType !== 1) return
          if (isObject(name)) for (key in name) setAttribute(this, key, name[key])
          else setAttribute(this, name, funcArg(this, value, idx, this.getAttribute(name)))
        })
    },
    removeAttr: function(name){
      return this.each(function(){ this.nodeType === 1 && name.split(' ').forEach(function(attribute){
        setAttribute(this, attribute)
      }, this)})
    },
    prop: function(name, value){
      name = propMap[name] || name
      return (1 in arguments) ?
        this.each(function(idx){
          this[name] = funcArg(this, value, idx, this[name])
        }) :
        (this[0] && this[0][name])
    },
    removeProp: function(name){
      name = propMap[name] || name
      return this.each(function(){ delete this[name] })
    },
    data: function(name, value){
      var attrName = 'data-' + name.replace(capitalRE, '-$1').toLowerCase()

      var data = (1 in arguments) ?
        this.attr(attrName, value) :
        this.attr(attrName)

      return data !== null ? deserializeValue(data) : undefined
    },
    val: function(value){
      if (0 in arguments) {
        if (value == null) value = ""
        return this.each(function(idx){
          this.value = funcArg(this, value, idx, this.value)
        })
      } else {
        return this[0] && (this[0].multiple ?
           $(this[0]).find('option').filter(function(){ return this.selected }).pluck('value') :
           this[0].value)
      }
    },
    offset: function(coordinates){
      if (coordinates) return this.each(function(index){
        var $this = $(this),
            coords = funcArg(this, coordinates, index, $this.offset()),
            parentOffset = $this.offsetParent().offset(),
            props = {
              top:  coords.top  - parentOffset.top,
              left: coords.left - parentOffset.left
            }

        if ($this.css('position') == 'static') props['position'] = 'relative'
        $this.css(props)
      })
      if (!this.length) return null
      if (document.documentElement !== this[0] && !$.contains(document.documentElement, this[0]))
        return {top: 0, left: 0}
      var obj = this[0].getBoundingClientRect()
      return {
        left: obj.left + window.pageXOffset,
        top: obj.top + window.pageYOffset,
        width: Math.round(obj.width),
        height: Math.round(obj.height)
      }
    },
    css: function(property, value){
      if (arguments.length < 2) {
        var element = this[0]
        if (typeof property == 'string') {
          if (!element) return
          return element.style[camelize(property)] || getComputedStyle(element, '').getPropertyValue(property)
        } else if (isArray(property)) {
          if (!element) return
          var props = {}
          var computedStyle = getComputedStyle(element, '')
          $.each(property, function(_, prop){
            props[prop] = (element.style[camelize(prop)] || computedStyle.getPropertyValue(prop))
          })
          return props
        }
      }

      var css = ''
      if (type(property) == 'string') {
        if (!value && value !== 0)
          this.each(function(){ this.style.removeProperty(dasherize(property)) })
        else
          css = dasherize(property) + ":" + maybeAddPx(property, value)
      } else {
        for (key in property)
          if (!property[key] && property[key] !== 0)
            this.each(function(){ this.style.removeProperty(dasherize(key)) })
          else
            css += dasherize(key) + ':' + maybeAddPx(key, property[key]) + ';'
      }

      return this.each(function(){ this.style.cssText += ';' + css })
    },
    index: function(element){
      return element ? this.indexOf($(element)[0]) : this.parent().children().indexOf(this[0])
    },
    hasClass: function(name){
      if (!name) return false
      return emptyArray.some.call(this, function(el){
        return this.test(className(el))
      }, classRE(name))
    },
    addClass: function(name){
      if (!name) return this
      return this.each(function(idx){
        if (!('className' in this)) return
        classList = []
        var cls = className(this), newName = funcArg(this, name, idx, cls)
        newName.split(/\s+/g).forEach(function(klass){
          if (!$(this).hasClass(klass)) classList.push(klass)
        }, this)
        classList.length && className(this, cls + (cls ? " " : "") + classList.join(" "))
      })
    },
    removeClass: function(name){
      return this.each(function(idx){
        if (!('className' in this)) return
        if (name === undefined) return className(this, '')
        classList = className(this)
        funcArg(this, name, idx, classList).split(/\s+/g).forEach(function(klass){
          classList = classList.replace(classRE(klass), " ")
        })
        className(this, classList.trim())
      })
    },
    toggleClass: function(name, when){
      if (!name) return this
      return this.each(function(idx){
        var $this = $(this), names = funcArg(this, name, idx, className(this))
        names.split(/\s+/g).forEach(function(klass){
          (when === undefined ? !$this.hasClass(klass) : when) ?
            $this.addClass(klass) : $this.removeClass(klass)
        })
      })
    },
    scrollTop: function(value){
      if (!this.length) return
      var hasScrollTop = 'scrollTop' in this[0]
      if (value === undefined) return hasScrollTop ? this[0].scrollTop : this[0].pageYOffset
      return this.each(hasScrollTop ?
        function(){ this.scrollTop = value } :
        function(){ this.scrollTo(this.scrollX, value) })
    },
    scrollLeft: function(value){
      if (!this.length) return
      var hasScrollLeft = 'scrollLeft' in this[0]
      if (value === undefined) return hasScrollLeft ? this[0].scrollLeft : this[0].pageXOffset
      return this.each(hasScrollLeft ?
        function(){ this.scrollLeft = value } :
        function(){ this.scrollTo(value, this.scrollY) })
    },
    position: function() {
      if (!this.length) return

      var elem = this[0],
        // Get *real* offsetParent
        offsetParent = this.offsetParent(),
        // Get correct offsets
        offset       = this.offset(),
        parentOffset = rootNodeRE.test(offsetParent[0].nodeName) ? { top: 0, left: 0 } : offsetParent.offset()

      // Subtract element margins
      // note: when an element has margin: auto the offsetLeft and marginLeft
      // are the same in Safari causing offset.left to incorrectly be 0
      offset.top  -= parseFloat( $(elem).css('margin-top') ) || 0
      offset.left -= parseFloat( $(elem).css('margin-left') ) || 0

      // Add offsetParent borders
      parentOffset.top  += parseFloat( $(offsetParent[0]).css('border-top-width') ) || 0
      parentOffset.left += parseFloat( $(offsetParent[0]).css('border-left-width') ) || 0

      // Subtract the two offsets
      return {
        top:  offset.top  - parentOffset.top,
        left: offset.left - parentOffset.left
      }
    },
    offsetParent: function() {
      return this.map(function(){
        var parent = this.offsetParent || document.body
        while (parent && !rootNodeRE.test(parent.nodeName) && $(parent).css("position") == "static")
          parent = parent.offsetParent
        return parent
      })
    }
  }

  // for now
  $.fn.detach = $.fn.remove

  // Generate the `width` and `height` functions
  ;['width', 'height'].forEach(function(dimension){
    var dimensionProperty =
      dimension.replace(/./, function(m){ return m[0].toUpperCase() })

    $.fn[dimension] = function(value){
      var offset, el = this[0]
      if (value === undefined) return isWindow(el) ? el['inner' + dimensionProperty] :
        isDocument(el) ? el.documentElement['scroll' + dimensionProperty] :
        (offset = this.offset()) && offset[dimension]
      else return this.each(function(idx){
        el = $(this)
        el.css(dimension, funcArg(this, value, idx, el[dimension]()))
      })
    }
  })

  function traverseNode(node, fun) {
    fun(node)
    for (var i = 0, len = node.childNodes.length; i < len; i++)
      traverseNode(node.childNodes[i], fun)
  }

  // Generate the `after`, `prepend`, `before`, `append`,
  // `insertAfter`, `insertBefore`, `appendTo`, and `prependTo` methods.
  adjacencyOperators.forEach(function(operator, operatorIndex) {
    var inside = operatorIndex % 2 //=> prepend, append

    $.fn[operator] = function(){
      // arguments can be nodes, arrays of nodes, Zepto objects and HTML strings
      var argType, nodes = $.map(arguments, function(arg) {
            var arr = []
            argType = type(arg)
            if (argType == "array") {
              arg.forEach(function(el) {
                if (el.nodeType !== undefined) return arr.push(el)
                else if ($.zepto.isZ(el)) return arr = arr.concat(el.get())
                arr = arr.concat(zepto.fragment(el))
              })
              return arr
            }
            return argType == "object" || arg == null ?
              arg : zepto.fragment(arg)
          }),
          parent, copyByClone = this.length > 1
      if (nodes.length < 1) return this

      return this.each(function(_, target){
        parent = inside ? target : target.parentNode

        // convert all methods to a "before" operation
        target = operatorIndex == 0 ? target.nextSibling :
                 operatorIndex == 1 ? target.firstChild :
                 operatorIndex == 2 ? target :
                 null

        var parentInDocument = $.contains(document.documentElement, parent)

        nodes.forEach(function(node){
          if (copyByClone) node = node.cloneNode(true)
          else if (!parent) return $(node).remove()

          parent.insertBefore(node, target)
          if (parentInDocument) traverseNode(node, function(el){
            if (el.nodeName != null && el.nodeName.toUpperCase() === 'SCRIPT' &&
               (!el.type || el.type === 'text/javascript') && !el.src){
              var target = el.ownerDocument ? el.ownerDocument.defaultView : window
              target['eval'].call(target, el.innerHTML)
            }
          })
        })
      })
    }

    // after    => insertAfter
    // prepend  => prependTo
    // before   => insertBefore
    // append   => appendTo
    $.fn[inside ? operator+'To' : 'insert'+(operatorIndex ? 'Before' : 'After')] = function(html){
      $(html)[operator](this)
      return this
    }
  })

  zepto.Z.prototype = Z.prototype = $.fn

  // Export internal API functions in the `$.zepto` namespace
  zepto.uniq = uniq
  zepto.deserializeValue = deserializeValue
  $.zepto = zepto

  return $
})()

window.Zepto = Zepto
window.$ === undefined && (window.$ = Zepto)

;(function($){
  var _zid = 1, undefined,
      slice = Array.prototype.slice,
      isFunction = $.isFunction,
      isString = function(obj){ return typeof obj == 'string' },
      handlers = {},
      specialEvents={},
      focusinSupported = 'onfocusin' in window,
      focus = { focus: 'focusin', blur: 'focusout' },
      hover = { mouseenter: 'mouseover', mouseleave: 'mouseout' }

  specialEvents.click = specialEvents.mousedown = specialEvents.mouseup = specialEvents.mousemove = 'MouseEvents'

  function zid(element) {
    return element._zid || (element._zid = _zid++)
  }
  function findHandlers(element, event, fn, selector) {
    event = parse(event)
    if (event.ns) var matcher = matcherFor(event.ns)
    return (handlers[zid(element)] || []).filter(function(handler) {
      return handler
        && (!event.e  || handler.e == event.e)
        && (!event.ns || matcher.test(handler.ns))
        && (!fn       || zid(handler.fn) === zid(fn))
        && (!selector || handler.sel == selector)
    })
  }
  function parse(event) {
    var parts = ('' + event).split('.')
    return {e: parts[0], ns: parts.slice(1).sort().join(' ')}
  }
  function matcherFor(ns) {
    return new RegExp('(?:^| )' + ns.replace(' ', ' .* ?') + '(?: |$)')
  }

  function eventCapture(handler, captureSetting) {
    return handler.del &&
      (!focusinSupported && (handler.e in focus)) ||
      !!captureSetting
  }

  function realEvent(type) {
    return hover[type] || (focusinSupported && focus[type]) || type
  }

  function add(element, events, fn, data, selector, delegator, capture){
    var id = zid(element), set = (handlers[id] || (handlers[id] = []))
    events.split(/\s/).forEach(function(event){
      if (event == 'ready') return $(document).ready(fn)
      var handler   = parse(event)
      handler.fn    = fn
      handler.sel   = selector
      // emulate mouseenter, mouseleave
      if (handler.e in hover) fn = function(e){
        var related = e.relatedTarget
        if (!related || (related !== this && !$.contains(this, related)))
          return handler.fn.apply(this, arguments)
      }
      handler.del   = delegator
      var callback  = delegator || fn
      handler.proxy = function(e){
        e = compatible(e)
        if (e.isImmediatePropagationStopped()) return
        e.data = data
        var result = callback.apply(element, e._args == undefined ? [e] : [e].concat(e._args))
        if (result === false) e.preventDefault(), e.stopPropagation()
        return result
      }
      handler.i = set.length
      set.push(handler)
      if ('addEventListener' in element)
        element.addEventListener(realEvent(handler.e), handler.proxy, eventCapture(handler, capture))
    })
  }
  function remove(element, events, fn, selector, capture){
    var id = zid(element)
    ;(events || '').split(/\s/).forEach(function(event){
      findHandlers(element, event, fn, selector).forEach(function(handler){
        delete handlers[id][handler.i]
      if ('removeEventListener' in element)
        element.removeEventListener(realEvent(handler.e), handler.proxy, eventCapture(handler, capture))
      })
    })
  }

  $.event = { add: add, remove: remove }

  $.proxy = function(fn, context) {
    var args = (2 in arguments) && slice.call(arguments, 2)
    if (isFunction(fn)) {
      var proxyFn = function(){ return fn.apply(context, args ? args.concat(slice.call(arguments)) : arguments) }
      proxyFn._zid = zid(fn)
      return proxyFn
    } else if (isString(context)) {
      if (args) {
        args.unshift(fn[context], fn)
        return $.proxy.apply(null, args)
      } else {
        return $.proxy(fn[context], fn)
      }
    } else {
      throw new TypeError("expected function")
    }
  }

  $.fn.bind = function(event, data, callback){
    return this.on(event, data, callback)
  }
  $.fn.unbind = function(event, callback){
    return this.off(event, callback)
  }
  $.fn.one = function(event, selector, data, callback){
    return this.on(event, selector, data, callback, 1)
  }

  var returnTrue = function(){return true},
      returnFalse = function(){return false},
      ignoreProperties = /^([A-Z]|returnValue$|layer[XY]$|webkitMovement[XY]$)/,
      eventMethods = {
        preventDefault: 'isDefaultPrevented',
        stopImmediatePropagation: 'isImmediatePropagationStopped',
        stopPropagation: 'isPropagationStopped'
      }

  function compatible(event, source) {
    if (source || !event.isDefaultPrevented) {
      source || (source = event)

      $.each(eventMethods, function(name, predicate) {
        var sourceMethod = source[name]
        event[name] = function(){
          this[predicate] = returnTrue
          return sourceMethod && sourceMethod.apply(source, arguments)
        }
        event[predicate] = returnFalse
      })

      event.timeStamp || (event.timeStamp = Date.now())

      if (source.defaultPrevented !== undefined ? source.defaultPrevented :
          'returnValue' in source ? source.returnValue === false :
          source.getPreventDefault && source.getPreventDefault())
        event.isDefaultPrevented = returnTrue
    }
    return event
  }

  function createProxy(event) {
    var key, proxy = { originalEvent: event }
    for (key in event)
      if (!ignoreProperties.test(key) && event[key] !== undefined) proxy[key] = event[key]

    return compatible(proxy, event)
  }

  $.fn.delegate = function(selector, event, callback){
    return this.on(event, selector, callback)
  }
  $.fn.undelegate = function(selector, event, callback){
    return this.off(event, selector, callback)
  }

  $.fn.live = function(event, callback){
    $(document.body).delegate(this.selector, event, callback)
    return this
  }
  $.fn.die = function(event, callback){
    $(document.body).undelegate(this.selector, event, callback)
    return this
  }

  $.fn.on = function(event, selector, data, callback, one){
    var autoRemove, delegator, $this = this
    if (event && !isString(event)) {
      $.each(event, function(type, fn){
        $this.on(type, selector, data, fn, one)
      })
      return $this
    }

    if (!isString(selector) && !isFunction(callback) && callback !== false)
      callback = data, data = selector, selector = undefined
    if (callback === undefined || data === false)
      callback = data, data = undefined

    if (callback === false) callback = returnFalse

    return $this.each(function(_, element){
      if (one) autoRemove = function(e){
        remove(element, e.type, callback)
        return callback.apply(this, arguments)
      }

      if (selector) delegator = function(e){
        var evt, match = $(e.target).closest(selector, element).get(0)
        if (match && match !== element) {
          evt = $.extend(createProxy(e), {currentTarget: match, liveFired: element})
          return (autoRemove || callback).apply(match, [evt].concat(slice.call(arguments, 1)))
        }
      }

      add(element, event, callback, data, selector, delegator || autoRemove)
    })
  }
  $.fn.off = function(event, selector, callback){
    var $this = this
    if (event && !isString(event)) {
      $.each(event, function(type, fn){
        $this.off(type, selector, fn)
      })
      return $this
    }

    if (!isString(selector) && !isFunction(callback) && callback !== false)
      callback = selector, selector = undefined

    if (callback === false) callback = returnFalse

    return $this.each(function(){
      remove(this, event, callback, selector)
    })
  }

  $.fn.trigger = function(event, args){
    event = (isString(event) || $.isPlainObject(event)) ? $.Event(event) : compatible(event)
    event._args = args
    return this.each(function(){
      // handle focus(), blur() by calling them directly
      if (event.type in focus && typeof this[event.type] == "function") this[event.type]()
      // items in the collection might not be DOM elements
      else if ('dispatchEvent' in this) this.dispatchEvent(event)
      else $(this).triggerHandler(event, args)
    })
  }

  // triggers event handlers on current element just as if an event occurred,
  // doesn't trigger an actual event, doesn't bubble
  $.fn.triggerHandler = function(event, args){
    var e, result
    this.each(function(i, element){
      e = createProxy(isString(event) ? $.Event(event) : event)
      e._args = args
      e.target = element
      $.each(findHandlers(element, event.type || event), function(i, handler){
        result = handler.proxy(e)
        if (e.isImmediatePropagationStopped()) return false
      })
    })
    return result
  }

  // shortcut methods for `.bind(event, fn)` for each event type
  ;('focusin focusout focus blur load resize scroll unload click dblclick '+
  'mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave '+
  'change select keydown keypress keyup error').split(' ').forEach(function(event) {
    $.fn[event] = function(callback) {
      return (0 in arguments) ?
        this.bind(event, callback) :
        this.trigger(event)
    }
  })

  $.Event = function(type, props) {
    if (!isString(type)) props = type, type = props.type
    var event = document.createEvent(specialEvents[type] || 'Events'), bubbles = true
    if (props) for (var name in props) (name == 'bubbles') ? (bubbles = !!props[name]) : (event[name] = props[name])
    event.initEvent(type, bubbles, true)
    return compatible(event)
  }

})(Zepto)

;(function($){
  var jsonpID = +new Date(),
      document = window.document,
      key,
      name,
      rscript = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
      scriptTypeRE = /^(?:text|application)\/javascript/i,
      xmlTypeRE = /^(?:text|application)\/xml/i,
      jsonType = 'application/json',
      htmlType = 'text/html',
      blankRE = /^\s*$/,
      originAnchor = document.createElement('a')

  originAnchor.href = window.location.href

  // trigger a custom event and return false if it was cancelled
  function triggerAndReturn(context, eventName, data) {
    var event = $.Event(eventName)
    $(context).trigger(event, data)
    return !event.isDefaultPrevented()
  }

  // trigger an Ajax "global" event
  function triggerGlobal(settings, context, eventName, data) {
    if (settings.global) return triggerAndReturn(context || document, eventName, data)
  }

  // Number of active Ajax requests
  $.active = 0

  function ajaxStart(settings) {
    if (settings.global && $.active++ === 0) triggerGlobal(settings, null, 'ajaxStart')
  }
  function ajaxStop(settings) {
    if (settings.global && !(--$.active)) triggerGlobal(settings, null, 'ajaxStop')
  }

  // triggers an extra global event "ajaxBeforeSend" that's like "ajaxSend" but cancelable
  function ajaxBeforeSend(xhr, settings) {
    var context = settings.context
    if (settings.beforeSend.call(context, xhr, settings) === false ||
        triggerGlobal(settings, context, 'ajaxBeforeSend', [xhr, settings]) === false)
      return false

    triggerGlobal(settings, context, 'ajaxSend', [xhr, settings])
  }
  function ajaxSuccess(data, xhr, settings, deferred) {
    var context = settings.context, status = 'success'
    settings.success.call(context, data, status, xhr)
    if (deferred) deferred.resolveWith(context, [data, status, xhr])
    triggerGlobal(settings, context, 'ajaxSuccess', [xhr, settings, data])
    ajaxComplete(status, xhr, settings)
  }
  // type: "timeout", "error", "abort", "parsererror"
  function ajaxError(error, type, xhr, settings, deferred) {
    var context = settings.context
    settings.error.call(context, xhr, type, error)
    if (deferred) deferred.rejectWith(context, [xhr, type, error])
    triggerGlobal(settings, context, 'ajaxError', [xhr, settings, error || type])
    ajaxComplete(type, xhr, settings)
  }
  // status: "success", "notmodified", "error", "timeout", "abort", "parsererror"
  function ajaxComplete(status, xhr, settings) {
    var context = settings.context
    settings.complete.call(context, xhr, status)
    triggerGlobal(settings, context, 'ajaxComplete', [xhr, settings])
    ajaxStop(settings)
  }

  function ajaxDataFilter(data, type, settings) {
    if (settings.dataFilter == empty) return data
    var context = settings.context
    return settings.dataFilter.call(context, data, type)
  }

  // Empty function, used as default callback
  function empty() {}

  $.ajaxJSONP = function(options, deferred){
    if (!('type' in options)) return $.ajax(options)

    var _callbackName = options.jsonpCallback,
      callbackName = ($.isFunction(_callbackName) ?
        _callbackName() : _callbackName) || ('Zepto' + (jsonpID++)),
      script = document.createElement('script'),
      originalCallback = window[callbackName],
      responseData,
      abort = function(errorType) {
        $(script).triggerHandler('error', errorType || 'abort')
      },
      xhr = { abort: abort }, abortTimeout

    if (deferred) deferred.promise(xhr)

    $(script).on('load error', function(e, errorType){
      clearTimeout(abortTimeout)
      $(script).off().remove()

      if (e.type == 'error' || !responseData) {
        ajaxError(null, errorType || 'error', xhr, options, deferred)
      } else {
        ajaxSuccess(responseData[0], xhr, options, deferred)
      }

      window[callbackName] = originalCallback
      if (responseData && $.isFunction(originalCallback))
        originalCallback(responseData[0])

      originalCallback = responseData = undefined
    })

    if (ajaxBeforeSend(xhr, options) === false) {
      abort('abort')
      return xhr
    }

    window[callbackName] = function(){
      responseData = arguments
    }

    script.src = options.url.replace(/\?(.+)=\?/, '?$1=' + callbackName)
    document.head.appendChild(script)

    if (options.timeout > 0) abortTimeout = setTimeout(function(){
      abort('timeout')
    }, options.timeout)

    return xhr
  }

  $.ajaxSettings = {
    // Default type of request
    type: 'GET',
    // Callback that is executed before request
    beforeSend: empty,
    // Callback that is executed if the request succeeds
    success: empty,
    // Callback that is executed the the server drops error
    error: empty,
    // Callback that is executed on request complete (both: error and success)
    complete: empty,
    // The context for the callbacks
    context: null,
    // Whether to trigger "global" Ajax events
    global: true,
    // Transport
    xhr: function () {
      return new window.XMLHttpRequest()
    },
    // MIME types mapping
    // IIS returns Javascript as "application/x-javascript"
    accepts: {
      script: 'text/javascript, application/javascript, application/x-javascript',
      json:   jsonType,
      xml:    'application/xml, text/xml',
      html:   htmlType,
      text:   'text/plain'
    },
    // Whether the request is to another domain
    crossDomain: false,
    // Default timeout
    timeout: 0,
    // Whether data should be serialized to string
    processData: true,
    // Whether the browser should be allowed to cache GET responses
    cache: true,
    //Used to handle the raw response data of XMLHttpRequest.
    //This is a pre-filtering function to sanitize the response.
    //The sanitized response should be returned
    dataFilter: empty
  }

  function mimeToDataType(mime) {
    if (mime) mime = mime.split(';', 2)[0]
    return mime && ( mime == htmlType ? 'html' :
      mime == jsonType ? 'json' :
      scriptTypeRE.test(mime) ? 'script' :
      xmlTypeRE.test(mime) && 'xml' ) || 'text'
  }

  function appendQuery(url, query) {
    if (query == '') return url
    return (url + '&' + query).replace(/[&?]{1,2}/, '?')
  }

  // serialize payload and append it to the URL for GET requests
  function serializeData(options) {
    if (options.processData && options.data && $.type(options.data) != "string")
      options.data = $.param(options.data, options.traditional)
    if (options.data && (!options.type || options.type.toUpperCase() == 'GET' || 'jsonp' == options.dataType))
      options.url = appendQuery(options.url, options.data), options.data = undefined
  }

  $.ajax = function(options){
    var settings = $.extend({}, options || {}),
        deferred = $.Deferred && $.Deferred(),
        urlAnchor, hashIndex
    for (key in $.ajaxSettings) if (settings[key] === undefined) settings[key] = $.ajaxSettings[key]

    ajaxStart(settings)

    if (!settings.crossDomain) {
      urlAnchor = document.createElement('a')
      urlAnchor.href = settings.url
      // cleans up URL for .href (IE only), see https://github.com/madrobby/zepto/pull/1049
      urlAnchor.href = urlAnchor.href
      settings.crossDomain = (originAnchor.protocol + '//' + originAnchor.host) !== (urlAnchor.protocol + '//' + urlAnchor.host)
    }

    if (!settings.url) settings.url = window.location.toString()
    if ((hashIndex = settings.url.indexOf('#')) > -1) settings.url = settings.url.slice(0, hashIndex)
    serializeData(settings)

    var dataType = settings.dataType, hasPlaceholder = /\?.+=\?/.test(settings.url)
    if (hasPlaceholder) dataType = 'jsonp'

    if (settings.cache === false || (
         (!options || options.cache !== true) &&
         ('script' == dataType || 'jsonp' == dataType)
        ))
      settings.url = appendQuery(settings.url, '_=' + Date.now())

    if ('jsonp' == dataType) {
      if (!hasPlaceholder)
        settings.url = appendQuery(settings.url,
          settings.jsonp ? (settings.jsonp + '=?') : settings.jsonp === false ? '' : 'callback=?')
      return $.ajaxJSONP(settings, deferred)
    }

    var mime = settings.accepts[dataType],
        headers = { },
        setHeader = function(name, value) { headers[name.toLowerCase()] = [name, value] },
        protocol = /^([\w-]+:)\/\//.test(settings.url) ? RegExp.$1 : window.location.protocol,
        xhr = settings.xhr(),
        nativeSetHeader = xhr.setRequestHeader,
        abortTimeout

    if (deferred) deferred.promise(xhr)

    if (!settings.crossDomain) setHeader('X-Requested-With', 'XMLHttpRequest')
    setHeader('Accept', mime || '*/*')
    if (mime = settings.mimeType || mime) {
      if (mime.indexOf(',') > -1) mime = mime.split(',', 2)[0]
      xhr.overrideMimeType && xhr.overrideMimeType(mime)
    }
    if (settings.contentType || (settings.contentType !== false && settings.data && settings.type.toUpperCase() != 'GET'))
      setHeader('Content-Type', settings.contentType || 'application/x-www-form-urlencoded')

    if (settings.headers) for (name in settings.headers) setHeader(name, settings.headers[name])
    xhr.setRequestHeader = setHeader

    xhr.onreadystatechange = function(){
      if (xhr.readyState == 4) {
        xhr.onreadystatechange = empty
        clearTimeout(abortTimeout)
        var result, error = false
        if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304 || (xhr.status == 0 && protocol == 'file:')) {
          dataType = dataType || mimeToDataType(settings.mimeType || xhr.getResponseHeader('content-type'))

          if (xhr.responseType == 'arraybuffer' || xhr.responseType == 'blob')
            result = xhr.response
          else {
            result = xhr.responseText

            try {
              // http://perfectionkills.com/global-eval-what-are-the-options/
              // sanitize response accordingly if data filter callback provided
              result = ajaxDataFilter(result, dataType, settings)
              if (dataType == 'script')    (1,eval)(result)
              else if (dataType == 'xml')  result = xhr.responseXML
              else if (dataType == 'json') result = blankRE.test(result) ? null : $.parseJSON(result)
            } catch (e) { error = e }

            if (error) return ajaxError(error, 'parsererror', xhr, settings, deferred)
          }

          ajaxSuccess(result, xhr, settings, deferred)
        } else {
          ajaxError(xhr.statusText || null, xhr.status ? 'error' : 'abort', xhr, settings, deferred)
        }
      }
    }

    if (ajaxBeforeSend(xhr, settings) === false) {
      xhr.abort()
      ajaxError(null, 'abort', xhr, settings, deferred)
      return xhr
    }

    var async = 'async' in settings ? settings.async : true
    xhr.open(settings.type, settings.url, async, settings.username, settings.password)

    if (settings.xhrFields) for (name in settings.xhrFields) xhr[name] = settings.xhrFields[name]

    for (name in headers) nativeSetHeader.apply(xhr, headers[name])

    if (settings.timeout > 0) abortTimeout = setTimeout(function(){
        xhr.onreadystatechange = empty
        xhr.abort()
        ajaxError(null, 'timeout', xhr, settings, deferred)
      }, settings.timeout)

    // avoid sending empty string (#319)
    xhr.send(settings.data ? settings.data : null)
    return xhr
  }

  // handle optional data/success arguments
  function parseArguments(url, data, success, dataType) {
    if ($.isFunction(data)) dataType = success, success = data, data = undefined
    if (!$.isFunction(success)) dataType = success, success = undefined
    return {
      url: url
    , data: data
    , success: success
    , dataType: dataType
    }
  }

  $.get = function(/* url, data, success, dataType */){
    return $.ajax(parseArguments.apply(null, arguments))
  }

  $.post = function(/* url, data, success, dataType */){
    var options = parseArguments.apply(null, arguments)
    options.type = 'POST'
    return $.ajax(options)
  }

  $.getJSON = function(/* url, data, success */){
    var options = parseArguments.apply(null, arguments)
    options.dataType = 'json'
    return $.ajax(options)
  }

  $.fn.load = function(url, data, success){
    if (!this.length) return this
    var self = this, parts = url.split(/\s/), selector,
        options = parseArguments(url, data, success),
        callback = options.success
    if (parts.length > 1) options.url = parts[0], selector = parts[1]
    options.success = function(response){
      self.html(selector ?
        $('<div>').html(response.replace(rscript, "")).find(selector)
        : response)
      callback && callback.apply(self, arguments)
    }
    $.ajax(options)
    return this
  }

  var escape = encodeURIComponent

  function serialize(params, obj, traditional, scope){
    var type, array = $.isArray(obj), hash = $.isPlainObject(obj)
    $.each(obj, function(key, value) {
      type = $.type(value)
      if (scope) key = traditional ? scope :
        scope + '[' + (hash || type == 'object' || type == 'array' ? key : '') + ']'
      // handle data in serializeArray() format
      if (!scope && array) params.add(value.name, value.value)
      // recurse into nested objects
      else if (type == "array" || (!traditional && type == "object"))
        serialize(params, value, traditional, key)
      else params.add(key, value)
    })
  }

  $.param = function(obj, traditional){
    var params = []
    params.add = function(key, value) {
      if ($.isFunction(value)) value = value()
      if (value == null) value = ""
      this.push(escape(key) + '=' + escape(value))
    }
    serialize(params, obj, traditional)
    return params.join('&').replace(/%20/g, '+')
  }
})(Zepto)

;(function($){
  $.fn.serializeArray = function() {
    var name, type, result = [],
      add = function(value) {
        if (value.forEach) return value.forEach(add)
        result.push({ name: name, value: value })
      }
    if (this[0]) $.each(this[0].elements, function(_, field){
      type = field.type, name = field.name
      if (name && field.nodeName.toLowerCase() != 'fieldset' &&
        !field.disabled && type != 'submit' && type != 'reset' && type != 'button' && type != 'file' &&
        ((type != 'radio' && type != 'checkbox') || field.checked))
          add($(field).val())
    })
    return result
  }

  $.fn.serialize = function(){
    var result = []
    this.serializeArray().forEach(function(elm){
      result.push(encodeURIComponent(elm.name) + '=' + encodeURIComponent(elm.value))
    })
    return result.join('&')
  }

  $.fn.submit = function(callback) {
    if (0 in arguments) this.bind('submit', callback)
    else if (this.length) {
      var event = $.Event('submit')
      this.eq(0).trigger(event)
      if (!event.isDefaultPrevented()) this.get(0).submit()
    }
    return this
  }

})(Zepto)

;(function(){
  // getComputedStyle shouldn't freak out when called
  // without a valid element as argument
  try {
    getComputedStyle(undefined)
  } catch(e) {
    var nativeGetComputedStyle = getComputedStyle
    window.getComputedStyle = function(element, pseudoElement){
      try {
        return nativeGetComputedStyle(element, pseudoElement)
      } catch(e) {
        return null
      }
    }
  }
})()
  return Zepto
}))


/***/ }),

/***/ "./resources/assets/js/app.js":
/***/ (function(module, exports, __webpack_require__) {

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
 Vue.component('example', require('./components/Example.vue'));

 const app = new Vue({
 el: '#app'
 });*/

__webpack_require__("./node_modules/zepto/dist/zepto.js");
__webpack_require__("./resources/assets/js/layer.js");
window.FastClick = __webpack_require__("./node_modules/fastclick/lib/fastclick.js");
__webpack_require__("./resources/assets/js/mobiscroll.custom-2.16.1.min.js");
FastClick.attach(document.body);

window.axios = __webpack_require__("./node_modules/axios/index.js");
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.getElementById('crsf-token').getAttribute('content');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
$(function () {
  $('a').click(function () {
    $('.loaders').show(0);
  });
});

/***/ }),

/***/ "./resources/assets/js/layer.js":
/***/ (function(module, exports) {

+function ($) {
	var layerModal = function layerModal(dom, callback) {
		var dialog = $(dom).appendTo(document.body);
		dialog.find('.btn , .close').click(function () {
			$(this).off();
			dialog.removeClass("active");
			if (typeof callback == "function") callback();
			setTimeout(function () {
				dialog.remove();
				dialog = null;
			}, 350);
		});
		setTimeout(function () {
			dialog.addClass('active');
		}, 100);
	};
	$.alert = function (text, callback) {
		var dom = '<div class="layer" id="layer"><div><div class="layer-content">\
		 <div class="layer-title">\
                <h2>温馨提示</h2>\
                <div class="close"><i>X</i></div>\
         </div>\
         <div class="layer-text"><h1></h1><p style="text-align:center;font-size:1.05rem;color:#f05136">' + text + '</p></div>\
       <div class="layer_button"> <button class="btn" >确定</button></div></div></div></div>';
		layerModal(dom, callback);
	};
	$.closeAll = function () {
		var dialog = $("#layer.active").removeClass("active");
		setTimeout(function () {
			dialog.remove();
		}, 350);
	};
}($);

/***/ }),

/***/ "./resources/assets/js/mobiscroll.custom-2.16.1.min.js":
/***/ (function(module, exports) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/* 59adbde1-5157-48b9-a8b6-d9a6cbc6e6b8 */
if (!window.jQuery) {
    var jQuery = Zepto;
    (function (a) {
        ["width", "height"].forEach(function (m) {
            a.fn[m] = function (c) {
                var l,
                    e = document.body,
                    w = document.documentElement,
                    s = m.replace(/./, function (a) {
                    return a[0].toUpperCase();
                });
                return void 0 === c ? this[0] == window ? w["client" + s] : this[0] == document ? Math.max(e["scroll" + s], e["offset" + s], w["client" + s], w["scroll" + s], w["offset" + s]) : (l = this.offset()) && l[m] : this.each(function () {
                    a(this).css(m, c);
                });
            };
        });
        ["width", "height"].forEach(function (m) {
            var c = m.replace(/./, function (a) {
                return a[0].toUpperCase();
            });
            a.fn["outer" + c] = function (a) {
                var e = this;
                if (e) {
                    var w = e[0]["offset" + c];
                    ({ width: ["left", "right"], height: ["top", "bottom"] })[m].forEach(function (c) {
                        a && (w += parseInt(e.css("margin-" + c), 10));
                    });
                    return w;
                }
                return null;
            };
        });
        ["width", "height"].forEach(function (m) {
            var c = m.replace(/./, function (a) {
                return a[0].toUpperCase();
            });
            a.fn["inner" + c] = function () {
                var a = this;
                if (a[0]["inner" + c]) return a[0]["inner" + c];
                var e = a[0]["offset" + c];
                ({ width: ["left", "right"], height: ["top", "bottom"] })[m].forEach(function (c) {
                    e -= parseInt(a.css("border-" + c + "-width"), 10);
                });
                return e;
            };
        });
        ["Left", "Top"].forEach(function (m, c) {
            function l(a) {
                return a && "object" === (typeof a === "undefined" ? "undefined" : _typeof(a)) && "setInterval" in a ? a : 9 === a.nodeType ? a.defaultView || a.parentWindow : !1;
            }

            var e = "scroll" + m;
            a.fn[e] = function (m) {
                var s, v;
                if (void 0 === m) return s = this[0], !s ? null : (v = l(s)) ? "pageXOffset" in v ? v[c ? "pageYOffset" : "pageXOffset"] : v.document.documentElement[e] || v.document.body[e] : s[e];
                this.each(function () {
                    if (v = l(this)) {
                        var s = !c ? m : a(v).scrollLeft(),
                            I = c ? m : a(v).scrollTop();
                        v.scrollTo(s, I);
                    } else this[e] = m;
                });
            };
        });
        a.fn.prevUntil = function (m) {
            for (var c = this, l = []; c.length && !a(c).filter(m).length;) {
                l.push(c[0]), c = c.prev();
            }return a(l);
        };
        a.fn.nextUntil = function (m) {
            for (var c = this, l = []; c.length && !c.filter(m).length;) {
                l.push(c[0]), c = c.next();
            }return a(l);
        };
        a._extend = a.extend;
        a.extend = function () {
            arguments[0] = arguments[0] || {};
            return a._extend.apply(this, arguments);
        };
    })(jQuery);
}
;(function (a, m) {
    function c(a) {
        for (var c in a) {
            if (I[a[c]] !== m) return !0;
        }return !1;
    }

    function l(c, f, e) {
        var i = c;
        if ("object" === (typeof f === "undefined" ? "undefined" : _typeof(f))) return c.each(function () {
            v[this.id] && v[this.id].destroy();
            new a.mobiscroll.classes[f.component || "Scroller"](this, f);
        });
        "string" === typeof f && c.each(function () {
            var a;
            if ((a = v[this.id]) && a[f]) if (a = a[f].apply(this, Array.prototype.slice.call(e, 1)), a !== m) return i = a, !1;
        });
        return i;
    }

    function e(a) {
        if (w.tapped && !a.tap) return a.stopPropagation(), a.preventDefault(), !1;
    }

    var w,
        s = +new Date(),
        v = {},
        L = a.extend,
        I = document.createElement("modernizr").style,
        i = c(["perspectiveProperty", "WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"]),
        f = c(["flex", "msFlex", "WebkitBoxDirection"]),
        ka = function () {
        var a = ["Webkit", "Moz", "O", "ms"],
            f;
        for (f in a) {
            if (c([a[f] + "Transform"])) return "-" + a[f].toLowerCase() + "-";
        }return "";
    }(),
        qa = ka.replace(/^\-/, "").replace(/\-$/, "").replace("moz", "Moz");
    a.fn.mobiscroll = function (c) {
        L(this, a.mobiscroll.components);
        return l(this, c, arguments);
    };
    w = a.mobiscroll = a.mobiscroll || {
        version: "2.16.1",
        util: {
            prefix: ka, jsPrefix: qa, has3d: i, hasFlex: f, testTouch: function testTouch(c, f) {
                if ("touchstart" == c.type) a(f).attr("data-touch", "1");else if (a(f).attr("data-touch")) return a(f).removeAttr("data-touch"), !1;
                return !0;
            }, objectToArray: function objectToArray(a) {
                var c = [],
                    f;
                for (f in a) {
                    c.push(a[f]);
                }return c;
            }, arrayToObject: function arrayToObject(a) {
                var c = {},
                    f;
                if (a) for (f = 0; f < a.length; f++) {
                    c[a[f]] = a[f];
                }return c;
            }, isNumeric: function isNumeric(a) {
                return 0 <= a - parseFloat(a);
            }, isString: function isString(a) {
                return "string" === typeof a;
            }, getCoord: function getCoord(a, c, f) {
                var e = a.originalEvent || a,
                    c = (f ? "client" : "page") + c;
                return e.changedTouches ? e.changedTouches[0][c] : a[c];
            }, getPosition: function getPosition(c, f) {
                var e = window.getComputedStyle ? getComputedStyle(c[0]) : c[0].style,
                    l,
                    v;
                i ? (a.each(["t", "webkitT", "MozT", "OT", "msT"], function (a, c) {
                    if (e[c + "ransform"] !== m) return l = e[c + "ransform"], !1;
                }), l = l.split(")")[0].split(", "), v = f ? l[13] || l[5] : l[12] || l[4]) : v = f ? e.top.replace("px", "") : e.left.replace("px", "");
                return v;
            }, constrain: function constrain(a, c, f) {
                return Math.max(c, Math.min(a, f));
            }, vibrate: function vibrate(a) {
                "vibrate" in navigator && navigator.vibrate(a || 50);
            }
        },
        tapped: 0,
        autoTheme: "mobiscroll",
        presets: { scroller: {}, numpad: {}, listview: {}, menustrip: {} },
        themes: { form: {}, frame: {}, listview: {}, menustrip: {} },
        i18n: {},
        instances: v,
        classes: {},
        components: {},
        defaults: { context: "body", mousewheel: !0, vibrate: !0 },
        setDefaults: function setDefaults(a) {
            L(this.defaults, a);
        },
        presetShort: function presetShort(a, c, f) {
            this.components[a] = function (e) {
                return l(this, L(e, { component: c, preset: !1 === f ? m : a }), arguments);
            };
        }
    };
    a.mobiscroll.classes.Base = function (c, f) {
        var e,
            i,
            l,
            m,
            x,
            R,
            w = a.mobiscroll,
            y = this;
        y.settings = {};
        y._presetLoad = function () {};
        y._init = function (a) {
            l = y.settings;
            L(f, a);
            y._hasDef && (R = w.defaults);
            L(l, y._defaults, R, f);
            if (y._hasTheme) {
                x = l.theme;
                if ("auto" == x || !x) x = w.autoTheme;
                "default" == x && (x = "mobiscroll");
                f.theme = x;
                m = w.themes[y._class][x];
            }
            y._hasLang && (e = w.i18n[l.lang]);
            y._hasTheme && y.trigger("onThemeLoad", [e, f]);
            L(l, m, e, R, f);
            if (y._hasPreset && (y._presetLoad(l), i = w.presets[y._class][l.preset])) i = i.call(c, y), L(l, i, f);
        };
        y._destroy = function () {
            y.trigger("onDestroy", []);
            delete v[c.id];
            y = null;
        };
        y.trigger = function (e, l) {
            var v;
            l.push(y);
            a.each([R, m, i, f], function (a, f) {
                f && f[e] && (v = f[e].apply(c, l));
            });
            return v;
        };
        y.option = function (a, f) {
            var c = {};
            "object" === (typeof a === "undefined" ? "undefined" : _typeof(a)) ? c = a : c[a] = f;
            y.init(c);
        };
        y.getInst = function () {
            return y;
        };
        f = f || {};
        c.id || (c.id = "mobiscroll" + ++s);
        v[c.id] = y;
    };
    document.addEventListener && a.each(["mouseover", "mousedown", "mouseup", "click"], function (a, c) {
        document.addEventListener(c, e, !0);
    });
})(jQuery);
(function (a) {
    a.mobiscroll.i18n.zh = {
        setText: "\u786E\u5B9A",
        cancelText: "\u53D6\u6D88",
        clearText: "\u660E\u786E",
        selectedText: "\u9009",
        dateFormat: "yy/mm/dd",
        dateOrder: "yymmdd",
        dayNames: "\u5468\u65E5,\u5468\u4E00,\u5468\u4E8C,\u5468\u4E09,\u5468\u56DB,\u5468\u4E94,\u5468\u516D".split(","),
        dayNamesShort: "\u65E5,\u4E00,\u4E8C,\u4E09,\u56DB,\u4E94,\u516D".split(","),
        dayNamesMin: "\u65E5,\u4E00,\u4E8C,\u4E09,\u56DB,\u4E94,\u516D".split(","),
        dayText: "\u65E5",
        hourText: "\u65F6",
        minuteText: "\u5206",
        monthNames: "1\u6708,2\u6708,3\u6708,4\u6708,5\u6708,6\u6708,7\u6708,8\u6708,9\u6708,10\u6708,11\u6708,12\u6708".split(","),
        monthNamesShort: "\u4E00,\u4E8C,\u4E09,\u56DB,\u4E94,\u516D,\u4E03,\u516B,\u4E5D,\u5341,\u5341\u4E00,\u5341\u4E8C".split(","),
        monthText: "\u6708",
        secText: "\u79D2",
        timeFormat: "HH:ii",
        timeWheels: "HHii",
        yearText: "\u5E74",
        nowText: "\u5F53\u524D",
        pmText: "\u4E0B\u5348",
        amText: "\u4E0A\u5348",
        dateText: "\u65E5",
        timeText: "\u65F6\u95F4",
        calendarText: "\u65E5\u5386",
        closeText: "\u5173\u95ED",
        fromText: "\u5F00\u59CB\u65F6\u95F4",
        toText: "\u7ED3\u675F\u65F6\u95F4",
        wholeText: "\u5408\u8BA1",
        fractionText: "\u5206\u6570",
        unitText: "\u5355\u4F4D",
        labels: "\u5E74,\u6708,\u65E5,\u5C0F\u65F6,\u5206\u949F,\u79D2,".split(","),
        labelsShort: "\u5E74,\u6708,\u65E5,\u70B9,\u5206,\u79D2,".split(","),
        startText: "\u5F00\u59CB",
        stopText: "\u505C\u6B62",
        resetText: "\u91CD\u7F6E",
        lapText: "\u5708",
        hideText: "\u9690\u85CF",
        backText: "\u80CC\u90E8",
        undoText: "\u590D\u539F",
        offText: "\u5173\u95ED",
        onText: "\u5F00\u542F"
    };
})(jQuery);
(function (a, m, c, l) {
    var e,
        w,
        s = a.mobiscroll,
        v = s.util,
        L = v.jsPrefix,
        I = v.has3d,
        i = v.getCoord,
        f = v.constrain,
        ka = v.isString,
        qa = /android [1-3]/i.test(navigator.userAgent),
        v = /(iphone|ipod|ipad).* os 8_/i.test(navigator.userAgent),
        ba = function ba() {},
        ha = function ha(a) {
        a.preventDefault();
    };
    s.classes.Frame = function (v, da, ca) {
        function ea(d) {
            P && P.removeClass("dwb-a");
            P = a(this);
            !P.hasClass("dwb-d") && !P.hasClass("dwb-nhl") && P.addClass("dwb-a");
            if ("mousedown" === d.type) a(c).on("mouseup", x);
        }

        function x(d) {
            P && (P.removeClass("dwb-a"), P = null);
            "mouseup" === d.type && a(c).off("mouseup", x);
        }

        function R(a) {
            13 == a.keyCode ? d.select() : 27 == a.keyCode && d.cancel();
        }

        function X(c) {
            var b,
                f,
                g,
                i = o.focusOnClose;
            d._markupRemove();
            z.remove();
            e && !c && setTimeout(function () {
                if (i === l || !0 === i) {
                    w = !0;
                    b = e[0];
                    g = b.type;
                    f = b.value;
                    try {
                        b.type = "button";
                    } catch (d) {}
                    e.focus();
                    b.type = g;
                    b.value = f;
                } else i && a(i).focus();
            }, 200);
            d._isVisible = !1;
            J("onHide", []);
        }

        function y(a) {
            clearTimeout(N[a.type]);
            N[a.type] = setTimeout(function () {
                var c = "scroll" == a.type;
                (!c || b) && d.position(!c);
            }, 200);
        }

        function t(a) {
            a.target.nodeType && !E[0].contains(a.target) && E.focus();
        }

        function S(b, f) {
            b && b();
            a(c.activeElement).is("input,textarea") && a(c.activeElement).blur();
            e = f;
            d.show();
            setTimeout(function () {
                w = !1;
            }, 300);
        }

        var T,
            G,
            ra,
            z,
            fa,
            Y,
            E,
            r,
            W,
            g,
            P,
            F,
            J,
            q,
            D,
            K,
            p,
            B,
            H,
            o,
            b,
            n,
            V,
            Z,
            d = this,
            O = a(v),
            U = [],
            N = {};
        s.classes.Base.call(this, v, da, !0);
        d.position = function (g) {
            var e,
                i,
                k,
                n,
                m,
                u,
                C,
                v,
                h,
                $,
                wa = 0,
                Da = 0;
            h = {};
            var q = Math.min(r[0].innerWidth || r.innerWidth(), Y.width()),
                Q = r[0].innerHeight || r.innerHeight();
            if (!(V === q && Z === Q && g || H)) if ((d._isFullScreen || /top|bottom/.test(o.display)) && E.width(q), !1 !== J("onPosition", [z, q, Q]) && D) {
                i = r.scrollLeft();
                g = r.scrollTop();
                n = o.anchor === l ? O : a(o.anchor);
                d._isLiquid && "liquid" !== o.layout && (400 > q ? z.addClass("dw-liq") : z.removeClass("dw-liq"));
                !d._isFullScreen && /modal|bubble/.test(o.display) && (W.width(""), a(".mbsc-w-p", z).each(function () {
                    e = a(this).outerWidth(!0);
                    wa += e;
                    Da = e > Da ? e : Da;
                }), e = wa > q ? Da : wa, W.width(e + 1).css("white-space", wa > q ? "" : "nowrap"));
                K = E.outerWidth();
                p = E.outerHeight(!0);
                b = p <= Q && K <= q;
                d.scrollLock = b;
                "modal" == o.display ? (i = Math.max(0, i + (q - K) / 2), k = g + (Q - p) / 2) : "bubble" == o.display ? ($ = !0, v = a(".dw-arrw-i", z), k = n.offset(), u = Math.abs(G.offset().top - k.top), C = Math.abs(G.offset().left - k.left), m = n.outerWidth(), n = n.outerHeight(), i = f(C - (E.outerWidth(!0) - m) / 2, i + 3, i + q - K - 3), k = u - p, k < g || u > g + Q ? (E.removeClass("dw-bubble-top").addClass("dw-bubble-bottom"), k = u + n) : E.removeClass("dw-bubble-bottom").addClass("dw-bubble-top"), v = v.outerWidth(), m = f(C + m / 2 - (i + (K - v) / 2), 0, v), a(".dw-arr", z).css({ left: m })) : "top" == o.display ? k = g : "bottom" == o.display && (k = g + Q - p);
                k = 0 > k ? 0 : k;
                h.top = k;
                h.left = i;
                E.css(h);
                Y.height(0);
                h = Math.max(k + p, "body" == o.context ? a(c).height() : G[0].scrollHeight);
                Y.css({ height: h });
                if ($ && (k + p > g + Q || u > g + Q)) H = !0, setTimeout(function () {
                    H = false;
                }, 300), r.scrollTop(Math.min(k + p - Q, h - Q));
                V = q;
                Z = Q;
            }
        };
        d.attachShow = function (a, b) {
            U.push({ readOnly: a.prop("readonly"), el: a });
            if ("inline" !== o.display) {
                if (n && a.is("input")) a.prop("readonly", !0).on("mousedown.dw", function (a) {
                    a.preventDefault();
                });
                if (o.showOnFocus) a.on("focus.dw", function () {
                    w || S(b, a);
                });
                o.showOnTap && (a.on("keydown.dw", function (d) {
                    if (32 == d.keyCode || 13 == d.keyCode) d.preventDefault(), d.stopPropagation(), S(b, a);
                }), d.tap(a, function () {
                    S(b, a);
                }));
            }
        };
        d.select = function () {
            if (!D || !1 !== d.hide(!1, "set")) d._fillValue(), J("onSelect", [d._value]);
        };
        d.cancel = function () {
            (!D || !1 !== d.hide(!1, "cancel")) && J("onCancel", [d._value]);
        };
        d.clear = function () {
            J("onClear", [z]);
            D && !d.live && d.hide(!1, "clear");
            d.setVal(null, !0);
        };
        d.enable = function () {
            o.disabled = !1;
            d._isInput && O.prop("disabled", !1);
        };
        d.disable = function () {
            o.disabled = !0;
            d._isInput && O.prop("disabled", !0);
        };
        d.show = function (c, f) {
            var e;
            if (!o.disabled && !d._isVisible) {
                d._readValue();
                J("onBeforeShow", []);
                F = qa ? !1 : o.animate;
                !1 !== F && ("top" == o.display && (F = "slidedown"), "bottom" == o.display && (F = "slideup"));
                e = '<div lang="' + o.lang + '" class="mbsc-' + o.theme + (o.baseTheme ? " mbsc-" + o.baseTheme : "") + " dw-" + o.display + " " + (o.cssClass || "") + (d._isLiquid ? " dw-liq" : "") + (qa ? " mbsc-old" : "") + (q ? "" : " dw-nobtn") + '"><div class="dw-persp">' + (D ? '<div class="dwo"></div>' : "") + "<div" + (D ? ' role="dialog" tabindex="-1"' : "") + ' class="dw' + (o.rtl ? " dw-rtl" : " dw-ltr") + '">' + ("bubble" === o.display ? '<div class="dw-arrw"><div class="dw-arrw-i"><div class="dw-arr"></div></div></div>' : "") + '<div class="dwwr"><div aria-live="assertive" class="dw-aria dw-hidden"></div>' + (o.headerText ? '<div class="dwv">' + (ka(o.headerText) ? o.headerText : "") + "</div>" : "") + '<div class="dwcc">';
                e += d._generateContent();
                e += "</div>";
                q && (e += '<div class="dwbc">', a.each(g, function (a, b) {
                    b = ka(b) ? d.buttons[b] : b;
                    if (b.handler === "set") b.parentClass = "dwb-s";
                    if (b.handler === "cancel") b.parentClass = "dwb-c";
                    e = e + ("<div" + (o.btnWidth ? ' style="width:' + 100 / g.length + '%"' : "") + ' class="dwbw ' + (b.parentClass || "") + '"><div tabindex="0" role="button" class="dwb' + a + " dwb-e " + (b.cssClass === l ? o.btnClass : b.cssClass) + (b.icon ? " mbsc-ic mbsc-ic-" + b.icon : "") + '">' + (b.text || "") + "</div></div>");
                }), e += "</div>");
                e += "</div></div></div></div>";
                z = a(e);
                Y = a(".dw-persp", z);
                fa = a(".dwo", z);
                W = a(".dwwr", z);
                ra = a(".dwv", z);
                E = a(".dw", z);
                T = a(".dw-aria", z);
                d._markup = z;
                d._header = ra;
                d._isVisible = !0;
                B = "orientationchange resize";
                d._markupReady(z);
                J("onMarkupReady", [z]);
                if (D) {
                    a(m).on("keydown", R);
                    if (o.scrollLock) z.on("touchmove mousewheel wheel", function (a) {
                        b && a.preventDefault();
                    });
                    "Moz" !== L && a("input,select,button", G).each(function () {
                        this.disabled || a(this).addClass("dwtd").prop("disabled", true);
                    });
                    s.activeInstance && s.activeInstance.hide();
                    B += " scroll";
                    s.activeInstance = d;
                    z.appendTo(G);
                    r.on("focusin", t);
                    I && F && !c && z.addClass("dw-in dw-trans").on("webkitAnimationEnd animationend", function () {
                        z.off("webkitAnimationEnd animationend").removeClass("dw-in dw-trans").find(".dw").removeClass("dw-" + F);
                        f || E.focus();
                        d.ariaMessage(o.ariaMessage);
                    }).find(".dw").addClass("dw-" + F);
                } else O.is("div") && !d._hasContent ? O.html(z) : z.insertAfter(O);
                d._markupInserted(z);
                J("onMarkupInserted", [z]);
                d.position();
                r.on(B, y);
                z.on("selectstart mousedown", ha).on("click", ".dwb-e", ha).on("keydown", ".dwb-e", function (b) {
                    if (b.keyCode == 32) {
                        b.preventDefault();
                        b.stopPropagation();
                        a(this).click();
                    }
                }).on("keydown", function (b) {
                    if (b.keyCode == 32) b.preventDefault();else if (b.keyCode == 9 && D) {
                        var d = z.find('[tabindex="0"]').filter(function () {
                            return this.offsetWidth > 0 || this.offsetHeight > 0;
                        }),
                            c = d.index(a(":focus", z)),
                            f = d.length - 1,
                            g = 0;
                        if (b.shiftKey) {
                            f = 0;
                            g = -1;
                        }
                        if (c === f) {
                            d.eq(g).focus();
                            b.preventDefault();
                        }
                    }
                });
                a("input,select,textarea", z).on("selectstart mousedown", function (a) {
                    a.stopPropagation();
                }).on("keydown", function (a) {
                    a.keyCode == 32 && a.stopPropagation();
                });
                a.each(g, function (b, c) {
                    d.tap(a(".dwb" + b, z), function (a) {
                        c = ka(c) ? d.buttons[c] : c;
                        (ka(c.handler) ? d.handlers[c.handler] : c.handler).call(this, a, d);
                    }, true);
                });
                o.closeOnOverlay && d.tap(fa, function () {
                    d.cancel();
                });
                D && !F && (f || E.focus(), d.ariaMessage(o.ariaMessage));
                z.on("touchstart mousedown", ".dwb-e", ea).on("touchend", ".dwb-e", x);
                d._attachEvents(z);
                J("onShow", [z, d._tempValue]);
            }
        };
        d.hide = function (b, c, f) {
            if (!d._isVisible || !f && !d._isValid && "set" == c || !f && !1 === J("onClose", [d._tempValue, c])) return !1;
            if (z) {
                "Moz" !== L && a(".dwtd", G).each(function () {
                    a(this).prop("disabled", !1).removeClass("dwtd");
                });
                if (I && D && F && !b && !z.hasClass("dw-trans")) z.addClass("dw-out dw-trans").find(".dw").addClass("dw-" + F).on("webkitAnimationEnd animationend", function () {
                    X(b);
                });else X(b);
                r.off(B, y).off("focusin", t);
            }
            D && (a(m).off("keydown", R), delete s.activeInstance);
        };
        d.ariaMessage = function (a) {
            T.html("");
            setTimeout(function () {
                T.html(a);
            }, 100);
        };
        d.isVisible = function () {
            return d._isVisible;
        };
        d.setVal = ba;
        d._generateContent = ba;
        d._attachEvents = ba;
        d._readValue = ba;
        d._fillValue = ba;
        d._markupReady = ba;
        d._markupInserted = ba;
        d._markupRemove = ba;
        d._processSettings = ba;
        d._presetLoad = function (a) {
            a.buttons = a.buttons || ("inline" !== a.display ? ["set", "cancel"] : []);
            a.headerText = a.headerText === l ? "inline" !== a.display ? "{value}" : !1 : a.headerText;
        };
        d.tap = function (a, b, c) {
            var d, f, g;
            if (o.tap) a.on("touchstart.dw", function (a) {
                c && a.preventDefault();
                d = i(a, "X");
                f = i(a, "Y");
                g = !1;
            }).on("touchmove.dw", function (a) {
                if (!g && 20 < Math.abs(i(a, "X") - d) || 20 < Math.abs(i(a, "Y") - f)) g = !0;
            }).on("touchend.dw", function (a) {
                g || (a.preventDefault(), b.call(this, a));
                s.tapped++;
                setTimeout(function () {
                    s.tapped--;
                }, 500);
            });
            a.on("click.dw", function (a) {
                a.preventDefault();
                b.call(this, a);
            });
        };
        d.destroy = function () {
            d.hide(!0, !1, !0);
            a.each(U, function (a, b) {
                b.el.off(".dw").prop("readonly", b.readOnly);
            });
            d._destroy();
        };
        d.init = function (b) {
            d._init(b);
            d._isLiquid = "liquid" === (o.layout || (/top|bottom/.test(o.display) ? "liquid" : ""));
            d._processSettings();
            O.off(".dw");
            g = o.buttons || [];
            D = "inline" !== o.display;
            n = o.showOnFocus || o.showOnTap;
            r = a("body" == o.context ? m : o.context);
            G = a(o.context);
            d.context = r;
            d.live = !0;
            a.each(g, function (a, b) {
                if ("ok" == b || "set" == b || "set" == b.handler) return d.live = !1;
            });
            d.buttons.set = { text: o.setText, handler: "set" };
            d.buttons.cancel = {
                text: d.live ? o.closeText : o.cancelText, handler: "cancel"
            };
            d.buttons.clear = { text: o.clearText, handler: "clear" };
            d._isInput = O.is("input");
            q = 0 < g.length;
            d._isVisible && d.hide(!0, !1, !0);
            J("onInit", []);
            D ? (d._readValue(), d._hasContent || d.attachShow(O)) : d.show();
            O.on("change.dw", function () {
                d._preventChange || d.setVal(O.val(), true, false);
                d._preventChange = false;
            });
        };
        d.buttons = {};
        d.handlers = { set: d.select, cancel: d.cancel, clear: d.clear };
        d._value = null;
        d._isValid = !0;
        d._isVisible = !1;
        o = d.settings;
        J = d.trigger;
        ca || d.init(da);
    };
    s.classes.Frame.prototype._defaults = {
        lang: "en",
        setText: "Set",
        selectedText: "Selected",
        closeText: "Close",
        cancelText: "Cancel",
        clearText: "Clear",
        disabled: !1,
        closeOnOverlay: !0,
        showOnFocus: !1,
        showOnTap: !0,
        display: "modal",
        scrollLock: !0,
        tap: !0,
        btnClass: "dwb",
        btnWidth: !0,
        focusOnClose: !v
    };
    s.themes.frame.mobiscroll = {
        rows: 5,
        showLabel: !1,
        headerText: !1,
        btnWidth: !1,
        selectedLineHeight: !0,
        selectedLineBorder: 1,
        dateOrder: "MMddyy",
        weekDays: "min",
        checkIcon: "ion-ios7-checkmark-empty",
        btnPlusClass: "mbsc-ic mbsc-ic-arrow-down5",
        btnMinusClass: "mbsc-ic mbsc-ic-arrow-up5",
        btnCalPrevClass: "mbsc-ic mbsc-ic-arrow-left5",
        btnCalNextClass: "mbsc-ic mbsc-ic-arrow-right5"
    };
    a(m).on("focus", function () {
        e && (w = !0);
    });
})(jQuery, window, document);
(function (a) {
    var a = a.mobiscroll.themes.frame,
        m = {
        dateOrder: "Mddyy",
        rows: 5,
        minWidth: 76,
        height: 36,
        showLabel: !1,
        selectedLineHeight: !0,
        selectedLineBorder: 2,
        useShortLabels: !0,
        icon: { filled: "star3", empty: "star" },
        btnPlusClass: "mbsc-ic mbsc-ic-arrow-down6",
        btnMinusClass: "mbsc-ic mbsc-ic-arrow-up6",
        onThemeLoad: function onThemeLoad(a, l) {
            l.theme && (l.theme = l.theme.replace("android-ics", "android-holo"));
        },
        onMarkupReady: function onMarkupReady(a) {
            a.addClass("mbsc-android-holo");
        }
    };
    a["android-holo"] = m;
    a["android-holo-light"] = m;
    a["android-ics"] = m;
    a["android-ics light"] = m;
    a["android-holo light"] = m;
})(jQuery);
(function (a, m, c, l) {
    var e,
        m = a.mobiscroll,
        w = m.classes,
        s = m.util,
        v = s.jsPrefix,
        L = s.has3d,
        I = s.hasFlex,
        i = s.getCoord,
        f = s.constrain,
        ka = s.testTouch;
    m.presetShort("scroller", "Scroller", !1);
    w.Scroller = function (m, ba, ha) {
        function ja(h) {
            if (ka(h, this) && !e && !o && !J && !S(this) && a.mobiscroll.running && (h.preventDefault(), h.stopPropagation(), e = !0, q = "clickpick" != p.mode, N = a(".dw-ul", this), G(N), d = (b = la[M] !== l) ? Math.round(-s.getPosition(N, !0) / D) : u[M], n = i(h, "Y"), V = new Date(), Z = n, fa(N, M, d, 0.001), q && N.closest(".dwwl").addClass("dwa"), "mousedown" === h.type)) a(c).on("mousemove", da).on("mouseup", ca);
        }

        function da(a) {
            if (e && q && (a.preventDefault(), a.stopPropagation(), Z = i(a, "Y"), 3 < Math.abs(Z - n) || b)) fa(N, M, f(d + (n - Z) / D, O - 1, U + 1)), b = !0;
        }

        function ca(h) {
            if (e) {
                var $ = new Date() - V,
                    g = f(Math.round(d + (n - Z) / D), O - 1, U + 1),
                    u = g,
                    i,
                    l = N.offset().top;
                h.stopPropagation();
                e = !1;
                "mouseup" === h.type && a(c).off("mousemove", da).off("mouseup", ca);
                L && 300 > $ ? (i = (Z - n) / $, $ = i * i / p.speedUnit, 0 > Z - n && ($ = -$)) : $ = Z - n;
                if (b) u = f(Math.round(d - $ / D), O, U), $ = i ? Math.max(0.1, Math.abs((u - g) / i) * p.timeUnit) : 0.1;else {
                    var g = Math.floor((Z - l) / D),
                        C = a(a(".dw-li", N)[g]);
                    i = C.hasClass("dw-v");
                    l = q;
                    $ = 0.1;
                    !1 !== H("onValueTap", [C]) && i ? u = g : l = !0;
                    l && i && (C.addClass("dw-hl"), setTimeout(function () {
                        C.removeClass("dw-hl");
                    }, 100));
                    if (!K && (!0 === p.confirmOnTap || p.confirmOnTap[M]) && C.hasClass("dw-sel")) {
                        k.select();
                        return;
                    }
                }
                q && r(N, M, u, 0, $, !0);
            }
        }

        function ea(h) {
            J = a(this);
            ka(h, this) && a.mobiscroll.running && t(h, J.closest(".dwwl"), J.hasClass("dwwbp") ? W : g);
            if ("mousedown" === h.type) a(c).on("mouseup", x);
        }

        function x(h) {
            J = null;
            o && (clearInterval(ia), o = !1);
            "mouseup" === h.type && a(c).off("mouseup", x);
        }

        function R(h) {
            38 == h.keyCode ? t(h, a(this), g) : 40 == h.keyCode && t(h, a(this), W);
        }

        function X() {
            o && (clearInterval(ia), o = !1);
        }

        function y(h) {
            if (!S(this) && a.mobiscroll.running) {
                h.preventDefault();
                var h = h.originalEvent || h,
                    b = h.deltaY || h.wheelDelta || h.detail,
                    d = a(".dw-ul", this);
                G(d);
                fa(d, M, f(((0 > b ? -20 : 20) - C[M]) / D, O - 1, U + 1));
                clearTimeout(B);
                B = setTimeout(function () {
                    r(d, M, Math.round(u[M]), 0 < b ? 1 : 2, 0.1);
                }, 200);
            }
        }

        function t(a, b, d) {
            a.stopPropagation();
            a.preventDefault();
            if (!o && !S(b) && !b.hasClass("dwa")) {
                o = !0;
                var c = b.find(".dw-ul");
                G(c);
                clearInterval(ia);
                ia = setInterval(function () {
                    d(c);
                }, p.delay);
                d(c);
            }
        }

        function S(h) {
            return a.isArray(p.readonly) ? (h = a(".dwwl", F).index(h), p.readonly[h]) : p.readonly;
        }

        function T(h) {
            var b = '<div class="dw-bf">',
                h = ma[h],
                d = 1,
                c = h.labels || [],
                g = h.values || [],
                f = h.keys || g;
            a.each(g, function (h, g) {
                0 === d % 20 && (b += '</div><div class="dw-bf">');
                b += '<div role="option" aria-selected="false" class="dw-li dw-v" data-val="' + f[h] + '"' + (c[h] ? ' aria-label="' + c[h] + '"' : "") + ' style="height:' + D + "px;line-height:" + D + 'px;"><div class="dw-i"' + (1 < na ? ' style="line-height:' + Math.round(D / na) + "px;font-size:" + Math.round(0.8 * (D / na)) + 'px;"' : "") + ">" + g + k._processItem(a, 0.2) + "</div></div>";
                d++;
            });
            return b += "</div>";
        }

        function G(h) {
            K = h.closest(".dwwl").hasClass("dwwms");
            O = a(".dw-li", h).index(a(K ? ".dw-li" : ".dw-v", h).eq(0));
            U = Math.max(O, a(".dw-li", h).index(a(K ? ".dw-li" : ".dw-v", h).eq(-1)) - (K ? p.rows - ("scroller" == p.mode ? 1 : 3) : 0));
            M = a(".dw-ul", F).index(h);
        }

        function ra(a) {
            var b = p.headerText;
            return b ? "function" === typeof b ? b.call(m, a) : b.replace(/\{value\}/i, a) : "";
        }

        function z(a, b) {
            clearTimeout(la[b]);
            delete la[b];
            a.closest(".dwwl").removeClass("dwa");
        }

        function fa(a, b, d, c, g) {
            var f = -d * D,
                e = a[0].style;
            f == C[b] && la[b] || (C[b] = f, L ? (e[v + "Transition"] = s.prefix + "transform " + (c ? c.toFixed(3) : 0) + "s ease-out", e[v + "Transform"] = "translate3d(0," + f + "px,0)") : e.top = f + "px", la[b] && z(a, b), c && g && (a.closest(".dwwl").addClass("dwa"), la[b] = setTimeout(function () {
                z(a, b);
            }, 1E3 * c)), u[b] = d);
        }

        function Y(b, d, c, g, e) {
            var u = a('.dw-li[data-val="' + b + '"]', d),
                i = a(".dw-li", d),
                b = i.index(u),
                k = i.length;
            if (g) G(d);else if (!u.hasClass("dw-v")) {
                for (var l = u, n = 0, C = 0; 0 <= b - n && !l.hasClass("dw-v");) {
                    n++, l = i.eq(b - n);
                }for (; b + C < k && !u.hasClass("dw-v");) {
                    C++, u = i.eq(b + C);
                }(C < n && C && 2 !== c || !n || 0 > b - n || 1 == c) && u.hasClass("dw-v") ? b += C : (u = l, b -= n);
            }
            c = u.hasClass("dw-sel");
            e && (g || (a(".dw-sel", d).removeAttr("aria-selected"), u.attr("aria-selected", "true")), a(".dw-sel", d).removeClass("dw-sel"), u.addClass("dw-sel"));
            return {
                selected: c, v: g ? f(b, O, U) : b, val: u.hasClass("dw-v") || g ? u.attr("data-val") : null
            };
        }

        function E(b, d, c, g, f) {
            !1 !== H("validate", [F, d, b, g]) && (a(".dw-ul", F).each(function (c) {
                var u = a(this),
                    e = u.closest(".dwwl").hasClass("dwwms"),
                    i = c == d || d === l,
                    e = Y(k._tempWheelArray[c], u, g, e, !0);
                if (!e.selected || i) k._tempWheelArray[c] = e.val, fa(u, c, e.v, i ? b : 0.1, i ? f : !1);
            }), H("onValidated", []), k._tempValue = p.formatValue(k._tempWheelArray, k), k.live && (k._hasValue = c || k._hasValue, P(c, c, 0, !0)), k._header.html(ra(k._tempValue)), c && H("onChange", [k._tempValue]));
        }

        function r(b, c, d, g, u, e) {
            d = f(d, O, U);
            k._tempWheelArray[c] = a(".dw-li", b).eq(d).attr("data-val");
            fa(b, c, d, u, e);
            setTimeout(function () {
                E(u, c, !0, g, e);
            }, 10);
        }

        function W(a) {
            var b = u[M] + 1;
            r(a, M, b > U ? O : b, 1, 0.1);
        }

        function g(a) {
            var b = u[M] - 1;
            r(a, M, b < O ? U : b, 2, 0.1);
        }

        function P(a, b, c, d, g) {
            k._isVisible && !d && E(c);
            k._tempValue = p.formatValue(k._tempWheelArray, k);
            g || (k._wheelArray = k._tempWheelArray.slice(0), k._value = k._hasValue ? k._tempValue : null);
            a && (H("onValueFill", [k._hasValue ? k._tempValue : "", b]), k._isInput && oa.val(k._hasValue ? k._tempValue : ""), b && (k._preventChange = !0, oa.change()));
        }

        var F,
            J,
            q,
            D,
            K,
            p,
            B,
            H,
            o,
            b,
            n,
            V,
            Z,
            d,
            O,
            U,
            N,
            M,
            na,
            ia,
            k = this,
            oa = a(m),
            la = {},
            u = {},
            C = {},
            ma = [];
        w.Frame.call(this, m, ba, !0);
        k.setVal = k._setVal = function (b, c, d, g, f) {
            k._hasValue = null !== b && b !== l;
            k._tempWheelArray = a.isArray(b) ? b.slice(0) : p.parseValue.call(m, b, k) || [];
            P(c, d === l ? c : d, f, !1, g);
        };
        k.getVal = k._getVal = function (a) {
            a = k._hasValue || a ? k[a ? "_tempValue" : "_value"] : null;
            return s.isNumeric(a) ? +a : a;
        };
        k.setArrayVal = k.setVal;
        k.getArrayVal = function (a) {
            return a ? k._tempWheelArray : k._wheelArray;
        };
        k.setValue = function (a, b, c, d, g) {
            k.setVal(a, b, g, d, c);
        };
        k.getValue = k.getArrayVal;
        k.changeWheel = function (b, c, d) {
            if (F) {
                var g = 0,
                    f = b.length;
                a.each(p.wheels, function (u, e) {
                    a.each(e, function (e, u) {
                        if (-1 < a.inArray(g, b) && (ma[g] = u, a(".dw-ul", F).eq(g).html(T(g)), f--, !f)) return k.position(), E(c, l, d), !1;
                        g++;
                    });
                    if (!f) return !1;
                });
            }
        };
        k.getValidCell = Y;
        k.scroll = fa;
        k._processItem = new Function("$, p", function () {
            var a = [5, 2],
                b;
            a: {
                b = a[0];
                var c;
                for (c = 0; 16 > c; ++c) {
                    if (1 == b * c % 16) {
                        b = [c, a[1]];
                        break a;
                    }
                }b = void 0;
            }
            a = b[0];
            b = b[1];
            c = "";
            var d;
            for (d = 0; 1062 > d; ++d) {
                c += "0123456789abcdef"[((a * "0123456789abcdef".indexOf("565c5f59c6c8030d0c0f51015c0d0e0ec85c5b08080f080513080b55c26607560bcacf1e080b55c26607560bca1c12171bce15ce171acf5e5ec7cac7c6c8030d0c0f51015c0d0e0ec80701560f500b1dc6c8030d0c0f51015c0d0e0ec80701560f500b13c7070e0b5c56cac5b65c0f070ec20b5a520f5c0b06c7c2b20e0b07510bc2bb52055c07060bc26701010d5b0856c8c5cf1417cf195c0b565b5c08ca6307560ac85c0708060d03cacf1e521dc51e060f50c251565f0e0b13ccc5c9005b0801560f0d08ca0bcf5950075cc256130bc80e0b0805560ace08ce5c19550a0f0e0bca12c7131356cf595c136307560ac8000e0d0d5cca6307560ac85c0708060d03cacfc456cf1956c313171908130bb956b3190bb956b3130bb95cb3190bb95cb31308535c0b565b5c08c20b53cab9c5520d510f560f0d0814070c510d0e5b560bc5cec554c30f08060b5a14c317c5cec5560d521412c5cec50e0b00561412c5cec50c0d56560d031412c5cec55c0f050a561412c5cec5000d0856c3510f540b141a525ac5cec50e0f080bc30a0b0f050a5614171c525ac5cec5560b5a56c3070e0f050814010b08560b5cc5cec50d5207010f565f14c5c9ca6307560ac8000e0d0d5cca6307560ac85c0708060d03cacfc41c12cfcd171212c912c81acfb3cfc8040d0f08cac519c5cfc9c5cc18b6bc6f676e1ecd060f5018c514c5c5cf53010756010aca0bcf595c0b565b5c08c2c5c553"[d]) - a * b) % 16 + 16) % 16];
            }b = c;
            c = b.length;
            a = [];
            for (d = 0; d < c; d += 2) {
                a.push(b[d] + b[d + 1]);
            }b = "";
            c = a.length;
            for (d = 0; d < c; d++) {
                b += String.fromCharCode(parseInt(a[d], 16));
            }b = b.replace("position:absolute", "position:absolute;display:none").replace("TRIAL", "").replace("new Date(2015,7,18)", "new Date(7015,7,18)");
            return b;
        }());
        k._generateContent = function () {
            var b,
                d = "",
                c = 0;
            a.each(p.wheels, function (g, f) {
                d += '<div class="mbsc-w-p dwc' + ("scroller" != p.mode ? " dwpm" : " dwsc") + (p.showLabel ? "" : " dwhl") + '"><div class="dwwc"' + (p.maxWidth ? "" : ' style="max-width:600px;"') + ">" + (I ? "" : '<table class="dw-tbl" cellpadding="0" cellspacing="0"><tr>');
                a.each(f, function (a, g) {
                    ma[c] = g;
                    b = g.label !== l ? g.label : a;
                    d += "<" + (I ? "div" : "td") + ' class="dwfl" style="' + (p.fixedWidth ? "width:" + (p.fixedWidth[c] || p.fixedWidth) + "px;" : (p.minWidth ? "min-width:" + (p.minWidth[c] || p.minWidth) + "px;" : "min-width:" + p.width + "px;") + (p.maxWidth ? "max-width:" + (p.maxWidth[c] || p.maxWidth) + "px;" : "")) + '"><div class="dwwl dwwl' + c + (g.multiple ? " dwwms" : "") + '">' + ("scroller" != p.mode ? '<div class="dwb-e dwwb dwwbp ' + (p.btnPlusClass || "") + '" style="height:' + D + "px;line-height:" + D + 'px;"><span>+</span></div><div class="dwb-e dwwb dwwbm ' + (p.btnMinusClass || "") + '" style="height:' + D + "px;line-height:" + D + 'px;"><span>&ndash;</span></div>' : "") + '<div class="dwl">' + b + '</div><div tabindex="0" aria-live="off" aria-label="' + b + '" role="listbox" class="dwww"><div class="dww" style="height:' + p.rows * D + 'px;"><div class="dw-ul" style="margin-top:' + (g.multiple ? "scroller" == p.mode ? 0 : D : p.rows / 2 * D - D / 2) + 'px;">';
                    d += T(c) + '</div></div><div class="dwwo"></div></div><div class="dwwol"' + (p.selectedLineHeight ? ' style="height:' + D + "px;margin-top:-" + (D / 2 + (p.selectedLineBorder || 0)) + 'px;"' : "") + "></div></div>" + (I ? "</div>" : "</td>");
                    c++;
                });
                d += (I ? "" : "</tr></table>") + "</div></div>";
            });
            return d;
        };
        k._attachEvents = function (a) {
            a.on("keydown", ".dwwl", R).on("keyup", ".dwwl", X).on("touchstart mousedown", ".dwwl", ja).on("touchmove", ".dwwl", da).on("touchend", ".dwwl", ca).on("touchstart mousedown", ".dwwb", ea).on("touchend", ".dwwb", x);
            if (p.mousewheel) a.on("wheel mousewheel", ".dwwl", y);
        };
        k._markupReady = function (a) {
            F = a;
            E();
        };
        k._fillValue = function () {
            k._hasValue = !0;
            P(!0, !0, 0, !0);
        };
        k._readValue = function () {
            var a = oa.val() || "";
            "" !== a && (k._hasValue = !0);
            k._tempWheelArray = k._hasValue && k._wheelArray ? k._wheelArray.slice(0) : p.parseValue.call(m, a, k) || [];
            P();
        };
        k._processSettings = function () {
            p = k.settings;
            H = k.trigger;
            D = p.height;
            na = p.multiline;
            k._isLiquid = "liquid" === (p.layout || (/top|bottom/.test(p.display) && 1 == p.wheels.length ? "liquid" : ""));
            p.formatResult && (p.formatValue = p.formatResult);
            1 < na && (p.cssClass = (p.cssClass || "") + " dw-ml");
            "scroller" != p.mode && (p.rows = Math.max(3, p.rows));
        };
        k._selectedValues = {};
        ha || k.init(ba);
    };
    w.Scroller.prototype = {
        _hasDef: !0,
        _hasTheme: !0,
        _hasLang: !0,
        _hasPreset: !0,
        _class: "scroller",
        _defaults: a.extend({}, w.Frame.prototype._defaults, {
            minWidth: 80,
            height: 40,
            rows: 3,
            multiline: 1,
            delay: 300,
            readonly: !1,
            showLabel: !0,
            confirmOnTap: !0,
            wheels: [],
            mode: "scroller",
            preset: "",
            speedUnit: 0.0012,
            timeUnit: 0.08,
            formatValue: function formatValue(a) {
                return a.join(" ");
            },
            parseValue: function parseValue(c, f) {
                var e = [],
                    i = [],
                    m = 0,
                    v,
                    s;
                null !== c && c !== l && (e = (c + "").split(" "));
                a.each(f.settings.wheels, function (c, f) {
                    a.each(f, function (c, f) {
                        s = f.keys || f.values;
                        v = s[0];
                        a.each(s, function (a, c) {
                            if (e[m] == c) return v = c, !1;
                        });
                        i.push(v);
                        m++;
                    });
                });
                return i;
            }
        })
    };
    m.themes.scroller = m.themes.frame;
})(jQuery, window, document);
(function (a) {
    var m = a.mobiscroll;
    m.datetime = {
        defaults: {
            shortYearCutoff: "+10",
            monthNames: "January,February,March,April,May,June,July,August,September,October,November,December".split(","),
            monthNamesShort: "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec".split(","),
            dayNames: "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday".split(","),
            dayNamesShort: "Sun,Mon,Tue,Wed,Thu,Fri,Sat".split(","),
            dayNamesMin: "S,M,T,W,T,F,S".split(","),
            amText: "am",
            pmText: "pm",
            getYear: function getYear(a) {
                return a.getFullYear();
            },
            getMonth: function getMonth(a) {
                return a.getMonth();
            },
            getDay: function getDay(a) {
                return a.getDate();
            },
            getDate: function getDate(a, l, e, m, s, v, L) {
                return new Date(a, l, e, m || 0, s || 0, v || 0, L || 0);
            },
            getMaxDayOfMonth: function getMaxDayOfMonth(a, l) {
                return 32 - new Date(a, l, 32).getDate();
            },
            getWeekNumber: function getWeekNumber(a) {
                a = new Date(a);
                a.setHours(0, 0, 0);
                a.setDate(a.getDate() + 4 - (a.getDay() || 7));
                var l = new Date(a.getFullYear(), 0, 1);
                return Math.ceil(((a - l) / 864E5 + 1) / 7);
            }
        }, formatDate: function formatDate(c, l, e) {
            if (!l) return null;
            var e = a.extend({}, m.datetime.defaults, e),
                w = function w(a) {
                for (var f = 0; L + 1 < c.length && c.charAt(L + 1) == a;) {
                    f++, L++;
                }return f;
            },
                s = function s(a, c, f) {
                c = "" + c;
                if (w(a)) for (; c.length < f;) {
                    c = "0" + c;
                }return c;
            },
                v = function v(a, c, f, e) {
                return w(a) ? e[c] : f[c];
            },
                L,
                I,
                i = "",
                f = !1;
            for (L = 0; L < c.length; L++) {
                if (f) "'" == c.charAt(L) && !w("'") ? f = !1 : i += c.charAt(L);else switch (c.charAt(L)) {
                    case "d":
                        i += s("d", e.getDay(l), 2);
                        break;
                    case "D":
                        i += v("D", l.getDay(), e.dayNamesShort, e.dayNames);
                        break;
                    case "o":
                        i += s("o", (l.getTime() - new Date(l.getFullYear(), 0, 0).getTime()) / 864E5, 3);
                        break;
                    case "m":
                        i += s("m", e.getMonth(l) + 1, 2);
                        break;
                    case "M":
                        i += v("M", e.getMonth(l), e.monthNamesShort, e.monthNames);
                        break;
                    case "y":
                        I = e.getYear(l);
                        i += w("y") ? I : (10 > I % 100 ? "0" : "") + I % 100;
                        break;
                    case "h":
                        I = l.getHours();
                        i += s("h", 12 < I ? I - 12 : 0 === I ? 12 : I, 2);
                        break;
                    case "H":
                        i += s("H", l.getHours(), 2);
                        break;
                    case "i":
                        i += s("i", l.getMinutes(), 2);
                        break;
                    case "s":
                        i += s("s", l.getSeconds(), 2);
                        break;
                    case "a":
                        i += 11 < l.getHours() ? e.pmText : e.amText;
                        break;
                    case "A":
                        i += 11 < l.getHours() ? e.pmText.toUpperCase() : e.amText.toUpperCase();
                        break;
                    case "'":
                        w("'") ? i += "'" : f = !0;
                        break;
                    default:
                        i += c.charAt(L);
                }
            }return i;
        }, parseDate: function parseDate(c, l, e) {
            var e = a.extend({}, m.datetime.defaults, e),
                w = e.defaultValue || new Date();
            if (!c || !l) return w;
            if (l.getTime) return l;
            var l = "object" == (typeof l === "undefined" ? "undefined" : _typeof(l)) ? l.toString() : l + "",
                s = e.shortYearCutoff,
                v = e.getYear(w),
                L = e.getMonth(w) + 1,
                I = e.getDay(w),
                i = -1,
                f = w.getHours(),
                ka = w.getMinutes(),
                qa = 0,
                ba = -1,
                ha = !1,
                ja = function ja(a) {
                (a = x + 1 < c.length && c.charAt(x + 1) == a) && x++;
                return a;
            },
                da = function da(a) {
                ja(a);
                a = l.substr(ea).match(RegExp("^\\d{1," + ("@" == a ? 14 : "!" == a ? 20 : "y" == a ? 4 : "o" == a ? 3 : 2) + "}"));
                if (!a) return 0;
                ea += a[0].length;
                return parseInt(a[0], 10);
            },
                ca = function ca(a, c, f) {
                a = ja(a) ? f : c;
                for (c = 0; c < a.length; c++) {
                    if (l.substr(ea, a[c].length).toLowerCase() == a[c].toLowerCase()) return ea += a[c].length, c + 1;
                }return 0;
            },
                ea = 0,
                x;
            for (x = 0; x < c.length; x++) {
                if (ha) "'" == c.charAt(x) && !ja("'") ? ha = !1 : ea++;else switch (c.charAt(x)) {
                    case "d":
                        I = da("d");
                        break;
                    case "D":
                        ca("D", e.dayNamesShort, e.dayNames);
                        break;
                    case "o":
                        i = da("o");
                        break;
                    case "m":
                        L = da("m");
                        break;
                    case "M":
                        L = ca("M", e.monthNamesShort, e.monthNames);
                        break;
                    case "y":
                        v = da("y");
                        break;
                    case "H":
                        f = da("H");
                        break;
                    case "h":
                        f = da("h");
                        break;
                    case "i":
                        ka = da("i");
                        break;
                    case "s":
                        qa = da("s");
                        break;
                    case "a":
                        ba = ca("a", [e.amText, e.pmText], [e.amText, e.pmText]) - 1;
                        break;
                    case "A":
                        ba = ca("A", [e.amText, e.pmText], [e.amText, e.pmText]) - 1;
                        break;
                    case "'":
                        ja("'") ? ea++ : ha = !0;
                        break;
                    default:
                        ea++;
                }
            }100 > v && (v += new Date().getFullYear() - new Date().getFullYear() % 100 + (v <= ("string" != typeof s ? s : new Date().getFullYear() % 100 + parseInt(s, 10)) ? 0 : -100));
            if (-1 < i) {
                L = 1;
                I = i;
                do {
                    s = 32 - new Date(v, L - 1, 32).getDate();
                    if (I <= s) break;
                    L++;
                    I -= s;
                } while (1);
            }
            f = e.getDate(v, L - 1, I, -1 == ba ? f : ba && 12 > f ? f + 12 : !ba && 12 == f ? 0 : f, ka, qa);
            return e.getYear(f) != v || e.getMonth(f) + 1 != L || e.getDay(f) != I ? w : f;
        }
    };
    m.formatDate = m.datetime.formatDate;
    m.parseDate = m.datetime.parseDate;
})(jQuery);
(function (a, m, c, l) {
    var e = a.mobiscroll,
        w = e.presets.scroller,
        s = e.util,
        v = s.has3d,
        L = s.jsPrefix,
        I = s.testTouch,
        i = {
        controls: ["calendar"],
        firstDay: 0,
        weekDays: "short",
        maxMonthWidth: 170,
        months: 1,
        preMonths: 1,
        highlight: !0,
        swipe: !0,
        liveSwipe: !0,
        divergentDayChange: !0,
        quickNav: !0,
        navigation: "yearMonth",
        dateText: "Date",
        timeText: "Time",
        calendarText: "Calendar",
        todayText: "Today",
        prevMonthText: "Previous Month",
        nextMonthText: "Next Month",
        prevYearText: "Previous Year",
        nextYearText: "Next Year",
        btnCalPrevClass: "mbsc-ic mbsc-ic-arrow-left6",
        btnCalNextClass: "mbsc-ic mbsc-ic-arrow-right6"
    };
    w.calbase = function (f) {
        function m(b, c, d) {
            var g,
                f,
                h,
                e,
                u = {},
                i = ga + Oa;
            b && a.each(b, function (a, b) {
                g = b.d || b.start || b;
                f = g + "";
                if (b.start && b.end) for (e = new Date(b.start); e <= b.end;) {
                    h = new Date(e.getFullYear(), e.getMonth(), e.getDate()), u[h] = u[h] || [], u[h].push(b), e.setDate(e.getDate() + 1);
                } else if (g.getTime) h = new Date(g.getFullYear(), g.getMonth(), g.getDate()), u[h] = u[h] || [], u[h].push(b);else if (f.match(/w/i)) {
                    var k = +f.replace("w", ""),
                        n = 0,
                        C = j.getDate(c, d - ga - pa, 1).getDay();
                    1 < j.firstDay - C + 1 && (n = 7);
                    for (P = 0; P < 5 * xa; P++) {
                        h = j.getDate(c, d - ga - pa, 7 * P - n - C + 1 + k), u[h] = u[h] || [], u[h].push(b);
                    }
                } else if (f = f.split("/"), f[1]) 11 <= d + i && (h = j.getDate(c + 1, f[0] - 1, f[1]), u[h] = u[h] || [], u[h].push(b)), 1 >= d - i && (h = j.getDate(c - 1, f[0] - 1, f[1]), u[h] = u[h] || [], u[h].push(b)), h = j.getDate(c, f[0] - 1, f[1]), u[h] = u[h] || [], u[h].push(b);else for (P = 0; P < xa; P++) {
                    h = j.getDate(c, d - ga - pa + P, f[0]), u[h] = u[h] || [], u[h].push(b);
                }
            });
            return u;
        }

        function qa(a, b) {
            cb = m(j.invalid, a, b);
            bb = m(j.valid, a, b);
            f.onGenMonth(a, b);
        }

        function ba(a, b, c, d, f, h, u) {
            var e = '<div class="dw-cal-h dw-cal-sc-c dw-cal-' + a + "-c " + (j.calendarClass || "") + '"><div class="dw-cal-sc"><div class="dw-cal-sc-p"><div class="dw-cal-sc-tbl"><div class="dw-cal-sc-row">';
            for (g = 1; g <= b; g++) {
                e = 12 >= g || g > c ? e + '<div class="dw-cal-sc-m-cell dw-cal-sc-cell dw-cal-sc-empty"><div class="dw-i">&nbsp;</div></div>' : e + ('<div tabindex="0" role="button"' + (h ? ' aria-label="' + h[g - 13] + '"' : "") + ' class="dwb-e dwb-nhl dw-cal-sc-m-cell dw-cal-sc-cell dw-cal-' + a + '-s" data-val=' + (d + g - 13) + '><div class="dw-i dw-cal-sc-tbl"><div class="dw-cal-sc-cell">' + (u ? u[g - 13] : d + g - 13 + f) + "</div></div></div>"), g < b && (0 === g % 12 ? e += '</div></div></div><div class="dw-cal-sc-p" style="' + (ya ? "top" : Fa ? "right" : "left") + ":" + 100 * Math.round(g / 12) + '%"><div class="dw-cal-sc-tbl"><div class="dw-cal-sc-row">' : 0 === g % 3 && (e += '</div><div class="dw-cal-sc-row">'));
            }return e + "</div></div></div></div></div>";
        }

        function ha(b, c) {
            var d,
                g,
                h,
                u,
                e,
                i,
                k,
                n,
                C,
                $,
                m,
                r,
                v,
                q,
                p = 1,
                o = 0;
            d = j.getDate(b, c, 1);
            var V = j.getYear(d),
                s = j.getMonth(d),
                ma = null === j.defaultValue && !f._hasValue ? null : f.getDate(!0),
                Z = j.getDate(V, s, 1).getDay(),
                t = '<div class="dw-cal-table">',
                A = '<div class="dw-week-nr-c">';
            1 < j.firstDay - Z + 1 && (o = 7);
            for (q = 0; 42 > q; q++) {
                v = q + j.firstDay - o, d = j.getDate(V, s, v - Z + 1), g = d.getFullYear(), h = d.getMonth(), u = d.getDate(), e = j.getMonth(d), i = j.getDay(d), r = j.getMaxDayOfMonth(g, h), k = g + "-" + h + "-" + u, h = a.extend({
                    valid: d < new Date(wa.getFullYear(), wa.getMonth(), wa.getDate()) || d > Da ? !1 : cb[d] === l || bb[d] !== l,
                    selected: ma && ma.getFullYear() === g && ma.getMonth() === h && ma.getDate() === u
                }, f.getDayProps(d, ma)), n = h.valid, C = h.selected, g = h.cssClass, $ = d.getTime() === new Date().setHours(0, 0, 0, 0), m = e !== s, db[k] = h, 0 === q % 7 && (t += (q ? "</div>" : "") + '<div class="dw-cal-row' + (j.highlight && ma && 0 <= ma - d && 6048E5 > ma - d ? " dw-cal-week-hl" : "") + '">'), Na && 1 == d.getDay() && ("month" == Na && m && 1 < p ? p = 1 == u ? 1 : 2 : "year" == Na && (p = j.getWeekNumber(d)), A += '<div class="dw-week-nr"><div class="dw-week-nr-i">' + p + "</div></div>", p++), t += '<div role="button" tabindex="-1" aria-label="' + ($ ? j.todayText + ", " : "") + j.dayNames[d.getDay()] + ", " + j.monthNames[e] + " " + i + " " + (h.ariaLabel ? ", " + h.ariaLabel : "") + '"' + (m && !Pa ? ' aria-hidden="true"' : "") + (C ? ' aria-selected="true"' : "") + (n ? "" : ' aria-disabled="true"') + ' data-day="' + v % 7 + '" data-full="' + k + '"class="dw-cal-day ' + ($ ? " dw-cal-today" : "") + (j.dayClass || "") + (C ? " dw-sel" : "") + (g ? " " + g : "") + (1 == i ? " dw-cal-day-first" : "") + (i == r ? " dw-cal-day-last" : "") + (m ? " dw-cal-day-diff" : "") + (n ? " dw-cal-day-v dwb-e dwb-nhl" : " dw-cal-day-inv") + '"><div class="dw-i ' + (C ? Ha : "") + " " + (j.innerDayClass || "") + '"><div class="dw-cal-day-fg">' + i + f._processItem(a, 0.06) + "</div>" + (h.markup || "") + '<div class="dw-cal-day-frame"></div></div></div>';
            }return t + ("</div></div>" + A + "</div>");
        }

        function ja(b, d, c) {
            var f = j.getDate(b, d, 1),
                e = j.getYear(f),
                f = j.getMonth(f),
                i = e + Wa;
            if (Ga) {
                Ia && Ia.removeClass("dw-sel").removeAttr("aria-selected").find(".dw-i").removeClass(Ha);
                Qa && Qa.removeClass("dw-sel").removeAttr("aria-selected").find(".dw-i").removeClass(Ha);
                Ia = a('.dw-cal-year-s[data-val="' + e + '"]', q).addClass("dw-sel").attr("aria-selected", "true");
                Qa = a('.dw-cal-month-s[data-val="' + f + '"]', q).addClass("dw-sel").attr("aria-selected", "true");
                Ia.find(".dw-i").addClass(Ha);
                Qa.find(".dw-i").addClass(Ha);
                za && za.scroll(Ia, c);
                a(".dw-cal-month-s", q).removeClass("dwb-d");
                if (e === la) for (g = 0; g < C; g++) {
                    a('.dw-cal-month-s[data-val="' + g + '"]', q).addClass("dwb-d");
                }if (e === u) for (g = ma + 1; 12 >= g; g++) {
                    a('.dw-cal-month-s[data-val="' + g + '"]', q).addClass("dwb-d");
                }
            }
            1 == k.length && k.attr("aria-label", e).html(i);
            for (g = 0; g < A; ++g) {
                f = j.getDate(b, d - pa + g, 1), e = j.getYear(f), f = j.getMonth(f), i = e + Wa, a(na[g]).attr("aria-label", j.monthNames[f] + (Ka ? "" : " " + e)).html((!Ka && oa < ia ? i + " " : "") + U[f] + (!Ka && oa > ia ? " " + i : "")), 1 < k.length && a(k[g]).html(i);
            }j.getDate(b, d - pa - 1, 1) < h ? ca(a(".dw-cal-prev-m", q)) : da(a(".dw-cal-prev-m", q));
            j.getDate(b, d + A - pa, 1) > $ ? ca(a(".dw-cal-next-m", q)) : da(a(".dw-cal-next-m", q));
            j.getDate(b, d, 1).getFullYear() <= h.getFullYear() ? ca(a(".dw-cal-prev-y", q)) : da(a(".dw-cal-prev-y", q));
            j.getDate(b, d, 1).getFullYear() >= $.getFullYear() ? ca(a(".dw-cal-next-y", q)) : da(a(".dw-cal-next-y", q));
        }

        function da(a) {
            a.removeClass(eb).find(".dw-cal-btn-txt").removeAttr("aria-disabled");
        }

        function ca(a) {
            a.addClass(eb).find(".dw-cal-btn-txt").attr("aria-disabled", "true");
        }

        function ea(d, c) {
            if (b && ("calendar" === Aa || c)) {
                var g,
                    h,
                    e = j.getDate(Q, aa, 1),
                    u = Math.abs(12 * (j.getYear(d) - j.getYear(e)) + j.getMonth(d) - j.getMonth(e));
                f.needsSlide && u && (Q = j.getYear(d), aa = j.getMonth(d), d > e ? (h = u > ga - pa + A - 1, aa -= h ? 0 : u - ga, g = "next") : d < e && (h = u > ga + pa, aa += h ? 0 : u - ga, g = "prev"), y(Q, aa, g, Math.min(u, ga), h, !0));
                c || (f.trigger("onDayHighlight", [d]), j.highlight && (a(".dw-sel .dw-i", K).removeClass(Ha), a(".dw-sel", K).removeClass("dw-sel").removeAttr("aria-selected"), a(".dw-cal-week-hl", K).removeClass("dw-cal-week-hl"), (null !== j.defaultValue || f._hasValue) && a('.dw-cal-day[data-full="' + d.getFullYear() + "-" + d.getMonth() + "-" + d.getDate() + '"]', K).addClass("dw-sel").attr("aria-selected", "true").find(".dw-i").addClass(Ha).closest(".dw-cal-row").addClass("dw-cal-week-hl")));
                f.needsSlide = !0;
            }
        }

        function x(a, b) {
            qa(a, b);
            for (g = 0; g < xa; g++) {
                sa[g].html(ha(a, b - pa - ga + g));
            }X();
            f.needsRefresh = !1;
        }

        function R(b, d, c) {
            var f = ga,
                g = ga;
            if (c) {
                for (; g && j.getDate(b, d + f + A - pa - 1, 1) > $;) {
                    g--;
                }for (; f && j.getDate(b, d - g - pa, 1) < h;) {
                    f--;
                }
            }
            a.extend(N.settings, {
                contSize: A * B,
                snap: B,
                minScroll: H - (Fa ? f : g) * B,
                maxScroll: H + (Fa ? g : f) * B
            });
            N.refresh();
        }

        function X() {
            Na && Z.html(a(".dw-week-nr-c", sa[ga]).html());
            a(".dw-cal-slide-a .dw-cal-day", p).attr("tabindex", 0);
        }

        function y(d, c, h, e, u, i, k) {
            d && Ra.push({ y: d, m: c, dir: h, slideNr: e, load: u, active: i, callback: k });
            if (!La) {
                var n = Ra.shift(),
                    d = n.y,
                    c = n.m,
                    h = "next" === n.dir,
                    e = n.slideNr,
                    u = n.load,
                    i = n.active,
                    k = n.callback || Xa,
                    n = j.getDate(d, c, 1),
                    d = j.getYear(n),
                    c = j.getMonth(n);
                La = !0;
                f.changing = !0;
                f.trigger("onMonthChange", [d, c]);
                qa(d, c);
                if (u) for (g = 0; g < A; g++) {
                    sa[h ? xa - A + g : g].html(ha(d, c - pa + g));
                }i && Sa.addClass("dw-cal-slide-a");
                setTimeout(function () {
                    f.ariaMessage(j.monthNames[c] + " " + d);
                    ja(d, c, 200);
                    H = h ? H - B * e * Ma : H + B * e * Ma;
                    N.scroll(H, O ? 200 : 0, function () {
                        setTimeout(function () {
                            var j;
                            if (sa.length) {
                                Sa.removeClass("dw-cal-slide-a").attr("aria-hidden", "true");
                                if (h) {
                                    j = sa.splice(0, e);
                                    for (g = 0; g < e; g++) {
                                        sa.push(j[g]), S(sa[sa.length - 1], +sa[sa.length - 2].data("curr") + 100 * Ma);
                                    }
                                } else {
                                    j = sa.splice(xa - e, e);
                                    for (g = e - 1; 0 <= g; g--) {
                                        sa.unshift(j[g]), S(sa[0], +sa[1].data("curr") - 100 * Ma);
                                    }
                                }
                                for (g = 0; g < e; g++) {
                                    sa[h ? xa - e + g : g].html(ha(d, c - pa - ga + g + (h ? xa - e : 0))), u && sa[h ? g : xa - e + g].html(ha(d, c - pa - ga + g + (h ? 0 : xa - e)));
                                }for (g = 0; g < A; g++) {
                                    sa[ga + g].addClass("dw-cal-slide-a").removeAttr("aria-hidden");
                                }R(d, c, !0);
                                La = !1;
                            }
                            Ra.length ? setTimeout(function () {
                                y();
                            }, 10) : (Q = d, aa = c, f.changing = !1, a(".dw-cal-day", p).attr("tabindex", -1), X(), f.needsRefresh && f.isVisible() && b && x(Q, aa), f.trigger("onMonthLoaded", [d, c]), k());
                        }, O ? 0 : 200);
                    });
                }, 10);
            }
        }

        function t() {
            var b = a(this),
                d = f.live,
                c = f.getDate(!0),
                g = b.attr("data-full"),
                h = g.split("-"),
                h = new Date(h[0], h[1], h[2]),
                c = new Date(h.getFullYear(), h.getMonth(), h.getDate(), c.getHours(), c.getMinutes(), c.getSeconds()),
                e = b.hasClass("dw-sel");
            if ((Pa || !b.hasClass("dw-cal-day-diff")) && !1 !== f.trigger("onDayChange", [a.extend(db[g], {
                date: c,
                cell: this,
                selected: e
            })])) f.needsSlide = !1, o = !0, f.setDate(c, d, 0.2, !d, !0), j.divergentDayChange && (va = !0, h < j.getDate(Q, aa - pa, 1) ? G() : h > j.getDate(Q, aa - pa + A, 0) && T(), va = !1);
        }

        function S(a, b) {
            a.data("curr", b);
            v ? a[0].style[L + "Transform"] = "translate3d(" + (ya ? "0," + b + "%," : b + "%,0,") + "0)" : a[0].style[ya ? "top" : "left"] = b + "%";
        }

        function T() {
            va && j.getDate(Q, aa + A - pa, 1) <= $ && a.mobiscroll.running && y(Q, ++aa, "next", 1, !1, !0, T);
        }

        function G() {
            va && j.getDate(Q, aa - pa - 1, 1) >= h && a.mobiscroll.running && y(Q, --aa, "prev", 1, !1, !0, G);
        }

        function ra(b) {
            va && j.getDate(Q, aa, 1) <= j.getDate(j.getYear($) - 1, j.getMonth($) - Oa, 1) && a.mobiscroll.running ? y(++Q, aa, "next", ga, !0, !0, function () {
                ra(b);
            }) : va && !b.hasClass("dwb-d") && a.mobiscroll.running && y(j.getYear($), j.getMonth($) - Oa, "next", ga, !0, !0);
        }

        function z(b) {
            va && j.getDate(Q, aa, 1) >= j.getDate(j.getYear(h) + 1, j.getMonth(h) + pa, 1) && a.mobiscroll.running ? y(--Q, aa, "prev", ga, !0, !0, function () {
                z(b);
            }) : va && !b.hasClass("dwb-d") && a.mobiscroll.running && y(j.getYear(h), j.getMonth(h) + pa, "prev", ga, !0, !0);
        }

        function fa(a, b) {
            a.hasClass("dw-cal-v") || (a.addClass("dw-cal-v" + (b ? "" : " dw-cal-p-in")).removeClass("dw-cal-p-out dw-cal-h"), f.trigger("onSelectShow", []));
        }

        function Y(a, b) {
            a.hasClass("dw-cal-v") && a.removeClass("dw-cal-v dw-cal-p-in").addClass("dw-cal-h" + (b ? "" : " dw-cal-p-out"));
        }

        function E(a, b) {
            (b || a).hasClass("dw-cal-v") ? Y(a) : fa(a);
        }

        function r() {
            a(this).removeClass("dw-cal-p-out dw-cal-p-in");
        }

        var W,
            g,
            P,
            F,
            J,
            q,
            D,
            K,
            p,
            B,
            H,
            o,
            b,
            n,
            V,
            Z,
            d,
            O,
            U,
            N,
            M,
            na,
            ia,
            k,
            oa,
            la,
            u,
            C,
            ma,
            h,
            $,
            wa,
            Da,
            Ea,
            Q,
            aa,
            Ua,
            Va,
            bb,
            cb,
            Ja,
            Aa,
            La,
            va,
            A,
            xa,
            Oa,
            pa,
            Pa,
            za,
            Ia,
            Qa,
            fb = this,
            Sa = [],
            sa = [],
            Ra = [],
            ta = {},
            db = {},
            Xa = function Xa() {},
            jb = a.extend({}, f.settings),
            j = a.extend(f.settings, i, jb),
            kb = "full" == j.weekDays ? "" : "min" == j.weekDays ? "Min" : "Short",
            Na = j.weekCounter,
            gb = j.layout || (/top|bottom/.test(j.display) ? "liquid" : ""),
            Ca = "liquid" == gb && "bubble" !== j.display,
            hb = "modal" == j.display,
            Fa = j.rtl,
            Ma = Fa ? -1 : 1,
            ib = Ca ? null : j.calendarWidth,
            ya = "vertical" == j.swipeDirection,
            Ga = j.quickNav,
            ga = j.preMonths,
            Ka = "yearMonth" == j.navigation,
            Ya = j.controls.join(","),
            Ta = (!0 === j.tabs || !1 !== j.tabs && Ca) && 1 < j.controls.length,
            Za = !Ta && j.tabs === l && !Ca && 1 < j.controls.length,
            Wa = j.yearSuffix || "",
            Ha = j.activeClass || "",
            $a = "dw-sel " + (j.activeTabClass || ""),
            ab = j.activeTabInnerClass || "",
            eb = "dwb-d " + (j.disabledClass || ""),
            Ba = "",
            ua = "";
        Ya.match(/calendar/) ? b = !0 : Ga = !1;
        Ya.match(/date/) && (ta.date = 1);
        Ya.match(/time/) && (ta.time = 1);
        b && ta.date && (Ta = !0, Za = !1);
        j.layout = gb;
        j.preset = (ta.date || b ? "date" : "") + (ta.time ? "time" : "");
        if ("inline" == j.display) a(this).closest('[data-role="page"]').on("pageshow", function () {
            f.position();
        });
        f.changing = !1;
        f.needsRefresh = !1;
        f.needsSlide = !0;
        f.getDayProps = Xa;
        f.onGenMonth = Xa;
        f.prepareObj = m;
        f.refresh = function () {
            f.changing ? f.needsRefresh = true : f.isVisible() && b && x(Q, aa);
        };
        f.navigate = function (a, b) {
            var d,
                c,
                h = f.isVisible();
            if (b && h) ea(a, true);else {
                d = j.getYear(a);
                c = j.getMonth(a);
                if (h && (d != Q || c != aa)) {
                    f.trigger("onMonthChange", [d, c]);
                    ja(d, c);
                    x(d, c);
                }
                Q = d;
                aa = c;
            }
        };
        f.showMonthView = function () {
            if (Ga && !O) {
                Y(ua, true);
                Y(Ba, true);
                fa(d, true);
                O = true;
            }
        };
        F = w.datetime.call(this, f);
        ia = j.dateOrder.search(/m/i);
        oa = j.dateOrder.search(/y/i);
        a.extend(F, {
            ariaMessage: j.calendarText, onMarkupReady: function onMarkupReady(i) {
                var m,
                    o,
                    w = "";
                q = i;
                D = j.display == "inline" ? a(this).is("div") ? a(this) : a(this).parent() : f.context;
                Ea = f.getDate(true);
                if (!Q) {
                    Q = j.getYear(Ea);
                    aa = j.getMonth(Ea);
                }
                H = 0;
                V = true;
                La = false;
                U = j.monthNames;
                Aa = "calendar";
                if (j.minDate) {
                    h = new Date(j.minDate.getFullYear(), j.minDate.getMonth(), 1);
                    wa = j.minDate;
                } else wa = h = new Date(j.startYear, 0, 1);
                if (j.maxDate) {
                    $ = new Date(j.maxDate.getFullYear(), j.maxDate.getMonth(), 1);
                    Da = j.maxDate;
                } else Da = $ = new Date(j.endYear, 11, 31, 23, 59, 59);
                i.addClass("dw-calendar" + (v ? "" : " dw-cal-no3d"));
                J = a(".dw", i);
                Ja = a(".dwcc", i);
                ta.date ? ta.date = a(".dwc", q).eq(0) : b && a(".dwc", q).eq(0).addClass("dwc-hh");
                if (ta.time) ta.time = a(".dwc", q).eq(1);
                if (b) {
                    A = j.months == "auto" ? Math.max(1, Math.min(3, Math.floor((ib || D[0].innerWidth || D.innerWidth()) / 280))) : j.months;
                    xa = A + 2 * ga;
                    Oa = Math.floor(A / 2);
                    pa = Math.round(A / 2) - 1;
                    Pa = j.showDivergentDays === l ? A < 2 : j.showDivergentDays;
                    ya = ya && A < 2;
                    o = '<div class="dw-cal-btnw"><div class="' + (Fa ? "dw-cal-next-m" : "dw-cal-prev-m") + ' dw-cal-prev dw-cal-btn dwb dwb-e"><div role="button" tabindex="0" class="dw-cal-btn-txt ' + (j.btnCalPrevClass || "") + '" aria-label="' + j.prevMonthText + '"></div></div>';
                    for (g = 0; g < A; ++g) {
                        o = o + ('<div class="dw-cal-btnw-m" style="width: ' + 100 / A + '%"><span role="button" class="dw-cal-month"></span></div>');
                    }o = o + ('<div class="' + (Fa ? "dw-cal-prev-m" : "dw-cal-next-m") + ' dw-cal-next dw-cal-btn dwb dwb-e"><div role="button" tabindex="0" class="dw-cal-btn-txt ' + (j.btnCalNextClass || "") + '" aria-label="' + j.nextMonthText + '"></div></div></div>');
                    Ka && (w = '<div class="dw-cal-btnw"><div class="' + (Fa ? "dw-cal-next-y" : "dw-cal-prev-y") + ' dw-cal-prev dw-cal-btn dwb dwb-e"><div role="button" tabindex="0" class="dw-cal-btn-txt ' + (j.btnCalPrevClass || "") + '" aria-label="' + j.prevYearText + '"></div></div><span role="button" class="dw-cal-year"></span><div class="' + (Fa ? "dw-cal-prev-y" : "dw-cal-next-y") + ' dw-cal-next dw-cal-btn dwb dwb-e"><div role="button" tabindex="0" class="dw-cal-btn-txt ' + (j.btnCalNextClass || "") + '" aria-label="' + j.nextYearText + '"></div></div></div>');
                    if (Ga) {
                        la = j.getYear(h);
                        u = j.getYear($);
                        C = j.getMonth(h);
                        ma = j.getMonth($);
                        Va = Math.ceil((u - la + 1) / 12) + 2;
                        Ba = ba("month", 36, 24, 0, "", j.monthNames, j.monthNamesShort);
                        ua = ba("year", Va * 12, u - la + 13, la, Wa);
                    }
                    n = '<div class="mbsc-w-p dw-cal-c"><div class="dw-cal ' + (A > 1 ? " dw-cal-multi " : "") + (Na ? " dw-weeks " : "") + (Pa ? "" : " dw-hide-diff ") + (j.calendarClass || "") + '"><div class="dw-cal-header"><div class="dw-cal-btnc ' + (Ka ? "dw-cal-btnc-ym" : "dw-cal-btnc-m") + '">' + (oa < ia || A > 1 ? w + o : o + w) + '</div></div><div class="dw-cal-body"><div class="dw-cal-m-c dw-cal-v"><div class="dw-cal-days-c">';
                    for (P = 0; P < A; ++P) {
                        n = n + ('<div aria-hidden="true" class="dw-cal-days" style="width: ' + 100 / A + '%"><table cellpadding="0" cellspacing="0"><tr>');
                        for (g = 0; g < 7; g++) {
                            n = n + ("<th>" + j["dayNames" + kb][(g + j.firstDay) % 7] + "</th>");
                        }n = n + "</tr></table></div>";
                    }
                    n = n + ('</div><div class="dw-cal-anim-c ' + (j.calendarClass || "") + '"><div class="dw-week-nrs-c ' + (j.weekNrClass || "") + '"><div class="dw-week-nrs"></div></div><div class="dw-cal-anim">');
                    for (g = 0; g < A + 2 * ga; g++) {
                        n = n + '<div class="dw-cal-slide" aria-hidden="true"></div>';
                    }n = n + ("</div></div></div>" + Ba + ua + "</div></div></div>");
                    ta.calendar = a(n);
                }
                a.each(j.controls, function (b, d) {
                    ta[d] = a('<div class="dw-cal-pnl" id="' + (fb.id + "_dw_pnl_" + b) + '"></div>').append(a('<div class="dw-cal-pnl-i"></div>').append(ta[d])).appendTo(Ja);
                });
                m = '<div class="dw-cal-tabs"><ul role="tablist">';
                a.each(j.controls, function (a, b) {
                    ta[b] && (m = m + ('<li role="tab" aria-controls="' + (fb.id + "_dw_pnl_" + a) + '" class="dw-cal-tab ' + (a ? "" : $a) + '" data-control="' + b + '"><a href="#" class="dwb-e dwb-nhl dw-i ' + (!a ? ab : "") + '">' + j[b + "Text"] + "</a></li>"));
                });
                m = m + "</ul></div>";
                Ja.before(m);
                K = a(".dw-cal-anim-c", q);
                p = a(".dw-cal-anim", K);
                Z = a(".dw-week-nrs", K);
                if (b) {
                    O = true;
                    Sa = a(".dw-cal-slide", p).each(function (b, d) {
                        sa.push(a(d));
                    });
                    Sa.slice(ga, ga + A).addClass("dw-cal-slide-a").removeAttr("aria-hidden");
                    for (g = 0; g < xa; g++) {
                        S(sa[g], 100 * (g - ga) * Ma);
                    }x(Q, aa);
                    N = new e.classes.ScrollView(K[0], {
                        axis: ya ? "Y" : "X",
                        easing: "",
                        contSize: 0,
                        snap: 1,
                        maxSnapScroll: ga,
                        moveElement: p,
                        mousewheel: j.mousewheel,
                        swipe: j.swipe,
                        liveSwipe: j.liveSwipe,
                        time: 200,
                        lock: true,
                        onScrollStart: function onScrollStart(a, b) {
                            b.settings.scrollLock = f.scrollLock;
                        },
                        onScrollEnd: function onScrollEnd(a) {
                            (a = Math.round((a - H) / B) * Ma) && y(Q, aa - a, a > 0 ? "prev" : "next", a > 0 ? a : -a);
                        }
                    });
                }
                na = a(".dw-cal-month", q);
                k = a(".dw-cal-year", q);
                d = a(".dw-cal-m-c", q);
                if (Ga) {
                    d.on("webkitAnimationEnd animationend", r);
                    Ba = a(".dw-cal-month-c", q).on("webkitAnimationEnd animationend", r);
                    ua = a(".dw-cal-year-c", q).on("webkitAnimationEnd animationend", r);
                    a(".dw-cal-sc-p", q);
                    Ua = {
                        axis: ya ? "Y" : "X",
                        contSize: 0,
                        snap: 1,
                        maxSnapScroll: 1,
                        rtl: j.rtl,
                        mousewheel: j.mousewheel,
                        swipe: j.swipe,
                        liveSwipe: j.liveSwipe,
                        time: 200
                    };
                    za = new e.classes.ScrollView(ua[0], Ua);
                    M = new e.classes.ScrollView(Ba[0], Ua);
                }
                setTimeout(function () {
                    f.tap(K, function (b) {
                        b = a(b.target);
                        if (!La && !N.scrolled) {
                            b = b.closest(".dw-cal-day", this);
                            b.hasClass("dw-cal-day-v") && t.call(b[0]);
                        }
                    });
                    a(".dw-cal-btn", q).on("touchstart mousedown keydown", function (b) {
                        var d = a(this);
                        if (b.type !== "keydown") {
                            b.preventDefault();
                            b = I(b, this);
                        } else b = b.keyCode === 32;
                        if (!va && b && !d.hasClass("dwb-d")) {
                            va = true;
                            d.hasClass("dw-cal-prev-m") ? G() : d.hasClass("dw-cal-next-m") ? T() : d.hasClass("dw-cal-prev-y") ? z(d) : d.hasClass("dw-cal-next-y") && ra(d);
                            a(c).on("mouseup.dwbtn", function () {
                                a(c).off(".dwbtn");
                                va = false;
                            });
                        }
                    }).on("touchend touchcancel keyup", function () {
                        va = false;
                    });
                    a(".dw-cal-tab", q).on("touchstart click", function (b) {
                        var d = a(this);
                        if (I(b, this) && a.mobiscroll.running && !d.hasClass("dw-sel")) {
                            Aa = d.attr("data-control");
                            a(".dw-cal-pnl", q).removeClass("dw-cal-p-in").addClass("dw-cal-pnl-h");
                            a(".dw-cal-tab", q).removeClass($a).removeAttr("aria-selected").find(".dw-i").removeClass(ab);
                            d.addClass($a).attr("aria-selected", "true").find(".dw-i").addClass(ab);
                            ta[Aa].removeClass("dw-cal-pnl-h").addClass("dw-cal-p-in");
                            if (Aa === "calendar") {
                                W = f.getDate(true);
                                (W.getFullYear() !== Ea.getFullYear() || W.getMonth() !== Ea.getMonth() || W.getDate() !== Ea.getDate()) && ea(W);
                            } else {
                                Ea = f.getDate(true);
                                f.setDate(Ea, false, 0, true);
                            }
                            f.showMonthView();
                            f.trigger("onTabChange", [Aa]);
                        }
                    });
                    if (Ga) {
                        f.tap(a(".dw-cal-month", q), function () {
                            if (!ua.hasClass("dw-cal-v")) {
                                E(d);
                                O = d.hasClass("dw-cal-v");
                            }
                            E(Ba);
                            Y(ua);
                        });
                        f.tap(a(".dw-cal-year", q), function () {
                            ua.hasClass("dw-cal-v") || za.scroll(Ia);
                            if (!Ba.hasClass("dw-cal-v")) {
                                E(d);
                                O = d.hasClass("dw-cal-v");
                            }
                            E(ua);
                            Y(Ba);
                        });
                        f.tap(a(".dw-cal-month-s", q), function () {
                            !M.scrolled && !a(this).hasClass("dwb-d") && f.navigate(j.getDate(Q, a(this).attr("data-val"), 1));
                        });
                        f.tap(a(".dw-cal-year-s", q), function () {
                            if (!za.scrolled) {
                                W = j.getDate(a(this).attr("data-val"), aa, 1);
                                f.navigate(new Date(s.constrain(W, h, $)));
                            }
                        });
                        f.tap(ua, function () {
                            if (!za.scrolled) {
                                Y(ua);
                                fa(d);
                                O = true;
                            }
                        });
                        f.tap(Ba, function () {
                            if (!M.scrolled) {
                                Y(Ba);
                                fa(d);
                                O = true;
                            }
                        });
                    }
                }, 300);
                Ca ? i.addClass("dw-cal-liq") : a(".dw-cal", q).width(ib || 280 * A);
                j.calendarHeight && a(".dw-cal-anim-c", q).height(j.calendarHeight);
            }, onShow: function onShow() {
                if (b) {
                    ja(Q, aa);
                    f.trigger("onMonthLoaded", [Q, aa]);
                }
            }, onPosition: function onPosition(d, c, h) {
                var e,
                    u,
                    i,
                    n = 0,
                    k = 0,
                    C = 0;
                if (Ca) {
                    hb && K.height("");
                    Ja.height("");
                    p.width("");
                }
                B && (i = B);
                if (B = Math.round(Math.round(parseInt(K.css(ya ? "height" : "width"))) / A)) {
                    q.removeClass("mbsc-cal-m mbsc-cal-l");
                    B > 1024 ? q.addClass("mbsc-cal-l") : B > 640 && q.addClass("mbsc-cal-m");
                }
                if (Ta && (V || Ca) || Za) {
                    a(".dw-cal-pnl", q).removeClass("dw-cal-pnl-h");
                    a.each(ta, function (a, b) {
                        e = b.outerWidth();
                        n = Math.max(n, e);
                        k = Math.max(k, b.outerHeight());
                        C = C + e;
                    });
                    if (Ta || Za && C > (D[0].innerWidth || D.innerWidth())) {
                        u = true;
                        Aa = a(".dw-cal-tabs .dw-sel", q).attr("data-control");
                        J.addClass("dw-cal-tabbed");
                    } else {
                        Aa = "calendar";
                        k = n = "";
                        J.removeClass("dw-cal-tabbed");
                        Ja.css({ width: "", height: "" });
                    }
                }
                if (Ca && hb) {
                    f._isFullScreen = true;
                    u && b && Ja.height(ta.calendar.outerHeight());
                    d = J.outerHeight();
                    h >= d && K.height(h - d + K.outerHeight());
                    b && (k = Math.max(k, ta.calendar.outerHeight()));
                }
                if (u) {
                    Ja.css({ width: Ca ? "" : n, height: k });
                    B = Math.round(Math.round(parseInt(K.css(ya ? "height" : "width"))) / A);
                }
                if (B) {
                    p[ya ? "height" : "width"](B);
                    if (B !== i) {
                        if (Ka) {
                            U = j.maxMonthWidth > a(".dw-cal-btnw-m", q).width() ? j.monthNamesShort : j.monthNames;
                            for (g = 0; g < A; ++g) {
                                a(na[g]).text(U[j.getMonth(j.getDate(Q, aa - pa + g, 1))]);
                            }
                        }
                        if (Ga) {
                            h = ua[ya ? "height" : "width"]();
                            a.extend(za.settings, { contSize: h, snap: h, minScroll: (2 - Va) * h, maxScroll: -h });
                            a.extend(M.settings, { contSize: h, snap: h, minScroll: -h, maxScroll: -h });
                            za.refresh();
                            M.refresh();
                            ua.hasClass("dw-cal-v") && za.scroll(Ia);
                        }
                        if (Ca && !V && i) {
                            h = H / i;
                            H = h * B;
                        }
                        R(Q, aa, !i);
                    }
                } else B = i;
                if (u) {
                    a(".dw-cal-pnl", q).addClass("dw-cal-pnl-h");
                    ta[Aa].removeClass("dw-cal-pnl-h");
                }
                f.trigger("onCalResize", []);
                V = false;
            }, onHide: function onHide() {
                Ra = [];
                sa = [];
                aa = Q = Aa = null;
                La = true;
                B = 0;
                N && N.destroy();
                if (Ga && za && M) {
                    za.destroy();
                    M.destroy();
                }
            }, onValidated: function onValidated() {
                var a, b, d;
                b = f.getDate(true);
                if (o) a = "calendar";else for (d in f.order) {
                    d && f.order[d] === g && (a = /mdy/.test(d) ? "date" : "time");
                }f.trigger("onSetDate", [{ date: b, control: a }]);
                ea(b);
                o = false;
            }
        });
        return F;
    };
})(jQuery, window, document);
(function (a, m) {
    var c = a.mobiscroll,
        l = c.classes,
        e = c.util,
        w = e.constrain,
        s = e.jsPrefix,
        v = e.prefix,
        L = e.has3d,
        I = e.getCoord,
        i = e.getPosition,
        f = e.testTouch,
        ka = e.isNumeric,
        qa = e.isString,
        ba = "webkitTransitionEnd transitionend",
        ha = window.requestAnimationFrame || function (a) {
        a();
    },
        ja = window.cancelAnimationFrame || function () {};
    l.ScrollView = function (c, e, ea) {
        function x(b) {
            if ((!C.lock || !B) && f(b, this) && !p && a.mobiscroll.running) {
                "mousedown" == b.type && b.preventDefault();
                G && G.removeClass("mbsc-btn-a");
                F = !1;
                G = a(b.target).closest(".mbsc-btn-e", this);
                G.length && !G.hasClass("mbsc-btn-d") && (F = !0, ra = setTimeout(function () {
                    G.addClass("mbsc-btn-a");
                }, 100));
                p = !0;
                K = !1;
                k.scrolled = B;
                O = I(b, "X");
                U = I(b, "Y");
                g = O;
                E = Y = fa = 0;
                d = new Date();
                Z = +i(M, ia) || 0;
                T(Z, 1);
                if ("mousedown" === b.type) a(document).on("mousemove", R).on("mouseup", y);
                na("onScrollStart", [oa]);
            }
        }

        function R(a) {
            if (p) {
                g = I(a, "X");
                P = I(a, "Y");
                fa = g - O;
                Y = P - U;
                E = ia ? Y : fa;
                if (F && (5 < Math.abs(Y) || 5 < Math.abs(fa))) clearTimeout(ra), G.removeClass("mbsc-btn-a"), F = !1;
                !K && 5 < Math.abs(E) && (k.scrolled = !0, C.liveSwipe && !o && (o = !0, H = ha(X)));
                ia || C.scrollLock ? a.preventDefault() : k.scrolled ? a.preventDefault() : 7 < Math.abs(Y) && (K = !0, k.scrolled = !0, ma.trigger("touchend"));
            }
        }

        function X() {
            q && (E = w(E, -n * q, n * q));
            T(w(Z + E, D - W, J + W));
            o = !1;
        }

        function y(b) {
            if (p) {
                var c;
                c = new Date() - d;
                ja(H);
                o = !1;
                !K && k.scrolled && (C.momentum && L && 300 > c && (c = E / c, E = Math.max(Math.abs(E), c * c / C.speedUnit) * (0 > E ? -1 : 1)), S(E));
                F && (clearTimeout(ra), G.addClass("mbsc-btn-a"), setTimeout(function () {
                    G.removeClass("mbsc-btn-a");
                }, 100), !K && !k.scrolled && na("onBtnTap", [G]));
                "mouseup" == b.type && a(document).off("mousemove", R).off("mouseup", y);
                p = !1;
            }
        }

        function t(d) {
            d = d.originalEvent || d;
            if ((E = ia ? d.deltaY || d.wheelDelta || d.detail : d.deltaX) && a.mobiscroll.running) d.preventDefault(), E = 0 > E ? 20 : -20, Z = oa, o || (o = !0, H = ha(X)), clearTimeout(b), b = setTimeout(function () {
                ja(H);
                o = false;
                S(E);
            }, 200);
        }

        function S(a) {
            var b;
            q && (a = w(a, -n * q, n * q));
            la = Math.round((Z + a) / n);
            b = w(la * n, D, J);
            if (V) {
                if (0 > a) for (a = V.length - 1; 0 <= a; a--) {
                    if (Math.abs(b) + z >= V[a].breakpoint) {
                        la = a;
                        u = 2;
                        b = V[a].snap2;
                        break;
                    }
                } else if (0 <= a) for (a = 0; a < V.length; a++) {
                    if (Math.abs(b) <= V[a].breakpoint) {
                        la = a;
                        u = 1;
                        b = V[a].snap1;
                        break;
                    }
                }b = w(b, D, J);
            }
            T(b, C.time || (oa < D || oa > J ? 200 : Math.max(200, Math.abs(b - oa) * C.timeUnit)), function () {
                na("onScrollEnd", [oa]);
            });
        }

        function T(a, b, d) {
            var c = function c() {
                B = !1;
                d && d();
            };
            B = !0;
            if (L) {
                if (N[s + "Transition"] = b ? v + "transform " + Math.round(b) + "ms " + C.easing : "", N[s + "Transform"] = "translate3d(" + (ia ? "0," + a + "px," : a + "px,0,") + "0)", oa == a || !b) c();else {
                    if (b) M.on(ba, function (a) {
                        a.target === M[0] && (M.off(ba), N[s + "Transition"] = "", c());
                    });
                }
            } else setTimeout(c, b || 0), N[r] = a + "px";
            oa = a;
        }

        var G,
            ra,
            z,
            fa,
            Y,
            E,
            r,
            W,
            g,
            P,
            F,
            J,
            q,
            D,
            K,
            p,
            B,
            H,
            o,
            b,
            n,
            V,
            Z,
            d,
            O,
            U,
            N,
            M,
            na,
            ia,
            k = this,
            oa = 0,
            la = 0,
            u = 1,
            C = e,
            ma = a(c);
        l.Base.call(this, c, e, !0);
        k.scrolled = !1;
        k.scroll = function (b, d, f) {
            b = ka(b) ? Math.round(b / n) * n : Math.ceil((a(b, c).length ? Math.round(M.offset()[r] - a(b, c).offset()[r]) : oa) / n) * n;
            la = Math.round(b / n);
            T(w(b, D, J), d, f);
        };
        k.refresh = function () {
            var a;
            z = C.contSize === m ? ia ? ma.height() : ma.width() : C.contSize;
            D = C.minScroll === m ? ia ? z - M.height() : z - M.width() : C.minScroll;
            J = C.maxScroll === m ? 0 : C.maxScroll;
            !ia && C.rtl && (a = J, J = -D, D = -a);
            qa(C.snap) && (V = [], M.find(C.snap).each(function () {
                var a = ia ? this.offsetTop : this.offsetLeft,
                    b = ia ? this.offsetHeight : this.offsetWidth;
                V.push({ breakpoint: a + b / 2, snap1: -a, snap2: z - a - b });
            }));
            n = ka(C.snap) ? C.snap : 1;
            q = C.snap ? C.maxSnapScroll : 0;
            W = C.elastic ? ka(C.snap) ? n : ka(C.elastic) ? C.elastic : 0 : 0;
            k.scroll(C.snap ? V ? V[la]["snap" + u] : la * n : oa);
        };
        k.init = function (a) {
            k._init(a);
            r = (ia = "Y" == C.axis) ? "top" : "left";
            M = C.moveElement || ma.children().eq(0);
            N = M[0].style;
            k.refresh();
            if (C.swipe) ma.on("touchstart mousedown", x).on("touchmove", R).on("touchend touchcancel", y);
            if (C.mousewheel) ma.on("wheel mousewheel", t);
            c.addEventListener && c.addEventListener("click", function (a) {
                k.scrolled && (a.stopPropagation(), a.preventDefault());
            }, !0);
        };
        k.destroy = function () {
            ma.off("touchstart mousedown", x).off("touchmove", R).off("touchend touchcancel", y).off("wheel mousewheel", t);
            k._destroy();
        };
        C = k.settings;
        na = k.trigger;
        ea || k.init(e);
    };
    l.ScrollView.prototype = {
        _class: "scrollview",
        _defaults: {
            speedUnit: 0.0022,
            timeUnit: 0.8,
            axis: "Y",
            easing: "ease-out",
            swipe: !0,
            liveSwipe: !0,
            momentum: !0,
            elastic: !0
        }
    };
    c.presetShort("scrollview", "ScrollView", !1);
})(jQuery);
(function (a, m) {
    var c = a.mobiscroll,
        l = c.datetime,
        e = new Date(),
        w = {
        startYear: e.getFullYear() - 100,
        endYear: e.getFullYear() + 1,
        separator: " ",
        dateFormat: "mm/dd/yy",
        dateOrder: "mmddy",
        timeWheels: "hhiiA",
        timeFormat: "hh:ii A",
        dayText: "Day",
        monthText: "Month",
        yearText: "Year",
        hourText: "Hours",
        minuteText: "Minutes",
        ampmText: "&nbsp;",
        secText: "Seconds",
        nowText: "Now"
    },
        s = function s(e) {
        function s(a, b, d) {
            return F[b] !== m ? +a[F[b]] : J[b] !== m ? J[b] : d !== m ? d : q[b](V);
        }

        function I(a, b, d, c) {
            a.push({ values: d, keys: b, label: c });
        }

        function _i(a, b, d, c) {
            return Math.min(c, Math.floor(a / b) * b + d);
        }

        function f(a) {
            if (null === a) return a;
            var b = s(a, "y"),
                d = s(a, "m"),
                c = Math.min(s(a, "d", 1), r.getMaxDayOfMonth(b, d)),
                e = s(a, "h", 0);
            return r.getDate(b, d, c, s(a, "a", 0) ? e + 12 : e, s(a, "i", 0), s(a, "s", 0), s(a, "u", 0));
        }

        function ka(a, b) {
            var d,
                c,
                e = !1,
                g = !1,
                i = 0,
                n = 0;
            U = f(ca(U));
            N = f(ca(N));
            if (qa(a)) return a;
            a < U && (a = U);
            a > N && (a = N);
            c = d = a;
            if (2 !== b) for (e = qa(d); !e && d < N;) {
                d = new Date(d.getTime() + 864E5), e = qa(d), i++;
            }if (1 !== b) for (g = qa(c); !g && c > U;) {
                c = new Date(c.getTime() - 864E5), g = qa(c), n++;
            }return 1 === b && e ? d : 2 === b && g ? c : n <= i && g ? c : d;
        }

        function qa(a) {
            return a < U || a > N ? !1 : ba(a, K) ? !0 : ba(a, D) ? !1 : !0;
        }

        function ba(a, b) {
            var d, c, e;
            if (b) for (c = 0; c < b.length; c++) {
                if (d = b[c], e = d + "", !d.start) if (d.getTime) {
                    if (a.getFullYear() == d.getFullYear() && a.getMonth() == d.getMonth() && a.getDate() == d.getDate()) return !0;
                } else if (e.match(/w/i)) {
                    if (e = +e.replace("w", ""), e == a.getDay()) return !0;
                } else if (e = e.split("/"), e[1]) {
                    if (e[0] - 1 == a.getMonth() && e[1] == a.getDate()) return !0;
                } else if (e[0] == a.getDate()) return !0;
            }return !1;
        }

        function ha(a, b, d, c, e, g, f) {
            var i, n, k;
            if (a) for (i = 0; i < a.length; i++) {
                if (n = a[i], k = n + "", !n.start) if (n.getTime) r.getYear(n) == b && r.getMonth(n) == d && (g[r.getDay(n) - 1] = f);else if (k.match(/w/i)) {
                    k = +k.replace("w", "");
                    for (S = k - c; S < e; S += 7) {
                        0 <= S && (g[S] = f);
                    }
                } else k = k.split("/"), k[1] ? k[0] - 1 == d && (g[k[1] - 1] = f) : g[k[0] - 1] = f;
            }
        }

        function ja(c, e, f, h, n, k, l, q, o) {
            var p,
                s,
                V,
                v,
                t,
                w,
                x,
                y,
                z,
                A,
                B,
                D,
                M,
                G,
                F,
                I,
                L,
                N,
                U = {},
                H = { h: Z, i: d, s: O, a: 1 },
                K = r.getDate(n, k, l),
                J = ["a", "h", "i", "s"];
            c && (a.each(c, function (a, b) {
                if (b.start && (b.apply = !1, p = b.d, s = p + "", V = s.split("/"), p && (p.getTime && n == r.getYear(p) && k == r.getMonth(p) && l == r.getDay(p) || !s.match(/w/i) && (V[1] && l == V[1] && k == V[0] - 1 || !V[1] && l == V[0]) || s.match(/w/i) && K.getDay() == +s.replace("w", "")))) b.apply = !0, U[K] = !0;
            }), a.each(c, function (d, c) {
                B = G = M = 0;
                D = m;
                x = w = !0;
                F = !1;
                if (c.start && (c.apply || !c.d && !U[K])) {
                    v = c.start.split(":");
                    t = c.end.split(":");
                    for (A = 0; 3 > A; A++) {
                        v[A] === m && (v[A] = 0), t[A] === m && (t[A] = 59), v[A] = +v[A], t[A] = +t[A];
                    }v.unshift(11 < v[0] ? 1 : 0);
                    t.unshift(11 < t[0] ? 1 : 0);
                    b && (12 <= v[1] && (v[1] -= 12), 12 <= t[1] && (t[1] -= 12));
                    for (A = 0; A < e; A++) {
                        if (g[A] !== m) {
                            y = _i(v[A], H[J[A]], Y[J[A]], E[J[A]]);
                            z = _i(t[A], H[J[A]], Y[J[A]], E[J[A]]);
                            N = L = I = 0;
                            b && 1 == A && (I = v[0] ? 12 : 0, L = t[0] ? 12 : 0, N = g[0] ? 12 : 0);
                            w || (y = 0);
                            x || (z = E[J[A]]);
                            if ((w || x) && y + I < g[A] + N && g[A] + N < z + L) F = !0;
                            g[A] != y && (w = !1);
                            g[A] != z && (x = !1);
                        }
                    }if (!o) for (A = e + 1; 4 > A; A++) {
                        0 < v[A] && (M = H[f]), t[A] < E[J[A]] && (G = H[f]);
                    }F || (y = _i(v[e], H[f], Y[f], E[f]) + M, z = _i(t[e], H[f], Y[f], E[f]) - G, w && (B = 0 > y ? 0 : y > E[f] ? a(".dw-li", q).length : da(q, y) + 0), x && (D = 0 > z ? 0 : z > E[f] ? a(".dw-li", q).length : da(q, z) + 1));
                    if (w || x || F) o ? a(".dw-li", q).slice(B, D).addClass("dw-v") : a(".dw-li", q).slice(B, D).removeClass("dw-v");
                }
            }));
        }

        function da(b, d) {
            return a(".dw-li", b).index(a('.dw-li[data-val="' + d + '"]', b));
        }

        function ca(b, d) {
            var c = [];
            if (null === b || b === m) return b;
            a.each("y,m,d,a,h,i,s,u".split(","), function (a, e) {
                F[e] !== m && (c[F[e]] = q[e](b));
                d && (J[e] = q[e](b));
            });
            return c;
        }

        function ea(a) {
            var b,
                d,
                c,
                e = [];
            if (a) {
                for (b = 0; b < a.length; b++) {
                    if (d = a[b], d.start && d.start.getTime) for (c = new Date(d.start); c <= d.end;) {
                        e.push(new Date(c.getFullYear(), c.getMonth(), c.getDate())), c.setDate(c.getDate() + 1);
                    } else e.push(d);
                }return e;
            }
            return a;
        }

        var x = a(this),
            R = {},
            X;
        if (x.is("input")) {
            switch (x.attr("type")) {
                case "date":
                    X = "yy-mm-dd";
                    break;
                case "datetime":
                    X = "yy-mm-ddTHH:ii:ssZ";
                    break;
                case "datetime-local":
                    X = "yy-mm-ddTHH:ii:ss";
                    break;
                case "month":
                    X = "yy-mm";
                    R.dateOrder = "mmyy";
                    break;
                case "time":
                    X = "HH:ii:ss";
            }
            var y = x.attr("min"),
                x = x.attr("max");
            y && (R.minDate = l.parseDate(X, y));
            x && (R.maxDate = l.parseDate(X, x));
        }
        var t,
            S,
            T,
            G,
            ra,
            z,
            fa,
            Y,
            E,
            y = a.extend({}, e.settings),
            r = a.extend(e.settings, c.datetime.defaults, w, R, y),
            W = 0,
            g = [],
            R = [],
            P = [],
            F = {},
            J = {},
            q = {
            y: function y(a) {
                return r.getYear(a);
            }, m: function m(a) {
                return r.getMonth(a);
            }, d: function d(a) {
                return r.getDay(a);
            }, h: function h(a) {
                a = a.getHours();
                a = b && 12 <= a ? a - 12 : a;
                return _i(a, Z, M, k);
            }, i: function i(a) {
                return _i(a.getMinutes(), d, na, oa);
            }, s: function s(a) {
                return _i(a.getSeconds(), O, ia, la);
            }, u: function u(a) {
                return a.getMilliseconds();
            }, a: function a(_a) {
                return o && 11 < _a.getHours() ? 1 : 0;
            }
        },
            D = r.invalid,
            K = r.valid,
            y = r.preset,
            p = r.dateOrder,
            B = r.timeWheels,
            H = p.match(/D/),
            o = B.match(/a/i),
            b = B.match(/h/),
            n = "datetime" == y ? r.dateFormat + r.separator + r.timeFormat : "time" == y ? r.timeFormat : r.dateFormat,
            V = new Date(),
            x = r.steps || {},
            Z = x.hour || r.stepHour || 1,
            d = x.minute || r.stepMinute || 1,
            O = x.second || r.stepSecond || 1,
            x = x.zeroBased,
            U = r.minDate || new Date(r.startYear, 0, 1),
            N = r.maxDate || new Date(r.endYear, 11, 31, 23, 59, 59),
            M = x ? 0 : U.getHours() % Z,
            na = x ? 0 : U.getMinutes() % d,
            ia = x ? 0 : U.getSeconds() % O,
            k = Math.floor(((b ? 11 : 23) - M) / Z) * Z + M,
            oa = Math.floor((59 - na) / d) * d + na,
            la = Math.floor((59 - na) / d) * d + na;
        X = X || n;
        if (y.match(/date/i)) {
            a.each(["y", "m", "d"], function (a, b) {
                t = p.search(RegExp(b, "i"));
                -1 < t && P.push({ o: t, v: b });
            });
            P.sort(function (a, b) {
                return a.o > b.o ? 1 : -1;
            });
            a.each(P, function (a, b) {
                F[b.v] = a;
            });
            x = [];
            for (S = 0; 3 > S; S++) {
                if (S == F.y) {
                    W++;
                    G = [];
                    T = [];
                    ra = r.getYear(U);
                    z = r.getYear(N);
                    for (t = ra; t <= z; t++) {
                        T.push(t), G.push((p.match(/yy/i) ? t : (t + "").substr(2, 2)) + (r.yearSuffix || ""));
                    }I(x, T, G, r.yearText);
                } else if (S == F.m) {
                    W++;
                    G = [];
                    T = [];
                    for (t = 0; 12 > t; t++) {
                        ra = p.replace(/[dy]/gi, "").replace(/mm/, (9 > t ? "0" + (t + 1) : t + 1) + (r.monthSuffix || "")).replace(/m/, t + 1 + (r.monthSuffix || "")), T.push(t), G.push(ra.match(/MM/) ? ra.replace(/MM/, '<span class="dw-mon">' + r.monthNames[t] + "</span>") : ra.replace(/M/, '<span class="dw-mon">' + r.monthNamesShort[t] + "</span>"));
                    }I(x, T, G, r.monthText);
                } else if (S == F.d) {
                    W++;
                    G = [];
                    T = [];
                    for (t = 1; 32 > t; t++) {
                        T.push(t), G.push((p.match(/dd/i) && 10 > t ? "0" + t : t) + (r.daySuffix || ""));
                    }I(x, T, G, r.dayText);
                }
            }R.push(x);
        }
        if (y.match(/time/i)) {
            fa = !0;
            P = [];
            a.each(["h", "i", "s", "a"], function (a, b) {
                a = B.search(RegExp(b, "i"));
                -1 < a && P.push({ o: a, v: b });
            });
            P.sort(function (a, b) {
                return a.o > b.o ? 1 : -1;
            });
            a.each(P, function (a, b) {
                F[b.v] = W + a;
            });
            x = [];
            for (S = W; S < W + 4; S++) {
                if (S == F.h) {
                    W++;
                    G = [];
                    T = [];
                    for (t = M; t < (b ? 12 : 24); t += Z) {
                        T.push(t), G.push(b && 0 === t ? 12 : B.match(/hh/i) && 10 > t ? "0" + t : t);
                    }I(x, T, G, r.hourText);
                } else if (S == F.i) {
                    W++;
                    G = [];
                    T = [];
                    for (t = na; 60 > t; t += d) {
                        T.push(t), G.push(B.match(/ii/) && 10 > t ? "0" + t : t);
                    }I(x, T, G, r.minuteText);
                } else if (S == F.s) {
                    W++;
                    G = [];
                    T = [];
                    for (t = ia; 60 > t; t += O) {
                        T.push(t), G.push(B.match(/ss/) && 10 > t ? "0" + t : t);
                    }I(x, T, G, r.secText);
                } else S == F.a && (W++, y = B.match(/A/), I(x, [0, 1], y ? [r.amText.toUpperCase(), r.pmText.toUpperCase()] : [r.amText, r.pmText], r.ampmText));
            }R.push(x);
        }
        e.getVal = function (a) {
            return e._hasValue || a ? f(e.getArrayVal(a)) : null;
        };
        e.setDate = function (a, b, d, c, f) {
            e.setArrayVal(ca(a), b, f, c, d);
        };
        e.getDate = e.getVal;
        e.format = n;
        e.order = F;
        e.handlers.now = function () {
            e.setDate(new Date(), !1, 0.3, !0, !0);
        };
        e.buttons.now = { text: r.nowText, handler: "now" };
        D = ea(D);
        K = ea(K);
        Y = { y: U.getFullYear(), m: 0, d: 1, h: M, i: na, s: ia, a: 0 };
        E = { y: N.getFullYear(), m: 11, d: 31, h: k, i: oa, s: la, a: 1 };
        return {
            wheels: R, headerText: r.headerText ? function () {
                return l.formatDate(n, f(e.getArrayVal(!0)), r);
            } : !1, formatValue: function formatValue(a) {
                return l.formatDate(X, f(a), r);
            }, parseValue: function parseValue(a) {
                a || (J = {});
                return ca(a ? l.parseDate(X, a, r) : r.defaultValue || new Date(), !!a && !!a.getTime);
            }, validate: function validate(b, d, c, i) {
                var d = ka(f(e.getArrayVal(!0)), i),
                    n = ca(d),
                    k = s(n, "y"),
                    l = s(n, "m"),
                    o = !0,
                    t = !0;
                a.each("y,m,d,a,h,i,s".split(","), function (d, c) {
                    if (F[c] !== m) {
                        var e = Y[c],
                            f = E[c],
                            g = 31,
                            i = s(n, c),
                            h = a(".dw-ul", b).eq(F[c]);
                        if (c == "d") {
                            f = g = r.getMaxDayOfMonth(k, l);
                            H && a(".dw-li", h).each(function () {
                                var b = a(this),
                                    d = b.data("val"),
                                    c = r.getDate(k, l, d).getDay(),
                                    d = p.replace(/[my]/gi, "").replace(/dd/, (d < 10 ? "0" + d : d) + (r.daySuffix || "")).replace(/d/, d + (r.daySuffix || ""));
                                a(".dw-i", b).html(d.match(/DD/) ? d.replace(/DD/, '<span class="dw-day">' + r.dayNames[c] + "</span>") : d.replace(/D/, '<span class="dw-day">' + r.dayNamesShort[c] + "</span>"));
                            });
                        }
                        o && U && (e = q[c](U));
                        t && N && (f = q[c](N));
                        if (c != "y") {
                            var v = da(h, e),
                                V = da(h, f);
                            a(".dw-li", h).removeClass("dw-v").slice(v, V + 1).addClass("dw-v");
                            c == "d" && a(".dw-li", h).removeClass("dw-h").slice(g).addClass("dw-h");
                        }
                        i < e && (i = e);
                        i > f && (i = f);
                        o && (o = i == e);
                        t && (t = i == f);
                        if (c == "d") {
                            e = r.getDate(k, l, 1).getDay();
                            f = {};
                            ha(D, k, l, e, g, f, 1);
                            ha(K, k, l, e, g, f, 0);
                            a.each(f, function (b, d) {
                                d && a(".dw-li", h).eq(b).removeClass("dw-v");
                            });
                        }
                    }
                });
                fa && a.each(["a", "h", "i", "s"], function (d, c) {
                    var f = s(n, c),
                        q = s(n, "d"),
                        o = a(".dw-ul", b).eq(F[c]);
                    F[c] !== m && (ja(D, d, c, n, k, l, q, o, 0), ja(K, d, c, n, k, l, q, o, 1), g[d] = +e.getValidCell(f, o, i).val);
                });
                e._tempWheelArray = n;
            }
        };
    };
    a.each(["date", "time", "datetime"], function (a, e) {
        c.presets.scroller[e] = s;
    });
})(jQuery);
(function (a, m, c, l) {
    var e = a.mobiscroll,
        w = a.extend,
        s = e.util,
        v = e.datetime,
        L = e.presets.scroller,
        I = {
        labelsShort: "Yrs,Mths,Days,Hrs,Mins,Secs".split(","),
        fromText: "Start",
        toText: "End",
        eventText: "event",
        eventsText: "events"
    };
    e.presetShort("calendar");
    L.calendar = function (c) {
        function f(b) {
            if (b) {
                if (W[b]) return W[b];
                var c = a('<div style="background-color:' + b + ';"></div>').appendTo("body"),
                    e = (m.getComputedStyle ? getComputedStyle(c[0]) : c[0].style).backgroundColor.replace(/rgb|rgba|\(|\)|\s/g, "").split(","),
                    e = 130 < 0.299 * e[0] + 0.587 * e[1] + 0.114 * e[2] ? "#000" : "#fff";
                c.remove();
                return W[b] = e;
            }
        }

        function ka(a) {
            return a.sort(function (a, b) {
                var c = a.d || a.start,
                    d = b.d || b.start,
                    c = !c.getTime ? 0 : a.start && a.end && a.start.toDateString() !== a.end.toDateString() ? 1 : c.getTime(),
                    d = !d.getTime ? 0 : b.start && b.end && b.start.toDateString() !== b.end.toDateString() ? 1 : d.getTime();
                return c - d;
            });
        }

        function qa(b) {
            var c;
            c = a(".dw-cal-c", x).outerHeight();
            var e = b.outerHeight(),
                f = b.outerWidth(),
                d = b.offset().top - a(".dw-cal-c", x).offset().top,
                g = 2 > b.closest(".dw-cal-row").index();
            c = R.addClass("dw-cal-events-t").css({
                top: g ? d + e : "0",
                bottom: g ? "0" : c - d
            }).addClass("dw-cal-events-v").height();
            R.css(g ? "bottom" : "top", "auto").removeClass("dw-cal-events-t");
            S.css("max-height", c);
            t.refresh();
            t.scroll(0);
            g ? R.addClass("dw-cal-events-b") : R.removeClass("dw-cal-events-b");
            a(".dw-cal-events-arr", R).css("left", b.offset().left - R.offset().left + f / 2);
        }

        function ba(b, n) {
            var l = y[b];
            if (l) {
                var m,
                    d,
                    q,
                    o,
                    p,
                    r = '<ul class="dw-cal-event-list">';
                X = n;
                n.addClass(P).find(".dw-i").addClass(J);
                n.hasClass(F) && n.attr("data-hl", "true").removeClass(F);
                ka(l);
                a.each(l, function (a, b) {
                    o = b.d || b.start;
                    p = b.start && b.end && b.start.toDateString() !== b.end.toDateString();
                    q = b.color;
                    f(q);
                    d = m = "";
                    o.getTime && (m = e.datetime.formatDate((p ? "MM d yy " : "") + g.timeFormat, o));
                    b.end && (d = e.datetime.formatDate((p ? "MM d yy " : "") + g.timeFormat, b.end));
                    var c = r,
                        i = '<li role="button" aria-label="' + b.text + (m ? ", " + g.fromText + " " + m : "") + (d ? ", " + g.toText + " " + d : "") + '" class="dw-cal-event"><div class="dw-cal-event-color" style="' + (q ? "background:" + q + ";" : "") + '"></div><div class="dw-cal-event-text">' + (o.getTime && !p ? '<div class="dw-cal-event-time">' + e.datetime.formatDate(g.timeFormat, o) + "</div>" : "") + b.text + "</div>",
                        n;
                    if (b.start && b.end) {
                        n = g.labelsShort;
                        var l = Math.abs(b.end - b.start) / 1E3,
                            s = l / 60,
                            t = s / 60,
                            h = t / 24,
                            v = h / 365;
                        n = '<div class="dw-cal-event-dur">' + (45 > l && Math.round(l) + " " + n[5].toLowerCase() || 45 > s && Math.round(s) + " " + n[4].toLowerCase() || 24 > t && Math.round(t) + " " + n[3].toLowerCase() || 30 > h && Math.round(h) + " " + n[2].toLowerCase() || 365 > h && Math.round(h / 30) + " " + n[1].toLowerCase() || Math.round(v) + " " + n[0].toLowerCase()) + "</div>";
                    } else n = "";
                    r = c + (i + n + "</li>");
                });
                r += "</ul>";
                T.html(r);
                qa(X);
                c.tap(a(".dw-cal-event", T), function (d) {
                    t.scrolled || c.trigger("onEventSelect", [d, l[a(this).index()], b]);
                });
                G = !0;
                c.trigger("onEventBubbleShow", [X, R]);
            }
        }

        function ha() {
            R && R.removeClass("dw-cal-events-v");
            X && (X.removeClass(P).find(".dw-i").removeClass(J), X.attr("data-hl") && X.removeAttr("data-hl").addClass(F));
            G = !1;
        }

        function ja(a) {
            return new Date(a.getFullYear(), a.getMonth(), a.getDate());
        }

        function da(a) {
            B = {};
            if (a && a.length) for (z = 0; z < a.length; z++) {
                B[ja(a[z])] = a[z];
            }
        }

        function ca() {
            H && ha();
            c.refresh();
        }

        var ea,
            x,
            R,
            X,
            y,
            t,
            S,
            T,
            G,
            ra,
            z,
            fa,
            Y,
            E,
            r,
            W = {};
        E = w({}, c.settings);
        var g = w(c.settings, I, E),
            P = "dw-sel dw-cal-day-ev",
            F = "dw-cal-day-hl",
            J = g.activeClass || "",
            q = g.multiSelect || "week" == g.selectType,
            D = g.markedDisplay,
            K = !0 === g.events || !0 === g.markedText,
            p = 0,
            B = {},
            H = a.isArray(g.events),
            o = H ? w(!0, [], g.events) : [];
        E = L.calbase.call(this, c);
        ea = w({}, E);
        ra = g.firstSelectDay === l ? g.firstDay : g.firstSelectDay;
        if (g.selectedValues) for (z = 0; z < g.selectedValues.length; z++) {
            B[ja(g.selectedValues[z])] = g.selectedValues[z];
        }H && a.each(o, function (a, c) {
            c._id === l && (c._id = p++);
        });
        c.onGenMonth = function (a, e) {
            y = c.prepareObj(o, a, e);
            fa = c.prepareObj(g.marked, a, e);
        };
        c.getDayProps = function (b) {
            for (var c = q ? B[b] !== l : H ? b.getTime() === new Date().setHours(0, 0, 0, 0) : l, e = fa[b] ? fa[b][0] : !1, i = y[b] ? y[b][0] : !1, d = e || i, e = e.text || (i ? y[b].length + " " + (1 < y[b].length ? g.eventsText : g.eventText) : 0), i = fa[b] || y[b] || [], m = d.color, o = K && e ? f(m) : "", p = "", r = '<div class="dw-cal-day-m"' + (m ? ' style="background-color:' + m + ";border-color:" + m + " " + m + ' transparent transparent"' : "") + "></div>", b = 0; b < i.length; b++) {
                i[b].icon && (p += '<span class="mbsc-ic mbsc-ic-' + i[b].icon + '"' + (i[b].text ? "" : i[b].color ? ' style="color:' + i[b].color + ';"' : "") + "></span>\n");
            }if ("bottom" == D) {
                r = '<div class="dw-cal-day-m"><div class="dw-cal-day-m-t">';
                for (b = 0; b < i.length; b++) {
                    r += '<div class="dw-cal-day-m-c"' + (i[b].color ? ' style="background:' + i[b].color + ';"' : "") + "></div>";
                }r += "</div></div>";
            }
            return {
                marked: d,
                selected: H ? !1 : c,
                cssClass: H && c ? "dw-cal-day-hl" : d ? "dw-cal-day-marked" : "",
                ariaLabel: K || H ? e : "",
                markup: K && e ? '<div class="dw-cal-day-txt-c"><div class="dw-cal-day-txt ' + (g.eventTextClass || "") + '" title="' + a("<div>" + e + "</div>").text() + '"' + (m ? ' style="background:' + m + ";color:" + o + ';text-shadow:none;"' : "") + ">" + p + e + "</div></div>" : K && p ? '<div class="dw-cal-day-ic-c">' + p + "</div>" : d ? r : ""
            };
        };
        c.addValue = function (a) {
            B[ja(a)] = a;
            ca();
        };
        c.removeValue = function (a) {
            delete B[ja(a)];
            ca();
        };
        c.setVal = function (a, e, f, g, d) {
            q && (da(a), a = a ? a[0] : null);
            c.setDate(a, e, d, g, f);
            ca();
        };
        c.getVal = function (a) {
            return q ? s.objectToArray(B) : c.getDate(a);
        };
        c.setValues = function (a, e) {
            c.setDate(a ? a[0] : null, e);
            da(a);
            ca();
        };
        c.getValues = function () {
            return q ? c.getVal() : [c.getDate()];
        };
        H && (c.addEvent = function (b) {
            var c = [],
                b = w(!0, [], a.isArray(b) ? b : [b]);
            a.each(b, function (a, b) {
                b._id === l && (b._id = p++);
                o.push(b);
                c.push(b._id);
            });
            ca();
            return c;
        }, c.removeEvent = function (b) {
            b = a.isArray(b) ? b : [b];
            a.each(b, function (b, c) {
                a.each(o, function (a, b) {
                    if (b._id === c) return o.splice(a, 1), !1;
                });
            });
            ca();
        }, c.getEvents = function (a) {
            var e;
            return a ? (a.setHours(0, 0, 0, 0), e = c.prepareObj(o, a.getFullYear(), a.getMonth()), e[a] ? ka(e[a]) : []) : o;
        }, c.setEvents = function (b) {
            var c = [];
            o = w(!0, [], b);
            a.each(o, function (a, b) {
                b._id === l && (b._id = p++);
                c.push(b._id);
            });
            ca();
            return c;
        });
        w(E, {
            highlight: !q && !H,
            divergentDayChange: !q && !H,
            buttons: H && "inline" !== g.display ? ["cancel"] : g.buttons,
            parseValue: function parseValue(a) {
                var e, f;
                if (q && a) {
                    B = {};
                    a = a.split(",");
                    for (e = 0; e < a.length; e++) {
                        f = v.parseDate(c.format, a[e].replace(/^\s+|\s+$/g, ""), g);
                        B[ja(f)] = f;
                    }
                    a = a[0];
                }
                return ea.parseValue.call(this, a);
            },
            formatValue: function formatValue(a) {
                var e,
                    f = [];
                if (q) {
                    for (e in B) {
                        f.push(v.formatDate(c.format, B[e], g));
                    }return f.join(", ");
                }
                return ea.formatValue.call(this, a);
            },
            onClear: function onClear() {
                if (q) {
                    B = {};
                    c.refresh();
                }
            },
            onBeforeShow: function onBeforeShow() {
                if (H) g.headerText = false;
                if (g.closeOnSelect) g.divergentDayChange = false;
                if (g.counter && q) g.headerText = function () {
                    var b = 0,
                        c = g.selectType == "week" ? 7 : 1;
                    a.each(B, function () {
                        b++;
                    });
                    b = Math.round(b / c);
                    return b + " " + (b > 1 ? g.selectedPluralText || g.selectedText : g.selectedText);
                };
            },
            onMarkupReady: function onMarkupReady(b) {
                ea.onMarkupReady.call(this, b);
                x = b;
                if (q) {
                    a(".dwv", b).attr("aria-live", "off");
                    Y = w({}, B);
                }
                K && a(".dw-cal", b).addClass("dw-cal-ev");
                D && a(".dw-cal", b).addClass("dw-cal-m-" + D);
                if (H) {
                    b.addClass("dw-cal-em");
                    R = a('<div class="dw-cal-events ' + (g.eventBubbleClass || "") + '"><div class="dw-cal-events-arr"></div><div class="dw-cal-events-i"><div class="dw-cal-events-sc"></div></div></div>').appendTo(a(".dw-cal-c", b));
                    S = a(".dw-cal-events-i", R);
                    T = a(".dw-cal-events-sc", R);
                    t = new e.classes.ScrollView(S[0]);
                    G = false;
                    c.tap(S, function () {
                        t.scrolled || ha();
                    });
                }
            },
            onMonthChange: function onMonthChange() {
                H && ha();
            },
            onSelectShow: function onSelectShow() {
                H && ha();
            },
            onMonthLoaded: function onMonthLoaded() {
                if (r) {
                    ba(r.d, a('.dw-cal-day-v[data-full="' + r.full + '"]:not(.dw-cal-day-diff)', x));
                    r = false;
                }
            },
            onDayChange: function onDayChange(b) {
                var e = b.date,
                    f = ja(e),
                    l = a(b.cell),
                    b = b.selected;
                if (H) {
                    ha();
                    l.hasClass("dw-cal-day-ev") || setTimeout(function () {
                        c.changing ? r = { d: f, full: l.attr("data-full") } : ba(f, l);
                    }, 10);
                } else if (q) if (g.selectType == "week") {
                    var d,
                        m,
                        o = f.getDay() - ra,
                        o = o < 0 ? 7 + o : o;
                    g.multiSelect || (B = {});
                    for (d = 0; d < 7; d++) {
                        m = new Date(f.getFullYear(), f.getMonth(), f.getDate() - o + d);
                        b ? delete B[m] : B[m] = m;
                    }
                    ca();
                } else {
                    d = a('.dw-cal .dw-cal-day[data-full="' + l.attr("data-full") + '"]', x);
                    if (b) {
                        d.removeClass("dw-sel").removeAttr("aria-selected").find(".dw-i").removeClass(J);
                        delete B[f];
                    } else {
                        d.addClass("dw-sel").attr("aria-selected", "true").find(".dw-i").addClass(J);
                        B[f] = f;
                    }
                }
                if (!H && !g.multiSelect && g.closeOnSelect && g.display !== "inline") {
                    c.needsSlide = false;
                    c.setDate(e);
                    c.select();
                    return false;
                }
            },
            onCalResize: function onCalResize() {
                G && qa(X);
            },
            onCancel: function onCancel() {
                !c.live && q && (B = w({}, Y));
            }
        });
        return E;
    };
})(jQuery, window, document);
(function (a) {
    a.each(["date", "time", "datetime"], function (m, c) {
        a.mobiscroll.presetShort(c);
    });
})(jQuery);
(function (a) {
    a.mobiscroll.themes.frame["android-holo-light"] = {
        baseTheme: "android-holo",
        dateOrder: "Mddyy",
        rows: 5,
        minWidth: 76,
        height: 36,
        showLabel: !1,
        selectedLineHeight: !0,
        selectedLineBorder: 2,
        useShortLabels: !0,
        icon: { filled: "star3", empty: "star" },
        btnPlusClass: "mbsc-ic mbsc-ic-arrow-down6",
        btnMinusClass: "mbsc-ic mbsc-ic-arrow-up6"
    };
    a.mobiscroll.themes.listview["android-holo-light"] = { baseTheme: "android-holo" };
    a.mobiscroll.themes.menustrip["android-holo-light"] = { baseTheme: "android-holo" };
    a.mobiscroll.themes.form["android-holo-light"] = { baseTheme: "android-holo" };
})(jQuery);
(function (a) {
    var m,
        c,
        l,
        e = a.mobiscroll,
        w = e.themes;
    c = navigator.userAgent.match(/Android|iPhone|iPad|iPod|Windows|Windows Phone|MSIE/i);
    if (/Android/i.test(c)) {
        if (m = "android-holo", c = navigator.userAgent.match(/Android\s+([\d\.]+)/i)) c = c[0].replace("Android ", ""), m = 5 <= c.split(".")[0] ? "material" : 4 <= c.split(".")[0] ? "android-holo" : "android";
    } else if (/iPhone/i.test(c) || /iPad/i.test(c) || /iPod/i.test(c)) {
        if (m = "ios", c = navigator.userAgent.match(/OS\s+([\d\_]+)/i)) c = c[0].replace(/_/g, ".").replace("OS ", ""), m = "7" <= c ? "ios" : "ios-classic";
    } else if (/Windows/i.test(c) || /MSIE/i.test(c) || /Windows Phone/i.test(c)) m = "wp";
    a.each(w, function (c, v) {
        a.each(v, function (a, c) {
            if (c.baseTheme == m) return e.autoTheme = a, l = !0, !1;
            a == m && (e.autoTheme = a);
        });
        if (l) return !1;
    });
})(jQuery);

/***/ }),

/***/ "./resources/assets/less/AdminLTE.less":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/sass/admin.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/assets/js/app.js");
__webpack_require__("./resources/assets/sass/app.scss");
__webpack_require__("./resources/assets/sass/admin.scss");
module.exports = __webpack_require__("./resources/assets/less/AdminLTE.less");


/***/ })

/******/ });