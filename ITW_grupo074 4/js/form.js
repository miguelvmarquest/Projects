'use strict'

function formulario() {
    var fo = document.forms.dados.elements;
    var nome = fo.nome.value;
    var email = fo.email.value;
    var data = fo.data.value;
    var banda = fo.banda.value;
    if (localStorage.length===0){
        var len = 1;
    }else{
        var len =parseInt(localStorage.length/3 + 1);
        parseInt(len);

    }
    localStorage.setItem(len+'nome',nome);
    localStorage.setItem(len+'email',email);
    localStorage.setItem(len+'data',data);
    localStorage.setItem(len+'banda',banda);

};
