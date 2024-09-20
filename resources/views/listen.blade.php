<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Listener</title>
    <style>
        #messages h3 {
            width: 100%;
        }

        #messages h3.me {
            text-align: right;
        }
    </style>
</head>
<body>
<div id="messages">

</div>
@vite('resources/js/app.js')
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Echo.private('messages')
                .listen('MessageSent', (e) => {
                    // let h3 = document.createElement('h3');
                    // h3.textContent = e.message;
                    // h3.classList.add(e.className); // Add class to indicate sender
                    // document.getElementById('messages').appendChild(h3)
                    console.log(e)
                });
        })
    </script>
@endpush
</body>
</html>
