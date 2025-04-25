/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./block/ppg-menu.js":
/*!***************************!*\
  !*** ./block/ppg-menu.js ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



const PPGmenu = ({
  menuLocation = 'ppgMenu',
  onMenuLoad = () => {},
  onCategorySelect = () => {}
}) => {
  const [menuItems, setMenuItems] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)([]);
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!menuLocation) {
      setError('No Menu Location provided');
      return;
    }
    const restPath = `mytheme/v1/menu-items/${menuLocation}`;
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
      path: restPath
    }).then(items => {
      setMenuItems(items);
      const categoryIds = items.filter(item => item.object === 'category').map(item => item.object_id);
      onMenuLoad(categoryIds);
    }).catch(err => {
      setError(err.message || 'Fetch error');
      onMenuLoad([]);
    });
  }, [menuLocation]);
  if (error) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
      children: ["Error: ", error]
    });
  }
  if (!menuItems.length) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      children: "Loading menu\u2026"
    });
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    class: "ppg-menu-wrapper",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("nav", {
      className: "ppg-menu-container",
      "aria-label": "Category Menu",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("ul", {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("li", {
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("a", {
            href: "#",
            className: "ppg-menu-item",
            onClick: e => {
              e.preventDefault();
              onCategorySelect(null);
            },
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h5", {
              children: "All"
            })
          })
        }), menuItems.map(item => {
          if (item.object !== 'category') {
            return null;
          }
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("li", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("a", {
              href: "#",
              className: "ppg-menu-item",
              onClick: e => {
                e.preventDefault();
                onCategorySelect(item.object_id);
              },
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h5", {
                children: item.title
              })
            })
          }, item.ID);
        })]
      })
    })
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PPGmenu);

/***/ }),

/***/ "./block/ppg.js":
/*!**********************!*\
  !*** ./block/ppg.js ***!
  \**********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _ppg_menu__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ppg-menu */ "./block/ppg-menu.js");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);







// Helper functions

