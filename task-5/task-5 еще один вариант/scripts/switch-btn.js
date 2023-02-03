(function () {
    const switchBtn = document.createElement('div');
    switchBtn.classList.add('switch-btn');
    document.body.append(switchBtn);

    const currentTheme = localStorage.getItem('theme');
    if (currentTheme == 'dark') {
        document.body.classList.add('dark');
        switchBtn.classList.add('switch-on');
    }
    
    switchBtn.addEventListener('click', function () {
        switchBtn.classList.toggle('switch-on');
        document.body.classList.toggle('dark');
        let theme = 'light';
        if (document.body.classList.contains('dark')) {
            theme = 'dark';
        }
        localStorage.setItem('theme', theme);
    });
})();
