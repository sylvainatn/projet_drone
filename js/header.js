// Récupere l'URL
let url = window.location.href;


// Récuperer la valeur d'un parametre de l'url
function getParameterValue(parameterName) 
{
    let parameterIndex = url.indexOf(parameterName + '=');

    if (parameterIndex !== -1) {
        // Récupérez la valeur du paramètre
        let valueStartIndex = parameterIndex + parameterName.length + 1;
        let valueEndIndex = url.indexOf('&', valueStartIndex);
        if (valueEndIndex === -1) {
            valueEndIndex = url.length;
        }
        let value = url.substring(valueStartIndex, valueEndIndex);
        return decodeURIComponent(value);
    }
    return null;
}


// Changer les classes
function changerClass(button, classToAdd, classToRemove) 
{
    button.classList.remove(classToRemove);
    button.classList.add(classToAdd);
}


// Mettre à jour les classes des boutons du header
function updateClasses() 
{
    let p = getParameterValue('p');
    let homeBtn = document.getElementById("btn1");
    let locationBtn = document.getElementById("btn2");
    let userBtn = document.getElementById("btn3");

    if (p == "map") {
        changerClass(homeBtn, "text-white", "text-secondary");
        changerClass(locationBtn, "text-secondary", "text-white");
    } else if (p == "login" || getParameterValue('utilisateur') != null) {
        changerClass(homeBtn, "text-white", "text-secondary");
        changerClass(userBtn, "text-secondary", "text-white");
    }
}


updateClasses();


if (url.indexOf("utilisateur") !== -1) {
    document.getElementById('navbar').classList.add('mb-3');

}
