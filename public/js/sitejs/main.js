$('#toggle-left-menu').click(function () {
    if ($('#left-menu').hasClass('small-left-menu')) {
        $('#left-menu').removeClass('small-left-menu');
    } else {
        $('#left-menu').addClass('small-left-menu');
    }
    $('#logo').toggleClass('small-left-menu');
    $('#main-content').toggleClass('small-left-menu');
    $('#header .header-left').toggleClass('small-left-menu');

    $('#logo .big-logo').toggle('300');
    $('#logo .small-logo').toggle('300');
    $('#logo').toggleClass('p-0 pl-1');
});


document.addEventListener("DOMContentLoaded", () => {
    const dropdownLinks = document.querySelectorAll(".main-navigation .has-sub > a");

    dropdownLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const parent = link.parentElement;
            parent.classList.toggle("open"); // Add/remove the 'open' class
        });
    });
});