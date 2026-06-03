<div class="philhealth-number3">
    @php
        $number = preg_replace('/\D/', '', $csf->philhealth_number);
        preg_match('/(\d{2})(\d{9})(\d)/', $number, $m);
    @endphp

    <div class="first"><p>@foreach(str_split($m[1] ?? '') as $digit){{$digit}}@endforeach</p></div>
    <div class="second"><p>@foreach(str_split($m[2] ?? '') as $digit){{$digit}}@endforeach</p></div>
    <div><p>@foreach(str_split($m[3] ?? '') as $digit){{$digit}}@endforeach</p></div>
</div>

<p class="last_name3">{{ $csf->last_name }}</p>
<p class="first_name3">{{ $csf->first_name }}</p>
<p class="name_extension3">{{ $csf->name_extension }}</p>
<p class="middle_name3">{{ $csf->middle_name }}</p>

<p class="birthdate3">
    @php
        $month = $csf->birthdate->format('m');
        $day = $csf->birthdate->format('d');
        $year = $csf->birthdate->format('Y');
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
</p>


<p class="male">
@if($csf->sex === 'Male')
            ✔
        @endif
</p>
<p class="female">
@if($csf->sex === 'Female')
            ✔
        @endif
</p>


<p class="unit">{{ $csf->unit_no }}</p>
<p class="building">{{ $csf->building_name }}</p>
<p class="house">{{ $csf->house_no }}</p>
<p class="street">{{ $csf->street }}</p>
<p class="village">{{ $csf->village }}</p>
<p class="brgy">{{ $csf->brgy }}</p>
<p class="city">{{ $csf->municipality }}</p>
<p class="province">{{ $csf->province }}</p>
<p class="country">{{ $csf->country }}</p>
<p class="zip_code">{{ $csf->zip_code }}</p>
<p class="landline">{{ $csf->landline_no }}</p>
<p class="mobile_no">{{ $csf->mobile_no }}</p>
<p class="email">{{ $csf->email_address }}</p>

<p class="noo">✔</p>

<div class="philhealth-number4">
@if($dependent && $dependent->philhealth_number)
        @php
            $number = $dependent->philhealth_number;
            preg_match('/(\d{2})(\d{9})(\d)/', $number, $m);
        @endphp

    <div class="first2"><p>@foreach(str_split($m[1] ?? '') as $digit){{$digit}}@endforeach</p></div>
    <div class="second2"><p>@foreach(str_split($m[2] ?? '') as $digit){{$digit}}@endforeach</p></div>
    <div><p>@foreach(str_split($m[3] ?? '') as $digit){{$digit}}@endforeach</p></div>
@endif
</div>


<p class="birthdate4">
    @if($dependent && $dependent->birthdate)
        @php
            $month = \Carbon\Carbon::parse($dependent->birthdate)->format('m');
            $day = \Carbon\Carbon::parse($dependent->birthdate)->format('d');
            $year = \Carbon\Carbon::parse($dependent->birthdate)->format('Y');
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


@if($dependent)
    <p class="last_name4">{{ $dependent->last_name }}</p>
    <p class="first_name4">{{ $dependent->first_name }}</p>
    <p class="name_extension4">{{ $dependent->name_extension }}</p>
    <p class="middle_name4">{{ $dependent->middle_name }}</p>
@endif

<p class="child2">
        @if($dependent->relationship === 'child')
            ✔
        @endif
    </p>

    <p class="parent2">
        @if($dependent->relationship === 'parent')
            ✔
        @endif
    </p>

    <p class="spouse2">
        @if($dependent->relationship === 'spouse')
            ✔
        @endif
    </p>

<p class="male2">
@if($dependent->sex === 'Male')
            ✔
        @endif
</p>
<p class="female2">
@if($dependent->sex === 'Female')
            ✔
        @endif
</p>

<p class="pan">B03036538</p>
<p class="nhci">Concepcion Rural Health Unit 1 Animal Bite Treatment Center</p>
<p class="brgy2">L. Cortez Street San Nicolas Poblacion</p>
<p class="city2">Concepcion</p>
<p class="province2">Tarlac</p>

<p class="doc_last_name">DELA VEGA</p>
<p class="doc_first_name">HAROLD</p>
<p class="doc_middle_name">P</p>

<p class="accreditation2">
    {{-- First 4 digits --}}
    @foreach(str_split('1501') as $digit)
        <span>{{ $digit }}</span>
    @endforeach

    <span class="group-gap"></span>

    {{-- Middle 7 digits --}}
    @foreach(str_split('1638387') as $digit)
        <span>{{ $digit }}</span>
    @endforeach

    <span class="group-gap"></span>
    <!-- <span class="group-gap"></span> -->

    {{-- Last digit --}}
    @foreach(str_split('1') as $digit)
        <span>{{ $digit }}</span>
    @endforeach
</p>

<p class="doc2">
    HAROLD P. DELA VEGA, DMD.
</p>

<p class="date_today4">
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

    {{-- Day (blank) --}}
    <span class="group-gap"></span>

    {{-- Year --}}
    @foreach(str_split($year) as $digit)
        <span>{{ $digit }}</span>
    @endforeach
</p>

<div class="page-break"></div>
@include('/Dependent/dependentThird')
