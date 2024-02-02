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

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   useUploadStore: () => (/* binding */ useUploadStore)\n/* harmony export */ });\n/* harmony import */ var pinia__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pinia */ \"./node_modules/pinia/dist/pinia.mjs\");\n\nvar initialState = function initialState() {\n  return {\n    videoId: null,\n    uploadStatus: 'idle' // Possible values: 'idle', 'uploading', 'processing', 'completed', 'error'\n  };\n};\nvar useUploadStore = (0,pinia__WEBPACK_IMPORTED_MODULE_0__.defineStore)('uploadStore', {\n  state: initialState,\n  actions: {\n    reset: function reset() {\n      // Reset the store to its original state (clear all data)\n      Object.assign(this, initialState());\n    },\n    setVideoId: function setVideoId(id) {\n      this.videoId = id;\n    },\n    setUploadStatus: function setUploadStatus(status) {\n      this.uploadStatus = status;\n    }\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvU3RvcmVzL1VwbG9hZFN0b3JlLmpzIiwibWFwcGluZ3MiOiI7Ozs7O0FBQW1DO0FBRW5DLElBQU1DLFlBQVksR0FBRyxTQUFmQSxZQUFZQSxDQUFBO0VBQUEsT0FBVTtJQUN4QkMsT0FBTyxFQUFFLElBQUk7SUFDYkMsWUFBWSxFQUFFLE1BQU0sQ0FBRTtFQUMxQixDQUFDO0FBQUEsQ0FBQztBQUVLLElBQU1DLGNBQWMsR0FBR0osa0RBQVcsQ0FBQyxhQUFhLEVBQUU7RUFDckRLLEtBQUssRUFBRUosWUFBWTtFQUNuQkssT0FBTyxFQUFFO0lBQ0xDLEtBQUssV0FBQUEsTUFBQSxFQUFHO01BQ0o7TUFDQUMsTUFBTSxDQUFDQyxNQUFNLENBQUMsSUFBSSxFQUFFUixZQUFZLENBQUMsQ0FBQyxDQUFDO0lBQ3ZDLENBQUM7SUFDRFMsVUFBVSxXQUFBQSxXQUFDQyxFQUFFLEVBQUU7TUFDWCxJQUFJLENBQUNULE9BQU8sR0FBR1MsRUFBRTtJQUNyQixDQUFDO0lBQ0RDLGVBQWUsV0FBQUEsZ0JBQUNDLE1BQU0sRUFBRTtNQUNwQixJQUFJLENBQUNWLFlBQVksR0FBR1UsTUFBTTtJQUM5QjtFQUNKO0FBQ0osQ0FBQyxDQUFDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL1N0b3Jlcy9VcGxvYWRTdG9yZS5qcz85MzE2Il0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IGRlZmluZVN0b3JlIH0gZnJvbSAncGluaWEnXHJcblxyXG5jb25zdCBpbml0aWFsU3RhdGUgPSAoKSA9PiAoe1xyXG4gICAgdmlkZW9JZDogbnVsbCxcclxuICAgIHVwbG9hZFN0YXR1czogJ2lkbGUnLCAvLyBQb3NzaWJsZSB2YWx1ZXM6ICdpZGxlJywgJ3VwbG9hZGluZycsICdwcm9jZXNzaW5nJywgJ2NvbXBsZXRlZCcsICdlcnJvcidcclxufSlcclxuXHJcbmV4cG9ydCBjb25zdCB1c2VVcGxvYWRTdG9yZSA9IGRlZmluZVN0b3JlKCd1cGxvYWRTdG9yZScsIHtcclxuICAgIHN0YXRlOiBpbml0aWFsU3RhdGUsXHJcbiAgICBhY3Rpb25zOiB7XHJcbiAgICAgICAgcmVzZXQoKSB7XHJcbiAgICAgICAgICAgIC8vIFJlc2V0IHRoZSBzdG9yZSB0byBpdHMgb3JpZ2luYWwgc3RhdGUgKGNsZWFyIGFsbCBkYXRhKVxyXG4gICAgICAgICAgICBPYmplY3QuYXNzaWduKHRoaXMsIGluaXRpYWxTdGF0ZSgpKVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc2V0VmlkZW9JZChpZCkge1xyXG4gICAgICAgICAgICB0aGlzLnZpZGVvSWQgPSBpZDtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHNldFVwbG9hZFN0YXR1cyhzdGF0dXMpIHtcclxuICAgICAgICAgICAgdGhpcy51cGxvYWRTdGF0dXMgPSBzdGF0dXM7XHJcbiAgICAgICAgfSxcclxuICAgIH0sXHJcbn0pIl0sIm5hbWVzIjpbImRlZmluZVN0b3JlIiwiaW5pdGlhbFN0YXRlIiwidmlkZW9JZCIsInVwbG9hZFN0YXR1cyIsInVzZVVwbG9hZFN0b3JlIiwic3RhdGUiLCJhY3Rpb25zIiwicmVzZXQiLCJPYmplY3QiLCJhc3NpZ24iLCJzZXRWaWRlb0lkIiwiaWQiLCJzZXRVcGxvYWRTdGF0dXMiLCJzdGF0dXMiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/Stores/UploadStore.js\n");

/***/ })

}]);