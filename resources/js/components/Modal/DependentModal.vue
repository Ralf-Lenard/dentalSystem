<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    show: boolean;
    editingDependent: any | null;
    selectedCsfId: number | null;
    oralChoices: string[];
}>();

const emit = defineEmits(['close']);

const form = useForm({
    philhealth_number: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    name_extension: '',
    birthdate: '', 
    relationship: '',
    date_admitted: '',
    date_discharged: '',
    oral_examination: [] as string[], 
    institution_fees: 0,
    diagnosis: '',
    representative_type: '',
    representative_other_type: '',
    representative: '',
    member_incapacitated_option: '',
    member_incapacitated_reason: '',
});

watch(() => props.editingDependent, (dep) => {
    if (dep) {
        const standardTypes = ['child', 'spouse', 'parent', 'sibling'];
        let repType = '';
        let repOther = '';

        if (dep.representative_type) {
            if (standardTypes.includes(dep.representative_type)) {
                repType = dep.representative_type;
            } else {
                repType = 'others';
                repOther = dep.representative_type;
            }
        }

        let incOption = '';
        let incReason = '';
        if (dep.member_incapacitated) {
            if (dep.member_incapacitated === 'Yes') {
                incOption = 'yes';
            } else {
                incOption = 'other';
                incReason = dep.member_incapacitated;
            }
        }

        form.philhealth_number = dep.philhealth_number || '';
        form.last_name = dep.last_name || '';
        form.first_name = dep.first_name || '';
        form.middle_name = dep.middle_name || '';
        form.name_extension = dep.name_extension || '';
        form.relationship = dep.relationship || '';
        form.birthdate = dep.birthdate || '';
        form.date_admitted = dep.date_admitted || '';
        form.date_discharged = dep.date_discharged || '';
        form.institution_fees = dep.institution_fees || 0;
        form.oral_examination = Array.isArray(dep.oral_examination) ? [...dep.oral_examination] : [];
        form.diagnosis = dep.diagnosis || '';
        form.representative_type = repType;
        form.representative_other_type = repOther;
        form.representative = dep.representative || '';
        form.member_incapacitated_option = incOption;
        form.member_incapacitated_reason = incReason;
    } else {
        form.reset();
    }
}, { immediate: true });

const handlePhilHealthInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    form.philhealth_number = target.value.replace(/\D/g, '').substring(0, 12);
};

const submit = () => {
    if (props.editingDependent) {
        form.put(`/csf/dependents/${props.editingDependent.id}`, { 
            onSuccess: () => emit('close'),
            preserveScroll: true 
        });
    } else if (props.selectedCsfId) {
        form.post(`/csf/${props.selectedCsfId}/dependents`, { 
            onSuccess: () => emit('close'),
            preserveScroll: true 
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-6 z-50">
        <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl max-h-[92vh] flex flex-col overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight">
                        {{ editingDependent ? 'Edit Dependent Record' : 'Register Dependent' }}
                    </h2>
                    <p class="text-sm opacity-90">Attach a family member to principal account</p>
                </div>
                <button @click="$emit('close')" class="p-2 hover:bg-white/20 rounded-full transition">✕</button>
            </div>

            <form @submit.prevent="submit" class="overflow-y-auto p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">PhilHealth Number</label>
                        <input :value="form.philhealth_number" @input="handlePhilHealthInput" type="text" maxlength="12" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Date of Birth</label>
                        <input v-model="form.birthdate" type="date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Relationship</label>
                        <select v-model="form.relationship" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 bg-white" required>
                            <option value="" disabled>Select relationship</option>
                            <option value="spouse">Spouse</option>
                            <option value="child">Child</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-sm font-semibold text-slate-600">Dependent's Name</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <input v-model="form.last_name" placeholder="Last Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                        <input v-model="form.first_name" placeholder="First Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                        <input v-model="form.middle_name" placeholder="Middle Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                        <input v-model="form.name_extension" placeholder="Extension" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Representative Relationship</label>
                        <select v-model="form.representative_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="">Select Representative Relationship</option>
                            <option value="child">Child</option>
                            <option value="spouse">Spouse</option>
                            <option value="parent">Parent</option>
                            <option value="sibling">Sibling</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div v-if="form.representative_type === 'others'" class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Specify Relationship</label>
                        <input v-model="form.representative_other_type" type="text" placeholder="e.g. Friend" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Representative Name</label>
                        <input v-model="form.representative" type="text" placeholder="Full name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" />
                    </div>
                </div>

                <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-rose-700 border-b pb-2">Member is incapacitated</h3>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" v-model="form.member_incapacitated_option" value="yes" class="accent-rose-600">
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" v-model="form.member_incapacitated_option" value="other" class="accent-rose-600">
                            <span>Other Reason</span>
                        </label>
                    </div>
                    <div v-if="form.member_incapacitated_option === 'other'" class="mt-2">
                        <input type="text" v-model="form.member_incapacitated_reason" placeholder="Specify reason" class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:ring-2 focus:ring-rose-500" />
                    </div>
                </div>

                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 space-y-6">
                    <h3 class="text-lg font-bold text-emerald-700 border-b pb-2">Admission Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-emerald-700">Date & Time Admitted</label>
                            <input v-model="form.date_admitted" type="datetime-local" class="w-full px-4 py-3 rounded-xl border border-emerald-200 focus:ring-2 focus:ring-emerald-500" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-rose-700">Date & Time Discharged</label>
                            <input v-model="form.date_discharged" type="datetime-local" class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:ring-2 focus:ring-rose-500" required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Diagnosis</label>
                        <textarea v-model="form.diagnosis" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Institution Fees (PHP)</label>
                        <input v-model.number="form.institution_fees" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Oral Examination</label>
                        <div class="p-4 border border-slate-200 rounded-xl bg-white max-h-40 overflow-y-auto space-y-2">
                            <div v-for="choice in oralChoices" :key="choice" class="flex items-center gap-3">
                                <input type="checkbox" :value="choice" v-model="form.oral_examination" class="w-5 h-5 accent-emerald-600">
                                <span class="text-sm text-slate-700">{{ choice }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="button" @click="$emit('close')" class="px-6 py-3 text-slate-500 font-semibold hover:text-slate-700">Cancel</button>
                    <button type="submit" :disabled="form.processing" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-3 rounded-2xl font-bold shadow-lg disabled:opacity-50 transition">
                        {{ editingDependent ? 'Update Dependent' : 'Save Dependent' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>