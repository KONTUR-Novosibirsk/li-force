export const storageManager = {
    // Избранное
    getFavorites() {
        return JSON.parse(localStorage.getItem('selectProducts')) || [];
    },

    setFavorites(products) {
        localStorage.setItem('selectProducts', JSON.stringify(products));
        this.dispatchUpdateEvent('favoritesUpdated');
    },

    addToFavorites(product) {
        const favorites = this.getFavorites();
        if (!favorites.some(item => item.id === product.id)) {
            favorites.push(product);
            this.setFavorites(favorites);
            this.dispatchUpdateEvent('favoritesUpdated'); // Важно: отправляем событие
            return true;
        }
        return false;
    },
    removeFromFavorites(productId) {
        const favorites = this.getFavorites().filter(item => item.id != productId);
        this.setFavorites(favorites);
        this.dispatchUpdateEvent('favoritesUpdated'); // Важно: отправляем событие
    },

    // Сравнение
    getCompare() {
        return JSON.parse(localStorage.getItem('compareProducts')) || [];
    },

    setCompare(products) {
        localStorage.setItem('compareProducts', JSON.stringify(products));
        this.dispatchUpdateEvent('compareUpdated');
    },

    addToCompare(product) {
        const compare = this.getCompare();
        if (!compare.some(item => item.id === product.id)) {
            compare.push(product);
            this.setCompare(compare);
            this.dispatchUpdateEvent('compareUpdated'); // Важно: отправляем событие
            return true;
        }
        return false;
    },

    removeFromCompare(productId) {
        const compare = this.getCompare().filter(item => item.id != productId);
        this.setCompare(compare);
        this.dispatchUpdateEvent('compareUpdated');
        this.updateHeaderCounters(); // Дополнительное обновление счетчиков
    },
    clearCompare() {
        localStorage.removeItem('compareProducts');
        this.dispatchUpdateEvent('compareUpdated');
        this.updateHeaderCounters(); // Дополнительное обновление счетчиков
    },
    // Общие методы
    dispatchUpdateEvent(eventName) {
        window.dispatchEvent(new CustomEvent(eventName));
    },

    updateHeaderCounters() {
        // Обновляем ВСЕ счётчики избранного
        const favorites = this.getFavorites();
        const favoriteCounters = document.querySelectorAll('.select-counter');
        favoriteCounters.forEach(counter => {
            counter.textContent = favorites.length;
            counter.style.display = favorites.length > 0 ? 'flex' : 'none';
        });

        // Обновляем ВСЕ счётчики сравнения
        const compare = this.getCompare();
        const compareCounters = document.querySelectorAll('.compare-counter');
        compareCounters.forEach(counter => {
            counter.textContent = compare.length;
            counter.style.display = compare.length > 0 ? 'flex' : 'none';
        });
    },

    isInCompare(productId) {
        const compare = this.getCompare();
        return compare.some(item => item.id == productId);
    },
    isInFavorites(productId) {
        const favorites = this.getFavorites();
        return favorites.some(item => item.id == productId);
    },

    initCounters() {
        this.updateHeaderCounters();
    }

};
