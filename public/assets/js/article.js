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

   editIcons.forEach(editIcon => {
      editIcon.addEventListener("click", () => {
         const parent = editIcon.parentElement.parentElement.parentElement;
         const commentContent = parent.querySelector(".comment-content");
   
         if(commentContent) {
            const form = document.createElement("form");
            form.style="width: 100%;height: 100%;display: flex;flex-direction: column;align-items: flex-end;gap: 0.5rem;";
            form.attributes.name="edit-comment";

            const input = document.createElement("textarea");
            input.style="width: 100%; min-height: 100px; box-shadow: inset 0 0 13px -5px; padding: 1rem; border: none; border-radius: 5px; resize: none; font-size: 1.1em;";
            const inputText = commentContent.textContent;
            input.append(inputText);

            const button = document.createElement("button");
            button.type="submit";
            button.innerText="Modifier";
            form.append(input);
            form.append(button);


            commentContent.replaceChild(form, commentContent.querySelector("p"))
         }
      });
   });
   
})();