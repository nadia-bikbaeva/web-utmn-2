import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";

const init = async () => {
    try {
        // Проверяем, вдруг пользователь уже авторизован
        const { ok: isLogged } = await Auth.me();

        if (isLogged) {
            return location.todos ? location.todos() : window.location.href = 'todos.html';
        }

        const regForm = document.querySelector('form'); // или document.getElementById('reg-form')

        if (regForm) {
            regForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Включаем лоадер при отправке формы
                if (loading && typeof loading.start === 'function') loading.start();

                try {
                    const email = regForm.querySelector('[type="email"]')?.value;
                    const name = regForm.querySelector('[type="text"]')?.value;
                    const age = regForm.querySelector('[type="number"]') ? Number(regForm.querySelector('[type="number"]').value) : 18;
                    const password = regForm.querySelectorAll('[type="password"]')[0]?.value;
                    const passwordRepeat = regForm.querySelectorAll('[type="password"]')[1]?.value;

                    if (password !== passwordRepeat) {
                        alert("Пароли не совпадают!");
                        return;
                    }

                    const result = await Auth.reg({ email, name, age, password });

                    if (result.ok) {
                        alert("Регистрация успешна!");
                        location.login();
                    } else {
                        alert(result.message || "Ошибка при регистрации");
                    }
                } catch (err) {
                    console.error("Ошибка при отправке формы регистрации:", err);
                } finally {
                    if (loading && typeof loading.stop === 'function') loading.stop();
                }
            });
        }

    } catch (error) {
        console.error("Критическая ошибка в reg.js:", error);
    } finally {
        if (loading && typeof loading.stop === 'function') {
            loading.stop();
        }
    }
}

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", init)
} else {
    init()
}