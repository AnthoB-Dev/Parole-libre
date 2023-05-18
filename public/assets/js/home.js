(function(){

   const categoryPolitique = "politique";
   const clrPolitique = "#FF4E00";

   const categoryEconomie = "économie";
   const clrEconomie = "#FFB100";

   const categoryGeopolitique = "géopolitique";
   const clrGeopolitique = "#3581B8";

   const categorySociete = "société";
   const clrSociete = "#C0C781";

   const categoryArtsLitteratures = "arts & littératures";
   const clrArtsLitteratures = "#C47AC0";

   const categoryParoleLibre = "parole libre";
   const clrParoleLibre = "#33673B";

   const coloringCarrousel = document.querySelector(".container-carrousel-header")
   const coloringCategory = document.querySelectorAll(".carrousel-article-category");
   const nav = document.querySelector('.container-header-nav');

   // Change la couleur dynamiquement du fond de chaque catégorie
   coloringCategory.forEach(e => {
      const politique = categoryPolitique.toUpperCase();
      const economie = categoryEconomie.toUpperCase();
      const geopolitique = categoryGeopolitique.toUpperCase();
      const societe = categorySociete.toUpperCase();
      const artsLitteratures = categoryArtsLitteratures.toUpperCase();
      const paroleLibre = categoryParoleLibre.toUpperCase();

      if(e.firstElementChild.textContent.toUpperCase() === politique) {
         e.style="background-color: " + clrPolitique + ";";
      } else if(e.firstElementChild.textContent.toUpperCase() === economie) {
         e.style="background-color: " + clrEconomie + ";";
      } else if(e.firstElementChild.textContent.toUpperCase() === geopolitique) {
         e.style="background-color: " + clrGeopolitique + ";";
      } else if(e.firstElementChild.textContent.toUpperCase() === societe) {
         e.style="background-color: " + clrSociete + ";";
      } else if(e.firstElementChild.textContent.toUpperCase() === artsLitteratures) {
         e.style="background-color: " + clrArtsLitteratures + ";";
      } else if(e.firstElementChild.textContent.toUpperCase() === paroleLibre) {
         e.style="background-color: " + clrParoleLibre + ";";
      }
   })

   // Sur chargement de la page, changement de la couleur de la bande de fond de l'élément du haut de la page
   window.onload = function() {

      // Obtention du chemin de la page actuelle
      const pathname = window.location.pathname;

      // Sélection de l'élément à modifier
      const container = document.querySelector('.container-carrousel-header');

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

   // Sur scoll vers le bas, ajoute la classe "sticky à la nav" après 240px parcouru
   window.onscroll = () => {
      if (window.scrollY >= 240) { 
         nav.classList.add('sticky');
      } else {
         nav.classList.remove('sticky');
      }
   }

}())