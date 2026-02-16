<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { useForm, router, Link, Head } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import FlashMessage from '@/components/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import MemberModal from '@/components/Modal/MemberModal.vue';
import DependentModal from '@/components/Modal/DependentModal.vue';

// --- Interfaces ---
interface Dependent {
    id: number;
    philhealth_number: string;
    last_name: string;
    first_name: string;
    middle_name?: string;
    name_extension?: string;
    relationship: string;
    birthdate: string;
    date_admitted: string;
    date_discharged: string;
    institution_fees: number;
    oral_examination: string[];
    diagnosis: string;
    representative_type?: string;
    representative?: string;
    member_incapacitated?: string;
}

interface Member {
    id: number;
    philhealth_number: string;
    last_name: string;
    first_name: string;
    middle_name?: string;
    name_extension?: string;
    birthdate: string;
    date_admitted: string;
    date_discharged: string;
    institution_fees: number;
    oral_examination: string[];
    diagnosis: string;
    dependents?: Dependent[];
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface CSFData {
    data: Member[];
    from: number | null;
    to: number | null;
    total: number;
    links: PaginationLink[];
}

// --- Props ---
const props = defineProps<{
    csf: CSFData;
    filters: {
        search?: string;
    };
    auth?: {
        user: {
            name: string;
            email: string;
        } | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dental CSF',
        href: '/csf',
    },
];

// --- Helper Functions ---
const formatDate = (dateString: string | null | undefined): string => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });
};

// --- State Management ---
const search = ref<string>(props.filters?.search || '');
const showMemberModal = ref<boolean>(false);
const showDependentModal = ref<boolean>(false);
const editingMember = ref<Member | null>(null);
const editingDependent = ref<Dependent | null>(null);
const selectedCsfId = ref<number | null>(null);

// --- SEARCH LOGIC (FIX) ---
// This watches the 'search' ref and triggers an Inertia visit when it changes
watch(search, debounce((value: string) => {
    router.get(
        '/csf', 
        { search: value }, 
        { 
            preserveState: true, // Keeps the input focused
            replace: true,       // Prevents clogging browser history
            preserveScroll: true // Stops page from jumping to top
        }
    );
}, 300));

// Defensive Computed for the Table
const membersList = computed(() => props.csf?.data || []);

// --- Form Definitions ---
const oralChoices = ['Orally Fit Child (OFC)', 'Dental Caries', 'Gingivitis', 'Periodontal Disease', 'Debris', 'Calculus', 'Abnormal Growth', 'Cleft Lip/Palate', 'Others'];

const closeModal = () => {
    showMemberModal.value = false;
    showDependentModal.value = false;
    editingMember.value = null;
    editingDependent.value = null;
    selectedCsfId.value = null;
};

const openMemberModal = (member: Member | null = null) => {
    editingMember.value = member; 
    showMemberModal.value = true;
};

const openDependentModal = (csfId: number, dependent: Dependent | null = null) => {
    selectedCsfId.value = csfId;      
    editingDependent.value = dependent; 
    showDependentModal.value = true;
};

const printRecord = (type: 'member' | 'dependents', id: number) => 
    window.open(`/csf/print/${type}/${id}`, '_blank');

