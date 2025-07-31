@php    
    $khcLogo= asset('storage/assests/khc.svg');  
@endphp
<div class="p-2 flex flex-col  w-full bg-white justify-around rounded-b-md shadow">
    <div class="p-2 flex justify-center ">
        <img class="max-w-32" src={{ $khcLogo }} alt="Kings Hope Church Logo"/>
    </div>
    <div class="p-2 flex flex-col text-center">
        <span class="text-gray-800 text-l">"The church is not just a building it is the people"</span>
        <span class="text-gray-800 text-m"> - Pastor Gavin</span>
    </div>
    <div class="p-2 flex justify-center text-center">
        <span class="text-gray-800 text-sm">© 2025 Kings Hope Church</span>
    </div>
</div>