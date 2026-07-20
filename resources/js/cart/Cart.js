class Cart {
    cartTotalCount = document.querySelectorAll('.js__cart_total_count');

    changeTotalCount(count) {
        if (this.cartTotalCount) {
            this.cartTotalCount.forEach((element) => {
                element.innerHTML = count;
            })
        }
    }

    addListeners(elements) {
        if (elements.length > 0) {
            elements.forEach((element) => {
                element.addEventListener('click', this.addToCart.bind(this))
            })
        }
    }

    addToCart(event)  {
        event.preventDefault();
        let productData = JSON.parse(event.currentTarget.dataset.product);
        let dataCount = document.getElementById('counter');
        let addCount = 1;
        if(dataCount !== undefined && dataCount !== null) {
            addCount = dataCount.dataset.count;
        }

        axios.post('/cart/add', {
            id: productData.id,
            count: addCount,
        }).then((response) => {
            this.changeTotalCount(response.data.cart_total_count)
        }).catch((error) => {
            console.log(error);
        })
    }
}

export default new Cart
