$(document).ready(function(){
	$("#floating_link").click(function(){
		var _t = $("#token").text();
		window.open("fup/?_t=" + _t,"cloud","width=850,height=500,scrollbars=auto,resizable=yes,Location=no,status=no,Menubar=no");
	});
	$("#floating_link").hover(
		function() { $( this ).fadeTo( 'fast', '1'); },
		function() { $( this ).fadeTo( 'fast', '.4'); }
		);
});

if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
}

$(document).ready(function(){
	$("#uploadfebutton").click(function(){
		var _t = $("#token").text();
		window.open("fupfe/?_t=" + _t,"cloud","width=850,height=500,scrollbars=auto,resizable=yes,Location=no,status=no,Menubar=no");
	});
});

$(document).ready(function(){
	$("#uploadtxtbutton").click(function(){
		var _t = $("#token").text();
		window.open("fup/?_t=" + _t,"cloud","width=850,height=500,scrollbars=auto,resizable=yes,Location=no,status=no,Menubar=no");
	});
});


$(function() {
	$( document ).tooltip();
});
