"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_ts_components_addBooks_StepTwoComponent_vue"],{

/***/ "./node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/ts-loader/index.js??clonedRuleSet-6!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=script&setup=true&lang=ts":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/ts-loader/index.js??clonedRuleSet-6!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=script&setup=true&lang=ts ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _StarRating_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./StarRating.vue */ "./resources/ts/components/addBooks/StarRating.vue");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/store */ "./resources/ts/store.ts");
/* harmony import */ var vue_router__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-router */ "./node_modules/vue-router/dist/vue-router.mjs");
var __awaiter = undefined && undefined.__awaiter || function (thisArg, _arguments, P, generator) {
  function adopt(value) {
    return value instanceof P ? value : new P(function (resolve) {
      resolve(value);
    });
  }
  return new (P || (P = Promise))(function (resolve, reject) {
    function fulfilled(value) {
      try {
        step(generator.next(value));
      } catch (e) {
        reject(e);
      }
    }
    function rejected(value) {
      try {
        step(generator["throw"](value));
      } catch (e) {
        reject(e);
      }
    }
    function step(result) {
      result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected);
    }
    step((generator = generator.apply(thisArg, _arguments || [])).next());
  });
};
var __generator = undefined && undefined.__generator || function (thisArg, body) {
  var _ = {
      label: 0,
      sent: function sent() {
        if (t[0] & 1) throw t[1];
        return t[1];
      },
      trys: [],
      ops: []
    },
    f,
    y,
    t,
    g;
  return g = {
    next: verb(0),
    "throw": verb(1),
    "return": verb(2)
  }, typeof Symbol === "function" && (g[Symbol.iterator] = function () {
    return this;
  }), g;
  function verb(n) {
    return function (v) {
      return step([n, v]);
    };
  }
  function step(op) {
    if (f) throw new TypeError("Generator is already executing.");
    while (g && (g = 0, op[0] && (_ = 0)), _) try {
      if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
      if (y = 0, t) op = [op[0] & 2, t.value];
      switch (op[0]) {
        case 0:
        case 1:
          t = op;
          break;
        case 4:
          _.label++;
          return {
            value: op[1],
            done: false
          };
        case 5:
          _.label++;
          y = op[1];
          op = [0];
          continue;
        case 7:
          op = _.ops.pop();
          _.trys.pop();
          continue;
        default:
          if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) {
            _ = 0;
            continue;
          }
          if (op[0] === 3 && (!t || op[1] > t[0] && op[1] < t[3])) {
            _.label = op[1];
            break;
          }
          if (op[0] === 6 && _.label < t[1]) {
            _.label = t[1];
            t = op;
            break;
          }
          if (t && _.label < t[2]) {
            _.label = t[2];
            _.ops.push(op);
            break;
          }
          if (t[2]) _.ops.pop();
          _.trys.pop();
          continue;
      }
      op = body.call(thisArg, _);
    } catch (e) {
      op = [6, e];
      y = 0;
    } finally {
      f = t = 0;
    }
    if (op[0] & 5) throw op[1];
    return {
      value: op[0] ? op[1] : void 0,
      done: true
    };
  }
};






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  __name: 'StepTwoComponent',
  props: {
    book: Object
  },
  emits: ['completeStepTwo'],
  setup: function setup(__props, _a) {
    var _this = this;
    var __expose = _a.expose,
      __emit = _a.emit;
    __expose();
    var props = __props;
    var authStore = (0,_store__WEBPACK_IMPORTED_MODULE_3__.useAuthStore)();
    var router = (0,vue_router__WEBPACK_IMPORTED_MODULE_4__.useRouter)();
    var readingStatus = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)('');
    var rating = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(0);
    var currentPage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    var comment = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)('');
    var emit = __emit;
    var updateRating = function updateRating(newRating) {
      console.log('New rating:', newRating);
      rating.value = newRating;
    };
    var submitBook = function submitBook() {
      return __awaiter(_this, void 0, void 0, function () {
        var bookData, response, error_1;
        return __generator(this, function (_a) {
          switch (_a.label) {
            case 0:
              bookData = {
                book: props.book,
                rating: rating.value,
                readingStatus: readingStatus.value,
                comment: comment.value,
                currentPage: currentPage.value
              };
              _a.label = 1;
            case 1:
              _a.trys.push([1, 3,, 4]);
              return [4 /*yield*/, axios__WEBPACK_IMPORTED_MODULE_2___default().post('/api/addBook', bookData, {
                headers: {
                  'Authorization': "Bearer ".concat(authStore.token),
                  'Content-Type': 'application/json'
                }
              })];
            case 2:
              response = _a.sent();
              if (response.status === 200) {
                router.push({
                  name: 'Dashboard'
                });
              }
              return [3 /*break*/, 4];
            case 3:
              error_1 = _a.sent();
              console.error('Error submitting book:', error_1);
              return [3 /*break*/, 4];
            case 4:
              return [2 /*return*/];
          }
        });
      });
    };
    var __returned__ = {
      props: props,
      authStore: authStore,
      router: router,
      readingStatus: readingStatus,
      rating: rating,
      currentPage: currentPage,
      comment: comment,
      emit: emit,
      updateRating: updateRating,
      submitBook: submitBook,
      StarRating: _StarRating_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
    };
    Object.defineProperty(__returned__, '__isScriptSetup', {
      enumerable: false,
      value: true
    });
    return __returned__;
  }
}));

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/ts-loader/index.js??clonedRuleSet-6!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/ts-loader/index.js??clonedRuleSet-6!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
var __spreadArray = undefined && undefined.__spreadArray || function (to, from, pack) {
  if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
    if (ar || !(i in from)) {
      if (!ar) ar = Array.prototype.slice.call(from, 0, i);
      ar[i] = from[i];
    }
  }
  return to.concat(ar || Array.prototype.slice.call(from));
};

