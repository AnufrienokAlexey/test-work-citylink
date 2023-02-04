const h1 = document.createElement('h1');
h1.textContent = TITLE;
document.body.append(h1);

const header = document.createElement('div');
header.classList.add('container', 'mb-5', 'robotoslab');
document.body.append(header);

const nav = document.createElement('nav');
nav.classList.add('nav');
header.append(nav);

typeOfOlympiad.forEach(el => {
    const a = document.createElement('a');
    a.classList.add('nav-link');
    a.href = el.href;
    a.textContent = el.name;
    nav.append(a);
})
