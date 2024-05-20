<!DOCTYPE html>
<html>

<head>
    <title>Buscar Pokémon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Buscar Pokémon</h1>
        <form id="search-form" class="mb-4">
            @csrf
            <input type="text" name="term" placeholder="Nombre del Pokémon" id="term"
                class="border rounded p-2 mb-2">
            <button type="submit" class="bg-green-500 text-white rounded p-2">Buscar</button>
        </form>
        <div id="errors" class="text-red-600"></div>
        <h2 class="text-xl font-semibold mb-2">Habilidades del Pokémon</h2>
        <ul id="abilities" class="list-disc pl-5"></ul>
        <h2 class="text-xl font-semibold mt-4 mb-2">Historial de búsquedas</h2>
        <ul id="historial" class="list-disc pl-5"></ul>
    </div>

    <script>
        $(document).ready(function() {
            // function para cargar el historial de las busquedas
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

            //evento de formulario (token de sesion)
            $('#search-form').on('submit', function(event) {
                event.preventDefault();
                let term = $('#term').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                //llamada AJAX
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

                        //mostramos habilitades
                        if (data.abilities) {
                            data.abilities.forEach(function(ability) {
                                $('#abilities').append('<li>' + ability + '</li>');
                            });
                        } else if (data.message) {
                            $('#errors').append('<div>' + data.message + '</div>');
                        } else {
                            $('#errors').append('<div>No se encontraron habilidades.</div>');
                        }

                        //cargamos historial
                        loadHistory();
                    },
                    error: function(xhr) {
                        let errors = JSON.parse(xhr.responseText);
                        $('#errors').empty().append('<div>' + errors.error + '</div>');
                    }
                });
            });

            //evento click para cargar los datos del historial de busqueda
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
