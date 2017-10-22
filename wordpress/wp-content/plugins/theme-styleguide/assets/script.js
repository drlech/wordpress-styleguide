(function() {

    class MenuItem {
        constructor(element) {
            this.el = element;
            this.subitems = null;
            this.isOpen = false;

            this.init();
        }

        init() {
            const subitems = this.el.getElementsByClassName('subitems');
            if (subitems.length) {
                this.subitems = subitems[0];
                this.bindClick();
            }
        }

        bindClick() {
            const box = this.el.getElementsByClassName('box')[0];
            box.addEventListener('click', e => {
                e.preventDefault();
                this.toggle();
            });
        }

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }

        open() {
            this.subitems.style.display = 'block';
            this.isOpen = true;
        }

        close() {
            this.subitems.style.display = '';
            this.isOpen = false;
        }
    }

    /* Initialization */

    const menuItems = document.getElementsByClassName('menu-item');
    for (let menuItem of menuItems) {
        new MenuItem(menuItem);
    }

})();