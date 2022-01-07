let spawn=false;
let path;
let current=null;
let angle=0;
let move=false;
let x;
let y;
let up=null;
let right=null;
let left=null;
let down=null;


$(document).on('click',".red",function () {
    if ($(this).hasClass("red") && $(this).attr('id') === 'reserve' && !spawn && !move) {

        $(".green").each(function() {
            $(this).removeClass("green");
            $(this).addClass("red");
        });
        $('td').each(function () {
            if(!$(this).hasClass('entry') && !$(this).hasClass('spawn')){
                $('input',this).attr("disabled",true);
            }
        });
        $(".red").removeClass('red');
        $(this).addClass("green");
        $(".spawn").each(function() {
            $(this).removeClass("green");
            $('img',this).attr('src', "/SIAM/img/vide.png");
            $(this).addClass("red");
        });
        $(".entry").each(function() {
            $(this).removeClass("green");
            $(this).addClass("red");
        });
        $("input[name=spawn]").each(function () {
            $("input:radio").prop('checked', $(this).prop("checked"));
        });
        spawn=true;
    }else if($(this).hasClass("red") && ($(this).hasClass("spawn") || $(this).hasClass("entry") || $(this).hasClass("pion")) && spawn && !move){
        up=null;
        down=null;
        left=null;
        right=null;
        path="";
        disablekey();
        $('input[name=move]').each(function () {
            $(this).prop('checked', false);
        });
        $('img.green').each(function (){
            if(!$(this).is('#reserve')){
                $(this).removeClass('green');
            }
        });
        $(".spawn").each(function () {
            $(this).removeClass('green');
            $('img',this).attr('src', "/SIAM/img/vide.png");
            $(this).addClass('red');
        });
        $(".entry").each(function () {
            $(this).removeClass('green');
            $(this).addClass('red');
        });
        if($(this).hasClass("spawn")){
            if (!path) {
                path = $('img#reserve').attr('src');
            }
            $('img', this).attr('src', path);
            $('input[name=entry]').each(function () {
                $(this).prop('checked', false);
            });
            $('input[name=pion]').each(function () {
                $(this).prop('checked', false);
            });
            current = $(this);
            $('input', current).attr('value', $('input', current).attr('value').substring(0, 2) + angle);

        }else{
            $('input[name=spawn]').each(function () {
                $(this).prop('checked', false);
            });
            $('input[name=entry]').each(function () {
                $(this).prop('checked', false);
            });
            $('input[name=pion]').each(function () {
                $(this).prop('checked', false);
            });
            let x = parseInt($(this).attr('value').charAt(0));
            let y = parseInt($(this).attr('value').charAt(1));
            up=null;
            down=null;
            left=null;
            right=null;
            if(x===0 && y===0){
                $('.right :input').attr("disabled",false);
                $('.down :input').attr("disabled",false);
                right="ok";
                down="ok";
            }else if(x===0 && y===4){
                $('.left :input').attr("disabled",false);
                $('.down :input').attr("disabled",false);
                left="ok";
                down="ok";
            }else if(x===4 && y===0){
                $('.up :input').attr("disabled",false);
                $('.right :input').attr("disabled",false);
                right="ok";
                up="ok";
            }else if(x===4 && y===4){
                $('.left :input').attr("disabled",false);
                $('.up :input').attr("disabled",false);
                left="ok";
                up="ok";
            }
        }
        $(this).removeClass('red');
        $(this).addClass("green");
        $('.current').removeClass('current');
        $(this).addClass('current');
    }else if($(this).hasClass("red") && $(this).hasClass("pion") && !spawn && !move) {
        move = true;
        current = $(this);
        path=$('img',this).attr('src');
        $('input[name=reserve]').attr("disabled", true);
        $(".pion").each(function () {
            $(this).removeClass('green');
            $(this).addClass('red');
        });
        $(".red").removeClass('red');
        $('input[name=pion]').attr('disabled',true);
        x = parseInt(current.attr('value').charAt(0));
        y = parseInt(current.attr('value').charAt(1));
        angle = parseInt(current.attr('value').charAt(2));
        $(".row").each(function () {
            $('input', this).prop('checked', false);
        });
        $('td').each(function () {
            if(!$(this).hasClass('pion') || $(this).hasClass('spawn') ){
                $('input',this).attr("disabled",true);
            }
            let tdx = parseInt($(this).attr('value').charAt(0));
            let tdy = parseInt($(this).attr('value').charAt(1));

            if ((tdx === x + 1 && tdy === y) || (tdx === x - 1 && tdy === y) || (tdx === x && tdy === y - 1) || (tdx === x && tdy === y + 1)) {
                $(this).addClass('red');
                if(tdy === y+1){
                    right=$(this);
                }else if(tdy === y-1){
                    left=$(this);
                }else if(tdx === x+1){
                    down=$(this);
                }else if(tdx === x-1){
                    up=$(this);
                }
            }
        });


        current.removeClass("red");
        current.addClass('green');
        current.addClass('current');
        $('input',current).attr('disabled',false);

    }
});

$(document).on('click',".rotate",function () {
    if(path) {
        angle++;
        if(angle===4){
            angle=0;
        }
        path=path.substring(0,5) + angle + path.substring(6,10);
        let value=$('input',current).attr('value').substring(0,2)+angle;
        $('input',current).attr('value',value);
        $('img',current).attr('src', path);
    }
});

function disablekey(){
    $('.left :input').attr("disabled",true);
    $('.up :input').attr("disabled",true);
    $('.right :input').attr("disabled",true);
    $('.down :input').attr("disabled",true);
}

$(document).on('click',".right",function () {
    if(right){
        cleardir();
        if(path) {
            right.removeClass('red');
            right.addClass('green');
        }
        $('img',this).addClass('green');
    }
});
$(document).on('click',".left",function () {
    if(left){

        cleardir();
        if(path) {
            left.removeClass('red');
            left.addClass('green');
        }
        $('img',this).addClass('green');

    }
});
$(document).on('click',".up",function () {
    if(up){
        cleardir();
        if(path) {
            up.removeClass('red');
            up.addClass('green');
        }
        $('img',this).addClass('green');

    }
});
$(document).on('click',".down",function () {
    if(down){
        cleardir();
        if(path) {
            down.removeClass('red');
            down.addClass('green');
        }
        $('img',this).addClass('green');

    }
});

function cleardir() {
    $('td').each(function () {
        if($(this).hasClass('green') && !$(this).hasClass('current')){
            $(this).removeClass('green');
            $(this).addClass('red');
        }
    });
    $('img','.up').removeClass('green');
    $('img','.down').removeClass('green');
    $('img','.left').removeClass('green');
    $('img','.right').removeClass('green');
}