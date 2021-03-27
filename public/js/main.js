
//console.log($);

$(document).ready(function(){

    alert("ok fully loaeded");

    $('body').css("background-color","#fcf8e3");


    $(".delete btn btn-secondary").click(function(e){

        return false;
    });



    $(".prev-item").click(function(e){

        return false;
    });

    $(".page-link").click(function(){

        $.ajax({
            url: 'next-item.php',

        }).done(function(){

            alert("ok next item called!");
        });

        return false;
    });

});

var Paginator = function(){

//TODO: https://ibaslogic.com/object-oriented-programming-javascript/
//ecma5 vs ecma6
}($);

