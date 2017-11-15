'use strict'

function listAllItems(){  
     for (var n = 0; n<(localStorage.length); n=n+1) {
         var keynivel = localStorage.key(n);
         var valuenivel = localStorage.getItem(keynivel);
         var indexDoNivel = keynivel.indexOf('nivel');
         var nivel = localStorage.getItem(keynivel);

         if (indexDoNivel !==-1){
            
            for (var j = 0; j<(localStorage.length); j=j+1) {
                var keypontos = localStorage.key(j);
                var indexDoPontos = keypontos.indexOf('pontos');
                var pontos = localStorage.getItem(keypontos);

                if (indexDoPontos !==-1 && keypontos.substr(0, indexDoPontos)===keynivel.substr(0, indexDoNivel)){
                    
                     for (var t = 0; t<(localStorage.length); t=t+1) {
                        var keytime = localStorage.key(t);
                        var indexDoTime = keytime.indexOf('time');
                        var time = localStorage.getItem(keytime);
                        if (indexDoTime !==-1 && keytime.substr(0, indexDoTime)===keynivel.substr(0, indexDoNivel)){
                            
                            for (var i = 0; i<(localStorage.length); i=i+1) {
                                var keynome = localStorage.key(i);
                                var nome = localStorage.getItem(keynome);
                                var indexDoNome = keynome.indexOf('uname');
                                if (indexDoNome!==-1 && keynome.substr(0, indexDoNome)===keynivel.substr(0, indexDoNivel)){
                                    
                                    
                                    $("#tabela"+valuenivel).append("<tr class='cenas"+nivel+"' id='"+keynivel.substr(0, indexDoNivel)+"nivel'><td>"+nome+"</td><td>"+pontos+"</td><td>"+time+"</td></tr>");
                                    var comprimento = ($(".cenas"+nivel).children().length);
                                    if (comprimento!==1){comprimento=comprimento/4};
                                    var bestever = parseInt(valuenivel)*(10+0.10);
                                    var nestecaso = (parseInt(time.substr(0,3))*3600)+parseInt(pontos)+(parseInt(time.substr(3,6))*60)+(parseFloat('0.'+time.substr(6,9)));
                                    var rankpontos = bestever/nestecaso;
                                    localStorage.setItem(keynivel.substr(0, indexDoNivel)+'rankpontos',rankpontos);
                                
                                    for (var chi=2; chi<comprimento+1; chi++){
                                      //  console.log($(".cenas"+nivel+":nth-child("+chi+")" ).attr('id'));
                                        var idForn = $(".cenas"+nivel+":nth-child("+chi+")" ).attr('id').indexOf('nivel');
                                        var acena = $(".cenas"+nivel+":nth-child("+chi+")" ).attr('id').substr(0, idForn);
                                        if ( rankpontos >= parseFloat(localStorage.getItem(acena+'rankpontos'))){
                                               
                                                $('#'+acena+'nivel' ).before( $( "#"+keynivel.substr(0, indexDoNivel)+"nivel" ) );
                                                chi=comprimento+1;
                                        };

                                    };
                                };
                            };
                        };
                     };

                };
            };
    
        };
        
    };
    
}

function rank (){
    for (var tab=1; tab<6;tab++) {
        console.log("aqui");
        if (tab===3){console.log(($(".cenas"+tab).children().length)/3)};
        
        for (var el=0; el<($(".cenas"+tab).children().length)/3; el++){
            console.log("ole");
            var rank= parseInt(el)+2;
            var rank2= parseInt(el)+1;
            console.log('opa'+tab);
            if (el===0){
                if (tab===5){localStorage.setItem('Nivel'+4,'<td>Nivel'+4+':</td>'+$(".cenas"+tab+":nth-child("+rank+")").html())
                }else{
                    localStorage.setItem('Nivel'+tab,'<td>Nivel'+tab+':</td>'+$(".cenas"+tab+":nth-child("+rank+")").html())};
            };
            $("<td>"+rank2+"</td>").prependTo(".cenas"+tab+":nth-child("+rank+")");
            
        };
    };

}

window.onload = function() {
    if(!window.location.hash) {
        window.location = window.location + '#loaded';
        window.location.reload();
    };
}