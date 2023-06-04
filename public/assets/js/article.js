(function () {

   const nav = document.querySelector('.container-header-nav');
   const articleCategorySpan = document.querySelector("#article-category-span");
   const articleTitle = document.querySelector(".article-header-title");
   const editIcons = document.querySelectorAll(".editIcon");
   const cancelIcons = document.querySelectorAll(".cancelIcon");

   const clrPolitique = "#FF4E00";
   const clrEconomie = "#FFB100";
   const clrGeopolitique = "#3581B8";
   const clrSociete = "#C0C781";
   const clrArtsLitteratures = "#C47AC0";
   const clrParoleLibre = "#33673B";

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
         carrouselArticleTitle.firstElementChild.style="color: " + clrPolitique + " !important;";

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

   // const form = document.querySelector(".container-addComment-input");
   // const url = window.location.pathname;
   
   // form.addEventListener("submit", async(e) => {
   //    e.preventDefault();
      
   //    const form = e.target;
   //    const data = new FormData(form);
   //    const url = window.location.pathname;

   //    try {
   //       const response = await fetch(url, { 
   //          method: "POST",
   //          body: data,
   //       });

   //       if (response.ok) {
   //          console.log("commentaire sauvegardé.");
   //       } else {
   //          console.error("Erreur lors de la sauvegarde du commentaire.");
   //       }

   //       window.location.href= `${url}`;
         
   //    } catch (error) {
   //       console.error("Erreur lors de la requête:", error);
   //    }
   // })

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

   editIcons.forEach(editIcon => {
      console.log(editIcon);
      editIcon.addEventListener("click", () => {
         console.log("test1");

         toggleCommentEditForm(editIcon);
      });
   });
   
})();