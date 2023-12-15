const tabs = document.getElementById("circleTab");
const tabsLinks = tabs.querySelectorAll(".tab-link");
const tabsContent = document.getElementById("tabContent");
const tabPanel = tabsContent.querySelectorAll(".tab-pane");

tabsLinks.forEach(links => {
  links.addEventListener("click", function(e) {
    e.preventDefault();

    tabsLinks.forEach(el => el.classList.remove("active"));
    this.classList.add("active");

    const id = this.getAttribute("aria-controls");

    tabPanel.forEach(tab => {
      tab.classList.remove("show");
      tab.classList.remove("active");
      // Add class if id is same
      if (id === tab.id) {
        e.target.classList.add("active");
        tab.classList.add("show");
        tab.classList.add("active");
      }
    });
  });
});