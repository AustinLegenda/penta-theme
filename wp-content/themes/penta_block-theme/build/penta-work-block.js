/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

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
/************************************************************************/
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!******************************************!*\
  !*** ./penta-blocks/penta-work-block.js ***!
  \******************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);




wp.blocks.registerBlockType("pentaportfoliogrid/penta-work-block", {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Penta Work Block", "work by category"),
  icon: "grid-view",
  edit: EditComponent,
  save: SaveComponent
});
function EditComponent() {
  const [categories, setCategories] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [postsByCategory, setPostsByCategory] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const containerRefs = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)([]);
  const btnRefs = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)([]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    // Fetch posts with categories from the custom API
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
      path: '/custom/v1/all-posts?parent=4'
    }).then(fetchedPosts => {
      const uniqueCategories = [];
      fetchedPosts.forEach(post => {
        post.categories.forEach(cat => {
          if (!uniqueCategories.some(category => category.id === cat.id)) {
            uniqueCategories.push(cat);
          }
        });
      });
      setCategories(uniqueCategories);
      const newPostsByCategory = {};
      uniqueCategories.forEach(category => {
        newPostsByCategory[category.id] = fetchedPosts.filter(post => post.categories.some(cat => cat.id === category.id));
      });
      setPostsByCategory(newPostsByCategory);
    });
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!categories.length || !Object.keys(postsByCategory).length) return;
    containerRefs.current.forEach((container, index) => {
      if (!container) return; // Skip if ref is not assigned

      const btnLeft = btnRefs.current[index]?.left;
      const btnRight = btnRefs.current[index]?.right;
      const handleMouseEnter = () => {
        if (btnLeft) btnLeft.classList.remove("transparent");
        if (btnRight) btnRight.classList.remove("transparent");
      };
      const handleMouseLeave = () => {
        if (btnLeft) btnLeft.classList.add("transparent");
        if (btnRight) btnRight.classList.add("transparent");
      };
      const handleScrollClick = direction => {
        container.scrollLeft += direction === "left" ? -200 : 200;
      };
      container.addEventListener("mouseenter", handleMouseEnter);
      container.addEventListener("mouseleave", handleMouseLeave);
      if (btnLeft) {
        btnLeft.addEventListener("click", () => handleScrollClick("left"));
      }
      if (btnRight) {
        btnRight.addEventListener("click", () => handleScrollClick("right"));
      }

      // Cleanup function to remove event listeners
      return () => {
        container.removeEventListener("mouseenter", handleMouseEnter);
        container.removeEventListener("mouseleave", handleMouseLeave);
        if (btnLeft) btnLeft.removeEventListener("click", () => handleScrollClick("left"));
        if (btnRight) btnRight.removeEventListener("click", () => handleScrollClick("right"));
      };
    });
  }, [categories, postsByCategory]);
  const renderWorkContainer = () => {
    return categories.map((category, index) => {
      const posts = postsByCategory[category.id] || [];
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: category.id
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
        className: "work-cat-title"
      }, category.name), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "work-cat-container",
        ref: el => containerRefs.current[index] = el
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "work-feed-container"
      }, posts.length > 0 ? posts.map(post => {
        const featuredImage = post.featured_img_src || 'path/to/default-image.jpg';
        const postTitle = post.title || 'No Title Available';
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
          key: post.id,
          href: post.link
        }, featuredImage ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
          src: featuredImage,
          alt: postTitle,
          loading: "lazy",
          style: {
            height: '198px',
            width: '300px',
            objectFit: 'cover'
          }
        }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "placeholder"
        }, "No Image"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", null, postTitle)));
      }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "No posts available for this category.")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "slide-left btns transparent",
        ref: el => {
          if (!btnRefs.current[index]) {
            btnRefs.current[index] = {};
          }
          btnRefs.current[index].left = el;
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "chevron-left"
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "slide-right btns transparent",
        ref: el => {
          if (!btnRefs.current[index]) {
            btnRefs.current[index] = {};
          }
          btnRefs.current[index].right = el;
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "chevron-right"
      }))));
    });
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "main-grid"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("article", {
    className: "main-grid-full"
  }, renderWorkContainer()));
}
function SaveComponent() {
  return null; // Render handled on the server via PHP template
}
})();

/******/ })()
;
//# sourceMappingURL=penta-work-block.js.map