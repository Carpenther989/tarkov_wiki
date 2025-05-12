
document.addEventListener('click', function(elements)
    {
        let documentTheme = document.documentElement.getAttribute('data-bs-theme');
        let dlButton = elements.target.closest('[data-dl-toggle]');
        if (dlButton)
        {
            let icon = dlButton.firstElementChild;
            if (icon.classList.contains('fa-sun'))
            {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                icon.classList.add('fa-sun');
                icon.classList.remove('fa-moon');
            }
            document.documentElement.setAttribute('data-bs-theme', documentTheme === 'light' ? 'dark' : 'light');
        }
    }
);