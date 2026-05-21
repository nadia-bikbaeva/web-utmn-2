const postsContainer = document.getElementById('posts');
const paginationContainer = document.getElementById('pagination');

const POSTS_PER_PAGE = 10;
let currentPage = 1;
let posts = [];

//Все посты
async function fetchPosts() {
    try {
        const response = await fetch('https://jsonplaceholder.typicode.com/posts');
        if (!response.ok) {
            throw new Error('Ошибка при получении постов');
        }
        posts = await response.json();
        renderPosts();
        renderPagination();
    } catch (error) {
        postsContainer.innerHTML = `<p>Ошибка: ${error.message}</p>`;
    }
}

function renderPosts() {
    postsContainer.innerHTML = '';

    const start = (currentPage - 1) * POSTS_PER_PAGE;
    const end = start + POSTS_PER_PAGE;
    const currentPosts = posts.slice(start, end);

    currentPosts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.innerHTML = `
            <h3>${post.title}</h3>
            <p>${post.body}</p>
            <a href="post.html?id=${post.id}">Читать далее</a>
        `;
        postsContainer.appendChild(postElement);
    });
}

function renderPagination() {
    paginationContainer.innerHTML = '';

    const totalPages = Math.ceil(posts.length / POSTS_PER_PAGE);

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.className = 'pagination';
        if (i === currentPage) {
            button.disabled = true;
        }
        button.addEventListener('click', () => {
            currentPage = i;
            renderPosts();
            renderPagination();
        });
        paginationContainer.appendChild(button);
    }
}

fetchPosts();