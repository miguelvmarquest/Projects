var BoxOpened = "";
var ImgOpened = "";
var Counter = 0;
var ImgFound = 0;

var facil = ['js/img/1.png', 'js/img/2.png', 'js/img/3.png', 'js/img/4.png', 'js/img/5.png'];
var medio = ['js/img2/1.png', 'js/img2/2.png', 'js/img2/3.png', 'js/img2/4.png', 'js/img2/5.png','js/img2/6.png', 'js/img2/7.png', 'js/img2/8.png'];
var dificil = ['js/img3/1.png', 'js/img3/2.png', 'js/img3/3.png', 'js/img3/4.png', 'js/img3/5.png','js/img3/6.png', 'js/img3/7.png', 'js/img3/8.png', 'js/img3/9.png', 'js/img3/10.png','js/img3/11.png', 'js/img3/12.png', 'js/img3/13.png', 'js/img3/14.png', 'js/img3/15.png'];
var extremo = ['js/img3/1.png', 'js/img3/2.png', 'js/img3/3.png', 'js/img3/4.png', 'js/img3/5.png','js/img3/6.png', 'js/img3/7.png', 'js/img3/8.png', 'js/img3/9.png', 'js/img3/10.png','js/img3/11.png', 'js/img3/12.png', 'js/img3/13.png', 'js/img3/14.png', 'js/img3/15.png','js/img2/1.png', 'js/img2/2.png', 'js/img2/3.png', 'js/img2/4.png', 'js/img2/5.png','js/img2/6.png', 'js/img2/7.png', 'js/img2/8.png', 'js/img2/9.png', 'js/img2/10.png','js/img3/11.png', 'js/img3/12.png', 'js/img3/13.png'];


var audioElement = document.createElement('audio');
audioElement.setAttribute('autoplay', 'autoplay');
audioElement.setAttribute('src', 'music/met/' + RandomAleatorio(4, 1) + '.mp3');
audioElement.load();
audioElement.play();


var PStopM = 1
function StopM() {
    audioElement.pause();
    PStopM = 0;
}

var ImgSource = facil;

function dificuldade(){
    if (document.getElementById("facil").selected == true) {
        audioElement.setAttribute('src', 'http://luna-ext.di.fc.ul.pt/~itw074/parte2/e24/music/met/' + RandomAleatorio(4, 1) + '.mp3');
        audioElement.load();
        audioElement.play();
		ImgSource = facil;
		$( "style" ).remove();
	    //$("head").append('<style type="text/css">#picbox {width: 550px;}</style>');
		var dif = 5;
	}
    else if (document.getElementById("media").selected == true) {
        audioElement.setAttribute('src', 'http://luna-ext.di.fc.ul.pt/~itw074/parte2/e24/music/key/' + RandomAleatorio(4, 1) + '.mp3');
        audioElement.load();
        audioElement.play();
		ImgSource = medio;
		$( "style" ).remove();
		$("head").append('<style type="text/css">#picbox {width: 440px; float:left; }</style>');
		var dif = 5;
	}
    else if (document.getElementById("dificil").selected == true) {
        audioElement.setAttribute('src', 'http://luna-ext.di.fc.ul.pt/~itw074/parte2/e24/music/foo/' + RandomAleatorio(4, 1) + '.mp3');
        audioElement.load();
        audioElement.play();
		ImgSource = dificil;
		$( "style" ).remove();
		$("head").append('<style type="text/css">#picbox {width: 660px; float:left;}</style>');
		var dif = 6;
	}
    else if (document.getElementById("extremo").selected == true) {
        audioElement.setAttribute('src', 'http://luna-ext.di.fc.ul.pt/~itw074/parte2/e24/music/RetroFuture.mp3');
        audioElement.load();
        audioElement.play();
		ImgSource = extremo;
		$( "style" ).remove();
		$("head").append('<style type="text/css">#picbox {width: 880px; float:left; margin-left: 5%}</style>');
		var dif = 10;
		}
	InitDif(dif);
	}

function RandomAleatorio(MaxValue, MinValue) {
		return Math.round(Math.random() * (MaxValue - MinValue) + MinValue);
	}
	
function ShuffleImages() {
	var ImgAll = $("#boxcard").children();
	var ImgThis = $("#boxcard" + " div:first-child");
	var ImgArr = new Array();

	for (var i = 0; i < ImgAll.length; i++) {
		ImgArr[i] = $("#" + ImgThis.attr("id") + " img").attr("src");
		ImgThis = ImgThis.next();
	}
	
		ImgThis = $("#boxcard" + " div:first-child");
	
	for (var z = 0; z < ImgAll.length; z++) {
	var RandomNumber = RandomAleatorio(0, ImgArr.length - 1);

		$("#" + ImgThis.attr("id") + " img").attr("src", ImgArr[RandomNumber]);
		ImgArr.splice(RandomNumber, 1);
		ImgThis = ImgThis.next();
	}
}

