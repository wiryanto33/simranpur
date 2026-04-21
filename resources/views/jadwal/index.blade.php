<x-app-layout>
    <x-page-header title="Jadwal Pemeliharaan" subtitle="Penjadwalan dan monitoring pemeliharaan kendaraan tempur">
    </x-page-header>

    <div x-data="{ currentTab: 'tabel' }">
        <div class="mb-4 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors" @click="currentTab = 'tabel'" :class="currentTab === 'tabel' ? 'border-[#C8A84B] text-[#1B3A2D]' : 'border-transparent hover:text-gray-600 hover:border-gray-300'" type="button" role="tab">Daftar Jadwal</button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors" @click="currentTab = 'kalender'" :class="currentTab === 'kalender' ? 'border-[#C8A84B] text-[#1B3A2D]' : 'border-transparent hover:text-gray-600 hover:border-gray-300'" type="button" role="tab">Kalender</button>
                </li>
            </ul>
        </div>

        <div x-show="currentTab === 'tabel'">
            @livewire('jadwal-index')
        </div>
        <div x-show="currentTab === 'kalender'" x-cloak>
            @livewire('jadwal-kalender')
        </div>
    </div>
    
    @livewire('jadwal-form')
</x-app-layout>
