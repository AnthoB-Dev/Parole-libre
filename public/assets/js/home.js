(function(){

   const categoryPolitique = "politique";
   const clrPolitique = "#FF4E00";
   const categoryEconomie = "économie";
   const clrEconomie = "#FFB100";
   const categoryGeopolitique = "géopolitique";
   const clrGeopolitique = "#3581B8";

   const coloringCarrousel = document.querySelector(".container-carrousel-header")
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

   // Sur chargement de la page, changement de la couleur de la bande de fond de l'élément du haut de la page
   window.onload = function() {

      // Obtention du chemin de la page actuelle
      const pathname = window.location.pathname;
      console.log(pathname)

      // Sélection de l'élément à modifier
      const container = document.querySelector('.container-carrousel-header');
      console.log(container)

      // Suppression toutes les classes de page précédentes
      container.classList.remove('accueil', 'politique', 'economie', 'geopolitique', 'societe', 'arts', 'paroleLibre');

      if (pathname === '/parole-libre/public/accueil') {
         container.classList.add('accueil');
      } else if (pathname === '/parole-libre/public/politique') {
         container.classList.add('politique');
      } else if (pathname === '/parole-libre/public/economie') {
         container.classList.add('economie');
      } else if (pathname === '/parole-libre/public/geopolitique') {
         container.classList.add('geopolitique');
      } else if (pathname === '/parole-libre/public/societe') {
         container.classList.add('societe');
      } else if (pathname === '/parole-libre/public/arts-litteratures') {
         container.classList.add('arts');
      } else if (pathname === '/parole-libre/public/parole-libre') {
         container.classList.add('paroleLibre');
      }   
   }

}())