const sanitizeNumber = num => Number.isInteger(num) ? num : 0;
const sanitizeArray = arr => Array.isArray(arr) ? arr : [];
const sanitizeString = str => typeof str === 'string' ? str : '';
wp.blocks.registerBlockType('pentaportfoliogrid/ppg', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Penta Portfolio Grid', 'ppg'),
  icon: 'grid-view',
  category: 'media',
  attributes: {
    textColor: {
      type: 'string',
      default: '#222'
    },
    tagName: {
      type: 'string',
      default: 'h4'
    },
    title: {
      type: 'string',
      source: 'text',
      default: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Default Title', 'pentaportfoliogrid')
    },
    paddingLeftRight: {
      type: 'string',
      default: '35px'
    },
    paddingTop: {
      type: 'string',
      default: '30px'
    },
    paddingBottom: {
      type: 'string',
      default: '0px'
    },
    selectedCategories: {
      type: 'array',
      default: []
    },
    numberOfItems: {
      type: 'number',
      default: 5
    },
    order: {
      type: 'string',
      default: 'desc'
    },
    orderBy: {
      type: 'string',
      default: 'date'
    },
    objectPositions: {
      type: 'array',
      default: []
    }
  },
  edit: EditComponent,
  save: () => null
});
function EditComponent({
  attributes,
  setAttributes
}) {
  const {
    tagName,
    textColor,
    paddingLeftRight,
    paddingTop,
    paddingBottom,
    objectPositions
  } = attributes;
  const [posts, setPosts] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)([]);
  const [loading, setLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(true);
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const [categoryIds, setCategoryIds] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)([]);
  const [selectedCategory, setSelectedCategory] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const [selectedElementIndex, setSelectedElementIndex] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const [objectPositionX, setObjectPositionX] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(50);
  const [objectPositionY, setObjectPositionY] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(50);
  const imageRefs = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useRef)([]);
  const cacheRef = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useRef)({});

  // Fetch posts when categoryIds or selectedCategory change
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    let categoriesParam = '';
    if (selectedCategory !== null) {
      categoriesParam = selectedCategory;
    } else if (categoryIds.length) {
      categoriesParam = categoryIds.join(',');
    } else {
      // no filter => empty grid
      setPosts([]);
      setLoading(false);
      return;
    }
    const base = `/custom/v1/all-posts?categories=${categoriesParam}` + `&per_page=-1` + `&orderby=${attributes.orderBy}` + `&order=${attributes.order}` + `&_embed`;
    ;
    const key = `${categoriesParam}-${attributes.numberOfItems}-${attributes.order}-${attributes.orderBy}`;
    if (cacheRef.current[key]) {
      setPosts(cacheRef.current[key]);
      setLoading(false);
      return;
    }
    setLoading(true);
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: base
    }).then(resp => {
      cacheRef.current[key] = resp;
      setPosts(resp);
      setLoading(false);
      imageRefs.current = resp.map(() => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)('div'));
      if (!objectPositions.length) {
        setAttributes({
          objectPositions: resp.map(() => ({
            x: 50,
            y: 50
          }))
        });
      }
    }).catch(err => {
      setError(err);
      setLoading(false);
    });
  }, [categoryIds, selectedCategory, attributes.numberOfItems, attributes.orderBy, attributes.order]);
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.useBlockProps)({
    style: {
      color: textColor
    }
  });
  if (loading) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Spinner, {});
  if (error) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Notice, {
    status: "error",
    children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('There was an error loading posts.', 'pentablocktheme')
  });
  const handleMenuLoad = ids => setCategoryIds(ids);
  const handleCategorySelect = id => setSelectedCategory(id);
  const handleImageClick = i => {
    setSelectedElementIndex(i);
    const pos = objectPositions[i] || {
      x: 50,
      y: 50
    };
    setObjectPositionX(pos.x);
    setObjectPositionY(pos.y);
  };
  const handleObjectPositionChange = (axis, v) => {
    if (selectedElementIndex === null) return;
    const num = sanitizeNumber(v);
    const updated = [...objectPositions];
    updated[selectedElementIndex] = {
      ...updated[selectedElementIndex],
      [axis]: num
    };
    setObjectPositionX(updated[selectedElementIndex].x);
    setObjectPositionY(updated[selectedElementIndex].y);
    setAttributes({
      objectPositions: updated
    });
  };
  const getContainerClass = (idx, count, fiveCount) => {
    if (count === 1) return 'one-col';
    if (count === 2) return 'two-col';
    if (count === 3) return 'three-col';
    if (count === 4) return 'two-col';
    if (count === 5) return fiveCount % 2 ? 'one-third-col-rev' : 'one-third-col';
    return '';
  };
  const renderFolioContainers = items => {
    const containers = [];
    let buf = [],
      fiveCount = 0;
    items.forEach((post, i) => {
      if (!post.featured_img_src) return;
      const pos = objectPositions[i] || {
        x: 50,
        y: 50
      };
      buf.push(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
        ref: el => imageRefs.current[i] = el,
        className: "ppg-item-size",
        onClick: () => handleImageClick(i),
        style: {
          cursor: 'pointer',
          border: selectedElementIndex === i ? '2px solid blue' : 'none'
        },
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
          className: "ppg-item-container",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("a", {
            href: post.link,
            className: "ppg-item",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("img", {
              src: sanitizeString(post.featured_img_src),
              alt: sanitizeString(post.title),
              loading: "lazy",
              style: {
                width: '100%',
                height: '100%',
                objectFit: 'cover',
                objectPosition: `${pos.x}% ${pos.y}%`
              }
            }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(tagName, {
              style: {
                color: textColor,
                margin: 0
              }
            }, sanitizeString(post.title) || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Default Title', 'pentaportfoliogrid'))]
          })
        })
      }, post.id));
      if ((i + 1) % 5 === 0 || i === items.length - 1) {
        const cls = getContainerClass(containers.length, buf.length, fiveCount);
        if (buf.length === 5) fiveCount++;
        containers.push(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
          className: `ppg-item-wrapper ${cls}`,
          children: buf.map((child, idx) => {
            const isBig = buf.length === 5 && idx === 0;
            const reverse = isBig && fiveCount % 2 === 0;
            return React.cloneElement(child, {
              className: `item-folio ${isBig ? '--big' : ''} ${reverse ? 'reverse' : ''}`
            });
          })
        }, containers.length));
        buf = [];
      }
    });
    return containers;
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)("div", {
    ...blockProps,
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.InspectorControls, {
      group: "styles",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Text Settings', 'pentablocktheme'),
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.PanelColorSettings, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Text Color', 'pentablocktheme'),
          initialOpen: true,
          colorSettings: [{
            value: textColor,
            onChange: c => setAttributes({
              textColor: sanitizeString(c)
            }),
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Text Color', 'pentablocktheme')
          }]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Heading Level', 'pentablocktheme'),
          value: tagName,
          options: [{
            label: 'H1',
            value: 'h1'
          }, {
            label: 'H2',
            value: 'h2'
          }, {
            label: 'H3',
            value: 'h3'
          }, {
            label: 'H4',
            value: 'h4'
          }, {
            label: 'H5',
            value: 'h5'
          }, {
            label: 'DIV',
            value: 'div'
          }],
          onChange: v => setAttributes({
            tagName: sanitizeString(v)
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Padding Settings', 'pentablocktheme'),
        initialOpen: true,
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Padding L/R', 'pentablocktheme'),
          value: parseInt(paddingLeftRight),
          onChange: v => setAttributes({
            paddingLeftRight: `${sanitizeNumber(v)}px`
          }),
          min: 0,
          max: 200
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Padding Top', 'pentablocktheme'),
          value: parseInt(paddingTop),
          onChange: v => setAttributes({
            paddingTop: `${sanitizeNumber(v)}px`
          }),
          min: 0,
          max: 200
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Padding Bottom', 'pentablocktheme'),
          value: parseInt(paddingBottom),
          onChange: v => setAttributes({
            paddingBottom: `${sanitizeNumber(v)}px`
          }),
          min: 0,
          max: 200
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Crop Selected Image', 'pentablocktheme'),
        initialOpen: true,
        children: selectedElementIndex !== null && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
            children: `${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Editing Image', 'pentablocktheme')} #${selectedElementIndex + 1}`
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Horizontal (X)', 'pentablocktheme'),
            value: objectPositionX,
            onChange: v => handleObjectPositionChange('x', v),
            min: 0,
            max: 100
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Vertical (Y)', 'pentablocktheme'),
            value: objectPositionY,
            onChange: v => handleObjectPositionChange('y', v),
            min: 0,
            max: 100
          })]
        })
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_ppg_menu__WEBPACK_IMPORTED_MODULE_4__["default"], {
      menuLocation: "ppgMenu",
      onMenuLoad: handleMenuLoad,
      onCategorySelect: handleCategorySelect
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      className: "ppg-wrapper",
      style: {
        paddingLeft: paddingLeftRight,
        paddingRight: paddingLeftRight,
        paddingTop: paddingTop,
        paddingBottom: paddingBottom
      },
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
        className: "ppg-container",
        children: posts.length === 0 ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('No posts found.', 'pentablocktheme')
        }) : renderFolioContainers(posts)
      })
    })]
  });
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (EditComponent);

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _block_ppg_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../block/ppg.js */ "./block/ppg.js");
/* harmony import */ var _style_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.css */ "./src/style.css");



/***/ }),

/***/ "./src/style.css":
/*!***********************!*\
  !*** ./src/style.css ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"index": 0,
/******/ 			"./style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkpenta_portfolio_grid"] = globalThis["webpackChunkpenta_portfolio_grid"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map