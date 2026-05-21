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
    getToppings() {
        return this.toppings;
    }

    getSize() {
        return this.type;
    }

    getStuffing() {
        return this.size;
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

const calculatePizza = () => {
    try {
        const selectedType = document.getElementById('pizza-type').value;
        const selectedSize = document.getElementById('pizza-size').value;

        const userPizza = new Pizza(selectedType, selectedSize);

        const checkboxes = document.querySelectorAll('.topping-checkbox:checked');
        checkboxes.forEach(cb => {
            userPizza.addTopping(cb.value);
        });

        const resultText = `
Вы соберете: ${userPizza.getSize()} пиццу
Размер: ${userPizza.getStuffing()}
Добавки: ${userPizza.getToppings().length > 0 ? userPizza.getToppings().join(', ') : 'без добавок'}
---------------------------------------
Итоговая стоимость: ${userPizza.calculatePrice()} руб.
Полная калорийность: ${userPizza.calculateCalories()} Ккал.
        `;

        const displayElement = document.getElementById('pizza-info');
        displayElement.style.color = '#333';
        displayElement.textContent = resultText.trim();

    } catch (error) {
        const displayElement = document.getElementById('pizza-info');
        displayElement.style.color = 'red';
        displayElement.textContent = `Ошибка при сборке: ${error.message}`;
    }
};

document.getElementById('btn-calculate').addEventListener('click', calculatePizza);

calculatePizza();