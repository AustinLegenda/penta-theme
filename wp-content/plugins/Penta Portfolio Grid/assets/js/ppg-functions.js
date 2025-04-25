function initFolioLayout() {
    var b = Array.from(document.getElementsByClassName("ppg-item-wrapper"));
    var c = Array.from(document.getElementsByClassName("ppg-item-size"));
    let y = document.querySelector(".ppg-container");
  
    var fullCount = 0;
  
    b.forEach((i) => {
      if (i.children.length == 1) {
        i.classList.add("one-col");
      } else if (i.children.length == 2) {
        i.classList.add("two-col");
      } else if (i.children.length == 3) {
        i.classList.add("three-col");
      } else if (i.children.length == 4) {
        i.classList.add("two-col");
      } else if (i.children.length == 5) {
        i.classList.add("full");
        if (fullCount % 2 === 0) {
          i.classList.add("one-third-col");
          i.children[0].classList.add("--big");
        } else {
          i.classList.add("one-third-col-rev");
          i.children[0].classList.add("--big");
      i.children[0].classList.add("reverse");
        }
        fullCount++;
      } else { }
      var x = Array.from(document.getElementsByClassName("full"));
      x.forEach((d) => {
        var foo = d.querySelector(".ppg-item-size");
        foo.classList.add("--big");
        //add big class to first child of every container that has 5 elements
      });
  
    });
  }
  initFolioLayout();