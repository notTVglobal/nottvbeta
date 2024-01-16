"use strict";
/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Stores_MovieStore_js"],{

/***/ "./resources/js/Stores/MovieStore.js":
/*!*******************************************!*\
  !*** ./resources/js/Stores/MovieStore.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   useMovieStore: () => (/* binding */ useMovieStore)\n/* harmony export */ });\n/* harmony import */ var pinia__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pinia */ \"./node_modules/pinia/dist/pinia.mjs\");\n\nvar initialState = function initialState() {\n  return {\n    id: 0,\n    category_id: 0,\n    category_sub_id: 0,\n    category_description: '',\n    sub_categories: []\n  };\n};\nvar useMovieStore = (0,pinia__WEBPACK_IMPORTED_MODULE_0__.defineStore)('movieStore', {\n  state: initialState,\n  actions: {\n    reset: function reset() {\n      // Reset the store to its original state (clear all data)\n      Object.assign(this, initialState());\n    } //\n  },\n  getters: {\n    //\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvU3RvcmVzL01vdmllU3RvcmUuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7QUFBb0M7QUFFcEMsSUFBTUMsWUFBWSxHQUFHLFNBQWZBLFlBQVlBLENBQUE7RUFBQSxPQUFVO0lBQ3hCQyxFQUFFLEVBQUUsQ0FBQztJQUNMQyxXQUFXLEVBQUUsQ0FBQztJQUNkQyxlQUFlLEVBQUUsQ0FBQztJQUNsQkMsb0JBQW9CLEVBQUUsRUFBRTtJQUN4QkMsY0FBYyxFQUFFO0VBQ3BCLENBQUM7QUFBQSxDQUFDO0FBRUssSUFBTUMsYUFBYSxHQUFHUCxrREFBVyxDQUFDLFlBQVksRUFBRTtFQUNuRFEsS0FBSyxFQUFFUCxZQUFZO0VBQ25CUSxPQUFPLEVBQUU7SUFDTEMsS0FBSyxXQUFBQSxNQUFBLEVBQUc7TUFDSjtNQUNBQyxNQUFNLENBQUNDLE1BQU0sQ0FBQyxJQUFJLEVBQUVYLFlBQVksQ0FBQyxDQUFDLENBQUM7SUFDdkMsQ0FBQyxDQUNEO0VBQ0osQ0FBQztFQUVEWSxPQUFPLEVBQUU7SUFDTDtFQUFBO0FBRVIsQ0FBQyxDQUFDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL1N0b3Jlcy9Nb3ZpZVN0b3JlLmpzPzMzN2QiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgZGVmaW5lU3RvcmUgfSBmcm9tIFwicGluaWFcIjtcblxuY29uc3QgaW5pdGlhbFN0YXRlID0gKCkgPT4gKHtcbiAgICBpZDogMCxcbiAgICBjYXRlZ29yeV9pZDogMCxcbiAgICBjYXRlZ29yeV9zdWJfaWQ6IDAsXG4gICAgY2F0ZWdvcnlfZGVzY3JpcHRpb246ICcnLFxuICAgIHN1Yl9jYXRlZ29yaWVzOiBbXSxcbn0pXG5cbmV4cG9ydCBjb25zdCB1c2VNb3ZpZVN0b3JlID0gZGVmaW5lU3RvcmUoJ21vdmllU3RvcmUnLCB7XG4gICAgc3RhdGU6IGluaXRpYWxTdGF0ZSxcbiAgICBhY3Rpb25zOiB7XG4gICAgICAgIHJlc2V0KCkge1xuICAgICAgICAgICAgLy8gUmVzZXQgdGhlIHN0b3JlIHRvIGl0cyBvcmlnaW5hbCBzdGF0ZSAoY2xlYXIgYWxsIGRhdGEpXG4gICAgICAgICAgICBPYmplY3QuYXNzaWduKHRoaXMsIGluaXRpYWxTdGF0ZSgpKTtcbiAgICAgICAgfSxcbiAgICAgICAgLy9cbiAgICB9LFxuXG4gICAgZ2V0dGVyczoge1xuICAgICAgICAvL1xuICAgIH1cbn0pO1xuIl0sIm5hbWVzIjpbImRlZmluZVN0b3JlIiwiaW5pdGlhbFN0YXRlIiwiaWQiLCJjYXRlZ29yeV9pZCIsImNhdGVnb3J5X3N1Yl9pZCIsImNhdGVnb3J5X2Rlc2NyaXB0aW9uIiwic3ViX2NhdGVnb3JpZXMiLCJ1c2VNb3ZpZVN0b3JlIiwic3RhdGUiLCJhY3Rpb25zIiwicmVzZXQiLCJPYmplY3QiLCJhc3NpZ24iLCJnZXR0ZXJzIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/Stores/MovieStore.js\n");

/***/ })

}]);