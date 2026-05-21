import Form from "../components/form.js";
import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";

const init = async () => {
    try {
        const { ok: isLogged } = await Auth.me();

        if (isLogged) {
            // Если авторизован — перенаправляем.
            // На случай, если location.user() сломается из-за путей PhpStorm,
            // делаем запасной вариант с относительным переходом.
            return location.user ? location.user() : window.location.href = 'user.html';
        }

        const formEl = document.getElementById('login-form');
        if (!formEl) {
            console.error("Критическая ошибка: Форма #login-form не найдена на странице!");
            return;
        }

        new Form(formEl, {
            'email': (value) => {
                if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value))) {
                    return 'Некорректный email';
                }
                return false;
            },
            'password': (value) => {
                if (value.length < 6) {
                    return 'Значение должно быть больше или равно 6';
                } else if (value.length >= 32) {
                    return 'Значение должно быть меньше 32';
                }
                return false;
            }
        }, async (values) => {
            // --- ЭТОТ КОД СРАБАТЫВАЕТ ПРИ ОТПРАВКЕ ФОРМЫ ---
            try {
                // Включаем лоадер на время запроса к бэкенду
                if (loading && typeof loading.start === 'function') loading.start();

                const result = await Auth.login(values);

                if (result && result.ok) {
                    // Перенаправляем на страницу задач после успешного входа
                    window.location.href = 'todos.html';
                } else {
                    alert(result?.message || 'Неверный логин или пароль');
                }
            } catch (authError) {
                console.error("Ошибка при попытке входа:", authError);
                alert("Произошла ошибка при соединении с сервером.");
            } finally {
                // Выключаем лоадер после ответа сервера (даже если пароль неверный)
                if (loading && typeof loading.stop === 'function') loading.stop();
            }
        });

    } catch (error) {
        // Ловим любые системные ошибки при загрузке скрипта
        console.error("Критическая ошибка в login.js:", error);
    } finally {
        // ЧТО БЫ НИ СЛУЧИЛОСЬ: первичный черный кружок при заходе на страницу исчезнет!
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