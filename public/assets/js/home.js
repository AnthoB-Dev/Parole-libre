const categoryPolitique = "politique";
const clrPolitique = "#FF4E00";
const categoryEconomie = "économie";
const clrEconomie = "#FFB100";
const categoryGeopolitique = "géopolitique";
const clrGeopolitique = "#3581B8";

const coloringCategory = document.querySelectorAll(".carrousel-article-category");

coloringCategory.forEach(e => {
   const politique = categoryPolitique.toUpperCase()
   const economie = categoryEconomie.toUpperCase()
   const geopolitique = categoryGeopolitique.toUpperCase()

   if(e.firstElementChild.textContent.toUpperCase() === politique) {
      e.style="background-color: " + clrPolitique + ";";
   } else if(e.firstElementChild.textContent.toUpperCase() === economie) {
      e.style="background-color: " + clrEconomie + ";";
   } else if(e.firstElementChild.textContent.toUpperCase() === geopolitique) {
      e.style="background-color: " + clrGeopolitique + ";";
   }
})