var _hoisted_1 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", null, "Step 2: Confirm Book Details", -1 /* HOISTED */);
var _hoisted_2 = {
  "class": "flex flex-col items-center p-8 bg-white rounded text-sm"
};
var _hoisted_3 = {
  key: 0,
  "class": "w-full"
};
var _hoisted_4 = {
  "class": "text-2xl font-bold text-gray-800 mb-4"
};
var _hoisted_5 = {
  "class": "mb-2 text-gray-600"
};
var _hoisted_6 = {
  "class": "italic mb-4 text-gray-600"
};
var _hoisted_7 = {
  "class": "flex flex-col md:flex-row"
};
var _hoisted_8 = {
  "class": "md:w-1/3 mb-4 md:mb-0"
};
var _hoisted_9 = ["src"];
var _hoisted_10 = {
  "class": "md:w-2/3 ml-4"
};
var _hoisted_11 = {
  "class": "mt-4"
};
var _hoisted_12 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "status",
  "class": "block text-gray-700 font-bold mb-2"
}, "Status de lecture:", -1 /* HOISTED */);
var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: "to_read"
}, "A lire", -1 /* HOISTED */);
var _hoisted_14 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: "reading"
}, "En cours de lecture", -1 /* HOISTED */);
var _hoisted_15 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: "read"
}, "Lu", -1 /* HOISTED */);
var _hoisted_16 = [_hoisted_13, _hoisted_14, _hoisted_15];
var _hoisted_17 = {
  key: 0,
  "class": "mt-4"
};
var _hoisted_18 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "currentPage",
  "class": "block text-gray-700 font-bold mb-2"
}, "Page actuelle:", -1 /* HOISTED */);
var _hoisted_19 = ["max"];
var _hoisted_20 = {
  "class": "mt-4"
};
var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "rating",
  "class": "block text-gray-700 font-bold mb-2"
}, "Note:", -1 /* HOISTED */);
var _hoisted_22 = {
  "class": "mt-4"
};
var _hoisted_23 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "comment",
  "class": "block text-gray-700 font-bold mb-2"
}, "Commentaire:", -1 /* HOISTED */);
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _a;
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [_hoisted_1, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [$setup.props.book ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", _hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($setup.props.book.volumeInfo.title || 'Titre non disponible'), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($setup.props.book.volumeInfo.authors && $setup.props.book.volumeInfo.authors.join(', ') || 'Auteur(s) non disponible(s)') + " | " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($setup.props.book.volumeInfo.publishedDate || 'Date non disponible'), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, " Catégorie : " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($setup.props.book.volumeInfo.categories ? $setup.props.book.volumeInfo.categories[0] : 'Non spécifiée'), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
    src: ((_a = $setup.props.book.volumeInfo.imageLinks) === null || _a === void 0 ? void 0 : _a.thumbnail) || '/images/default-cover.png',
    alt: "Couverture du livre",
    "class": "w-full"
  }, null, 8 /* PROPS */, _hoisted_9)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($setup.props.book.volumeInfo.description || 'Description non disponible') + " ", 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [_hoisted_12, (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
    id: "status",
    "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
      return $setup.readingStatus = $event;
    }),
    "class": "w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"
  }, __spreadArray([], _hoisted_16, true), 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelSelect, $setup.readingStatus]])]), $setup.readingStatus === 'reading' ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_17, [_hoisted_18, (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "number",
    id: "currentPage",
    "onUpdate:modelValue": _cache[1] || (_cache[1] = function ($event) {
      return $setup.currentPage = $event;
    }),
    max: $setup.props.book.volumeInfo.pageCount,
    "class": "w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500",
    placeholder: "Numéro de la page actuelle"
  }, null, 8 /* PROPS */, _hoisted_19), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, $setup.currentPage]])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [_hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["StarRating"], {
    initialRating: $setup.rating,
    "onUpdate:rating": $setup.updateRating
  }, null, 8 /* PROPS */, ["initialRating"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [_hoisted_23, (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("textarea", {
    id: "comment",
    "onUpdate:modelValue": _cache[2] || (_cache[2] = function ($event) {
      return $setup.comment = $event;
    }),
    placeholder: "Entrez votre commentaire",
    "class": "w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, $setup.comment]])])])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    "class": "mt-4 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition self-end",
    onClick: $setup.submitBook
  }, " Ajouter le livre ")])], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./resources/ts/components/addBooks/StepTwoComponent.vue":
/*!***************************************************************!*\
  !*** ./resources/ts/components/addBooks/StepTwoComponent.vue ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _StepTwoComponent_vue_vue_type_template_id_060dd632_ts_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true */ "./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true");
