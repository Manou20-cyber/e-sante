<x-patient-layout title="Prendre rendez-vous">

@php
    $joursMap = [1 => 'Lun', 2 => 'Mar', 3 => 'Mer', 4 => 'Jeu', 5 => 'Ven', 6 => 'Sam', 7 => 'Dim'];
    $creneauxData = $creneaux->mapWithKeys(fn ($group, $jour) => [$jour => $group->map(fn ($c) => [
        'id' => $c->id,
        'heure_debut' => $c->heure_debut,
        'heure_fin' => $c->heure_fin,
        'duree' => $c->duree_consultation,
        'prix' => $c->prix,
    ])]);
@endphp

<div x-data="bookingCalendar({{ Js::from($creneauxData) }})">
    <div class="mb-4">
        <a href="{{ route('patient.cabinets.show', $cabinet) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux opticiens
        </a>
    </div>

    {{-- Profil opticien --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-teal-400 flex items-center justify-center font-bold text-white text-3xl shrink-0">
            {{ strtoupper(substr($opticien->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $opticien->name }}</h2>
            <p class="text-sm text-gray-500">{{ $cabinet->nom }} — {{ $cabinet->ville }}</p>
        </div>
    </div>

    @if($creneaux->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
            <p class="text-gray-500 font-medium">Aucune disponibilité configurée.</p>
            <p class="text-sm text-gray-400 mt-1">Revenez plus tard ou choisissez un autre opticien.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Colonne gauche : Calendrier --}}
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Choisissez une date</h3>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                    {{-- En-tête mois --}}
                    <div class="flex items-center justify-between mb-4">
                        <button type="button" @click="prevMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <h4 class="font-semibold text-gray-800" x-text="monthYear"></h4>
                        <button type="button" @click="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Jours de la semaine --}}
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <template x-for="d in ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']" :key="d">
                            <div class="text-center text-xs font-medium text-gray-500 py-1" x-text="d"></div>
                        </template>
                    </div>

                    {{-- Calendrier --}}
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="day in daysInMonth" :key="day.date">
                            <button type="button"
                                    @click="day.hasCreneau ? selectDay(day.date, day.jourSemaine) : null"
                                    :disabled="!day.hasCreneau"
                                    :class="{
                                        'opacity-30 cursor-not-allowed': !day.hasCreneau,
                                        'bg-blue-600 text-white': selectedDate === day.date,
                                        'hover:bg-gray-100': day.hasCreneau && selectedDate !== day.date,
                                        'bg-gray-50': !day.currentMonth
                                    }"
                                    :title="day.hasCreneau ? 'Disponible' : ''"
                                    class="aspect-square rounded-lg text-sm font-medium transition flex items-center justify-center">
                                <span x-text="day.day"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Colonne droite : Créneaux + Formulaire --}}
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Réserver</h3>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <template x-if="selectedDate">
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg text-sm text-blue-700">
                            <p class="font-medium" x-text="formattedSelectedDate"></p>
                            <p class="text-xs mt-0.5" x-show="selectedCreneauId !== null" x-text="selectedCreneauPrix"></p>
                        </div>
                    </template>

                    {{-- Créneaux du jour sélectionné --}}
                    <div x-show="selectedDate && dayCreneaux.length > 0 && !selectedCreneauId" class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Créneaux disponibles</p>
                        <div class="space-y-2">
                            <template x-for="creneau in dayCreneaux" :key="creneau.id">
                                <button type="button"
                                        @click="selectCreneau(creneau.id, creneau.jourSemaine)"
                                        class="w-full text-left px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center justify-between transition">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="creneau.heure_debut + ' – ' + creneau.heure_fin"></p>
                                        <p class="text-xs text-gray-400" x-text="creneau.duree + ' min'"></p>
                                    </div>
                                    <p class="text-xs font-bold text-teal-700" x-text="creneau.prix ? creneau.prix.toLocaleString() + ' XAF' : 'Gratuit'"></p>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Formulaire complet --}}
                    <div x-show="selectedCreneauId !== null">
                        <form method="POST"
                              action="{{ route('patient.cabinets.book', [$cabinet, $opticien]) }}">
                            @csrf
                            <input type="hidden" name="creneau_id" :value="selectedCreneauId">
                            <input type="hidden" name="date" :value="selectedDate">

                            <div class="space-y-4">
                                <div x-show="availableHours.length > 0">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Créneaux horaires *
                                        </label>
                                        <span x-show="selectedHours.length > 0"
                                              class="text-xs text-blue-600 font-medium"
                                              x-text="selectedHours.length + ' sélectionné(s)'"></span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <template x-for="slot in availableHours" :key="slot.value">
                                            <label class="cursor-pointer">
                                                <input type="checkbox" name="heures[]" :value="slot.value"
                                                       x-model="selectedHours"
                                                       class="sr-only peer">
                                                <span class="block text-center py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 text-gray-600
                                                            peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600
                                                            hover:border-blue-400 hover:text-blue-600 transition"
                                                      x-text="slot.label"></span>
                                            </label>
                                        </template>
                                    </div>
                                    <x-input-error :messages="$errors->get('heures')" class="mt-1"/>
                                </div>

                                <div x-show="selectedDate && availableHours.length === 0">
                                    <p class="text-sm text-amber-600 bg-amber-50 p-3 rounded-lg">
                                        Aucun créneau disponible pour cette date. Choisissez un autre jour.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Type de consultation *
                                    </label>
                                    <select name="type" required
                                            class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm">
                                        <option value="bilan_visuel">Bilan visuel</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="controle">Contrôle</option>
                                        <option value="adaptation_lentilles">Adaptation lentilles</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Motif (optionnel)</label>
                                    <textarea name="motif" rows="2"
                                              class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                                              placeholder="Décrivez votre motif de consultation..."></textarea>
                                </div>
                            </div>

                            <button type="submit"
                                    :disabled="!selectedDate || selectedHours.length === 0"
                                    :class="{ 'opacity-50 cursor-not-allowed': !selectedDate || selectedHours.length === 0 }"
                                    class="mt-5 w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm">
                                Confirmer la demande de rendez-vous
                            </button>
                            <p class="text-xs text-center text-gray-400 mt-2">
                                Le cabinet confirmera votre rendez-vous dans les plus brefs délais.
                            </p>
                        </form>
                    </div>

                    {{-- Placeholder --}}
                    <div x-show="selectedCreneauId === null && !selectedDate" class="text-center text-gray-400 py-12">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Sélectionnez une date dans le calendrier<br>pour voir les créneaux et réserver.</p>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>

