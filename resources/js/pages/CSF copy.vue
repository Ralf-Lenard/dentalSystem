<script setup>
import { ref, watch } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import FlashMessage from '@/components/FlashMessage.vue'; 

const props = defineProps({
    csf: Object,
    filters: Object
});

// --- Helper Functions ---
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });
};

const formatForInput = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

const formatForDateTimeInput = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
};

// --- State Management ---
const search = ref(props.filters.search || '');
const showMemberModal = ref(false);
const showDependentModal = ref(false);
const editingMember = ref(null);
const editingDependent = ref(null);
const selectedCsfId = ref(null);

// --- Form Definitions ---
const memberForm = useForm({
    philhealth_number: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    name_extension: '',
    birthdate: '',
    date_admitted: '',
    date_discharged: '',
    oral_examination: [], 
    institution_fees: 0,
    diagnosis: '',
});

const dependentForm = useForm({
    philhealth_number: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    name_extension: '',
    birthdate: '', 
    relationship: '',
    date_admitted: '',
    date_discharged: '',
    oral_examination: [], 
    institution_fees: 0,
    diagnosis: '',

    // ✅ Representative
    representative_type: '',
    representative_other_type: '',
    representative: '',

    // ✅ Incapacitation
    member_incapacitated_option: '',   // 'yes' or 'other'
    member_incapacitated_reason: '',   // reason if 'other'
});


// --- Logic ---
watch(search, debounce((value) => {
    router.get('/csf', { search: value }, { 
        preserveState: true, 
        replace: true 
    });
}, 300));

const handlePhilHealthInput = (e, formType) => {
    const value = e.target.value.replace(/\D/g, '').substring(0, 12);
    if (formType === 'member') memberForm.philhealth_number = value;
    else dependentForm.philhealth_number = value;
};

const openMemberModal = (member = null) => {
    editingMember.value = member;
    if (member) {
        // Set defaults then immediately reset to load data into fields
        memberForm.defaults({ 
            philhealth_number: member.philhealth_number || '',
            last_name: member.last_name || '',
            first_name: member.first_name || '',
            middle_name: member.middle_name || '',
            name_extension: member.name_extension || '',
            birthdate: formatForInput(member.birthdate),
            date_admitted: formatForDateTimeInput(member.date_admitted),
            date_discharged: formatForDateTimeInput(member.date_discharged),
            institution_fees: member.institution_fees || 0,
            oral_examination: Array.isArray(member.oral_examination) ? member.oral_examination : [],
            diagnosis: member.diagnosis || '',
        });
    } else {
        memberForm.defaults({ 
            philhealth_number: '', last_name: '', first_name: '', middle_name: '', 
            name_extension: '', birthdate: '', date_admitted: '', 
            date_discharged: '', oral_examination: [], institution_fees: 0, diagnosis: ''
        });
    }
    memberForm.reset();
    showMemberModal.value = true;
};

