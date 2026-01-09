const menu = document.querySelector(".menu-vertical");
const type_icons = document.querySelectorAll(".type_icon");
const lists = document.querySelectorAll(".icon-list");
const icones = document.querySelectorAll(".icones") ;

// Quand la souris entre sur le menu → afficher tout
menu.addEventListener("mouseover", () => {
    menu.style.gap = "5px" ;
    menu.style.width = "180px" ;
    menu.style.justifyContent="center" ;
    menu.style.paddingTop = "17px" ;
    menu.style.Height="1000px" ;
    menu.style.maxHeight="1000px" ;

    type_icons.forEach(icone => {
        icone.style.display = "flex";
    });
    icones.forEach(icon => {
        icon.style.marginTop ="15px" ;
    }) ;

    lists.forEach(list => {
        list.style.display = "flex";
        list.style.marginLeft ="20px" ;
    });
});

// Quand la souris quitte le menu → cacher tout
menu.addEventListener("mouseout", () => {
    menu.style.gap ="100px" ;
    menu.style.width="60px" ;
    menu.style.justifyContent="center" ;

    type_icons.forEach(icon => {
        icon.style.display = "none";
    });
    icones.forEach(icon => {
        icon.style.marginTop ="35px" ;
    }) ;

    lists.forEach(list => {
        list.style.display = "none";
    });
});
