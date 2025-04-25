/**
 * Hero Image
 */
function ApplyHeroImgClass() {
  var heroImg = document.getElementsByClassName("header-container")[0].getElementsByTagName("img")[0];

  heroImg.classList.add("hero-img", "main-grid-bleed");
}
if (document.body.contains(document.getElementById("PentaHeader"))) {
  ApplyHeroImgClass();
}

/**
 *Nav Menu Magic
 window resize event functions
https://developer.mozilla.org/en-US/docs/Web/API/MediaQueryList/matches
**/
//Constructors

var control = Array.from(document.getElementsByClassName("nav-control-btns"));
var color = Array.from(document.getElementsByClassName("mobile-nav"));
var footer = document.querySelector("footer");
var navItems = document.getElementById("menu-main-menu");
var nav = document.getElementsByTagName("nav")[0];

function MobileNav() {
  //set nav control buttons
  control[0].classList.remove("hide");
  control[1].classList.add("hide");
  //check for white and remove
  if (color[0].classList.contains("white")) {
    color.forEach((i) => {
      i.classList.remove("white");
    });
  } else {}
  //check for footer and remove
  if (footer.classList.contains("out-of-step")) {
    footer.classList.remove("out-of-step", "white");
  } else {}
  //hide open close elements
  navItems.classList.add("hide");
  nav.classList.remove("open", "nav-overlay");
}

function killMobileNav() {
  //reset nav items
  nav.classList.remove("nav-overlay", "open");
  navItems.classList.remove("hide");
  //hide all control buttons
  control.forEach((i) => {
    i.classList.add("hide");
  });
  //remove white classes
  color.forEach((i) => {
    i.classList.remove("white");
  });
  //remove footer class
  footer.classList.remove("out-of-step", "white");
}

function openMobileNav() {
  navItems.classList.remove("hide");
  nav.classList.add("nav-overlay", "open");
  //add white classes
  color.forEach((i) => {
    i.classList.add("white");
  });
  //toggle control buttons
  control.forEach((i) => {
    i.classList.toggle("hide");
  });
  //add footer class
  footer.classList.add("out-of-step", "white");

}

//click event
control.forEach((i) => {
  i.addEventListener("click", () => {
    if (control[0].classList.contains("hide")) {
      MobileNav();
    } else {
      openMobileNav();
    }
  });
});

//setup listener and callback
function addMQListener(mq, callback) {
  if (mq.addEventListener) {
    mq.addEventListener("change", callback);
  } else {
    mq.addListener(callback);
  }
}

//window-size-change event
addMQListener(window.matchMedia("(max-width:750px)"), (event) => {
  if (event.matches) {
    MobileNav();
  } else {
    killMobileNav();
  }
});
/**
 * killing landscape function for mobile nav. Is source of glitch on med-lg screens. 
 */
//window-orientation-change event
// addMQListener(window.matchMedia("(orientation:landscape)"),
//   event => {
//     if (event.matches) {
//       killMobileNav();
//     } else {
//       MobileNav();
//     }
//   }
// );

//window-size event for devices and page load at or below 750px
if (window.innerWidth <= 750) {
  MobileNav();
} else {

}


/**
 *Folio scroll behavior
 **/
//constructors 
var btns = Array.from(document.getElementsByClassName("btns"));
var container = Array.from(document.getElementsByClassName("work-cat-container"));

//utility functions 
btns.forEach((i) => {
  if (/Mobi|Android/i.test(navigator.userAgent)) {
    i.style.display = "none";
  }
  var x = i.parentNode;
  var y = Array.from(x.querySelectorAll(".work-feed-container"));
  y.forEach((n) => {
    n.addEventListener("mouseenter", () => {
      i.classList.toggle("transparent");
    });
    container.forEach((d) => {
      d.addEventListener("mouseleave", () => {
        if (i.classList.contains("transparent")) {
          //don't do shit
        } else {
          i.classList.toggle("transparent");
        }
      });
    });
    btns.forEach((e) => {
      e.addEventListener("mouseleave", () => {
        i.classList.add("transparent");
      });
    });
    i.addEventListener("click", () => {
      if (i.classList.contains("slide-left")) {
        n.scrollLeft -= 200;
      } else {
        n.scrollLeft += 200;
      }
    });
  });
});


