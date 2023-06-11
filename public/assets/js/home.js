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

   const coloringSections = document.querySelectorAll(".container-body-category-content");
   const nav = document.querySelector('.container-header-nav');
   const articles = document.querySelectorAll("article");


   // Sur chargement de la page et selon le chemin de la page, changement de la couleur de la bande de fond des sections. 
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
                           
               }
            })
         })

      } else if (pathname.startsWith('/parole-libre/public/articles/politique')) {
         container.classList.add('politique');
         
      } else if (pathname.startsWith('/parole-libre/public/articles/economie')) {
         container.classList.add('economie');

      } else if (pathname.startsWith('/parole-libre/public/articles/geopolitique')) {
         container.classList.add('geopolitique');

      } else if (pathname.startsWith('/parole-libre/public/articles/societe')) {
         container.classList.add('societe');

      } else if (pathname.startsWith('/parole-libre/public/articles/arts-litteratures')) {
         container.classList.add('artsLitteratures');

      } else if (pathname.startsWith('/parole-libre/public/articles/parole-libre')) {
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