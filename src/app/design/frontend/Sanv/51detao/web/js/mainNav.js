jQuery(document).ready(function ($) {


    //首页显示分类,其他页面不显示分类,hover时显示
    $('.nav--desktop .hoverClassify').hover(function () {
        if ($(this).children('.menu--desktop').hasClass('hidden')) {
            $(this).children('.menu--desktop').toggle();
        }
        return false;
    });


    //pc端一级菜单hover时,显示二三级菜单
    var titleVal = '';
    $(".menu--desktop .menu_links").hover(function () {
        var menuDesktopH = $(this).parent().height();
        var secMenu = $(this).find(".menu_content_scroll");
        var disTop = $(this).offset().top - $(this).parents(".menu").offset().top;

        if ($(this).find('.menu_link').attr('title')) {
            titleVal = $(this).find('.menu_link').attr('title');
            $(this).find('.menu_link').removeAttr('title');
        } else {
            $(this).find('.menu_link').attr('title', titleVal);
        }

        if (secMenu.css('display') == "none") {
            $(this).find('.menu_link').css({"background-color":"#ffffff","color":"#646464"});
            secMenu.css({"top": 0, 'height': menuDesktopH}).show();
        } else {
            $(this).find('.menu_link').css({"background-color":"transparent","color":"#ffffff"});
            secMenu.hide();
        }
    });

//判断移动端菜单是否存在
    if (!!$('.nav--mobile')) {
        var menuList = $(".menu .menu_links");
        var navList = $('.nav--desktop .nav_links').find('.nav_link');
        var contentHtml = '';
        var listHtml = '';
        var navLinksHtml = '';
        var navListHtml = '';
        var inputGroupHtml = '';
        
        inputGroupHtml = $('.nav--desktop .input-group').prop('outerHTML');


        for (var i = 0; i < menuList.length; i++) {
            contentHtml += $(menuList[i]).find('.menu_content_scroll').prop('outerHTML');
        }
        for (var i = 0; i < menuList.length; i++) {
            var deskHtml = $(menuList[i]).find('.menu_content_scroll').prop('outerHTML');
            $(menuList[i]).find('.menu_content_scroll').remove();
            listHtml += $(menuList[i]).prop('outerHTML');
            $(menuList[i]).append(deskHtml);
        }
        var html = '<nav class="NewNav clearfix hidden-lg-up nav--mobile">' +
            inputGroupHtml +
            ' <nav class="menu menu--mobile  hidden-lg-up">' +
            '<div class="menu_list">' +
            ' <div class="leftWrapper">' +
            listHtml +
            ' </div>' +
            '</div> ' +
            '<nav class="menu_content"> ' +
            '<div class="rightWrapper"> ' +
            contentHtml +
            '</div> ' +
            '</nav> ' +
            '</nav>' +
            '</nav>';

        $(".nav--desktop").after(html);
        $(".nav--desktop").addClass('hidden-md-down');

        for (var i = 0; i < navList.length; i++) {
            $(navList[i]).html('<i class="nav-link__content">' + navList[i].innerHTML + '</i>');
            navListHtml += $(navList[i]).prop('outerHTML');
        }

        navLinksHtml = '<div class="nav_links--mobile hidden-lg-up">' +
            '<nav class="nav_links clearfix">'
            + navListHtml +
            '</nav>' +
            '</div>';
        $(".nav--desktop").before(navLinksHtml);

        $(".nav_links--mobile .nav_links").removeClass('hidden-md-down');


        $($('.menu--mobile').find('.menu_links')[0]).find('.menu_link').addClass('active');
        $('.menu--mobile').find('.menu_links').find('.menu_link').attr('href', 'javascript:;');
    }


    var mobileLinks = $('.nav_links--mobile');
    var mobileNav = $('.nav--mobile');
    //头部header弹出内容
    $(".header_main .classify ").click(function () {

        if (mobileLinks) {
            if (!mobileLinks.hasClass('in')) {
                mobileLinks.fadeIn().addClass('in');
                $(this).find('.fa').removeClass('fa-reorder').addClass('fa-close');
            } else {
                mobileLinks.fadeOut().removeClass('in');
                $(this).find('.fa').removeClass('fa-close').addClass('fa-reorder');
            }
        }

    });

    //底部分类弹出及滑动
    function categoryFade() {
        function handler() {
            event.preventDefault();
        }
        $('.mobile_bottom .icon-classify').click(function () {
            if (!mobileNav.hasClass('in')) {
                mobileNav.fadeIn().addClass('in');
                var categoryList = function () {
                    var parentDom = document.querySelector('.menu_list');
                    var childDom = parentDom.querySelector('.leftWrapper');
                    var parentContent = document.querySelector('.menu_content');
                    var childContent = parentContent.querySelector('.rightWrapper');

                    parentContentTop = parentContent.offsetTop;

                    var parentH = parentDom.offsetHeight;
                    var childH = childDom.offsetHeight;


                    var minPosition = parentH - childH;
                    var maxPosition = 0;

                    var currY = 0;

                    var addTransition = function (dom) {
                        dom.style.webkitTransition = 'all 0.2s';
                        /*兼容*/
                        dom.style.transition = 'all 0.2s';
                    };
                    var setTranslateY = function (dom, y) {
                        dom.style.webkitTransform = 'translateY(' + y + 'px)';
                        dom.style.transform = 'translateY(' + y + 'px)';
                    };
                    var lis = childDom.querySelectorAll('.menu--mobile .menu_link');
                    childDom.onclick = function (e) {
                        var li = e.target;

                        var targetId = li.getAttribute("data-href");
                        var targetContent = childContent.querySelector(targetId);
                        contentList = childContent.querySelectorAll(".menu_content_scroll");
                        for (var i = 0; i < contentList.length; i++) {
                            contentList[i].style.display = "none";
                        }
                        targetContent.style.display = "block";

                        //右侧二三级菜单滑动(动态获取内容的高度,并确定高度已获取)
                        setTimeout(function () {
                            var contentScroll = new IScroll('.menu_content',
                                {
                                    scrollY: true,
                                    freeScroll: true,
                                    click: true,
                                    preventDefault: true
                                });
                            document.addEventListener('touchmove', handler, false);

                        }, 150);

                        for (var i = 0; i < lis.length; i++) {
                            lis[i].className = "menu_link";
                            lis[i].index = i;
                        }
                        li.className = "menu_link active";

                        var translateY = -li.index * 50;

                        if (translateY > minPosition) {
                            currY = translateY;
                            addTransition(childDom);
                            setTranslateY(childDom, currY);
                        }
                        else if (parentH > childH) {
                            currY = 0;
                            setTranslateY(childDom, currY);
                        }
                        else {
                            currY = minPosition;
                            setTranslateY(childDom, currY);
                        }

                    }
                }
                //左侧一级菜单滑动
                try {
                    var listScroll = new IScroll('.menu_list',
                        {
                            scrollY: true,
                            freeScroll: true,
                            click: true,
                            preventDefault: true
                        });
                    document.addEventListener('touchmove', handler, false);
                }
                catch (e) {
                    console.log('IScroll is error');
                }
                try {
                    var contentScroll2 = new IScroll('.menu_content',
                        {
                            scrollY: true,
                            freeScroll: true,
                            click: true,
                            preventDefault: true
                        });
                    document.addEventListener('touchmove', handler, false);
                }
                catch (e) {
                    console.log('IScroll is error');
                }
                categoryList();
            } else {
                mobileNav.fadeOut().removeClass('in');
                document.removeEventListener('touchmove', handler, false);
            }

        });

        $('.NewNav .comeBack').click(function () {
            mobileNav.fadeOut().removeClass('in');
            document.removeEventListener('touchmove', handler, false);
        });
    }

    categoryFade();

});

