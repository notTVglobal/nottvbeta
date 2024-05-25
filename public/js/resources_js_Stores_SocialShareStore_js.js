"use strict";
/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Stores_SocialShareStore_js"],{

/***/ "./resources/js/Stores/SocialShareStore.js":
/*!*************************************************!*\
  !*** ./resources/js/Stores/SocialShareStore.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   useUploadStore: () => (/* binding */ useUploadStore)\n/* harmony export */ });\n/* harmony import */ var pinia__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pinia */ \"./node_modules/pinia/dist/pinia.mjs\");\n\nvar initialState = function initialState() {\n  return {\n    socialSharingModal: false // show or hide Modal\n  };\n};\nvar useUploadStore = (0,pinia__WEBPACK_IMPORTED_MODULE_0__.defineStore)('uploadStore', {\n  state: initialState,\n  actions: {\n    reset: function reset() {\n      // Reset the store to its original state (clear all data)\n      Object.assign(this, initialState());\n    },\n    showSocialSharing: function showSocialSharing() {\n      this.socialSharingModal = true;\n    },\n    hideSocialSharing: function hideSocialSharing() {\n      this.socialSharingModal = false;\n    }\n  },\n  getters: {\n    //\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvU3RvcmVzL1NvY2lhbFNoYXJlU3RvcmUuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7QUFBbUM7QUFFbkMsSUFBTUMsWUFBWSxHQUFHLFNBQWZBLFlBQVlBLENBQUE7RUFBQSxPQUFVO0lBQ3hCQyxrQkFBa0IsRUFBRSxLQUFLLENBQUU7RUFDL0IsQ0FBQztBQUFBLENBQUM7QUFFSyxJQUFNQyxjQUFjLEdBQUdILGtEQUFXLENBQUMsYUFBYSxFQUFFO0VBQ3JESSxLQUFLLEVBQUVILFlBQVk7RUFDbkJJLE9BQU8sRUFBRTtJQUNMQyxLQUFLLFdBQUFBLE1BQUEsRUFBRztNQUNSO01BQ0lDLE1BQU0sQ0FBQ0MsTUFBTSxDQUFDLElBQUksRUFBRVAsWUFBWSxDQUFDLENBQUMsQ0FBQztJQUN2QyxDQUFDO0lBQ0RRLGlCQUFpQixXQUFBQSxrQkFBQSxFQUFHO01BQ2hCLElBQUksQ0FBQ1Asa0JBQWtCLEdBQUcsSUFBSTtJQUNsQyxDQUFDO0lBQ0RRLGlCQUFpQixXQUFBQSxrQkFBQSxFQUFHO01BQ2hCLElBQUksQ0FBQ1Isa0JBQWtCLEdBQUcsS0FBSztJQUNuQztFQUNKLENBQUM7RUFDRFMsT0FBTyxFQUFFO0lBQ0w7RUFBQTtBQUVSLENBQUMsQ0FBQyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9TdG9yZXMvU29jaWFsU2hhcmVTdG9yZS5qcz8wNTlkIl0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IGRlZmluZVN0b3JlIH0gZnJvbSAncGluaWEnXHJcblxyXG5jb25zdCBpbml0aWFsU3RhdGUgPSAoKSA9PiAoe1xyXG4gICAgc29jaWFsU2hhcmluZ01vZGFsOiBmYWxzZSwgLy8gc2hvdyBvciBoaWRlIE1vZGFsXHJcbn0pXHJcblxyXG5leHBvcnQgY29uc3QgdXNlVXBsb2FkU3RvcmUgPSBkZWZpbmVTdG9yZSgndXBsb2FkU3RvcmUnLCB7XHJcbiAgICBzdGF0ZTogaW5pdGlhbFN0YXRlLFxyXG4gICAgYWN0aW9uczoge1xyXG4gICAgICAgIHJlc2V0KCkge1xyXG4gICAgICAgIC8vIFJlc2V0IHRoZSBzdG9yZSB0byBpdHMgb3JpZ2luYWwgc3RhdGUgKGNsZWFyIGFsbCBkYXRhKVxyXG4gICAgICAgICAgICBPYmplY3QuYXNzaWduKHRoaXMsIGluaXRpYWxTdGF0ZSgpKVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc2hvd1NvY2lhbFNoYXJpbmcoKSB7XHJcbiAgICAgICAgICAgIHRoaXMuc29jaWFsU2hhcmluZ01vZGFsID0gdHJ1ZVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgaGlkZVNvY2lhbFNoYXJpbmcoKSB7XHJcbiAgICAgICAgICAgIHRoaXMuc29jaWFsU2hhcmluZ01vZGFsID0gZmFsc2VcclxuICAgICAgICB9LFxyXG4gICAgfSxcclxuICAgIGdldHRlcnM6IHtcclxuICAgICAgICAvL1xyXG4gICAgfVxyXG59KSJdLCJuYW1lcyI6WyJkZWZpbmVTdG9yZSIsImluaXRpYWxTdGF0ZSIsInNvY2lhbFNoYXJpbmdNb2RhbCIsInVzZVVwbG9hZFN0b3JlIiwic3RhdGUiLCJhY3Rpb25zIiwicmVzZXQiLCJPYmplY3QiLCJhc3NpZ24iLCJzaG93U29jaWFsU2hhcmluZyIsImhpZGVTb2NpYWxTaGFyaW5nIiwiZ2V0dGVycyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/Stores/SocialShareStore.js\n");

/***/ })

}]);