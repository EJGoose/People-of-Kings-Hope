<div class="flex space-x-4 items-center">
    <span role="presentation" aria-hidden="true" class="h-8 text-base w-8 flex relative flex-shrink-0 ">
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