const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const logowanieLink = document.querySelector('.logowanie-link');
const iconClose = document.querySelector('.icon-close');


registerLink.addEventListener('click', ()=> {
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', ()=> {
    wrapper.classList.remove('active');
});

logowanieLink.addEventListener('click', ()=> {
    wrapper.classList.add('active-popup');
});

logowanieLink.addEventListener('click', ()=> {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

iconClose.addEventListener('click', ()=> {
    wrapper.classList.remove('active-popup');
    wrapper.classList.remove('active');
});

