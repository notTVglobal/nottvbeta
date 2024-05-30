<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error - The notTV Team')</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #1a1a1a; /* Dark background color */
            color: #f5f5f5; /* Light text color */
        }
        .content {
            max-width: 600px;
            padding: 20px;
            background-color: #2a2a2a; /* Slightly lighter dark background for content */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            margin-bottom: 40px;
        }
        .logo img {
            max-width: 200px;
        }
        .message {
            margin-top: 20px;
            font-size: 1.2em;
        }
        a {
            color: #ffcc00; /* Brand accent color */
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .brand-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 1em;
            color: #1a1a1a;
            background-color: #ffcc00; /* Brand yellow color */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .brand-button:hover {
            background-color: #e6b800; /* Darker shade of yellow for hover effect */
        }
    </style>
</head>
<body>
<div class="content">
    <div class="logo">
        <a href="/"><img src="https://not.tv/storage/images/logo_white_512.png" alt="notTV Logo"></a>
    </div>
    <div class="message">
        @yield('message')
    </div>
    <div>
        <a href="https://help.not.tv" class="brand-button">Contact Us</a>
    </div>
</div>
</body>
</html>
