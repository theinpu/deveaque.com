$(document).ready(function () {

    var posts = $('span.rating a.vote');

    posts.click(function () {
        var url = $(this).attr('href');
        var item = $(this).parent();
        $.getJSON(url, function (data) {
            if (!data.error) {
                $('span.count', item).text(' ' + data.rating + ' ');
                $('a', item).remove();
            } else {
                console.log(data.msg);
            }
        });
        return false;
    });

});