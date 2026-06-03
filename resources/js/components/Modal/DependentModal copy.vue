<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch, ref } from 'vue';


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
    sex: '',
    relationship: '',
    date_admitted: '',
    date_discharged: '',
    oral_examination: [] as string[],
    institution_fees: 0,
    mandatory_discount: 0,
    philhealth_benefit: 0,
    diagnosis: '',
    final_diagnosis: '',
    procedure: '',

    representative_type: '',
    representative_other_type: '',
    representative: '',
    member_incapacitated_option: 'yes', 
    member_incapacitated_reason: '',

    // Referral
    is_referred: 0 as number,
    hci_name: null as string | null,
    pan_referring_hci: null as string | null,
    date_of_referral: null as string | null,
    referral_transaction_code: null as string | null,
    institution_name: null as string | null,
    type_institution: null as string | null,

    // Tooth Fields
    first_tooth: null as number | null,
    first_tooth_service: '' as string | null,

    second_tooth: null as number | null,
    second_tooth_service: '' as string | null,

    third_tooth: null as number | null,
    third_tooth_service: '' as string | null,

    fourth_tooth: null as number | null,
    fourth_tooth_service: '' as string | null,
});

const visibleTeeth = ref(1);

const addTooth = () => {
    if (visibleTeeth.value < 4) {
        visibleTeeth.value++;
    }
};

const removeTooth = () => {
    if (visibleTeeth.value > 1) {
        visibleTeeth.value--;

        // Clear removed tooth data
        if (visibleTeeth.value < 4) {
            form.fourth_tooth = null;
            form.fourth_tooth_service = null;
        }
        if (visibleTeeth.value < 3) {
            form.third_tooth = null;
            form.third_tooth_service = null;
        }
        if (visibleTeeth.value < 2) {
            form.second_tooth = null;
            form.second_tooth_service = null;
        }
    }
};

watch(() => props.editingDependent, (dep) => {
    if (!dep) {
        form.reset();
        return;
    }

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

    let incOption = 'yes';
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
    form.sex = dep.sex || '';
    form.relationship = dep.relationship || '';
    form.birthdate = dep.birthdate ? new Date(dep.birthdate).toISOString().split('T')[0] : '';

    const formatDateTimeLocal = (val: string | null) => {
        if (!val) return '';
        const d = new Date(val);
        const pad = (n: number) => n.toString().padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    form.date_admitted = formatDateTimeLocal(dep.date_admitted);
    form.date_discharged = formatDateTimeLocal(dep.date_discharged);
    form.institution_fees = dep.institution_fees || 0;
    form.mandatory_discount = dep.mandatory_discount || 0;
    form.philhealth_benefit = dep.philhealth_benefit || 0;
    form.oral_examination = Array.isArray(dep.oral_examination) ? [...dep.oral_examination] : [];
    form.diagnosis = dep.diagnosis || '';
    form.final_diagnosis = dep.final_diagnosis || '';
    form.procedure = dep.procedure || '';
    form.representative_type = repType;
    form.representative_other_type = repOther;
    form.representative = dep.representative || '';
    form.member_incapacitated_option = incOption;
    form.member_incapacitated_reason = incReason;

    form.is_referred = dep.is_referred ? 1 : 0;
    form.hci_name = dep.hci_name || null;
    form.pan_referring_hci = dep.pan_referring_hci || null;
    form.date_of_referral = dep.date_of_referral ? new Date(dep.date_of_referral).toISOString().split('T')[0] : null;
    form.referral_transaction_code = dep.referral_transaction_code || null;
    form.institution_name = dep.institution_name || null;
    form.type_institution = dep.type_institution || null;

    form.first_tooth = dep.first_tooth;
    form.second_tooth = dep.second_tooth;
    form.third_tooth = dep.third_tooth;
    form.fourth_tooth = dep.fourth_tooth;

}, { immediate: true });

// Clear fields when toggled to "No"
watch(() => form.is_referred, (value) => {
    if (value === 0) {
        form.hci_name = null;
        form.pan_referring_hci = null;
        form.date_of_referral = null;
        form.referral_transaction_code = null;
        form.institution_name = null;
        form.type_institution = null;
    }
});

const handlePhilHealthInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    form.philhealth_number = target.value.replace(/\D/g, '').substring(0, 12);
};

