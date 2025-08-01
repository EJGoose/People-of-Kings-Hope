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
                            <x-contact-thumbnail />
                        </td>
                        <td class="px-3 py-2 align-baseline md:table-cell hidden">
                            <x-contact-details />
                        </td>
                        <td class="px-3 py-2 align-baseline w-10 relative">
                            <x-expanded-card />
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