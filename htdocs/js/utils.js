$(document).keyup(function (e) {
    if (e.ctrlKey && e.which == 39) {
        navigateNext();
    }
    if (e.ctrlKey && e.which == 37) {
        navigatePrev();
    }
});

function navigatePrev() {
    var currentPage = $('.pages:first > span');
    var prev = currentPage.prev('a');
    if (prev.attr('href') != undefined) {
        location.href = prev.attr('href');
    }
}

function navigateNext() {
    var currentPage = $('.pages:first > span');
    var next = currentPage.next('a');
    if (next.attr('href') != undefined) {
        location.href = next.attr('href');
    }
}