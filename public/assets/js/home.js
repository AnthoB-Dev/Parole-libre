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

   const headerNavbar = document.querySelector("#header-navbar");
   const headerNavLinks = headerNavbar.querySelectorAll("li");
   const path = window.location.pathname;

   const nav = document.querySelector('.container-header-nav');
   const articles = document.querySelectorAll("article");

   const carrouGroup = document.querySelector(".carrousel-group-1");
   const carrouChilds = carrouGroup.querySelectorAll("article");

   
   /**
    * Sur chargement de la page et selon le chemin de la page, changement de la couleur de la bande de fond des sections.\
    * Et appel widthChanger() à la fin de la fonction qui adapte un autre background de la const container : la bande blanche.
    */
   window.onload = function categoryBgSwapper() {
      const pathname = window.location.pathname;
      let path = "";

      if (pathname.startsWith("/parole-libre/public/categorie/parole-libre")) {
         path = "/parole-libre/public/categorie/parole-libre";
      } else {
         path = "/parole-libre/public/categorie";
      }
      
      // Sélection de l'élément à modifier
      const container = document.querySelector('.container-carrousel-header');

      // Suppression toutes les classes de page précédentes
      container.classList.remove('accueil', 'politique', 'economie', 'geopolitique', 'societe', 'arts', 'paroleLibre');
      
      if(pathname === '/accueil' || pathname === "/parole-libre/public/accueil") {
         
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

      } else if (pathname.startsWith(path + '/politique')) {
         container.classList.add('politique');
         
      } else if (pathname.startsWith(path + '/economie')) {
         container.classList.add('economie');

      } else if (pathname.startsWith(path + '/geopolitique')) {
         container.classList.add('geopolitique');

      } else if (pathname.startsWith(path + '/societe')) {
         container.classList.add('societe');

      } else if (pathname.startsWith(path + '/arts-litteratures')) {
         container.classList.add('artsLitteratures');

      } else if (pathname.startsWith(path)) {
         container.classList.add('paroleLibre');
      }  
      
      widthChanger();
   }

   /**
    * Change la taille de la bande blanche présente derrière les heroArticles de chaque catégories pour correspondre au nombre d'articles présents dans carrousel-group.\
    * La fonction part de la width du ::before de container-carrousel-groups 77% et divise par 3 (le nb max d'article dans le container) ce qui fait 25.66 et muliplie celui-ci par le nombre d'élement (articles) présent dans le container.\
    * Ce qui permet d'adapter la taille de la bande blanche selon le nombre d'articles.\
    * Pour modifer le ::before, j'ai créer un element <style> que j'ai injecté dans le dom avec le css modifé vu que j'ai pas trouvé comment faire ça mieux.
    */
   function widthChanger() {
      const width = 25.66 * carrouChilds.length;
      if(carrouChilds.length <= 3) {
         const style = document.createElement('style');
         style.textContent =  `.container-carrousel-groups::before {width: ${width}% !important;}`;
         document.head.appendChild(style);
      }
   }

   /**
    * Sur scoll vers le bas, ajoute la classe "sticky à la nav" après 240px parcouru  
    */
   window.onscroll = () => {
      if (window.scrollY >= 240) { 
         nav.classList.add('sticky');
      } else {
         nav.classList.remove('sticky');
      }
   }

   /**
    * Ajoute des classes css aux liens de la navigation du header. Active sur les nav principal et subActive sur les liens du drop down de Parole Libre.
    */
   headerNavLinks.forEach(e => {
      const link = e.querySelector("a");
      if(link.getAttribute('href') === path) {
         if(e.parentElement.classList.contains("header-navlinks")) {
            e.classList.add('active');
         } else {
            e.classList.add("subActive");
         }
      }
   })
   
}())