document.addEventListener('DOMContentLoaded', function() {
    const btnPaypal = document.getElementById('__button_paypal');
    const btnBank = document.getElementById('__button_bank_card');
    const formPaypal = document.getElementById('__paypal_form');
    const formBank = document.getElementById('__bank_form');
    const sendBtn = document.getElementById('__send');


    btnPaypal.addEventListener('click', () => {
        if (formPaypal.classList.contains('form-active')) {
            formPaypal.classList.remove('form-active');
            formBank.classList.remove('form-active');
        } else {
            formPaypal.classList.add('form-active');
            sendBtn.setAttribute('name', '__sendPaypal');
        }
    });

    btnBank.addEventListener('click', () => {
        if (formBank.classList.contains('form-active')) {
            formBank.classList.remove('form-active');
            formPaypal.classList.remove('form-active');
        } else {
            formBank.classList.add('form-active');
            sendBtn.setAttribute('name', '__sendBankCard');
        }
    })
});