<div class="bg-white p-6 rounded-lg shadow-sm"
    x-data="{
        events: {{ $eventsJson }},
        init() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: this.events,
                eventClick: function(info) {
                    let e = info.event.extendedProps;
                    let content = `
                        <b>Kendaraan:</b> ${e.kendaraan} <br>
                        <b>Mekanik:</b> ${e.mekanik} <br>
                        <b>Status:</b> ${e.status} <br>
                        <b>Tipe:</b> ${e.jenis} <br>
                        <b>Estimasi:</b> ${e.estimasi} Hari <br>
                        <b>Checklist Item:</b> ${e.checklistCount}
                    `;
                    
                    Swal.fire({
                        title: info.event.title,
                        html: content,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Tutup',
                        cancelButtonText: 'Edit Jadwal',
                        confirmButtonColor: '#2D5A45'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            // Call livewire edit
                            @this.dispatch('editJadwal', { id: info.event.id });
                        }
                    });
                }
            });
            calendar.render();
            
            // Re-render when switching tabs to fix sizing 
            this.$watch('currentTab', (val) => {
                if (val === 'kalender') {
                    setTimeout(() => { calendar.updateSize(); }, 100);
                }
            });

            // Re-fetch events if livewire updates
            Livewire.on('jadwalSaved', () => {
                @this.$refresh;
                setTimeout(() => { location.reload(); }, 1500); // quick hack to refresh fullcalendar
            });
        }
    }"
>
    <!-- Include FullCalendar & SweetAlert CDN -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <div class="mb-4 flex space-x-4 text-xs font-medium text-gray-600 border-b pb-4">
        <span>Legenda:</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#10B981] mr-1"></span> Harian</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#3B82F6] mr-1"></span> Mingguan</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#8B5CF6] mr-1"></span> Bulanan</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#F59E0B] mr-1"></span> Triwulan</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#EF4444] mr-1"></span> Tahunan</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-[#6B7280] mr-1"></span> Insidentil</span>
    </div>

    <div wire:ignore>
        <div id="calendar" class="h-[700px]"></div>
    </div>
</div>
