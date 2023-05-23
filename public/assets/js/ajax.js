(function () {
   
   const form = document.querySelector("container-addComment-input");
   
   const editComment = async () => {
   
      try {
         const response = await fetch(form.action, {
            body: new FormData(form),
            method: "POST",
         });
   
         const json = await response.json();
         console.log(json);
      } catch (error) {
         console.error("Erreur lors de la requête:", error);
      }
   
      // window.location.href = "http://localhost/sf_bididi/public/ajax/";
   };
   
   window.addEventListener("load", editComment);

})();
