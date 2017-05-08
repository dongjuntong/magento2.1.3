require(['jquery','slick','lazyload','mainav'], function($){
    $(function(){
        $('.slick').slick();
        document.documentElement.className = "js";
        $("img[data-original]").lazyload({
            effect: 'slideDown',
            skip_invisible: false,
            failure_limit: 15,
            threshold: 200
        });
    });
});