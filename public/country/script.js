document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    const csrfToken = document.cookie.split(';').find(c => c.trim().startsWith('csrftoken=')).split('=')[1];

    console.log(csrfToken);

    // Funkcija za dohvat popisa država
    function getDrzave() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://127.0.0.1:8000/api/states', true);
        xhr.onload = function () {
            if (this.status === 200) {
                const drzave = JSON.parse(this.responseText);
                let output = '';
                drzave.forEach(function (drzava) {
                    output += `
                        <li class="list-group-item">${drzava.name}
                            <button onclick="editState(${drzava.id})" class="btn btn-secondary btn-sm">Uredi</button>
                            <button onclick="deleteState(${drzava.id})" class="btn btn-danger btn-sm">Izbriši</button>
                        </li>
                    `;
                });
                document.getElementById('drzave-list').innerHTML = output;
            }
        }
        xhr.send();
    }

    if (id) {
        // Dohvati detalje države za uređivanje
        fetchStateDetails(id, csrfToken);
    } else {
        // Ako nema ID-a u URL-u, prikaži običan popis država
        getDrzave();
    }
});

function fetchStateDetails(id, csrfToken) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `http://127.0.0.1:8000/api/states/${id}`, true);
    xhr.onload = function () {
        if (this.status === 200) {
            const drzava = JSON.parse(this.responseText);
            document.getElementById('name').value = drzava?.name;
            document.getElementById('code').value = drzava?.code; // Dodajte ovo ako postoji polje 'code'
            setupEditForm(id, csrfToken);
        }
    }
    xhr.send();
}

function setupEditForm(id, csrfToken) {
    const editForm = document.getElementById('edit-form');
    editForm.onsubmit = function (e) {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const code = document.getElementById('code').value; // Dodajte ovo ako postoji polje 'code'

        const xhr = new XMLHttpRequest();
        xhr.open('PUT', `http://127.0.0.1:8000/api/states/${id}`, true);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken); // Ispravljeno na velika slova
        xhr.setRequestHeader("Accept", "application/json");

        xhr.onload = function () {
            if (this.status === 200) {
                window.location.href = 'index.html';
            }
        }
        xhr.send(JSON.stringify({ name: name, code: code })); // Dodajte 'code' u podatke ako postoji
    };
}

function editState(id) {
    window.location.href = `edit.html?id=${id}`;
}

function deleteState(id) {
    const confirmDelete = confirm('Jeste li sigurni da želite izbrisati ovu državu?');
    if (confirmDelete) {
        const csrfToken = document.cookie.split(';').find(c => c.trim().startsWith('csrftoken=')).split('=')[1];
        const xhr = new XMLHttpRequest();
        xhr.open('DELETE', `http://127.0.0.1:8000/api/states/${id}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.onload = function () {
            if (this.status === 200) {
                window.location.reload(); // Osvježi stranicu umjesto preusmjeravanja na index.html
            }
        }
        xhr.send();
    }
}