const openDependentModal = (csfId, dependent = null) => {
    selectedCsfId.value = csfId;
    editingDependent.value = dependent;

    if (dependent) {
        // ✅ Determine representative type for the form
        const standardTypes = ['child', 'spouse', 'parent', 'sibling'];
        let representativeType = '';
        let representativeOtherType = '';

        if (dependent.representative_type) {
            if (standardTypes.includes(dependent.representative_type)) {
                // Standard type (child, spouse, parent, sibling)
                representativeType = dependent.representative_type;
                representativeOtherType = '';
            } else {
                // Custom type → show "others" and populate input
                representativeType = 'others';
                representativeOtherType = dependent.representative_type;
            }
        }

        // ✅ Determine member incapacitated option and reason
        let memberIncapacitatedOption = '';
        let memberIncapacitatedReason = '';

        if (dependent.member_incapacitated) {
            if (dependent.member_incapacitated === 'Yes') {
                memberIncapacitatedOption = 'yes';
                memberIncapacitatedReason = '';
            } else {
                memberIncapacitatedOption = 'other';
                memberIncapacitatedReason = dependent.member_incapacitated;
            }
        }

        // ✅ Populate form defaults for editing
        dependentForm.defaults({ 
            philhealth_number: dependent.philhealth_number || '',
            last_name: dependent.last_name || '',
            first_name: dependent.first_name || '',
            middle_name: dependent.middle_name || '',
            name_extension: dependent.name_extension || '',
            relationship: dependent.relationship || '',
            birthdate: formatForInput(dependent.birthdate),
            date_admitted: formatForDateTimeInput(dependent.date_admitted),
            date_discharged: formatForDateTimeInput(dependent.date_discharged),
            institution_fees: dependent.institution_fees || 0,
            oral_examination: Array.isArray(dependent.oral_examination) ? dependent.oral_examination : [],
            diagnosis: dependent.diagnosis || '',

            // ✅ Representative fields
            representative_type: representativeType,
            representative_other_type: representativeOtherType,
            representative: dependent.representative || '',

            // ✅ Incapacitated fields
            member_incapacitated_option: memberIncapacitatedOption,
            member_incapacitated_reason: memberIncapacitatedReason,
        });
    } else {
        // ✅ Defaults for new dependent
        dependentForm.defaults({ 
            philhealth_number: '',
            last_name: '',
            first_name: '',
            middle_name: '',
            name_extension: '',
            relationship: '',
            birthdate: '',
            date_admitted: '',
            date_discharged: '',
            institution_fees: 0,
            oral_examination: [],
            diagnosis: '',

            // ✅ Representative fields
            representative_type: '',
            representative_other_type: '',
            representative: '',

            // ✅ Incapacitated fields
            member_incapacitated_option: '',
            member_incapacitated_reason: '',
        });
    }

    // ✅ Reset the form to apply defaults
    dependentForm.reset();
    showDependentModal.value = true;
};


const submitMember = () => {
    if (editingMember.value) {
        // Use PUT for updating existing records
        memberForm.put(`/csf/${editingMember.value.id}`, { 
            onSuccess: () => closeModal(),
            preserveScroll: true,
            onError: (errors) => console.log(errors)
        });
    } else {
        memberForm.post('/csf', { 
            onSuccess: () => closeModal(),
            preserveScroll: true 
        });
    }
};

const submitDependent = () => {
    if (editingDependent.value) {
        dependentForm.put(`/csf/dependents/${editingDependent.value.id}`, { 
            onSuccess: () => closeModal(),
            preserveScroll: true 
        });
    } else {
        dependentForm.post(`/csf/${selectedCsfId.value}/dependents`, { 
            onSuccess: () => closeModal(),
            preserveScroll: true 
        });
    }
};

const closeModal = () => {
    showMemberModal.value = false;
    showDependentModal.value = false;
    editingMember.value = null;
    editingDependent.value = null;
    selectedCsfId.value = null;
    memberForm.reset();
    dependentForm.reset();
    memberForm.clearErrors();
    dependentForm.clearErrors();
};

const oralChoices = [
    'Orally Fit Child (OFC)', 'Dental Caries', 'Gingivitis', 
    'Periodontal Disease', 'Debris', 'Calculus', 
    'Abnormal Growth', 'Cleft Lip/Palate', 'Others',
];

const printRecord = (type, id) => window.open(`/csf/print/${type}/${id}`, '_blank');

const deleteRecord = (type, id) => {
    if (confirm('Are you sure you want to delete this record?')) {
        const url = type === 'member' ? `/csf/${id}` : `/csf/dependents/${id}`;
        router.delete(url, { preserveScroll: true });
    }
};
</script>

