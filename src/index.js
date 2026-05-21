class Pizza {
    static TYPES = {
        'Маргарита': { price: 500, calories: 300 },
        'Пепперони': { price: 800, calories: 400 },
        'Баварская': { price: 700, calories: 450 }
    };

    static SIZES = {
        'маленькая': { price: 100, calories: 100 },
        'большая': { price: 200, calories: 200 }
    };

    static TOPPINGS = {
        'сливочная моцарелла': { price: 50, calories: 20 },
        'сырный борт': {
            'маленькая': { price: 150, calories: 50 },
            'большая': { price: 300, calories: 50 }
        },
        'чеддер и пармезан': {
            'маленькая': { price: 150, calories: 50 },
            'большая': { price: 300, calories: 50 }
        }
    };

    constructor(type, size) {
        if (!Pizza.TYPES[type]) throw new Error(`Неизвестный вид пиццы: ${type}`);
        if (!Pizza.SIZES[size]) throw new Error(`Неизвестный размер пиццы: ${size}`);

        this.type = type;
        this.size = size;
        this.toppings = [];
    }

    addTopping(topping) {
        if (!Pizza.TOPPINGS[topping]) {
            throw new Error(`Неверная добавка: ${topping}`);
        }
        if (!this.toppings.includes(topping)) {
            this.toppings.push(topping);
        }
    }
    _getToppingProperty(topping, property) {
        const toppingInfo = Pizza.TOPPINGS[topping];
        return typeof toppingInfo[property] !== 'undefined'
            ? toppingInfo[property]
            : toppingInfo[this.size][property];
    }

    calculatePrice() {
        const basePrice = Pizza.TYPES[this.type].price + Pizza.SIZES[this.size].price;
        return this.toppings.reduce((total, topping) => {
            return total + this._getToppingProperty(topping, 'price');
        }, basePrice);
    }

    calculateCalories() {
        const baseCalories = Pizza.TYPES[this.type].calories + Pizza.SIZES[this.size].calories;
        return this.toppings.reduce((total, topping) => {
            return total + this._getToppingProperty(topping, 'calories');
        }, baseCalories);
    }
}

let currentType = 'Пепперони';
let currentSize = 'маленькая';
let currentToppings = new Set();

function updateCartButton() {
    const pizza = new Pizza(currentType, currentSize);
    currentToppings.forEach(topping => pizza.addTopping(topping));
    document.getElementById('total-price').textContent = pizza.calculatePrice();
    document.getElementById('total-calories').textContent = pizza.calculateCalories();
}

document.querySelectorAll('.pizza-card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('.pizza-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        currentType = card.dataset.type;
        updateCartButton();
    });
});

document.querySelectorAll('.size-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.size-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        currentSize = tab.dataset.size;
        updateCartButton();
    });
});

document.querySelectorAll('.topping-card').forEach(card => {
    card.addEventListener('click', () => {
        const toppingName = card.dataset.topping;
        if (currentToppings.has(toppingName)) {
            currentToppings.delete(toppingName);
            card.classList.remove('active');
        } else {
            currentToppings.add(toppingName);
            card.classList.add('active');
        }

        updateCartButton();
    });
});

updateCartButton();