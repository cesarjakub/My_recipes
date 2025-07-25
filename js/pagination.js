const recipesContainer = document.querySelector('.recipes-cards');
const paginationContainer = document.querySelector('.pagination');

function fetchRecipes(page = 1) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `?xhr=true&page=${page}`, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            recipesContainer.innerHTML = response.html;
            renderPagination(response.currentPage, response.totalPages);
        } else {
            console.error(`Error: ${xhr.status}`);
        }
    };

    xhr.onerror = function () {
        console.error('Request failed.');
    };

    xhr.send();
}

function renderPagination(currentPage, totalPages) {
    let paginationHTML = '';

    if (currentPage > 1) {
        paginationHTML += `<button data-page="${currentPage - 1}" class="pagination-btn">&laquo;</button>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `<button data-page="${i}" class="pagination-btn ${i === currentPage ? 'active' : ''}">${i}</button>`;
    }

    if (currentPage < totalPages) {
        paginationHTML += `<button data-page="${currentPage + 1}" class="pagination-btn">&raquo;</button>`;
    }

    paginationContainer.innerHTML = paginationHTML;

    document.querySelectorAll('.pagination-btn').forEach(button => {
        button.addEventListener('click', function () {
            const page = parseInt(this.getAttribute('data-page'), 10);
            fetchRecipes(page);
        });
    });
}

fetchRecipes();
