/**
 * créé une requête http en fonction du navigateur
 * @constructor
 */
function Xhr() {
    let obj = null;
    try {
        obj = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (Error) {
        try {
            obj = new ActiveXObject("MSXML2.XMLHTTP");
        } catch (Error) {
            try {
                obj = new XMLHttpRequest();
            } catch (Error) {
                alert("impossible de créer l\'objet XMLHttpRequest");
            }
        }
    }
    return obj;
}

/**
 *
 * @param file : "ficher.doc"
 * @param methode : "Get" / "POST"
 */
function exercice_1_html(file,methode) {
    //création d'une instance de la clasXMLHtpRequest
    let req=new Xhr();

    //Connaitre le changement d'un état de la liaison
    req.onreadystatechange = function(){
        if(req.readyState === 4){
            //effectue une action si changement, ici on récupère la réponse
            //alert(req.responseText);
            //récupération du résultat

            document.getElementById("resultat").innerHTML=req.responseText;
        }
    }
    //récupération du fichier réponse.txt ( en mode sychrone => false / true => Asynchrone )
    req.open(methode,file, true);
    req.send(null);

    //document.getElementsByTagName("a")[0].style.visibility = 'hidden';
}