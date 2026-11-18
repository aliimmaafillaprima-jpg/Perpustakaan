<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — LENDORA</title>
    
    <link rel="icon" href="{{ asset('images/logo_smk.jpg') }}" type="image/jpeg">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
{{-- GRADASI UNGU KE PINK GELAP (TIDAK BIRU) --}}
<body class="bg-gradient-to-br from-purple-900 via-slate-900 to-pink-900 min-h-screen flex items-center justify-center p-4">
    @yield('content')
</body>
</html>