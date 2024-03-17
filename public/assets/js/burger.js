(function(){

   const btnBurger = document.querySelector("#burger");
   const burgerNav = document.querySelector("#burger-nav");
   const header = document.querySelector("header");
   const btnClose = document.querySelector(".btnClose");
   const filter = document.querySelector(".filter");

   const closeBurger = () => {
      filter.classList.add("hidden");
      burgerNav.classList.remove("openBurger");
      btnClose.removeEventListener("click", closeBurger);
      filter.removeEventListener("click", closeBurger);
   }
   
   const burgerToggle = () => {
      if(burgerNav.classList.contains("openBurger")) {
         filter.classList.add("hidden");
         burgerNav.classList.remove("openBurger");
         header.style = "overflow: initial !important;";
      } else {
         burgerNav.classList.add("openBurger");
         filter.classList.remove("hidden");
         btnClose.addEventListener("click", closeBurger);
         filter.addEventListener("click", closeBurger);
      }
   }
    
   if(btnBurger) {
   btnBurger.addEventListener("click", burgerToggle);
   }

})();