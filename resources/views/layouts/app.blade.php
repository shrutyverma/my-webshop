<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>My Webshop</title>
    <style>
        /* Reset some default styles */
        body, h1, h2, p {
            margin: 0;
            padding: 0;
        }

        /* Define a background color for the body */
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            min-height: 100vh; /* Ensure the body covers the full viewport height */
            display: flex;
            flex-direction: column; /* Set flex direction to column for sticky footer */
        }

        /* Style the header */
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }

        header nav {
            text-align: right;
        }

        header nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        /* Style the main content */
        main {
            flex: 1; /* Allow main content to expand and push footer to the bottom */
            padding: 20px;
        }

        /* Style the footer */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="#">Home</a>
            <a href="#">Products</a>
            <a href="#">Contact</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        &copy; 2023 My Webshop
    </footer>
</body>
</html>
