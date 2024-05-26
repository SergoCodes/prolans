// Filter button selection func
function selectButton(button) {
    var buttons = document.querySelectorAll('.filter-button');
    buttons.forEach(function(btn) {
        btn.classList.remove('selected');
    });

    button.classList.add('selected');
}

// Sticky Header
document.addEventListener('DOMContentLoaded', function() {
    var header = document.querySelector('Header');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});