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

   const coloringCategory = document.querySelectorAll(".carrousel-article-category");
   const coloringSections = document.querySelectorAll(".container-body-category-content");
   const nav = document.querySelector('.container-header-nav');
   const articles = document.querySelectorAll("article");
   const h3Titles = document.querySelectorAll("h3");
   const h5Titles = document.querySelectorAll("h5");
   const sidebarTitle = document.querySelector(".container-parole-title")
   const sidebarH5 = document.querySelectorAll(".body-sidebar-cards-content-title")
   
   const politique = categoryPolitique.toUpperCase();
   const economie = categoryEconomie.toUpperCase();
   const geopolitique = categoryGeopolitique.toUpperCase();
   const societe = categorySociete.toUpperCase();
   const artsLitteratures = categoryArtsLitteratures.toUpperCase();
   const paroleLibre = categoryParoleLibre.toUpperCase();


   // Change la couleur dynamiquement du fond de chaque catégorie
   coloringCategory.forEach(e => {

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

   // Sur chargement de la page et selon le chemin de la page, changement de la couleur de la bande de fond de l'élément du haut de la page et de tous titres possible. 
   window.onload = function() {
      // Obtention du chemin de la page actuelle
      const pathname = window.location.pathname;
      
      // Sélection de l'élément à modifier
      const container = document.querySelector('.container-carrousel-header');

      // Suppression toutes les classes de page précédentes
      container.classList.remove('accueil', 'politique', 'economie', 'geopolitique', 'societe', 'arts', 'paroleLibre');

      if(pathname === '/parole-libre/public/accueil') {
         
         container.classList.add('accueil');

         articles.forEach(e => {
            const categories = e.querySelectorAll("input");
            const h3Titles = e.querySelectorAll("h3")
            const h4Titles = e.querySelectorAll("h4")
            categories.forEach(category => {
               const articleCategory = category.getAttribute("data-category");
               const categoryToLower = articleCategory.toLowerCase();
               const eParents = e.parentElement;
               
               if(!eParents.classList.contains("carrousel-group-1")) {
                  
                  h4Titles.forEach(e => {
                     const e_container_body_categories_content = e.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement
                     
                     if(categoryToLower == "politique") {
                        e_container_body_categories_content.classList.add("politique");
                        e.style="color :" + clrPolitique + ";";
                     } else if(categoryToLower == "géopolitique") {
                        e_container_body_categories_content.classList.add("geopolitique");
                        e.style="color :" + clrGeopolitique + ";";
                     } else if(categoryToLower == "arts & littératures") {
                        e_container_body_categories_content.classList.add("artsLitteratures");
                        e.style="color :" + clrArtsLitteratures + ";";
                     } else if(categoryToLower == "économie") {
                        e_container_body_categories_content.classList.add("economie");
                        e.style="color :" + clrEconomie + ";";
                     } else if(categoryToLower == "société") {
                        e_container_body_categories_content.classList.add("societe");
                        e.style="color :" + clrSociete + ";";
                     } else if(categoryToLower == "parole-libre") {
                        e_container_body_categories_content.classList.add("paroleLibre");
                        e.style="color :" + clrParoleLibre + ";";
                     }
                  })        
                           
               } else {
                  
                  h3Titles.forEach(e => {
                     if(categoryToLower == "politique") {
                        e.style="color :" + clrPolitique + ";";
                     } else if(categoryToLower == "géopolitique") {
                        e.style="color :" + clrGeopolitique + ";";
                     } else if(categoryToLower == "arts & littératures") {
                        e.style="color :" + clrArtsLitteratures + ";";
                     } else if(categoryToLower == "économie") {
                        e.style="color :" + clrEconomie + ";";
                     } else if(categoryToLower == "société") {
                        e.style="color :" + clrSociete + ";";
                     } else if(categoryToLower == "parole-libre") {
                        e.style="color :" + clrParoleLibre + ";";
                     }
                  })
               }
            })
         })

      } else if (pathname.startsWith('/parole-libre/public/politique')) {
         container.classList.add('politique');
         sidebarTitle.style="background-color: " + clrPolitique + "!important;" + "color: #ffffff !important;";
         sidebarH5.style="color: #ffffff !important;";

         h3Titles.forEach(e => {
            e.style="color: " + clrPolitique + ";";
         })
         h5Titles.forEach(e => {
            e.parentElement.style="background-color: " + clrPolitique + ";";
         })
         
      } else if (pathname.startsWith('/parole-libre/public/economie')) {
         container.classList.add('economie');
         sidebarTitle.style="background-color: " + clrEconomie + "!important;";

         h3Titles.forEach(e => {
            e.style="color: " + clrEconomie + ";";
         })
         h5Titles.forEach(e => {
            e.parentElement.style="background-color: " + clrEconomie + ";";
         })

      } else if (pathname.startsWith('/parole-libre/public/geopolitique')) {
         container.classList.add('geopolitique');
         sidebarTitle.style="background-color: " + clrGeopolitique + "!important;";

         h3Titles.forEach(e => {
            e.style="color: " + clrGeopolitique + ";";
         })
         h5Titles.forEach(e => {
            e.parentElement.style="background-color: " + clrGeopolitique + ";";
         })

      } else if (pathname.startsWith('/parole-libre/public/societe')) {
         container.classList.add('societe');
         sidebarTitle.style="background-color: " + clrSociete + "!important;";

         h3Titles.forEach(e => {
            e.style="color: " + clrSociete + ";";
         })
         h5Titles.forEach(e => {
            e.parentElement.style="background-color: " + clrSociete + ";";
         })

      } else if (pathname.startsWith('/parole-libre/public/arts-litteratures')) {
         container.classList.add('artsLitteratures');
         sidebarTitle.style="background-color: " + clrArtsLitteratures + "!important;";

         h3Titles.forEach(e => {
            e.style="color: " + clrArtsLitteratures + ";";
         })
         h5Titles.forEach(e => {
            e.parentElement.style="background-color: " + clrArtsLitteratures + ";";
         })

      } else if (pathname.startsWith('/parole-libre/public/parole-libre')) {
         container.classList.add('paroleLibre');
         sidebarTitle.style="background-color: " + clrParoleLibre + "!important;";

         h3Titles.forEach(e => {
            e.style="color: " + clrParoleLibre + ";";
         })
         h5Titles.forEach(e => {
            e.parentElement.style="background-color: " + clrParoleLibre + ";";
         })
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