/* harmony import */ var _StepTwoComponent_vue_vue_type_script_setup_true_lang_ts__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./StepTwoComponent.vue?vue&type=script&setup=true&lang=ts */ "./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=script&setup=true&lang=ts");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_StepTwoComponent_vue_vue_type_script_setup_true_lang_ts__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_StepTwoComponent_vue_vue_type_template_id_060dd632_ts_true__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/ts/components/addBooks/StepTwoComponent.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=script&setup=true&lang=ts":
/*!**************************************************************************************************!*\
  !*** ./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=script&setup=true&lang=ts ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_laravel_mix_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_ts_loader_index_js_clonedRuleSet_6_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_StepTwoComponent_vue_vue_type_script_setup_true_lang_ts__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_ts_loader_index_js_clonedRuleSet_6_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_StepTwoComponent_vue_vue_type_script_setup_true_lang_ts__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/ts-loader/index.js??clonedRuleSet-6!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./StepTwoComponent.vue?vue&type=script&setup=true&lang=ts */ "./node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/ts-loader/index.js??clonedRuleSet-6!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=script&setup=true&lang=ts");
 

/***/ }),

/***/ "./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true":
/*!*****************************************************************************************************!*\
  !*** ./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_laravel_mix_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_ts_loader_index_js_clonedRuleSet_6_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_StepTwoComponent_vue_vue_type_template_id_060dd632_ts_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_ts_loader_index_js_clonedRuleSet_6_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_3_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_StepTwoComponent_vue_vue_type_template_id_060dd632_ts_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/ts-loader/index.js??clonedRuleSet-6!../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[3]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true */ "./node_modules/laravel-mix/node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/ts-loader/index.js??clonedRuleSet-6!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[3]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/ts/components/addBooks/StepTwoComponent.vue?vue&type=template&id=060dd632&ts=true");


/***/ })

}]);