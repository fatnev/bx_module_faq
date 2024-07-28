const faqItems = document.querySelectorAll('.faq details');

faqItems.forEach((item) => {
    item.addEventListener('toggle', (event) => {
        if (event.target.open) {
            faqItems.forEach((otherItem) => {
                if (otherItem !== event.target) {
                    otherItem.removeAttribute('open');
                }
            });
        }
    });
});