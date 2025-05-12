let editingCard = null;
const form = document.getElementById('anime_form');
const modal = new bootstrap.Modal(document.getElementById('modal_addAnime'));
const submitButton = form.querySelector('button[type="submit"]');

form.addEventListener('submit', (e) =>
    {
        e.preventDefault();
        const name = document.getElementById('input_name').value;
        const seasons = document.getElementById('input_seasons').value;
        const episodes = document.getElementById('input_episodes').value;
        const description = document.getElementById('input_description').value;

        if (editingCard)
        {
            editingCard.querySelector('#anime_name').textContent = name;
            editingCard.querySelector('#anime_seasons').textContent = seasons;
            editingCard.querySelector('#anime_episodes').textContent = episodes;
            editingCard.querySelector('#anime_description').textContent = description;
            editingCard = null;

            updateLocalStorage();
            submitButton.textContent = 'Přidat anime';
        } else {
            cardFetch(name, seasons, episodes, description)
        }
        form.reset();
        modal.hide();
    }
);

function editAnime(card)
{
    editingCard = card;

    document.getElementById('input_name').value = card.querySelector('#anime_name').textContent;
    document.getElementById('input_seasons').value = card.querySelector('#anime_seasons').textContent;
    document.getElementById('input_episodes').value = card.querySelector('#anime_episodes').textContent;
    document.getElementById('input_description').value = card.querySelector('#anime_description').textContent;
    submitButton.textContent = "Uložit změny";
    modal.show();
}


function cardFetch(name, seasons, episodes, description)
{
    fetch('support/card.html')
        .then(response => response.text())
        .then(data =>
            {
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;

                let card = tempDiv.querySelector(".col-md-3");

                if (card)
                {
                    card.querySelector('#anime_name').textContent = name;
                    card.querySelector('#anime_seasons').textContent = seasons;
                    card.querySelector('#anime_episodes').textContent = episodes;
                    card.querySelector('#anime_description').textContent = description;

                    let btnDelete = card.querySelector('[data-delete-anime]');
                    let btnEdit = card.querySelector('[data-edit-anime]');

                    if (btnDelete)
                    {
                        btnDelete.addEventListener('click', () =>
                            {
                                card.remove();
                                updateLocalStorage()
                            }
                        );
                    }

                    if (btnEdit)
                    {
                        btnEdit.addEventListener('click', () => editAnime(card));
                    }

                    document.getElementById('my_anime_list').appendChild(card);
                    card.scrollIntoView({behavior: 'smooth', block: 'end'});

                    updateLocalStorage();
                }
            }
        ).catch(error => console.error(error));
}

function updateLocalStorage()
{
    let animeList = [];

    document.querySelectorAll("#my_anime_list .col-md-3").forEach(card =>
        {
            animeList.push(
                {
                    name: card.querySelector('#anime_name').textContent,
                    seasons: card.querySelector('#anime_seasons').textContent,
                    episodes: card.querySelector('#anime_episodes').textContent,
                    description: card.querySelector('#anime_description').textContent
                }
            );
        }
    );

    localStorage.setItem("animeList", JSON.stringify(animeList));
    buttonVisibility();
}

function loadAnimeFromStorage()
{
    let storedAnimeList = localStorage.getItem("animeList");

    if (storedAnimeList)
    {
        JSON.parse(storedAnimeList).forEach(anime =>
            {
                cardFetch(anime.name, anime.seasons, anime.episodes, anime.description)
            }
        );
    }
}

function buttonVisibility()
{

    if (getCookie('userName') !== null)
    {
        setTimeout(() => {
            document.querySelectorAll('[data-delete-anime]').forEach(card => card.classList.remove('visually-hidden'));
            document.querySelectorAll('[data-edit-anime]').forEach(card => card.classList.remove('visually-hidden'));
        }, 500);
    } else {
        setTimeout(() => {
            document.querySelectorAll('[data-delete-anime]').forEach(card => card.classList.add('visually-hidden'));
            document.querySelectorAll('[data-edit-anime]').forEach(card => card.classList.add('visually-hidden'));
        }, 500);
    }
}

function filterAnime(searchText)
{
    let cards = document.querySelectorAll("#my_anime_list .col-md-3");

    cards.forEach(card =>
        {
            let animeName = card.querySelector('#anime_name').textContent.toLowerCase();

            if (animeName.includes(searchText))
            {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    );
}


window.loadAnimeFromStorage = loadAnimeFromStorage;
window.buttonVisibility = buttonVisibility;
window.filterAnime = filterAnime;