<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch, ref } from 'vue';

const props = defineProps<{
    show: boolean;
    editingMember: any | null;
    oralChoices: string[];
}>();

const emit = defineEmits(['close']);

const formatToDate = (dateStr: string | null) => {
    if (!dateStr) return '';
    return new Date(dateStr).toISOString().split('T')[0];
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
    sex: '',
    birthdate: '',
    date_admitted: '',
    date_discharged: '',
    
    // Tooth Info
    first_tooth: null as number | null,
    first_tooth_service: '',
    second_tooth: null as number | null,
    second_tooth_service: '',
    third_tooth: null as number | null,
    third_tooth_service: '',
    fourth_tooth: null as number | null,
    fourth_tooth_service: '',

    // Fees
    institution_fees: 0,
    mandatory_discount: 0,
    philhealth_benefit: 0,
    
    // Diagnosis & Procedure
    diagnosis: '',
    final_diagnosis: '',
    procedure: '',

    // Referral
    is_referred: 0,
    hci_name: '',
    pan_referring_hci: '',
    date_of_referral: '',
    referral_transaction_code: '',
    institution_name: '',
    type_institution: '',

    // Address & Contact
    unit_no: '',
    building_name: '',
    house_no: '',
    street: '',
    village: '',
    brgy: '',
    municipality: '',
    province: '',
    country: 'Philippines',
    zip_code: '',
    landline_no: '',
    mobile_no: '',
    email_address: '',
});

const visibleTeeth = ref(1);

const addTooth = () => { if (visibleTeeth.value < 4) visibleTeeth.value++; };
const removeTooth = () => {
    if (visibleTeeth.value > 1) {
        if (visibleTeeth.value === 4) { form.fourth_tooth = null; form.fourth_tooth_service = ''; }
        if (visibleTeeth.value === 3) { form.third_tooth = null; form.third_tooth_service = ''; }
        if (visibleTeeth.value === 2) { form.second_tooth = null; form.second_tooth_service = ''; }
        visibleTeeth.value--;
    }
};

watch(() => props.editingMember, (member) => {
    if (!member) {
        form.reset();
        visibleTeeth.value = 1;
        return;
    }

    form.philhealth_number = member.philhealth_number || '';
    form.last_name = member.last_name || '';
    form.first_name = member.first_name || '';
    form.middle_name = member.middle_name || '';
    form.name_extension = member.name_extension || '';
    form.sex = member.sex || '';
    form.birthdate = member.birthdate ? formatToDate(member.birthdate) : '';
    form.date_admitted = member.date_admitted ? formatToDateTime(member.date_admitted) : '';
    form.date_discharged = member.date_discharged ? formatToDateTime(member.date_discharged) : '';
    
    form.first_tooth = member.first_tooth;
    form.first_tooth_service = member.first_tooth_service || '';
    form.second_tooth = member.second_tooth;
    form.second_tooth_service = member.second_tooth_service || '';
    form.third_tooth = member.third_tooth;
    form.third_tooth_service = member.third_tooth_service || '';
    form.fourth_tooth = member.fourth_tooth;
    form.fourth_tooth_service = member.fourth_tooth_service || '';

    if (member.fourth_tooth) visibleTeeth.value = 4;
    else if (member.third_tooth) visibleTeeth.value = 3;
    else if (member.second_tooth) visibleTeeth.value = 2;
    else visibleTeeth.value = 1;

    form.institution_fees = member.institution_fees || 0;
    form.mandatory_discount = member.mandatory_discount || 0;
    form.philhealth_benefit = member.philhealth_benefit || 0;
    form.diagnosis = member.diagnosis || '';
    form.final_diagnosis = member.final_diagnosis || '';
    form.procedure = member.procedure || '';

    form.is_referred = member.is_referred ? 1 : 0;
    form.hci_name = member.hci_name || '';
    form.pan_referring_hci = member.pan_referring_hci || '';
    form.date_of_referral = member.date_of_referral ? formatToDate(member.date_of_referral) : '';
    form.referral_transaction_code = member.referral_transaction_code || '';
    form.institution_name = member.institution_name || '';
    form.type_institution = member.type_institution || '';

    form.unit_no = member.unit_no || '';
    form.building_name = member.building_name || '';
    form.house_no = member.house_no || '';
    form.street = member.street || '';
    form.village = member.village || '';
    form.brgy = member.brgy || '';
    form.municipality = member.municipality || '';
    form.province = member.province || '';
    form.zip_code = member.zip_code || '';
    form.mobile_no = member.mobile_no || '';
    form.landline_no = member.landline_no || '';
    form.email_address = member.email_address || '';
}, { immediate: true });

