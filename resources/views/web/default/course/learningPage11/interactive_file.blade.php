<html>
<head>
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- Kemetic Theme CSS -->
    <link href="{{ asset('/assets/default/css/font.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/default/css/app.css')}}">
    <style>
        body.play-iframe-page {
            background-color: #1C1C1C; /* Kemetic dark background */
            color: #F2C94C; /* Gold text */
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .interactive-file-iframe {
            width: 95%;
            height: 95%;
            border: 2px solid #F2C94C; /* Gold border */
            border-radius: 12px;
            background-color: #111; /* Dark iframe background */
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        /* Loading fallback if iframe is empty */
        .iframe-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;
        }

        .iframe-loading img {
            width: 80px;
            margin-bottom: 15px;
        }

        .iframe-loading p {
            color: #B0B0B0;
            font-size: 16px;
        }
    </style>
</head>
<body class="play-iframe-page">
    @if(!empty($iframe))
        {!! $iframe !!}
    @else
        <iframe src="{{ $path }}" frameborder="0" allowfullscreen class="interactive-file-iframe"></iframe>
    @endif
</body>
</html>

