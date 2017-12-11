
// Load all our javascript
$(document).ready(function() {
    ReloadJavaScript();
});

// When people scroll to far make the items rotate
$(window).scroll(function(){
    // Check if player is using a phone, if so dont change it while scrolling
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $('.top nav').css('transform', '');
//        $('.top nav').css('transform', 'rotateY(180deg)');
    } else {
        if($(window).scrollTop() > 200){
            if(window.innerWidth > 1048){
                $('.top nav').css('transform', '');
                $('.top nav').css('transform', 'rotateX(180deg)');
            } 
//            else {
//                $('.top nav').css('transform', '');
//                $('.top nav').css('transform', 'rotateY(180deg)');
//            }
        }
    }
});
$(window).scroll(function(){
    if($(window).scrollTop() < 200){
        $('.top nav').css('transform', '');
    }
});  

// Function to reload javascript after switching pages with javascript
function ReloadJavaScript(){
    // Make anwsers appear when you press the question on contact page
    $(".message#question.clickable").click(function () {
        // TODO Check for all classes if the class is active , if so remove that class first.
        $(this).toggleClass("hidden");
        $(this).next().toggleClass("hidden");
        return false;
    });

    $(".message#anwser.clickable").click(function () {
        $(this).toggleClass("hidden");
        $(this).prev().toggleClass("hidden");
        return false;
    });

    // Makes it able to use the scroll up button
    $('nav ul.back a li#1').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    }); 

    // Make it so it activates the active class when u hover over an item
    $('span#1 img').hover(function() {
        $('span#1').toggleClass('active');
    });

    $('span#2 img').hover(function() {
        $('span#2').toggleClass('active');
    });
    
    $('span#3 img').hover(function() {
        $('span#3').toggleClass('active');
    });

    $('span#4 img').hover(function() {
        $('span#4').toggleClass('active');
    });

    $('span#5 img').hover(function() {
        $('span#5').toggleClass('active');
    });

    $('span#6 img').hover(function() {
        $('span#6').toggleClass('active');
    });

    $('span#7 img').hover(function() {
        $('span#7').toggleClass('active');
    });
}