<template>
    <FlashMessage />
    <div class="p-8 bg-gray-50 min-h-screen font-sans text-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">CSF Membership</h1>
                    <p class="text-gray-500 mt-1">Manage hospital membership and dependents records.</p>
                </div>
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <span class="absolute left-4 top-3 text-gray-400">🔍</span>
                        <input 
                            v-model="search" 
                            type="text" 
                            placeholder="Search name or ID..." 
                            class="pl-11 pr-4 py-2.5 w-full md:w-72 border-gray-200 rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-blue-500 transition-all border outline-none"
                        />
                    </div>
                    <button @click="openMemberModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2 whitespace-nowrap">
                        <span class="text-xl">+</span> Add Member
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-400 uppercase text-xs font-black">
                        <tr>
                            <th class="p-5">PhilHealth ID</th>
                            <th class="p-5">Full Name</th>
                            <th class="p-5">Birthdate</th>
                            <th class="p-5">Admitted</th>
                            <th class="p-5">Dependents</th>
                            <th class="p-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template v-for="member in csf.data" :key="member.id">
                            <tr class="hover:bg-blue-50/40 transition-colors group">
                                <td class="p-5 font-mono text-sm text-gray-600">{{ member.philhealth_number }}</td>
                                <td class="p-5">
                                    <div class="font-bold text-gray-900 capitalize">
                                        {{ member.last_name }}, {{ member.first_name }} {{ member.middle_name }} {{ member.name_extension }}
                                    </div>
                                    <div class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">Member</div>
                                </td>
                                <td class="p-5 text-sm text-gray-600">{{ formatDate(member.birthdate) }}</td>
                                <td class="p-5 text-sm text-gray-900 font-semibold">{{ formatDate(member.date_admitted) }}</td>
                                <td class="p-5">
                                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">
                                        {{ member.dependents?.length || 0 }} Records
                                    </span>
                                </td>
                                <td class="p-5 text-right space-x-1">
                                    <button @click="printRecord('member', member.id)" class="px-3 py-1.5 text-orange-600 text-xs font-bold hover:bg-orange-50 rounded-lg transition">Print</button>
                                    <button @click="openDependentModal(member.id)" class="px-3 py-1.5 text-green-600 text-xs font-bold hover:bg-green-50 rounded-lg transition">Add Dep</button>
                                    <button @click="openMemberModal(member)" class="px-3 py-1.5 text-blue-600 text-xs font-bold hover:bg-blue-50 rounded-lg transition">Edit</button>
                                    <button @click="deleteRecord('member', member.id)" class="px-3 py-1.5 text-red-500 text-xs font-bold hover:bg-red-50 rounded-lg transition">Delete</button>
                                </td>
                            </tr>
                            <tr v-for="dep in member.dependents" :key="dep.id" class="bg-gray-50/30 text-sm text-gray-600 border-l-4 border-blue-200">
                                <td class="p-3 pl-12 font-mono text-[11px] text-gray-400">{{ dep.philhealth_number }}</td>
                                <td class="p-3 pl-4 capitalize italic">
                                    <span class="text-gray-300 font-normal mr-2">└─</span> 
                                    {{ dep.last_name }}, {{ dep.first_name }} {{ dep.middle_name }} {{ dep.name_extension }}
                                    <span class="ml-2 text-[10px] not-italic bg-blue-100 text-blue-700 px-2 py-0.5 rounded uppercase font-bold">{{ dep.relationship }}</span>
                                </td>
                                <td class="p-3 text-xs text-gray-500">{{ formatDate(dep.birthdate) }}</td>
                                <td class="p-3 text-xs text-gray-500 font-medium">{{ formatDate(dep.date_admitted) }}</td>
                                <td></td>
                                <td class="p-3 text-right space-x-3 pr-5">
                                    <button @click="printRecord('dependents', member.id)" class="text-orange-500 hover:text-orange-700 font-bold text-xs uppercase">Print Full</button>
                                    <button @click="openDependentModal(member.id, dep)" class="text-gray-400 hover:text-blue-600 font-bold text-xs uppercase">Edit</button>
                                    <button @click="deleteRecord('dep', dep.id)" class="text-gray-400 hover:text-red-500 font-bold text-xs uppercase">Delete</button>
                                </td>
                            </tr>
                        </template>
                        <tr v-if="csf.data.length === 0">
                            <td colspan="6" class="p-10 text-center text-gray-400 italic">No records found matching your search.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-500 font-medium">Showing {{ csf.from }} to {{ csf.to }} of {{ csf.total }} entries</div>
                <div class="flex items-center gap-1">
                    <template v-for="(link, index) in csf.links" :key="index">
                        <Link v-if="link.url" :href="link.url" v-html="link.label" class="px-4 py-2 rounded-xl text-sm font-bold transition-all border" :class="link.active ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50'"/>
                        <span v-else v-html="link.label" class="px-4 py-2 text-gray-300 text-sm"></span>
                    </template>
                </div>
            </div>
        </div>

        <div v-if="showMemberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-6 z-50">
            <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl max-h-[92vh] flex flex-col overflow-hidden">
                <div class="px-8 py-6 border-b bg-gradient-to-r from-blue-600 to-blue-500 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight">
                            {{ editingMember ? 'Edit Member Record' : 'New Patient Registration' }}
                        </h2>
                        <p class="text-sm opacity-90">Complete patient admission details</p>
                    </div>
                    <button @click="closeModal" class="p-2 hover:bg-white/20 rounded-full transition">✕</button>
                </div>

                <form @submit.prevent="submitMember" class="overflow-y-auto p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">PhilHealth Number</label>
                            <input :value="memberForm.philhealth_number" @input="handlePhilHealthInput($event, 'member')" type="text" maxlength="12" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Date of Birth</label>
                            <input v-model="memberForm.birthdate" type="date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-slate-600">Full Legal Name</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <input v-model="memberForm.last_name" placeholder="Last Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                            <input v-model="memberForm.first_name" placeholder="First Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                            <input v-model="memberForm.middle_name" placeholder="Middle Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                            <input v-model="memberForm.name_extension" placeholder="Extension" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>


                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-6">
                        <h3 class="text-lg font-bold text-slate-700 border-b pb-2">Admission Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-blue-700">Date & Time Admitted</label>
                                <input v-model="memberForm.date_admitted" type="datetime-local" class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-rose-700">Date & Time Discharged</label>
                                <input v-model="memberForm.date_discharged" type="datetime-local" class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:ring-2 focus:ring-rose-500">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Diagnosis</label>
                            <textarea v-model="memberForm.diagnosis" rows="3" placeholder="Enter patient diagnosis..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Institution Fees (PHP)</label>
                            <input v-model.number="memberForm.institution_fees" type="number" step="0.01" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Oral Examination</label>
                            <div class="p-4 border border-slate-200 rounded-xl bg-white max-h-40 overflow-y-auto space-y-2">
                                <div v-for="choice in oralChoices" :key="choice" class="flex items-center gap-3">
                                    <input type="checkbox" :value="choice" v-model="memberForm.oral_examination" class="w-5 h-5 accent-blue-600">
                                    <span class="text-sm text-slate-700">{{ choice }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t">
                        <button type="button" @click="closeModal" class="px-6 py-3 text-slate-500 font-semibold hover:text-slate-700">Cancel</button>
                        <button type="submit" :disabled="memberForm.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-2xl font-bold shadow-lg disabled:opacity-50 transition">
                            {{ editingMember ? 'Save Changes' : 'Confirm Registration' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="showDependentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-6 z-50">
            <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl max-h-[92vh] flex flex-col overflow-hidden">
                
                <div class="px-8 py-6 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight">
                            {{ editingDependent ? 'Edit Dependent Record' : 'Register Dependent' }}
                        </h2>
                        <p class="text-sm opacity-90">Attach a family member to principal account</p>
                    </div>
                    <button @click="closeModal" class="p-2 hover:bg-white/20 rounded-full transition">✕</button>
                </div>

                <form @submit.prevent="submitDependent" class="overflow-y-auto p-8 space-y-8">
                    
                    <!-- PhilHealth + Relationship -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">PhilHealth Number</label>
                            <input 
                                :value="dependentForm.philhealth_number" 
                                @input="handlePhilHealthInput($event, 'dependent')" 
                                type="text" 
                                maxlength="12"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" 
                                required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Date of Birth</label>
                            <input v-model="dependentForm.birthdate"
                                type="date"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Relationship</label>
                            <select v-model="dependentForm.relationship"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 bg-white"
                                required>
                                <option value="" disabled>Select relationship</option>
                                <option value="spouse">Spouse</option>
                                <option value="child">Child</option>
                                <option value="parent">Parent</option>
                            </select>
                        </div>
                    </div>

                    <!-- Name Section -->
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-slate-600">Dependent's Name</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <input v-model="dependentForm.last_name" placeholder="Last Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                            <input v-model="dependentForm.first_name" placeholder="First Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" required>
                            <input v-model="dependentForm.middle_name" placeholder="Middle Name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                            <input v-model="dependentForm.name_extension" placeholder="Extension" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <!-- Representative Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Representative Relationship -->
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">
                                Representative Relationship
                            </label>

                            <select 
                                v-model="dependentForm.representative_type"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 bg-white"
                            >
                                <option value="">Select Representative Relationship</option>
                                <option value="child">Child</option>
                                <option value="spouse">Spouse</option>
                                <option value="parent">Parent</option>
                                <option value="sibling">Sibling</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <!-- If Others → Specify Relationship Type -->
                        <div v-if="dependentForm.representative_type === 'others'" class="space-y-2 mt-2">
                            <label class="text-sm font-semibold text-slate-600">
                                Specify Relationship (e.g. Friend, Brother-in-law)
                            </label>

                            <input
                                v-model="dependentForm.representative_other_type"
                                type="text"
                                placeholder="Enter relationship type"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                            />
                        </div>

                        <!-- Representative Name -->
                        <div class="space-y-2 mt-2">
                            <label class="text-sm font-semibold text-slate-600">
                                Representative Name
                            </label>

                            <input
                                v-model="dependentForm.representative"
                                type="text"
                                placeholder="Enter representative full name"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                            />
                        </div>

                    </div>

                    <!-- Incapacitation Section -->
                    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 space-y-4">
                        <h3 class="text-lg font-bold text-rose-700 border-b pb-2">Member is incapacitated</h3>

                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2">
                                <input type="radio" v-model="dependentForm.member_incapacitated_option" value="yes" class="accent-rose-600">
                                <span>Yes</span>
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" v-model="dependentForm.member_incapacitated_option" value="other" class="accent-rose-600">
                                <span>Other Reason</span>
                            </label>
                        </div>

                        <div v-if="dependentForm.member_incapacitated_option === 'other'" class="mt-2">
                            <input type="text"
                                v-model="dependentForm.member_incapacitated_reason"
                                placeholder="Specify reason for incapacitation"
                                class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:ring-2 focus:ring-rose-500"
                            />
                        </div>
                    </div>

                    <!-- Admission Details -->
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 space-y-6">
                        <h3 class="text-lg font-bold text-emerald-700 border-b pb-2">Admission Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-emerald-700">Date & Time Admitted</label>
                                <input v-model="dependentForm.date_admitted"
                                    type="datetime-local"
                                    class="w-full px-4 py-3 rounded-xl border border-emerald-200 focus:ring-2 focus:ring-emerald-500"
                                    required>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-rose-700">Date & Time Discharged</label>
                                <input v-model="dependentForm.date_discharged"
                                    type="datetime-local"
                                    class="w-full px-4 py-3 rounded-xl border border-rose-200 focus:ring-2 focus:ring-rose-500"
                                    required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Diagnosis</label>
                            <textarea v-model="dependentForm.diagnosis"
                                rows="3"
                                placeholder="Enter dependent diagnosis..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Fees + Oral Exam -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Institution Fees (PHP)</label>
                            <input v-model.number="dependentForm.institution_fees"
                                type="number"
                                step="0.01"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500"
                                required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Oral Examination</label>
                            <div class="p-4 border border-slate-200 rounded-xl bg-white max-h-40 overflow-y-auto space-y-2">
                                <div v-for="choice in oralChoices" :key="choice" class="flex items-center gap-3">
                                    <input type="checkbox"
                                        :value="choice"
                                        v-model="dependentForm.oral_examination"
                                        class="w-5 h-5 accent-emerald-600">
                                    <span class="text-sm text-slate-700">{{ choice }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t">
                        <button type="button"
                            @click="closeModal"
                            class="px-6 py-3 text-slate-500 font-semibold hover:text-slate-700">
                            Cancel
                        </button>

                        <button type="submit"
                            :disabled="dependentForm.processing"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-3 rounded-2xl font-bold shadow-lg disabled:opacity-50 transition">
                            {{ editingDependent ? 'Update Dependent' : 'Save Dependent' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>