function ResetGame() {
	ShuffleImages();
	$("#boxcard" + " div img").hide();
	$("#boxcard" + " div").css("visibility", "visible");
	Counter = 0;
	$("#success").remove();
	$("#counter").html("" + Counter);
	BoxOpened = "";
	ImgOpened = "";
	ImgFound = 0;
    h1.textContent = "00:00:00";
    seconds = 0; minutes = 0; hours = 0;
	return false;
}
var inicio = 0;
function OpenCard() {
	var id = $(this).attr("id");
	if ($("#" + id + " img").is(":hidden")) {
		if (inicio == 0) {
        timer();
        inicio = 1;
		};
		$("#boxcard" + " div").unbind("click", OpenCard);
	
		$("#" + id + " img").show();

		if (ImgOpened == "") {
			BoxOpened = id;
			ImgOpened = $("#" + id + " img").attr("src");
			setTimeout(function() {
				$("#boxcard" + " div").bind("click", OpenCard)
			}, 300);
		} else {
			CurrentOpened = $("#" + id + " img").attr("src");
			if (ImgOpened != CurrentOpened) {
				setTimeout(function() {
					$("#" + id + " img").hide();
					$("#" + BoxOpened + " img").hide();
					BoxOpened = "";
					ImgOpened = "";
				}, 400);
			} else {
				$("#" + id + " img").parent().css("visibility", "visible");
				$("#" + BoxOpened + " img").parent().css("visibility", "visible");
				ImgFound++;
				BoxOpened = "";
				ImgOpened = "";
			}
			setTimeout(function() {
				$("#boxcard" + " div").bind("click", OpenCard)
			}, 400);
		}
		Counter++;
		$("#counter").html("" + Counter);

		if (ImgFound == ImgSource.length) {
            // stop counter
            clearTimeout(t);
            inicio = 0;
			$("#counter").prepend('<span id="success">Ganhaste! Com apenas </span>');
			//$("#jogo").append('<form>');
			//$("#jogo").append('<label>User Name: <input type="text" name="uname" id="uname"></label>');
			//$("#jogo").append('<input type="button" value="Enviar" onclick="getUname();"/>');
			//$("#jogo").append('</form>');
		    //getUname();
			audioElement.setAttribute('src', 'music/FAKE.mp3');
			audioElement.load();
			audioElement.play();
			var uname = prompt('Escreva o seu user name sff');
            var tempo = document.getElementById('tabtime');
            var len =localStorage.length;
            var par = ImgSource.length/5
            if (ImgSource.length==8){par=2}
            localStorage.setItem(len+'uname',uname);
            localStorage.setItem(len+'pontos',Counter);
            localStorage.setItem(len+'time',tempo.innerHTML);
            localStorage.setItem(len+'nivel',parseInt(par));
		}
	}
}

//function getUname(){
//	var uname = document.getElementById("uname").value;
//	localStorage.setItem('uname',uname);
//}
function Init() {
for (var y = 1; y < 3 ; y++) {
	$.each(ImgSource, function(i, val) {
		$("#boxcard").append("<div class=carta id=card" + y + i + "><img src=" + val + " />");
	});

}
	$("#boxcard" + " div").click(OpenCard);
	ShuffleImages();
};

function InitDif(dif) {
$( ".carta" ).remove();
$( "br" ).remove();

for (var y = 1; y < 3 ; y++) {
	$.each(ImgSource, function(i, val) {
		$("#boxcard").append("<div class=carta id=card" + y + i + "><img src=" + val + " />");
	});
}
	$("#boxcard" + " div").click(OpenCard);
	ShuffleImages();
	ResetGame();
};

var h1 = document.getElementsByTagName('p')[0],
    start = document.getElementById('start'),
    stop = document.getElementById('stop'),
    clear = document.getElementById('clear'),
    seconds = 0, minutes = 0, hours = 0,
    t;

function add() {
    seconds++;
    if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
            minutes = 0;
            hours++;
        }
    }
    
    h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

    timer();
}
function timer() {
    t = setTimeout(add, 1000);
};

$(document).ready(function () {
    $('#jogo').click(function () {
        if (audioElement.ended == true && PStopM == 1) {
            if (document.getElementById("facil").selected == true) {
                audioElement.setAttribute('src', 'music/met/' + RandomAleatorio(4, 1) + '.mp3');
                audioElement.load();
            };
            if (document.getElementById("media").selected == true){
                audioElement.setAttribute('src', 'music/key/' + RandomAleatorio(4, 1) + '.mp3');
                audioElement.load();
            };
            if (document.getElementById("dificil").selected == true) {
                audioElement.setAttribute('src', 'music/foo/' + RandomAleatorio(4, 1) + '.mp3');
                audioElement.load();
            };
            audioElement.play();
        };
    });
});

function funcTab (){
    for (var tab=1; tab<5;tab++){
        var keytab= 'Nivel'+tab;
        var va = localStorage.getItem(keytab).slice(0,30);
        $('#'+keytab).append(va);
    };
}

window.onload = Init(),funcTab();