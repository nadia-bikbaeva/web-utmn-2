import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";

const init = async () => {
    try {
        const { ok: isLogged, data } = await Auth.me();

        if (!isLogged) {
            return location.login();
        }

        console.log("Данные от бэкенда Auth.me():", data);
        const userInfoEl = document.getElementById('user-info');
        if (!userInfoEl) {
            console.error("Критическая ошибка: Элемент #user-info не найден в HTML!");
            return;
        }

        const user = data && data.user ? data.user : data;

        if (user) {
            const userInfoAgeEl = userInfoEl.querySelector('[data-user-age]');
            if (userInfoAgeEl) userInfoAgeEl.innerText = user.age || '—';

            const userInfoNameEl = userInfoEl.querySelector('[data-user-name]');
            if (userInfoNameEl) userInfoNameEl.innerText = user.name || 'Без имени';

            const userInfoEmailEl = userInfoEl.querySelector('[data-user-email]');
            if (userInfoEmailEl) userInfoEmailEl.innerText = user.email || '—';
        } else {
            console.warn("Предупреждение: Объект пользователя пустой или отсутствует в ответе сервера.");
        }

    } catch (error) {
        console.error("Произошла ошибка в user.js:", error);
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