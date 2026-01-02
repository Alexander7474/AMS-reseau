function infoBox(text="info mal configuré", champId="#", link="#") {
  var infoDiv = document.createElement("div");
  infoDiv.classList.add("alert","alert-info","alert-dismissible", "fade", "show", "mt-2", "mb-2");
  infoDiv.setAttribute("role", "alert");

  infoDiv.innerHTML = text;

  var btnInfo = document.createElement("button");
  btnInfo.classList.add("btn-close");
  btnInfo.setAttribute("type", "button");
  btnInfo.setAttribute("data-bs-dismiss", "alert");

  if(link != "#"){
    var aInfo = document.createElement("a");
    aInfo.classList.add("alert-link");
    aInfo.setAttribute("href", link);
    aInfo.innerHTML = "More";
    infoDiv.appendChild(aInfo);
  }

  infoDiv.appendChild(btnInfo);
  document.getElementById(champId).appendChild(infoDiv);
} 
