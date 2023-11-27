document.addEventListener('DOMContentLoaded', function () {
    var toggleSlider = document.getElementById('toggleSlider');
    var isDarkMode = localStorage.getItem('isDarkMode') === 'true';

    // Встановлення стану теми відповідно до збереженого значення
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        document.querySelector('.navbar').classList.add('navbar-dark');
        document.querySelector('.navbar').classList.remove('navbar-light');
        toggleSlider.checked = true;
    } else {
        document.body.classList.remove('dark-mode');
        document.querySelector('.navbar').classList.add('navbar-light');
        document.querySelector('.navbar').classList.remove('navbar-dark');
        toggleSlider.checked = false;
    }

    toggleSlider.addEventListener('change', function () {
        var isDarkMode = this.checked;

        // Встановлення теми відповідно до стану перемикача
        document.body.classList.toggle('dark-mode', isDarkMode);
        document.querySelector('.navbar').classList.toggle('navbar-dark', isDarkMode);
        document.querySelector('.navbar').classList.toggle('navbar-light', !isDarkMode);

        // Збереження стану теми в localStorage
        localStorage.setItem('isDarkMode', isDarkMode);
    });
});
