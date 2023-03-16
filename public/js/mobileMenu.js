function openMenu() {
    document.getElementById('myDropdown').classList.toggle('showMenu');
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    if (!document.getElementsByClassName('mobileMenuBtn')[0].contains(event.target)) {
        var dropdowns = document.getElementsByClassName("dropdown");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('showMenu')) {
                openDropdown.classList.remove('showMenu');
            }
        }
    }
} 