const submit = () => {
    const action = props.editingMember ? 'put' : 'post';
    const url = props.editingMember ? `/csf/${props.editingMember.id}` : '/csf';

    form[action](url, {
        onSuccess: () => { emit('close'); form.reset(); },
        preserveScroll: true,
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl max-w-5xl w-full shadow-2xl max-h-[95vh] flex flex-col overflow-hidden">
            <div class="px-8 py-5 border-b bg-gradient-to-r from-blue-700 to-indigo-600 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ editingMember ? 'Edit Member Record' : 'New Patient Registration' }}</h2>
                    <p class="text-xs opacity-80 uppercase tracking-wider">Comprehensive CSF Form</p>
                </div>
                <button @click="$emit('close')" class="hover:bg-white/20 p-2 rounded-full transition">✕</button>
            </div>

            <form @submit.prevent="submit" class="overflow-y-auto p-8 space-y-10">
                <section class="space-y-6">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-b pb-2">1. Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-600 mb-1">PhilHealth Number</label>
                            <input v-model="form.philhealth_number" type="text"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 transition"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-1">Sex</label>
                            <select v-model="form.sex"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-1">Date of Birth</label>
                            <input v-model="form.birthdate" type="date"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input v-model="form.last_name" placeholder="Last Name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" required>
                        <input v-model="form.first_name" placeholder="First Name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" required>
                        <input v-model="form.middle_name" placeholder="Middle Name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.name_extension" placeholder="Ext (Jr/Sr)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </section>

                <section class="space-y-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-b pb-2">2. Contact & Address</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input v-model="form.email_address" type="email" placeholder="Email Address" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.mobile_no" placeholder="Mobile Number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.landline_no" placeholder="Landline" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <input v-model="form.unit_no" placeholder="Unit No" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.building_name" placeholder="Building Name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.house_no" placeholder="House No" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.street" placeholder="Street" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.village" placeholder="Village / Subdivision" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.brgy" placeholder="Barangay" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.municipality" placeholder="Municipality" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.province" placeholder="Province" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.zip_code" placeholder="Zip Code" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <input v-model="form.country" placeholder="Country" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </section>

                <section class="space-y-6">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-b pb-2">3. Admission & Clinical</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase">Admission Date/Time</label>
                            <input v-model="form.date_admitted" type="datetime-local" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase">Discharge Date/Time</label>
                            <input v-model="form.date_discharged" type="datetime-local" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Initial Diagnosis / Clinical Notes</label>
                            <textarea v-model="form.diagnosis" rows="2" placeholder="Enter notes..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-600">Final Diagnosis</label>
                                <select v-model="form.final_diagnosis" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled>Select diagnosis</option>
                                    <option value="Dental caries, unspecified">Dental caries, unspecified</option>
                                    <option value="Chronic gingivitis, plaque induced">Chronic gingivitis, plaque induced</option>
                                    <option value="Aggressive periodontitis, unspecified">Aggressive periodontitis, unspecified</option>
                                    <option value="Periapical abscess without sinus">Periapical abscess without sinus</option>
                                    <option value="Impacted teeth (impacted third molars)">Impacted teeth (impacted third molars)</option>
                                    <option value="Cracked tooth">Cracked tooth</option>
                                    <option value="Loss of teeth due to accident or decay">Loss of teeth due to accident or decay</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-600">Procedure</label>
                                <select v-model="form.procedure" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled>Select procedure</option>
                                    <option value="1st visit mandatory services only">1st visit mandatory services only</option>
                                    <option value="1st visit with mandatory services">1st visit with mandatory services</option>
                                    <option value="2nd visit with mandatory services">2nd visit with mandatory services</option>
                                    <option value="2nd visit mandatory services only">2nd visit mandatory services only</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-6">
                        <h3 class="text-lg font-bold text-slate-700 border-b pb-2">Tooth Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase">1st Tooth</label>
                                <input v-model.number="form.first_tooth" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Tooth No.">
                                <input v-model="form.first_tooth_service" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Service">
                            </div>
                            <div v-if="visibleTeeth >= 2" class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase">2nd Tooth</label>
                                <input v-model.number="form.second_tooth" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Tooth No.">
                                <input v-model="form.second_tooth_service" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Service">
                            </div>
                            <div v-if="visibleTeeth >= 3" class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase">3rd Tooth</label>
                                <input v-model.number="form.third_tooth" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Tooth No.">
                                <input v-model="form.third_tooth_service" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Service">
                            </div>
                            <div v-if="visibleTeeth >= 4" class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase">4th Tooth</label>
                                <input v-model.number="form.fourth_tooth" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Tooth No.">
                                <input v-model="form.fourth_tooth_service" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200" placeholder="Service">
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="addTooth" v-if="visibleTeeth < 4" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">+ Add Tooth</button>
                            <button type="button" @click="removeTooth" v-if="visibleTeeth > 1" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">− Remove</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Total Charges</label>
                            <input v-model.number="form.institution_fees" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Mandatory Discount</label>
                            <input v-model.number="form.mandatory_discount" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">PhilHealth Benefit</label>
                            <input v-model.number="form.philhealth_benefit" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                </section>

                <section class="space-y-6 bg-blue-50 p-6 rounded-2xl border border-blue-100">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-b pb-2">4. Referral Information</h3>
                    <div class="flex items-center gap-8">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" v-model="form.is_referred" :value="1" class="accent-blue-600">
                            <span class="text-sm font-semibold text-slate-600">Yes</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" v-model="form.is_referred" :value="0" class="accent-blue-600">
                            <span class="text-sm font-semibold text-slate-600">No</span>
                        </label>
                    </div>

                    <div v-if="form.is_referred == 1" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                        <input v-model="form.hci_name" placeholder="Name of HCI" class="w-full px-4 py-2.5 rounded-xl border border-blue-200">
                        <input v-model="form.pan_referring_hci" placeholder="PAN of Referring HCI" class="w-full px-4 py-2.5 rounded-xl border border-blue-200">
                        <input v-model="form.date_of_referral" type="date" class="w-full px-4 py-2.5 rounded-xl border border-blue-200">
                        <input v-model="form.referral_transaction_code" placeholder="Referral Transaction Code" class="w-full px-4 py-2.5 rounded-xl border border-blue-200">
                        <input v-model="form.institution_name" placeholder="Name of Institution" class="w-full px-4 py-2.5 rounded-xl border border-blue-200 md:col-span-2">
                        <input v-model="form.type_institution" placeholder="Type of Institution" class="w-full px-4 py-2.5 rounded-xl border border-blue-200 md:col-span-2">
                    </div>
                </section>

                <div class="flex justify-end gap-4 pt-8 border-t">
                    <button type="button" @click="$emit('close')" class="px-6 py-3 text-slate-500 font-semibold hover:bg-slate-50 rounded-xl transition">Cancel</button>
                    <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-3 rounded-xl font-bold shadow-lg disabled:opacity-50 transition">
                        {{ editingMember ? 'Update Record' : 'Create Record' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>