<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    show: boolean;
    editingMember: any | null;
    oralChoices: string[];
}>();

const emit = defineEmits(['close']);

// --- Helpers for Date Formatting ---
const formatToDate = (dateStr: string | null) => {
    if (!dateStr) return '';
    return new Date(dateStr).toISOString().split('T')[0]; // Returns YYYY-MM-DD
};

const formatToDateTime = (dateStr: string | null) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const pad = (n: number) => n.toString().padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
};

const form = useForm({
    philhealth_number: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    name_extension: '',
    birthdate: '',
    date_admitted: '',
    date_discharged: '',
    oral_examination: [] as string[], 
    institution_fees: 0,
    diagnosis: '',
});

watch(() => props.editingMember, (member) => {
    if (member) {
        form.philhealth_number = member.philhealth_number || '';
        form.last_name = member.last_name || '';
        form.first_name = member.first_name || '';
        form.middle_name = member.middle_name || '';
        form.name_extension = member.name_extension || '';
        
        // Use formatting helpers here
        form.birthdate = formatToDate(member.birthdate);
        form.date_admitted = formatToDateTime(member.date_admitted);
        form.date_discharged = formatToDateTime(member.date_discharged);
        
        form.institution_fees = member.institution_fees || 0;
        form.oral_examination = Array.isArray(member.oral_examination) ? [...member.oral_examination] : [];
        form.diagnosis = member.diagnosis || '';
    } else {
        form.reset();
    }
}, { immediate: true });

const handlePhilHealthInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    form.philhealth_number = target.value.replace(/\D/g, '').substring(0, 12);
};

const submit = () => {
    if (props.editingMember) {
        // Ensure we target the correct ID for the PUT request
        form.put(`/csf/${props.editingMember.id}`, { 
            onSuccess: () => {
                form.reset();
                emit('close');
            },
            onError: (errors) => console.log(errors), // Check console if it fails
            preserveScroll: true,
        });
    } else {
        form.post('/csf', { 
            onSuccess: () => {
                form.reset();
                emit('close');
            },
            onError: (errors) => console.log(errors),
            preserveScroll: true 
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-6 z-50">
        <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl max-h-[92vh] flex flex-col overflow-hidden">
            <div class="px-8 py-6 border-b bg-gradient-to-r from-blue-600 to-blue-500 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight">
                        {{ editingMember ? 'Edit Member Record' : 'New Patient Registration' }}
                    </h2>
                    <p class="text-sm opacity-90">Complete patient admission details</p>
                </div>
                <button @click="$emit('close')" class="p-2 hover:bg-white/20 rounded-full transition">✕</button>
            </div>

            <form @submit.prevent="submit" class="overflow-y-auto p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">PhilHealth Number</label>
                        <input :value="form.philhealth_number" @input="handlePhilHealthInput" type="text" maxlength="12" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Date of Birth</label>
                        <input v-model="form.birthdate" type="date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-sm font-semibold text-slate-600">Full Legal Name</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <input v-model="form.last_name" placeholder="Last Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                        <input v-model="form.first_name" placeholder="First Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                        <input v-model="form.middle_name" placeholder="Middle Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                        <input v-model="form.name_extension" placeholder="Extension" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-6">
                    <h3 class="text-lg font-bold text-slate-700 border-b pb-2">Admission Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-blue-700">Date & Time Admitted</label>
                            <input v-model="form.date_admitted" type="datetime-local" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-rose-700">Date & Time Discharged</label>
                            <input v-model="form.date_discharged" type="datetime-local" class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:ring-2 focus:ring-rose-500">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Diagnosis</label>
                        <textarea v-model="form.diagnosis" rows="3" placeholder="Enter patient diagnosis..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Institution Fees (PHP)</label>
                        <input v-model.number="form.institution_fees" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Oral Examination</label>
                        <div class="p-4 border border-slate-200 rounded-xl bg-white max-h-40 overflow-y-auto space-y-2">
                            <div v-for="choice in oralChoices" :key="choice" class="flex items-center gap-3">
                                <input type="checkbox" :value="choice" v-model="form.oral_examination" class="w-5 h-5 accent-blue-600">
                                <span class="text-sm text-slate-700">{{ choice }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="button" @click="$emit('close')" class="px-6 py-3 text-slate-500 font-semibold hover:text-slate-700">Cancel</button>
                    <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-2xl font-bold shadow-lg disabled:opacity-50 transition">
                        {{ editingMember ? 'Save Changes' : 'Confirm Registration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>