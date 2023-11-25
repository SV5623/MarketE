document.addEventListener('DOMContentLoaded', function () {
    // Отримання стану теми з сервера
    fetch('/MarketTry/theme.php')
        .then(response => response.json())
        .then(data => {
            var body = document.body;
            var navbar = document.querySelector('.navbar');

            // Встановлення теми відповідно до збереженого стану
            if (data.darkMode) {
                body.classList.add('dark-mode');
                navbar.classList.add('navbar-dark');
                navbar.classList.remove('navbar-light');
            } else {
                body.classList.remove('dark-mode');
                navbar.classList.add('navbar-light');
                navbar.classList.remove('navbar-dark');
            }
        });

    // Додайте обробник подій для кнопки або повзунка
    var toggleSlider = document.getElementById('toggleSlider');

    toggleSlider.addEventListener('change', function () {
        var body = document.body;
        var navbar = document.querySelector('.navbar');
        var isDarkMode = this.checked;

        // Встановлення теми відповідно до стану кнопки або повзунка
        body.classList.toggle('dark-mode', isDarkMode);
        navbar.classList.toggle('navbar-dark', isDarkMode);
        navbar.classList.toggle('navbar-light', !isDarkMode);

        // Відправити запит на сервер для оновлення теми
        fetch('/MarketTry/theme.php', {
            method: 'POST',
            body: JSON.stringify({ toggleTheme: isDarkMode }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => console.log('Theme updated successfully:', data))
        .catch(error => console.error('Error updating theme:', error));
    });
});
