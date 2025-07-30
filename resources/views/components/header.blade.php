@php    
$bgImg = asset('storage/assests/home_worship.webp');
$logo = asset('storage/assests/KH_Main.png');
@endphp
<div class="bg-fixed bg-cover fixed inset-0 opacity-25" style="background-image: url({{ $bgImg }});"></div>
<div class="relative px-0 pb-12 lg:px-8">
    <header class ="flex flex-col items-center w-full">
        <div class="w-full h-24 text-gray-800 flex justify-center">
            <nav class="w-full max-w-screen-lg flex justify-between">
                <a href="/">
                    <img class="h-18 py-2 pl-4" src={{ $logo }} alt="Kings Hope Logo">
                </a>
            </nav>
        </div>
        
        <h1 class="text-gray-800 text-3xl md:text-5xl mb-1.5">{{ $heading }}</h1>
    </header>