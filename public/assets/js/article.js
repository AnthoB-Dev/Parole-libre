(function () {
   const nav = document.querySelector(".container-header-nav");
   const articleCategorySpan = document.querySelector("#article-category-span");
   const articleTitle = document.querySelector(".article-header-title");
   const editIcons = document.querySelectorAll(".editIcon");
   const formsOfSuppr = document.querySelectorAll(".comment_delete_form");

   const clrPolitique = "#FF4E00";
   const clrEconomie = "#FFB100";
   const clrGeopolitique = "#3581B8";
   const clrSociete = "#C0C781";
   const clrArtsLitteratures = "#C47AC0";
   const clrParoleLibre = "#33673B";

   /**
    * Conteneur du select const categorySelect
    * @type {HTMLElement} 
    */
   const categoryContainer = document.querySelector("#article-category-span");
   /**
    * h1 element - "Titre de l'article"
    * @type {HTMLElement}
    */
   const title = document.querySelector(".article-header-title");
   /**
    * Label element - Enfant du h1 : const title
    * @type {HTMLElement}
    */
   const titleColor = title.querySelector("label");
   /**
    * Select element - Selecteur de catégorie de la page ajout / modificacion d'article
    * @type {HTMLElement} 
    */
   const categorySelect = document.querySelector("#article_category");

   /**
    * Sur scoll vers le bas, ajoute la classe "sticky" à la nav après 240px parcouru
    */
   window.onscroll = () => {
      if (window.scrollY >= 240) {
         nav.classList.add("sticky");
      } else {
         nav.classList.remove("sticky");
      }
   };

   /**
    * Change les couleurs du titre et du "bouton" de la catégorie sur la page d'un article en fonction de la dite catégorie.
    */
   window.onload = function () {
      // Obtention du chemin de la page actuelle
      const pathname = window.location.pathname;
      let path = "";

      if (pathname.startsWith("/parole-libre/public/categorie/parole-libre")) {
         path = "/parole-libre/public/categorie/parole-libre";
      } else {
         path = "/parole-libre/public/categorie";
      }
      
      if (pathname.startsWith(path + "/politique")) {
         articleCategorySpan.style =            "background-color: " + clrPolitique + " !important;";
         articleTitle.style =                   "border-bottom: solid 2px " + clrPolitique + " !important;";
         articleTitle.firstElementChild.style = "color: " + clrPolitique + " !important;";
         // carrouselArticleTitle.firstElementChild.style="color: " + clrPolitique + " !important;";
      } else if (pathname.startsWith(path + "/economie")) {
         articleCategorySpan.style =            "background-color: " + clrEconomie + " !important;";
         articleTitle.style =                   "border-bottom: solid 2px " + clrEconomie + " !important;";
         articleTitle.firstElementChild.style = "color: " + clrEconomie + " !important;";
      } else if (pathname.startsWith(path + "/geopolitique")) {
         articleCategorySpan.style =            "background-color: " + clrGeopolitique + " !important;";
         articleTitle.style =                   "border-bottom: solid 2px " + clrGeopolitique + " !important;";
         articleTitle.firstElementChild.style = "color: " + clrGeopolitique + " !important;";
      } else if (pathname.startsWith(path + "/societe")) {
         articleCategorySpan.style =            "background-color: " + clrSociete + " !important;";
         articleTitle.style =                   "border-bottom: solid 2px " + clrSociete + " !important;";
         articleTitle.firstElementChild.style = "color: " + clrSociete + " !important;";
      } else if (pathname.startsWith(path + "/arts-litteratures")) {
         articleCategorySpan.style =            "background-color: " + clrArtsLitteratures + " !important;";
         articleTitle.style =                   "border-bottom: solid 2px " + clrArtsLitteratures + " !important;";
         articleTitle.firstElementChild.style = "color: " + clrArtsLitteratures + " !important;";
      } else if (pathname.startsWith(path)) {
         articleCategorySpan.style =            "background-color: " + clrParoleLibre + " !important;";
         articleTitle.style =                   "border-bottom: solid 2px " + clrParoleLibre + " !important;";
         articleTitle.firstElementChild.style = "color: " + clrParoleLibre + " !important;";
      }
   };

   formsOfSuppr.forEach(form => {
      form.addEventListener("click", confSuppr);
   });

   /**
    * Permet de confirmer une action.
    * @param {PointerEvent} e preventDefault() si confirm = false, si true ne fait rien en retournant false.
    */
   function confSuppr(e) {
      return !confirm("Confirmez vous vouloir effectué cette action?") ? e.preventDefault() : false;
   }
   
   /**
    * Sert à faire apparaitre le formulaire de modification d'un commentaire d'article \
    * Cherche la div parent du bouton de modification cliqué et si il existe, selectionne les élements enfants avec des querySelectors
    * @param {HTMLElement} editIcon Logo de modication d'un commentaire
    */
   const toggleCommentEditForm = (editIcon) => {
      const parent = editIcon.parentElement?.parentElement?.parentElement?.parentElement;

      if (parent) {
         const commentContent = parent.querySelector(".comment-content");
         const commentActions = parent.querySelector(".comment-actions");
         const icons = commentActions.querySelector(".action-icons");
         const iconPencil = icons.querySelector(".editIcon");
         const iconUndo = icons.querySelector(".cancelIcon");
         const form = commentContent.querySelector("form");
         const paragraph = commentContent.querySelector("p");

         if (commentContent.firstElementChild) {
            if (!paragraph.classList.contains("hidden")) {
               form.classList.toggle("hidden");
               paragraph.classList.toggle("hidden");
               iconPencil.classList.toggle("hidden");
               iconUndo.classList.toggle("hidden");
               iconUndo.addEventListener("click", () =>
                  toggleCommentEditForm(editIcon)
               );
            }
         }
      }
   };

   /**
    * Change les couleurs dynamiquement sur la page d'ajout et de modification d'un article selon le choix de catégorie de l'article \
    * Dans l'état, doit être changé manuellement lorsque / ou si une nouvelle catégorie est ajouté.
    * @param {number} v - ID of the current Category
    */
   const changeColorsInNewArticle = (v) => {
      if (v == 1) {
         categoryContainer.style =  "background-color: " + clrPolitique + "!important;";
         title.style =              "border-bottom: 2px solid " + clrPolitique + "!important;";
         titleColor.style =         "color: " + clrPolitique + "!important;";
      } else if (v == 2) {
         categoryContainer.style =  "background-color: " + clrArtsLitteratures + "!important;";
         title.style =              "border-bottom: 2px solid " + clrArtsLitteratures + "!important;";
         titleColor.style =         "color: " + clrArtsLitteratures + "!important;";
      } else if (v == 5) {
         categoryContainer.style =  "background-color: " + clrEconomie + "!important;";
         title.style =              "border-bottom: 2px solid " + clrEconomie + "!important;";
         titleColor.style =         "color: " + clrEconomie + "!important;";
      } else if (v == 6) { 
         categoryContainer.style =  "background-color: " + clrSociete + "!important;";
         title.style =              "border-bottom: 2px solid " + clrSociete + "!important;";
         titleColor.style =         "color: " + clrSociete + "!important;";
      } else if (v == 7) {
         categoryContainer.style =  "background-color: " + clrGeopolitique + "!important;";
         title.style =              "border-bottom: 2px solid " + clrGeopolitique + "!important;";
         titleColor.style =         "color: " + clrGeopolitique + "!important;";
      } else if (v == 8) {
         categoryContainer.style =  "background-color: " + clrParoleLibre + "!important;";
         title.style =              "border-bottom: 2px solid " + clrParoleLibre + "!important;";
         titleColor.style =         "color: " + clrParoleLibre + "!important;";
      }
   };

   /**
    * Si la variable editIcons n'est pas null, qu'il y a donc des bouton de modifications présent sur la page, ajoute un eventListener sur chacun d'eux et lance la fonction toggleCommentEditForm
    */
   if (editIcons) {
      editIcons.forEach((editIcon) => {
         editIcon.addEventListener("click", () => {
            toggleCommentEditForm(editIcon);
         });
      });
   }

   /**
    * Sert de condition à l'activation des évenements liés aux pages d'ajout et modification d'un article
    */
   if (document.getElementById("fileInputLabel")) {

      /**
       * Permet la prévisualisation de l'image chargée sur la page d'ajout ou de modification d'un article
       */
      document.getElementById("article_image").addEventListener("change", function (event) {
         let reader = new FileReader();
         reader.onload = function (e) {
            document.getElementById("image").src = e.target.result;
         };
         reader.readAsDataURL(event.target.files[0]);
      });

      /**
       * Au chargement de la fenêtre où se trouve fileInputLabel, récupère la "value" du select actif et le stock dans "v" puis l'envoie à changeColorsInNewArticle \
       * Dans l'état, ne sert pas à grand chose puisque le seul moyen d'arriver ici est de passé la condition, remplie seulement lorsque je suis sur une page où j'ai un selectionneur de fichier \
       * Et qui de toute façon ne fera rien à cause de la valeur du select par défaut qui ne correspond à aucune catégorie 
       */
      window.onload = function () {
         let v = categorySelect.value;
         changeColorsInNewArticle(v);
      };

      /**
       * Sur un changement d'état du select, récupère la nouvelle valeure et change la couleur.
       */
      categorySelect.addEventListener("change", () => {
         let v = categorySelect.value;
         changeColorsInNewArticle(v);
      });
   }
})();