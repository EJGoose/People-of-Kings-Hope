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