<script>
function bookingCalendar(creneauxData) {
    return {
        creneauxData: creneauxData,
        selectedCreneauId: null,
        selectedJour: null,
        selectedDate: '',
        selectedHours: [],
        currentDate: new Date(),

        get monthYear() {
            return this.currentDate.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
        },

        get daysInMonth() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startPadding = firstDay.getDay() || 7;

            const days = [];
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Jours du mois précédent
            for (let i = startPadding - 1; i > 0; i--) {
                const d = new Date(year, month, -i);
                days.push({ day: d.getDate(), date: d.toISOString().split('T')[0], currentMonth: false, hasCreneau: false });
            }

            // Jours du mois en cours
            for (let d = 1; d <= lastDay.getDate(); d++) {
                const date = new Date(year, month, d);
                const dateStr = date.toISOString().split('T')[0];
                const dayOfWeek = date.getDay() || 7;
                const hasCreneau = this.creneauxData[dayOfWeek] && this.creneauxData[dayOfWeek].length > 0 && date >= today;
                days.push({ day: d, date: dateStr, currentMonth: true, hasCreneau, jourSemaine: dayOfWeek });
            }

            // Jours du mois suivant
            const remaining = 42 - days.length;
            for (let d = 1; d <= remaining; d++) {
                const date = new Date(year, month + 1, d);
                days.push({ day: d, date: date.toISOString().split('T')[0], currentMonth: false, hasCreneau: false });
            }

            return days;
        },

        get dayCreneaux() {
            if (!this.selectedDate) return [];
            const date = new Date(this.selectedDate);
            const dayOfWeek = date.getDay() || 7;
            return this.creneauxData[dayOfWeek] || [];
        },

        get formattedSelectedDate() {
            if (!this.selectedDate) return '';
            const d = new Date(this.selectedDate);
            return d.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
        },

        get availableHours() {
            const creneau = Object.values(this.creneauxData).flat().find(c => c.id === this.selectedCreneauId);
            if (!creneau) return [];

            const slots = [];
            const [startH, startM] = creneau.heure_debut.split(':').map(Number);
            const [endH, endM] = creneau.heure_fin.split(':').map(Number);
            const duree = creneau.duree;

            let currentMins = startH * 60 + startM;
            const endMins = endH * 60 + endM;

            const fmt = (mins) => {
                const h = Math.floor(mins / 60);
                const m = mins % 60;
                return `${h.toString().padStart(2, '0')}h${m === 0 ? '' : m.toString().padStart(2, '0')}`;
            };

            while (currentMins + duree <= endMins) {
                const slotEnd = currentMins + duree;
                slots.push({
                    value: `${Math.floor(currentMins / 60).toString().padStart(2, '0')}:${(currentMins % 60).toString().padStart(2, '0')}`,
                    label: `${fmt(currentMins)} – ${fmt(slotEnd)}`,
                });
                currentMins += duree;
            }

            return slots;
        },

        get selectedCreneauPrix() {
            const creneau = Object.values(this.creneauxData).flat().find(c => c.id === this.selectedCreneauId);
            if (!creneau) return '';
            return creneau.prix ? creneau.prix.toLocaleString() + ' XAF' : 'Gratuit';
        },

        prevMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.currentDate = new Date(this.currentDate);
        },

        nextMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.currentDate = new Date(this.currentDate);
        },

        selectDay(date, jourSemaine) {
            this.selectedDate = date;
            this.selectedCreneauId = null;
            this.selectedHours = [];
        },

        selectCreneau(id, jour) {
            this.selectedCreneauId = id;
            this.selectedJour = jour;
            this.selectedHours = [];
        }
    };
}
</script>

</x-patient-layout>