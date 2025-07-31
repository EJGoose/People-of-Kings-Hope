<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>The People of Kings Hope</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])                
        @endif
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            [x-cloak] {display: none !important;}
        </style>
    </head>
    <body>
        <x-header heading="Connecting at Kings Hope Church"/>
        <main class="px-2 my-5 sm:p-4 max-w-5xl mx-auto w-full" x-data="form_handler({{ Js::from($apiResponse['data'])  }}, {{ Js::from($apiResponse['pagination']) }})">
            <div class="p-2 flex flex-row w-full bg-white justify-center rounded-t-md shadow">
                <h2 class="text-gray-800 text-2xl">Search for others in our fellowship</h2>
            </div>
            <div class="flex flex-row w-full bg-white sticky z-20">
                <form action="" method="GET" id="search-content"  :class="{'animate-pulse': loadingContent}" class="bg-white border-gray-200 border p-3 w-full">
                    <input name='p' type="hidden" value='1' hidden="1" x-model="currentPage">
                    <div class="space-y-1 w-full">
                        <div class="h-14 flex relative rounded-md shadow-sm  text-base focus-within:border-blue-900 focus-within:ring focus-within:ring-sky-700 focus-within:ring-opacity-50 focus-within:z-10">
                            <div class="flex items-center justify-center rounded-md text-gray-400 select-none w-10 absolute inset-y-0 left-0">
                                {{-- search icon --}}
                                <span :class="loadingContent ? 'animate-bounce':''" class="w-6 h-6 inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"     fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </span>
                            </div>
                            <input name='q' placeholder="Search" value type='text' id='q' @input.debounce.500ms="submit()" :placeholder='loadingContent ? "Searching..." : "Search"' aria-label="Search" x-model="searchQuery" class="border-gray-300 placeholder-gray-400 text-gray-800 text-base block w-full rounded-1-md pl-14 focus:outline-none focus:ring-0">
                            <div class="bg-gray-100 border border-gray-300 border-1-0 flex items-center justify-center px-3 rounded-r-md text-gray-700 select-none">
                                <span class="whitespace-nowrap space-x-1 flex items-center">
                                    <button @click.prevent ="currentPage--; $nextTick(() => submit())" :class="{'text-gray-200' : currentPage < 2}" class="h-6 rounded-md text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-800 hover:scale-110" :disabled='currentPage < 2'>
                                        {{-- left arrow icon --}}
                                        <span class="w-6 h-6 inline-block">
                                            <svg class="w-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        </span>
                                    </button>
                                    <span class="hidden sm:inline leading-5 text-gray-400">
                                        <span class="text-gray-600" x-text='activeData.p'></span>
                                            of 
                                        <span class="text-gray-600" x-text='activeData.pageEnd'></span>
                                    </span>
                                    <button @click.prevent="currentPage++; $nextTick(() => submit())" :class="{'text-gray-200': !activeData.more}" class="h-6 text-blue-700 focus:ring-2 focus:ring-blue-800 rounded-md focus:outline-none hover:scale-110" :disabled='!activeData.more'>
                                        {{-- right arrow icon --}}
                                        <span class=" w-6 h-6 inline-block">
                                            <svg class="w-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                        </span>
                                        </button>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <template x-if="errorMsg">
                            <span class="text-red-500 block px-1 text-wrap text-l text-center" x-text="errorMsg"></span>
                        </template>
                    </div>
                </form>
            </div>
            <div>
                <div x-cloak x-show="contactData.length<1 ? true : false" class="p-4 w-full bg-sky-50 flex justify-center shadow rounded-md">
                    <span>Your search has not returned any results</span>
                </div>
                <template x-if="!errorMsg">
                    <table :class='loadingContent ? "animate-pulse bg-gray-200" : "bg-white"' class="w-full shadow">
                        <tbody class="divide-y divide-gray-100">
                            <template x-for='contact in contactData' :key='contact.id'>
                                <tr x-data="{open: false }" @click.prevent="open =!open" @click.outside="open = false" :class='{"bg-opacity-50 bg-sky-50" :open}' class="cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-800 rounded hover:bg-opacity-50 focus:bg-opacity-50 hover:bg-sky-50 focus:bg-sky-50 relative">
                                    <td class="px-3 py-2 ">
                                        <div class="flex space-x-4 items-center">
                                            <span role="presentation" arai-hidden="true" class="h-8 text-base w-8 flex relative flex-shrink-0 ">
                                                    <template x-if="contact.image">
                                                        <img class="rounded-full outline-1  outline-sky-800" :src='contact.image' :aria='contact.name'></img>
                                                    </template>
                                                    <template x-if="!contact.image">
                                                        <span class="rounded-full uppercase bg-sky-50 border-transparent text-blue-900 outline-1 outline-sky-800 font-semibold bg-cover bg-center flex flex-col items-center justify-center leading-none w-full h-full">
                                                            <span x-text='contact.initials'></span>
                                                        </span>
                                                    </template>
                                            </span>
                                            <div>
                                                <div class="space-x-2 text-slate-800">
                                                    <span x-text='contact.name'></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 align-baseline md:table-cell hidden">
                                        <ul class="flex-col text-sm text-gray-700 space-y-2">
                                            <template x-if='contact.email'>
                                                <li class="flex items-baseline space-x-2 leading-5">
                                                    {{-- mail icon --}}
                                                    <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg> 
                                                    </span>
                                                    <a :href="'mailto:' + contact.email" class="hover:text-blue-600 hover:underline text-sky-800 truncate z-10 relative" x-text="contact.email"></a>
                                                </li>
                                            </template>
                                            <template x-if='contact.mobile'>
                                                <li class="flex items-baseline space-x-2 leading-5">
                                                    {{-- mobile icon --}}
                                                    <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/></svg>
                                                    </span>
                                                    <a :href="'tel:'+contact.mobile" class="hover:text-blue-600 hover:underline text-sky-800  truncate z-10 relative" x-text="contact.mobile"></a>
                                                </li>
                                            </template>
                                            <template x-if='contact.telephone'>
                                                {{-- telephone icon --}}
                                                <li class="flex items-baseline space-x-2 leading-5">
                                                    <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 16 16">
                                                        <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg>
                                                    </span>
                                                    <a :href="'tel:'+contact.telephone" class="hover:text-blue-600 hover:underline text-sky-800 truncate z-10 relative" x-text="contact.telephone"></a>
                                                </li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td class="px-3 py-2 align-baseline w-10 relative">
                                        <div>
                                            <a href="#" :title='contact.name' class="absolute focus:outline-none focus-ring-2-inset-0 rounded hover:bg-opacity-50 hover:bg-cyan-50 focus:bg-cyan-50 mix-blend-darken"></a>
                                            <div x-show="open" x-transition:enter="transtion ease-out duration-300" x-transtion:enter-start="opacity-0" x-transtion:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300" x-transtion:leave-start="opacity-100" x-transtion:leave-end="opacity-0" class="block fixed inset-0 z-40" style="display: none; backdrop-filter: blur(8px);"></div>
                                            <ul x-show="open" class="bottom-0 fixed left-2 p-2 right-2 md:left-1/2 md:top-1/2 md:max-w-lg md:-translate-x-1/2 md:-translate-y-1/2 md:w-full md:right-auto md:bottom-auto bg-cyan-50 rounded-md shadow-lg text-base md:text-sm z-50 border-1 border-slate-800">
                                                <div>
                                                    <div class="relative flex flex-col isolate m-0">
                                                        <div class="p-2 grow flex flex-col space-y-2">
                                                            <div class="relative isolate grow">
                                                                <div class="-mt-6 float-right mb-4 ml-4 ring-2 ring-sky-900 rounded-full shadow-md">
                                                                    <span role="presentation" aria-hidden="true" class="h-20 sm:h-24 text-4xl sm:text-5xl w-20 sm:w-24 flex relative flex-shrink-0">
                                                                        <template x-if="contact.image">
                                                                            <img class="rounded-full" :src='contact.image' :aria='contact.name'></img>
                                                                        </template>
                                                                        <template x-if="!contact.image">
                                                                            <span class="rounded-full uppercase bg-sky-50 border-transparent text-blue-700 font-light bg-cover bg-center flex flex-col items-center justify-center leading-none w-full h-full">
                                                                                <span class="pb-2" x-text='contact.initials'></span>
                                                                            </span>
                                                                        </template>
                                                                    </span>
                                                                </div>
                                                                <div class="grow space-y-2">
                                                                    <h3 class="text-base font-semibold antialiased text-slate-700" x-text="contact.name"></h3>
                                                                    <div class="text-sm grow">
                                                                        <div class="space-y-2">
                                                                            <ul class="flex-col text-sm text-gray-700 space-y-2 mt-2">
                                                                                <template x-if='contact.email'>
                                                                                    <li class="flex items-baseline space-x-2 leading-5">
                                                                                        {{-- mail icon --}}
                                                                                        <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 16 16">
                                                                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg> 
                                                                                        </span>
                                                                                        <a :href="'mailto:' + contact.email" tabindex="-1" @click.stop class="hover:text-blue-600 hover:underline text-sky-800 truncate z-10 relative" x-text="contact.email"></a>
                                                                                    </li>
                                                                                </template>
                                                                                <template x-if='contact.mobile'>
                                                                                    <li class="flex items-baseline space-x-2 leading-5">
                                                                                        {{-- mobile icon --}}
                                                                                        <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                                                                            <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                                                            <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/></svg>
                                                                                        </span>
                                                                                        <a :href="'tel:'+contact.mobile" tabindex="-1" @click.stop class="hover:text-blue-600 hover:underline text-sky-800 truncate z-10 relative" x-text="contact.mobile"></a>
                                                                                    </li>
                                                                                </template>
                                                                                <template x-if='contact.telephone'>
                                                                                    <li class="flex items-baseline space-x-2 leading-5">
                                                                                        {{-- telephone icon --}}
                                                                                        <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 16 16">
                                                                                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg>
                                                                                        </span>
                                                                                        <a :href="'tel:'+contact.telephone" class="hover:text-blue-600 hover:underline text-sky-800 truncate z-10 relative" x-text="contact.telephone"></a>
                                                                                    </li>
                                                                                </template>
                                                                                <template x-if="contact.address">
                                                                                    <li class="flex items-baseline space-x-2 leading-5">
                                                                                        {{-- house icon --}}
                                                                                        <span class="icon w-3 h-3 inline-block fill-current text-gray-500 shrink-0">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                                                                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/></svg>
                                                                                        </span>
                                                                                        <span tabindex="-1" class="text-gray-800 relative" x-text="contact.address.address"></span>
                                                                                        {{-- External Link Icon --}}
                                                                                        <a x-bind:href="contact.address.url" @click.stop target="_blank">
                                                                                            <span class="w-4 h-4 inline-block fill-current text-sky-800 hover:text-blue-400 -mb-1">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                                                                                <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
                                                                                                <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
                                                                                                </svg>
                                                                                            </span>
                                                                                        </a>
                                                                                    </li>
                                                                                </template>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-1 flex flex-col gap-1"></div>
                                                {{-- x icon --}}
                                                <div>
                                                    <div class="text-gray-300 hover:text-red-400 p-8 text-center">
                                                        <span class="icon w-6 h-6 inline-block fill-current">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/></svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                        {{-- three dots icon --}}
                                        <span class ="icon w-4 h-4 inline-block fill-current text-gray-500 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                                            </svg>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </template>
            </div>
                <x-footer/>
        </main>
    </body>
</html>
