"use strict";
/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Stores_UploadStore_js"],{

/***/ "./resources/js/Stores/UploadStore.js":
/*!********************************************!*\
  !*** ./resources/js/Stores/UploadStore.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   useUploadStore: () => (/* binding */ useUploadStore)\n/* harmony export */ });\n/* harmony import */ var pinia__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pinia */ \"./node_modules/pinia/dist/pinia.mjs\");\n\nvar initialState = function initialState() {\n  return {\n    videoId: null,\n    uploadStatus: 'idle' // Possible values: 'idle', 'uploading', 'processing', 'completed', 'error'\n  };\n};\nvar useUploadStore = (0,pinia__WEBPACK_IMPORTED_MODULE_0__.defineStore)('uploadStore', {\n  state: initialState,\n  actions: {\n    reset: function reset() {\n      // Reset the store to its original state (clear all data)\n      Object.assign(this, initialState());\n    },\n    setVideoId: function setVideoId(id) {\n      this.videoId = id;\n    },\n    setUploadStatus: function setUploadStatus(status) {\n      this.uploadStatus = status;\n    }\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvU3RvcmVzL1VwbG9hZFN0b3JlLmpzIiwibWFwcGluZ3MiOiI7Ozs7O0FBQW1DO0FBRW5DLElBQU1DLFlBQVksR0FBRyxTQUFmQSxZQUFZQSxDQUFBO0VBQUEsT0FBVTtJQUN4QkMsT0FBTyxFQUFFLElBQUk7SUFDYkMsWUFBWSxFQUFFLE1BQU0sQ0FBRTtFQUMxQixDQUFDO0FBQUEsQ0FBQztBQUVLLElBQU1DLGNBQWMsR0FBR0osa0RBQVcsQ0FBQyxhQUFhLEVBQUU7RUFDckRLLEtBQUssRUFBRUosWUFBWTtFQUNuQkssT0FBTyxFQUFFO0lBQ0xDLEtBQUssV0FBQUEsTUFBQSxFQUFHO01BQ0o7TUFDQUMsTUFBTSxDQUFDQyxNQUFNLENBQUMsSUFBSSxFQUFFUixZQUFZLENBQUMsQ0FBQyxDQUFDO0lBQ3ZDLENBQUM7SUFDRFMsVUFBVSxXQUFBQSxXQUFDQyxFQUFFLEVBQUU7TUFDWCxJQUFJLENBQUNULE9BQU8sR0FBR1MsRUFBRTtJQUNyQixDQUFDO0lBQ0RDLGVBQWUsV0FBQUEsZ0JBQUNDLE1BQU0sRUFBRTtNQUNwQixJQUFJLENBQUNWLFlBQVksR0FBR1UsTUFBTTtJQUM5QjtFQUNKO0FBQ0osQ0FBQyxDQUFDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL1N0b3Jlcy9VcGxvYWRTdG9yZS5qcz85MzE2Il0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IGRlZmluZVN0b3JlIH0gZnJvbSAncGluaWEnXG5cbmNvbnN0IGluaXRpYWxTdGF0ZSA9ICgpID0+ICh7XG4gICAgdmlkZW9JZDogbnVsbCxcbiAgICB1cGxvYWRTdGF0dXM6ICdpZGxlJywgLy8gUG9zc2libGUgdmFsdWVzOiAnaWRsZScsICd1cGxvYWRpbmcnLCAncHJvY2Vzc2luZycsICdjb21wbGV0ZWQnLCAnZXJyb3InXG59KVxuXG5leHBvcnQgY29uc3QgdXNlVXBsb2FkU3RvcmUgPSBkZWZpbmVTdG9yZSgndXBsb2FkU3RvcmUnLCB7XG4gICAgc3RhdGU6IGluaXRpYWxTdGF0ZSxcbiAgICBhY3Rpb25zOiB7XG4gICAgICAgIHJlc2V0KCkge1xuICAgICAgICAgICAgLy8gUmVzZXQgdGhlIHN0b3JlIHRvIGl0cyBvcmlnaW5hbCBzdGF0ZSAoY2xlYXIgYWxsIGRhdGEpXG4gICAgICAgICAgICBPYmplY3QuYXNzaWduKHRoaXMsIGluaXRpYWxTdGF0ZSgpKVxuICAgICAgICB9LFxuICAgICAgICBzZXRWaWRlb0lkKGlkKSB7XG4gICAgICAgICAgICB0aGlzLnZpZGVvSWQgPSBpZDtcbiAgICAgICAgfSxcbiAgICAgICAgc2V0VXBsb2FkU3RhdHVzKHN0YXR1cykge1xuICAgICAgICAgICAgdGhpcy51cGxvYWRTdGF0dXMgPSBzdGF0dXM7XG4gICAgICAgIH0sXG4gICAgfSxcbn0pIl0sIm5hbWVzIjpbImRlZmluZVN0b3JlIiwiaW5pdGlhbFN0YXRlIiwidmlkZW9JZCIsInVwbG9hZFN0YXR1cyIsInVzZVVwbG9hZFN0b3JlIiwic3RhdGUiLCJhY3Rpb25zIiwicmVzZXQiLCJPYmplY3QiLCJhc3NpZ24iLCJzZXRWaWRlb0lkIiwiaWQiLCJzZXRVcGxvYWRTdGF0dXMiLCJzdGF0dXMiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/Stores/UploadStore.js\n");

/***/ })

}]);