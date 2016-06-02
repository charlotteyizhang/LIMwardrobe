/**
 * Created by user on 2016/2/18.
 */
jQuery( document ).ready(function( $ ) {
    $('#loginBtn').on('show.bs.popover',function(){
        $('#registerBtn').popover('hide');
    })
    $('#loginBtn').popover({
        html: true,
        placement:  function (context, source) {
            var w = $(window).width();

            if (w < 939) {
                return "bottom";
            }else{
                return "bottom";
            }
        },
        content: function () {
            return $(this).parent().find('.loginForm').html();
        }
    })
    $('#registerBtn').on('show.bs.popover',function(){
        $('#loginBtn').popover('hide');
    })
    $('#registerBtn').popover({
        html: true,
        placement: function (context, source) {
            var w = $(window).width();

            if (w < 939) {
                return "bottom";
            }else{
                return "bottom";
            }
        },
        content: function () {
            return $(this).parent().find('.registerForm').html();
        }
    })
    $('#userBtn').popover({
        html: true,
        placement: function (context, source) {
            var w = $(window).width();

            if (w < 939) {
                return "bottom";
            }else{
                return "bottom";
            }
        },
        content: function (){
            return $(this).parent().find('.userInfo').html();
        }
    })
    $('#close').click(function(){
        $('#userText').html("");
        $('#close').html("");
    })
    $("#slider").on("change", function(){
        var limit = $(this).val();
     $("#amount").html(Number(limit));
     $.post('php/updateUser.php',{'user_limit' : limit}, function(data) {
        $("#amount").html(data);
     });
    });
});
//check if username is fine
function valiate(username){
    $.post('php/validate.php', {'user_name':username}, function(data) {
        $("#userText").show();
        $("#userText").html(data);
        $("#close").html("x");
    });
}

//buy clothes insert into wardrobe
function buy(name, price, pic_id, weight, user_id){
    $.post('php/buy.php',
        {
            'name': name,
            'price': price,
            'weight': weight,
            'pic_id': pic_id,
            'user_id': user_id
        },
        function(data){
            $("#text").html("<a href='index.php'><p>It's in your wardrobe, click to check</p></a>");
        }
        )
}

//change the clothes status into invalid
function changeStatus(id){
    $.post('php/changeStatus.php',
    {'id':id},
    function(data){
        location.reload();
    });
}

window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));

function getSlider() {
    $( "#slider" ).slider({
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.value );
      }
    });
    $( "#amount" ).val( "$" + $( "#slider" ).slider( "value" ) );
  };

//send data to wardrobe
function sendDataToWardrobe(weight, userid){
    $.post('php/updatewardrobe.php',
    {'weight' : weight,
    'user_id' : userid
    },function(jsonData) {
        var json = $.parseJSON(jsonData);
        $(json).each(function(i, val){
            getDataFromWardrobe(val.user_id);
        });
    });
}

function getDataFromWardrobe(user_id){
    $.post('php/querywardrobe.php',
    {'user_id' : user_id},function(jsonData) {
        var json = $.parseJSON(jsonData);
        $(json).each(function(i, val){
            var clothes_id = val.clothes_id;
            var times = val.times;
            var PT = val.PT;
            PT = PT.toFixed(2);
            var string1 = "times"+clothes_id;
            var string2 = "PT"+clothes_id;
            $("#"+string1).html("Times: "+times);
            $("#"+string2).html("Price per wear: "+ PT);
        });
    });
}
