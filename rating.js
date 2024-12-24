(function(a){
    a.fn.codexworld_rating_widget = function(p){
        var p = p || {};
        var b = p.starLength || "5";
        var c = p.callbackFunctionName || "";
        var e = p.initialValue || "0";
        var d = p.imageDirectory || "images";
        var r = p.inputAttr || "";
        var f = e;
        var g = a(this);
        b = parseInt(b);
        init();
        
        // Hover effect for stars
        g.next("ul").children("li").hover(function(){
            $(this).parent().children("li").css('background-position','0px 0px');
            var a = $(this).parent().children("li").index($(this));
            $(this).parent().children("li").slice(0, a + 1).css('background-position','0px -28px');
        }, function(){});

        // Click event to capture rating and post ID
        g.next("ul").children("li").click(function(){
            var a = $(this).parent().children("li").index($(this));
            var attrVal = (r != '') ? g.attr(r) : '';
            f = a + 1;
            g.val(f);

            // Capture post ID from an attribute or hidden input
            var postID = g.data('post-id') || $('#postID').val(); // Adjust selector as needed

            // Send the rating to the server
            if (c != "") {
                eval(c + "(" + g.val() + ", '" + postID + "', '" + attrVal + "')");
            }
        });

        // Restore visual state when hovering out
        g.next("ul").hover(function(){}, function(){
            if (f == "") {
                $(this).children("li").slice(0, f).css('background-position','0px 0px');
            } else {
                $(this).children("li").css('background-position','0px 0px');
                $(this).children("li").slice(0, f).css('background-position','0px -28px');
            }
        });

        // Initialize star rating widget UI
        function init(){
            $('<div style="clear:both;"></div>').insertAfter(g);
            g.css("float", "left");
            var a = $("<ul>");
            a.addClass("codexworld_rating_widget");
            for (var i = 1; i <= b; i++) {
                a.append('<li style="background-image:url(' + d + '/widget_star.gif)"><span>' + i + '</span></li>');
            }
            a.insertAfter(g);
            if (e != "") {
                f = e;
                g.val(e);
                g.next("ul").children("li").slice(0, f).css('background-position','0px -28px');
            }
        }
    }
})(jQuery);
