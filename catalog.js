import { Catalog } from "src/components/catalog.js"

const renderPostItem = item => `
    <a  
        href="/posts?id=${item.id}"
        class="post-item"
    >
        <span class="post-item__title">
            ${item.title}
        </span>
        <span class="post-item__body">
            ${item.body}
        </span>
    </a>
`

const getPostItems = async ({ limit, page }) => {
    try {
        const response = await fetch(`https://jsonplaceholder.typicode.com/posts?_limit=${limit}&_page=${page}`);

        if (!response.ok) {
            throw new Error(`Ошибка сервера: ${response.status}`);
        }

        const total = +response.headers.get('x-total-count');
        const items = await response.json();

        return { items, total };
    } catch (error) {
        console.error("Не удалось загрузить посты:", error);
        return { items: [], total: 0 };
    }
}
const init = () => {
    const catalog = document.getElementById('catalog')
    if (catalog) {
        new Catalog(catalog, {
            renderItem: renderPostItem,
            getItems: getPostItems
        }).init()
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init)
} else {
    init()
}