const deleteRecord = (type: 'member' | 'dep', id: number) => {
    if (confirm('Are you sure you want to delete this record?')) {
        const url = type === 'member' ? `/csf/${id}` : `/csf/dependents/${id}`;
        router.delete(url, { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Dental CSF" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage />
        <div class="p-8 bg-gray-50 min-h-screen font-sans text-gray-800">
            <div class="sticky top-0 z-20 bg-gray-50/95 backdrop-blur-sm pb-6 pt-2">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
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
                                class="pl-11 pr-10 py-2.5 w-full md:w-80 border-gray-200 rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-blue-500 transition-all border outline-none"
                            />
                            <button 
                                v-if="search" 
                                @click="search = ''" 
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 p-1"
                            >
                                ✕
                            </button>
                        </div>
                        <button 
                            @click="openMemberModal()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2 whitespace-nowrap"
                        >
                            <span class="text-xl">+</span> Add Member
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden w-full">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-400 uppercase text-xs font-black">
                            <tr>
                                <th class="p-5 whitespace-nowrap">PhilHealth ID</th>
                                <th class="p-5">Full Name</th>
                                <th class="p-5">Birthdate</th>
                                <th class="p-5">Admitted</th>
                                <th class="p-5">Dependents</th>
                                <th class="p-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template v-for="member in membersList" :key="member.id">
                                <tr class="hover:bg-blue-50/40 transition-colors group">
                                    <td class="p-5 font-mono text-sm text-gray-600">{{ member.philhealth_number }}</td>
                                    <td class="p-5">
                                        <div class="font-bold text-gray-900 capitalize">
                                            {{ member.last_name }}, {{ member.first_name }} 
                                            {{ member.middle_name ?? '' }} {{ member.name_extension ?? '' }}
                                        </div>
                                        <div class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">Member</div>
                                    </td>
                                    <td class="p-5 text-sm text-gray-600">{{ formatDate(member.birthdate) }}</td>
                                    <td class="p-5 text-sm text-gray-900 font-semibold">{{ formatDate(member.date_admitted) }}</td>
                                    <td class="p-5">
                                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">
                                            {{ (member.dependents || []).length }} Records
                                        </span>
                                    </td>
                                    <td class="p-5 text-right space-x-1 whitespace-nowrap">
                                        <button @click="printRecord('member', member.id)" class="px-3 py-1.5 text-orange-600 text-xs font-bold hover:bg-orange-50 rounded-lg transition">Print</button>
                                        <button @click="openDependentModal(member.id)" class="px-3 py-1.5 text-green-600 text-xs font-bold hover:bg-green-50 rounded-lg transition">Add Dep</button>
                                        <button @click="openMemberModal(member)" class="px-3 py-1.5 text-blue-600 text-xs font-bold hover:bg-blue-50 rounded-lg transition">Edit</button>
                                        <button @click="deleteRecord('member', member.id)" class="px-3 py-1.5 text-red-500 text-xs font-bold hover:bg-red-50 rounded-lg transition">Delete</button>
                                    </td>
                                </tr>

                                <template v-if="member.dependents && member.dependents.length > 0">
                                    <tr v-for="dep in member.dependents" :key="dep.id" class="bg-gray-50/30 text-sm text-gray-600 border-l-4 border-blue-200">
                                        <td class="p-3 pl-12 font-mono text-[11px] text-gray-400">{{ dep.philhealth_number }}</td>
                                        <td class="p-3 pl-4 capitalize italic">
                                            <span class="text-gray-300 font-normal mr-2">└─</span> 
                                            {{ dep.last_name }}, {{ dep.first_name }} 
                                            {{ dep.middle_name ?? '' }} {{ dep.name_extension ?? '' }}
                                            <span class="ml-2 text-[10px] not-italic bg-blue-100 text-blue-700 px-2 py-0.5 rounded uppercase font-bold">{{ dep.relationship }}</span>
                                        </td>
                                        <td class="p-3 text-xs text-gray-500">{{ formatDate(dep.birthdate) }}</td>
                                        <td class="p-3 text-xs text-gray-500 font-medium">{{ formatDate(dep.date_admitted) }}</td>
                                        <td></td>
                                        <td class="p-3 text-right space-x-3 pr-5 whitespace-nowrap">
                                            <button @click="printRecord('dependents', dep.id)" class="text-orange-500 hover:text-orange-700 font-bold text-xs uppercase">Print Full</button>
                                            <button @click="openDependentModal(member.id, dep)" class="text-gray-400 hover:text-blue-600 font-bold text-xs uppercase">Edit</button>
                                            <button @click="deleteRecord('dep', dep.id)" class="text-gray-400 hover:text-red-500 font-bold text-xs uppercase">Delete</button>
                                        </td>
                                    </tr>
                                </template>
                            </template>

                            <tr v-if="membersList.length === 0">
                                <td colspan="6" class="p-10 text-center text-gray-400 italic">No records found matching your search.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-500 font-medium">
                    Showing {{ csf?.from || 0 }} to {{ csf?.to || 0 }} of {{ csf?.total || 0 }} entries
                </div>
                <div class="flex items-center gap-1">
                    <template v-for="(link, index) in csf.links" :key="index">
                        <Link 
                            v-if="link.url" 
                            :href="link.url" 
                            v-html="link.label" 
                            class="px-4 py-2 rounded-xl text-sm font-bold transition-all border" 
                            :class="link.active ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50'"
                        />
                        <span v-else v-html="link.label" class="px-4 py-2 text-gray-300 text-sm"></span>
                    </template>
                </div>
            </div>

            <MemberModal 
                :show="showMemberModal" 
                :editingMember="editingMember" 
                :oralChoices="oralChoices" 
                @close="closeModal" 
            />

            <DependentModal 
                :show="showDependentModal" 
                :editingDependent="editingDependent" 
                :selectedCsfId="selectedCsfId"
                :oralChoices="oralChoices" 
                @close="closeModal" 
            />
        </div>
    </AppLayout>
</template>