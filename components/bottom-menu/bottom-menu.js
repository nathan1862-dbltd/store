document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', (e) => {

        const clickedItem = e.target.closest('.nav-item');

        if(!clickedItem) return;

        const navItems = document.querySelectorAll('.nav-item');

        navItems.forEach(item => {
            item.classList.remove('active');
        });

        clickedItem.classList.add('active');

    });

});
