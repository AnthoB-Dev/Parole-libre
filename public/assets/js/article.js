(function () {

   const nav = document.querySelector('.container-header-nav');
   const articleCategorySpan = document.querySelector("#article-category-span");
   const articleTitle = document.querySelector(".article-header-title");
   const editIcons = document.querySelectorAll(".editIcon");

   const clrPolitique = "#FF4E00";
   const clrEconomie = "#FFB100";
   const clrGeopolitique = "#3581B8";
   const clrSociete = "#C0C781";
   const clrArtsLitteratures = "#C47AC0";
   const clrParoleLibre = "#33673B";

   const categoryContainer = document.querySelector("#article-category-span");
   const title = document.querySelector(".article-header-title");
   const titleColor = title.querySelector("label");

   const categorySelect = document.querySelector("#article_category");

   // Sur scoll vers le bas, ajoute la classe "sticky à la nav" après 240px parcouru
   window.onscroll = () => {
      if (window.scrollY >= 240) { 
         nav.classList.add('sticky');
      } else {
         nav.classList.remove('sticky');
      }
   }

   // Change les couleurs du titre et du "bouton" de la catégorie sur la page /{category}/article/{id} en fonction de la dite catégorie
   window.onload = function() {
      // Obtention du chemin de la page actuelle
      const pathname = window.location.pathname;
      
      if (pathname.startsWith('/parole-libre/public/politique')) {
         articleCategorySpan.style= "background-color: " + clrPolitique + " !important;";
         articleTitle.style= "border-bottom: solid 2px " + clrPolitique + " !important;";
         articleTitle.firstElementChild.style="color: " + clrPolitique + " !important;";
         // carrouselArticleTitle.firstElementChild.style="color: " + clrPolitique + " !important;";

      } else if (pathname.startsWith('/parole-libre/public/economie')) {
         articleCategorySpan.style= "background-color: " + clrEconomie + " !important;";
         articleTitle.style= "border-bottom: solid 2px " + clrEconomie + " !important;";
         articleTitle.firstElementChild.style="color: " + clrEconomie + " !important;";

      } else if (pathname.startsWith('/parole-libre/public/geopolitique')) {
         articleCategorySpan.style= "background-color: " + clrGeopolitique + " !important;";
         articleTitle.style= "border-bottom: solid 2px " + clrGeopolitique + " !important;";
         articleTitle.firstElementChild.style="color: " + clrGeopolitique + " !important;";

      } else if (pathname.startsWith('/parole-libre/public/societe')) {
         articleCategorySpan.style= "background-color: " + clrSociete + " !important;";
         articleTitle.style= "border-bottom: solid 2px " + clrSociete + " !important;";
         articleTitle.firstElementChild.style="color: " + clrSociete + " !important;";

      } else if (pathname.startsWith('/parole-libre/public/arts-litteratures')) {
         articleCategorySpan.style= "background-color: " + clrArtsLitteratures + " !important;";
         articleTitle.style= "border-bottom: solid 2px " + clrArtsLitteratures + " !important;";
         articleTitle.firstElementChild.style="color: " + clrArtsLitteratures + " !important;";

      } else if (pathname.startsWith('/parole-libre/public/parole-libre')) {
         articleCategorySpan.style= "background-color: " + clrParoleLibre + " !important;";
         articleTitle.style= "border-bottom: solid 2px " + clrParoleLibre + " !important;";
         articleTitle.firstElementChild.style="color: " + clrParoleLibre + " !important;";
      }   
   }

   const toggleCommentEditForm = (editIcon) => {

      const parent = editIcon.parentElement?.parentElement?.parentElement;
      
      if(parent) {

         const commentContent = parent.querySelector(".comment-content");
         const commentActions = parent.querySelector(".comment-actions");
         const icons = commentActions.querySelector(".action-icons");
         const iconPencil = icons.querySelector(".editIcon");
         const iconUndo = icons.querySelector(".cancelIcon");
         const form = commentContent.querySelector("form");
         const paragraph = commentContent.querySelector("p");

         if(commentContent.firstElementChild) {
            
            if (!paragraph.classList.contains("hidden")) {
               form.classList.toggle("hidden");
               paragraph.classList.toggle("hidden");
               iconPencil.classList.toggle("hidden");
               iconUndo.classList.toggle("hidden");
               iconUndo.addEventListener("click", () => toggleCommentEditForm(editIcon));
            } else {
               form.classList.toggle("hidden");
               paragraph.classList.toggle("hidden");
               iconPencil.classList.toggle("hidden");
               iconUndo.classList.toggle("hidden");
               iconUndo.addEventListener("click", () => toggleCommentEditForm(editIcon));
            }
         }
      }
   };

   const changeColorsInNewArticle = (v) => {
      
      if(v == 1) {
         categoryContainer.style = "background-color: " + clrPolitique + "!important;";
         title.style = "border-bottom: 2px solid " + clrPolitique + "!important;"
         titleColor.style = "color: " + clrPolitique + "!important;";
      } else if (v == 2) {
         categoryContainer.style = "background-color: " + clrArtsLitteratures + "!important;";
         title.style = "border-bottom: 2px solid " + clrArtsLitteratures + "!important;"
         titleColor.style = "color: " + clrArtsLitteratures + "!important;";
      } else if (v == 5) {
         categoryContainer.style = "background-color: " + clrEconomie + "!important;";
         title.style = "border-bottom: 2px solid " + clrEconomie + "!important;"
         titleColor.style = "color: " + clrEconomie + "!important;";
      } else if (v == 6) {
         categoryContainer.style = "background-color: " + clrSociete + "!important;";
         title.style = "border-bottom: 2px solid " + clrSociete + "!important;"
         titleColor.style = "color: " + clrSociete + "!important;";
      } else if (v == 7) {
         categoryContainer.style = "background-color: " + clrGeopolitique + "!important;";
         title.style = "border-bottom: 2px solid " + clrGeopolitique + "!important;"
         titleColor.style = "color: " + clrGeopolitique + "!important;";
      } else if (v == 8) {
         categoryContainer.style = "background-color: " + clrParoleLibre + "!important;";
         title.style = "border-bottom: 2px solid " + clrParoleLibre + "!important;"
         titleColor.style = "color: " + clrParoleLibre + "!important;";
      }
   }

   if(editIcons) {

      editIcons.forEach(editIcon => {
         editIcon.addEventListener("click", () => {
            toggleCommentEditForm(editIcon);
         });
      });
   }

   if(document.getElementById('fileInputLabel')) {
      
      // document.getElementById('fileInputLabel').addEventListener('click', function (event) {
      //    event.preventDefault();
      //    document.getElementById('article_image').click();
      // });
      
      document.getElementById('article_image').addEventListener('change', function (event) {
         let reader = new FileReader();
         reader.onload = function(e) {
            document.getElementById('image').src = e.target.result;
         };
         reader.readAsDataURL(event.target.files[0]);
      });

      window.onload = function(){
         let v = categorySelect.value;
         changeColorsInNewArticle(v);
      };

      categorySelect.addEventListener("change", () => {
         let v = categorySelect.value;
         changeColorsInNewArticle(v);
      });
   }
   
})();