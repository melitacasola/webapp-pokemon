<!DOCTYPE html>
<html>

<head>
    <title>Buscar Pokémon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Buscar Pokémon</h1>
    <form id="search-form">
        @csrf
        <input type="text" name="term" placeholder="Nombre del Pokémon" id="term">
        <button type="submit">Buscar</button>
    </form>
    <div id="errors"></div>
    <h2>Habilidades del Pokémon</h2>
    <ul id="abilities"></ul>
    <h2>Historial de búsquedas</h2>
    <ul id="historial"></ul>

    <script>
        $(document).ready(function() {
            function loadHistory() {
                $.ajax({
                    url: '{{ route('buscar.historial') }}',
                    type: 'GET',
                    success: function(data) {
                        $('#historial').empty();
                        data.historial.forEach(function(item) {
                            $('#historial').append(
                                '<li><a href="#" class="search-term" data-term="' + item
                                .term + '">' + item.term + '</a></li>');
                        });
                    }
                });
            }

            $('#search-form').on('submit', function(event) {
                event.preventDefault();
                let term = $('#term').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('buscar.procesar') }}',
                    type: 'POST',
                    data: {
                        _token: token,
                        term: term
                    },
                    success: function(data) {
                        $('#abilities').empty();
                        $('#errors').empty();

                        if (data.abilities) {
                            data.abilities.forEach(function(ability) {
                                $('#abilities').append('<li>' + ability + '</li>');
                            });
                        } else {
                            $('#errors').append('<div>No se encontraron habilidades.</div>');
                        }

                        loadHistory();
                    },
                    error: function(xhr) {
                        let errors = JSON.parse(xhr.responseText);
                        $('#errors').empty().append('<div>' + errors.error + '</div>');
                    }
                });
            });

            $(document).on('click', '.search-term', function(event) {
                event.preventDefault();
                let term = $(this).data('term');
                $('#term').val(term);
                $('#search-form').submit();
            });

            loadHistory();
        });
    </script>
</body>

</html>
