<p class="n3">
    @if(!$csf->is_referred)
        ✔
    @endif
</p>

<p class="y3">
    @if($csf->is_referred)
        ✔
    @endif
</p>

<p class="nhci3">{{ $csf->hci_name }}</p>
<p class="pan3">{{ $csf->pan_referring_hci }}</p>

<p class="date_of_referral">
    @if($csf && $csf->date_of_referral)
        @php
            $month = \Carbon\Carbon::parse($csf->date_of_referral)->format('m');
            $day = \Carbon\Carbon::parse($csf->date_of_referral)->format('d');
            $year = \Carbon\Carbon::parse($csf->date_of_referral)->format('Y');
        @endphp

        {{-- Month --}}
        @foreach(str_split($month) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Day --}}
        @foreach(str_split($day) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    @endif
</p>

<p class="ref_transac">{{ $csf->referral_transaction_code }}</p>

<p class="nn3">
    @if(!$csf->is_referred)
        ✔
    @endif
</p>

<p class="yy3">
    @if($csf->is_referred)
        ✔
    @endif
</p>

<p class="name_institution">{{ $csf->institution_name }}</p>
<p class="type_institution">{{ $csf->type_institution }}</p>

<p class="date_admitted3">
    @if($csf && $csf->date_admitted)
        @php
            $month = \Carbon\Carbon::parse($csf->date_admitted)->format('m');
            $day = \Carbon\Carbon::parse($csf->date_admitted)->format('d');
            $year = \Carbon\Carbon::parse($csf->date_admitted)->format('Y');
        @endphp

        {{-- Month --}}
        @foreach(str_split($month) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Day --}}
        @foreach(str_split($day) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    @endif
</p>

<p class="improve">
    ✔
</p>

<p class="diagnosis">{{ $csf->diagnosis }}</p>

<p class="final_diagnosis">{{ $csf->final_diagnosis }}</p>
<p class="icd_code">
    @if ($csf->final_diagnosis === "Dental caries, unspecified")
        K02.9
    @elseif($csf->final_diagnosis === "Chronic gingivitis, plaque induced")
        K05.10
    @elseif($csf->final_diagnosis === "Aggressive periodontitis, unspecified")
        K05.20
    @elseif ($csf->final_diagnosis === "Periapical abscess without sinus")
        K04.7
    @elseif ($csf->final_diagnosis === "Impacted teeth (impacted third molars)")
        K01.1
    @elseif ($csf->final_diagnosis === "Cracked tooth")
        K03.81
    @else ($csf->final_diagnosis === "Loss of teeth due to accident or decay")
        K08.1
    @endif
</p>

<p class="procedure_done">{{ $csf->procedure }}</p>
<p class="package_code">
    @if ($csf->procedure === "1st visit mandatory services only")
        OPH01
    @elseif($csf->procedure === "1st visit with mandatory services")
        OPH01A
    @elseif($csf->procedure === "1st visit with mandatory services.")
        OPH01B
    @elseif ($csf->procedure === "2nd visit with mandatory services")
        OPH02
    @elseif ($csf->procedure === "2nd visit mandatory services only")
        OPH02A
    @else ($csf->procedure === " 2nd visit with mandatory services.")
        OPH02B
    @endif
</p>

<p class="tooth_number">{{ $csf->first_tooth }}</p>
<p class="second_number">{{ $csf->second_tooth }}</p>
<p class="third_number">{{ $csf->third_tooth }}</p>
<p class="fourth_number">{{ $csf->fourth_tooth }}</p>

<p class="dental_service">{{ $csf->first_tooth_service }}</p>
<p class="dental_service2">{{ $csf->second_tooth_service }}</p>
<p class="dental_service3">{{ $csf->third_tooth_service }}</p>
<p class="dental_service4">{{ $csf->fourth_tooth_service }}</p>

<p class="benefit_claim">
    @if ($csf->procedure === "1st visit mandatory services only")
        OPH01
    @elseif($csf->procedure === "1st visit with mandatory services")
        OPH01A
    @elseif($csf->procedure === "1st visit with mandatory services.")
        OPH01B
    @elseif ($csf->procedure === "2nd visit with mandatory services")
        OPH02
    @elseif ($csf->procedure === "2nd visit mandatory services only")
        OPH02A
    @else ($csf->procedure === " 2nd visit with mandatory services.")
        OPH02B
    @endif
</p>

<p class="with_copayment">
    ✔
</p>

<p class="total1">
    {{ $csf->institution_fees + $csf->mandatory_discount + $csf->philhealth_benefit }}
</p>

<p class="charges">{{ $csf->institution_fees }}</p>
<p class="mandatory">{{ $csf->mandatory_discount }}</p>
<p class="benefit">{{ $csf->philhealth_benefit }}</p>

<p class="total2">{{$csf->institution_fees + $csf->mandatory_discount + $csf->philhealth_benefit}}
</p>


@php
    use Carbon\Carbon;

    $age = Carbon::parse($csf->birthdate)->age;
@endphp

<!-- <p class="last_signature">
    @if($age <= 18 && $csf->representative_name)
        {{ $csf->representative_name }}
    @else
        {{ 
                    $csf->first_name . ' ' .
            (!empty($csf->middle_name) ? strtoupper(substr($csf->middle_name, 0, 1)) . '. ' : '') .
            $csf->last_name 
                }}
    @endif
</p> -->

<p class="date_today33">
@php
            $month = $csf->date_discharged->format('m');
            $year = $csf->date_discharged->format('Y');
        @endphp
    {{-- Month --}}
    @foreach(str_split($month) as $digit)
        <span>{{ $digit }}</span>
    @endforeach

    <span class="group-gap"></span>
    <span class="group-gap"></span>
    <span class="group-gap"></span>


    {{-- Day (blank) --}}
    <span class="group-gap"></span>

    {{-- Year --}}
    @foreach(str_split($year) as $digit)
        <span>{{ $digit }}</span>
    @endforeach
</p>



@php
    $age = Carbon::parse($csf->birthdate)->age;
@endphp

<p class="last_signature2">
   ROXAN B. VILLANUEVA
</p>

<p class="date_today333">
@php
            $month = $csf->date_discharged->format('m');
            $year = $csf->date_discharged->format('Y');
        @endphp

    {{-- Month --}}
    @foreach(str_split($month) as $digit)
        <span>{{ $digit }}</span>
    @endforeach

    <span class="group-gap"></span>
    <span class="group-gap"></span>
    <span class="group-gap"></span>

    {{-- Day (blank) --}}
    <span class="group-gap"></span>

    {{-- Year --}}
    @foreach(str_split($year) as $digit)
        <span>{{ $digit }}</span>
    @endforeach
</p>