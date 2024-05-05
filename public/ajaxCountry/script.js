$(document).ready(function() {
    // Učitavanje podataka
    function loadData() {
        $.ajax({
            url: 'http://localhost:8000/api/states',
            method: 'GET',
            success: function(data) {
                var rows = '';
                $.each(data, function(key, value) {
                    rows += '<tr><td>' + value.id + '</td><td>' + value.name +
                        '</td><td><a href="edit.html?id=' + value.id + '">Uredi</a> | ' +
                        '<a href="#" class="delete" data-id="' + value.id + '">Izbriši</a></td></tr>';

                });
                $('#data').html(rows);
            }
        });
    }

    // Dodavanje novog modela
    $('#addForm').submit(function(e) {
        e.preventDefault();
        var name = $('#name').val();
        $.ajax({
            url: 'http://localhost:8000/api/states',
            method: 'POST',
            data: { name: name },
            success: function(response) {
                loadData();
                $('#name').val('');
            }
        });
    });

    // Ažuriranje modela
    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        console.log(id);
        var name = $('#name').val();
        $.ajax({
            url: 'http://localhost:8000/api/states/' + id,
            method: 'PUT',
            data: { name: name },
            success: function(response) {
                loadData();
                window.location.href = 'index.html';
            }
        });
    });

    // Brisanje modela
    $('body').on('click', '.delete', function() {
        var id = $(this).data('id');
        if(confirm('Da li ste sigurni da želite izbrisati ovaj model?')) {
            $.ajax({
                url: 'http://localhost:8000/api/states/' + id,
                method: 'DELETE',
                success: function(response) {
                    loadData();
                }
            });
        }
    });


    loadData();
});

const searchQuery = document.getElementById('searchQuery');
const searchResults = document.getElementById('searchResults');

searchQuery.addEventListener('input', function() {
    const query = searchQuery.value;
    axios.get('/api/search', { params: { query: query } })
        .then(response => {
            const results = response.data;
            searchResults.innerHTML = '';
            if (results.length) {
                const ul = document.createElement('ul');
                results.forEach(result => {
                    const li = document.createElement('li');
                    li.textContent = result.name;
                    ul.appendChild(li);
                });
                searchResults.appendChild(ul);
            } else {
                searchResults.textContent = 'Nema rezultata za prikaz.';
            }
        })
        .catch(error => {
            console.error(error);
            searchResults.textContent = 'Došlo je do greške prilikom pretrage.';
        });
});

