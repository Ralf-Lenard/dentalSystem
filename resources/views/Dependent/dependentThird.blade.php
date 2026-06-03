<p class="n3">
    @if(!$dependent->is_referred)
        ✔
    @endif
</p>

<p class="y3">
    @if($dependent->is_referred)
        ✔
    @endif
</p>

<p class="nhci3">{{ $dependent->hci_name }}</p>
<p class="pan3">{{ $dependent->pan_referring_hci }}</p>

<p class="date_of_referral">
    @if($dependent && $dependent->date_of_referral)
        @php
            $month = \Carbon\Carbon::parse($dependent->date_of_referral)->format('m');
            $day = \Carbon\Carbon::parse($dependent->date_of_referral)->format('d');
            $year = \Carbon\Carbon::parse($dependent->date_of_referral)->format('Y');
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

<p class="ref_transac">{{ $dependent->referral_transaction_code }}</p>

<p class="nn3">
    @if(!$dependent->is_referred)
        ✔
    @endif
</p>

<p class="yy3">
    @if($dependent->is_referred)
        ✔
    @endif
</p>

<p class="name_institution">{{ $dependent->institution_name }}</p>
<p class="type_institution">{{ $dependent->type_institution }}</p>

<p class="date_admitted3">
    @if($dependent && $dependent->date_admitted)
        @php
            $month = \Carbon\Carbon::parse($dependent->date_admitted)->format('m');
            $day = \Carbon\Carbon::parse($dependent->date_admitted)->format('d');
            $year = \Carbon\Carbon::parse($dependent->date_admitted)->format('Y');
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

<p class="diagnosis">{{ $dependent->diagnosis }}</p>

<p class="final_diagnosis">{{ $dependent->final_diagnosis }}</p>
<p class="icd_code">
    @if ($dependent->final_diagnosis === "Dental caries, unspecified")
        K02.9
    @elseif($dependent->final_diagnosis === "Chronic gingivitis, plaque induced")
        K05.10
    @elseif($dependent->final_diagnosis === "Aggressive periodontitis, unspecified")
        K05.20
    @elseif ($dependent->final_diagnosis === "Periapical abscess without sinus")
        K04.7
    @elseif ($dependent->final_diagnosis === "Impacted teeth (impacted third molars)")
        K01.1
    @elseif ($dependent->final_diagnosis === "Cracked tooth")
        K03.81
    @else ($dependent->final_diagnosis === "Loss of teeth due to accident or decay")
        K08.1
    @endif
</p>

<p class="procedure_done">{{ $dependent->procedure }}</p>
<p class="package_code">
    @if ($dependent->procedure === "1st visit mandatory services only")
        OPH01
    @elseif($dependent->procedure === "1st visit with mandatory services")
        OPH01A
    @elseif($dependent->procedure === "1st visit with mandatory services.")
        OPH01B
    @elseif ($dependent->procedure === "2nd visit with mandatory services")
        OPH02
    @elseif ($dependent->procedure === "2nd visit mandatory services only")
        OPH02A
    @else ($dependent->procedure === " 2nd visit with mandatory services.")
        OPH02B
    @endif
</p>

<p class="tooth_number">{{ $dependent->first_tooth }}</p>
<p class="second_number">{{ $dependent->second_tooth }}</p>
<p class="third_number">{{ $dependent->third_tooth }}</p>
<p class="fourth_number">{{ $dependent->fourth_tooth }}</p>

<p class="dental_service">{{ $dependent->first_tooth_service }}</p>
<p class="dental_service2">{{ $dependent->second_tooth_service }}</p>
<p class="dental_service3">{{ $dependent->third_tooth_service }}</p>
<p class="dental_service4">{{ $dependent->fourth_tooth_service }}</p>

<p class="benefit_claim">
    @if ($dependent->procedure === "1st visit mandatory services only")
        OPH01
    @elseif($dependent->procedure === "1st visit with mandatory services")
        OPH01A
    @elseif($dependent->procedure === "1st visit with mandatory services.")
        OPH01B
    @elseif ($dependent->procedure === "2nd visit with mandatory services")
        OPH02
    @elseif ($dependent->procedure === "2nd visit mandatory services only")
        OPH02A
    @else ($dependent->procedure === " 2nd visit with mandatory services.")
        OPH02B
    @endif
</p>

<p class="with_copayment">
    ✔
</p>

<p class="total1">
    {{ $dependent->institution_fees + $dependent->mandatory_discount + $dependent->philhealth_benefit }}
</p>

<p class="charges">{{ $dependent->institution_fees }}</p>
<p class="mandatory">{{ $dependent->mandatory_discount }}</p>
<p class="benefit">{{ $dependent->philhealth_benefit }}</p>

<p class="total2">{{$dependent->institution_fees + $dependent->mandatory_discount + $dependent->philhealth_benefit}}
</p>


@php
    use Carbon\Carbon;

    $age = Carbon::parse($dependent->birthdate)->age;
@endphp

<p class="last_signature">
    @if($age <= 18 && $dependent->representative_name)
        {{ $dependent->representative_name }}
    @else
        {{ 
                    $dependent->first_name . ' ' .
            (!empty($dependent->middle_name) ? strtoupper(substr($dependent->middle_name, 0, 1)) . '. ' : '') .
            $dependent->last_name 
                }}
    @endif
</p>

<p class="date_today33">
    @php
        $month = $today->format('m'); // e.g., "02"
        $year = $today->format('Y'); // e.g., "2026"
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
    $age = Carbon::parse($dependent->birthdate)->age;
@endphp

<p class="last_signature2">
    @if($age <= 18 && $dependent->representative_name)
        {{ $dependent->representative_name }}
    @else
        {{ 
                $dependent->first_name . ' ' .
            (!empty($dependent->middle_name) ? strtoupper(substr($dependent->middle_name, 0, 1)) . '. ' : '') .
            $dependent->last_name 
                }}
    @endif
</p>

<p class="date_today333">
    @php
        $month = $today->format('m'); // e.g., "02"
        $year = $today->format('Y'); // e.g., "2026"
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