const submit = () => {

if (form.is_referred === 0) {
    form.hci_name = null;
    form.pan_referring_hci = null;
    form.date_of_referral = null;
    form.referral_transaction_code = null;
    form.institution_name = null;
    form.type_institution = null;
}

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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">PhilHealth Number</label>
                        <input :value="form.philhealth_number" @input="handlePhilHealthInput" type="text" maxlength="12"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Date of Birth</label>
                        <input v-model="form.birthdate" type="date"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Relationship</label>
                        <select v-model="form.relationship"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 bg-white" required>
                            <option value="" disabled>Select relationship</option>
                            <option value="spouse">Spouse</option>
                            <option value="child">Child</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Sex</label>
                        <select v-model="form.sex"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
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
                    <h3 class="text-lg font-bold text-emerald-700 border-b pb-2">Admission & Clinical Details</h3>
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
                        <textarea v-model="form.diagnosis" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Final Diagnosis</label>

                        <select
                            v-model="form.final_diagnosis"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                        >
                            <option value="" disabled>Select diagnosis</option>

                            <option value="Dental caries, unspecified">
                                Dental caries, unspecified
                            </option>

                            <option value="Chronic gingivitis, plaque induced">
                                Chronic gingivitis, plaque induced
                            </option>

                            <option value="Aggressive periodontitis, unspecified">
                                Aggressive periodontitis, unspecified
                            </option>

                            <option value="Periapical abscess without sinus">
                                Periapical abscess without sinus
                            </option>

                            <option value="Impacted teeth (impacted third molars)">
                                Impacted teeth (impacted third molars)
                            </option>

                            <option value="Cracked tooth">
                                Cracked tooth
                            </option>

                            <option value="Loss of teeth due to accident or decay">
                                Loss of teeth due to accident or decay
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Procedure</label>
                        <textarea v-model="form.procedure" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-6">
                    <h3 class="text-lg font-bold text-slate-700 border-b pb-2">
                        Tooth Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- 1st Tooth -->
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase">1st Tooth</label>
                            <input v-model.number="form.first_tooth" type="number"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Tooth Number">

                            <input v-model="form.first_tooth_service" type="text"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Dental Service">
                        </div>

                        <!-- 2nd Tooth -->
                        <div v-if="visibleTeeth >= 2" class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase">2nd Tooth</label>
                            <input v-model.number="form.second_tooth" type="number"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500">

                            <input v-model="form.second_tooth_service" type="text"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Dental Service">
                        </div>

                        <!-- 3rd Tooth -->
                        <div v-if="visibleTeeth >= 3" class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase">3rd Tooth</label>
                            <input v-model.number="form.third_tooth" type="number"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500">

                            <input v-model="form.third_tooth_service" type="text"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Dental Service">
                        </div>

                        <!-- 4th Tooth -->
                        <div v-if="visibleTeeth >= 4" class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase">4th Tooth</label>
                            <input v-model.number="form.fourth_tooth" type="number"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500">

                            <input v-model="form.fourth_tooth_service" type="text"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Dental Service">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button type="button"
                            @click="addTooth"
                            v-if="visibleTeeth < 4"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                            + Add Tooth
                        </button>

                        <button type="button"
                            @click="removeTooth"
                            v-if="visibleTeeth > 1"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            − Remove
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Total Charges</label>
                        <input v-model.number="form.institution_fees" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">Mandatory Discount</label>
                        <input v-model.number="form.mandatory_discount" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600">PhilHealth Benefit</label>
                        <input v-model.number="form.philhealth_benefit" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
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

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 space-y-6">
                    <h3 class="text-lg font-bold text-blue-700 border-b pb-2">Referral Information</h3>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" v-model="form.is_referred" :value="1" class="accent-blue-600">
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" v-model="form.is_referred" :value="0" class="accent-blue-600">
                            <span>No</span>
                        </label>
                    </div>

                    <div v-if="form.is_referred === 1" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-blue-700">Name of HCI</label>
                            <input v-model="form.hci_name" type="text" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-blue-700">PAN of Referring HCI</label>
                            <input v-model="form.pan_referring_hci" type="text" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-blue-700">Date of Referral</label>
                            <input v-model="form.date_of_referral" type="date" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-blue-700">Referral Transaction Code</label>
                            <input v-model="form.referral_transaction_code" type="text" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-semibold text-blue-700">Name of Institution</label>
                            <input v-model="form.institution_name" type="text" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-semibold text-blue-700">Type of Institution</label>
                            <input v-model="form.type_institution" type="text" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="button" @click="$emit('close')" class="px-6 py-3 text-slate-500 font-semibold hover:text-slate-700">Cancel</button>
                    <button type="submit" :disabled="form.processing"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-3 rounded-2xl font-bold shadow-lg disabled:opacity-50 transition">
                        {{ editingDependent ? 'Update Dependent' : 'Save Dependent' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>