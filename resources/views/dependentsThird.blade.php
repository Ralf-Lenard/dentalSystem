<p class="accreditation22">
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

    {{-- Last digit --}}
    @foreach(str_split('1') as $digit)
        <span>{{ $digit }}</span>
    @endforeach
</p>

<p class="doc2">
    HAROLD P. DELA VEGA, DMD.
</p>

<p class="check5">✔</p>

<p class="date_today55">
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

<p class="check6">✔</p>

<p class="institution_fees">
    @if($dependent)
        {{ number_format($dependent->institution_fees, 2) }}
    @endif
</p>

<p class="professional_fees">
    0.00
</p>

<p class="total_fees">
    @if($dependent)
        {{ number_format($dependent->institution_fees, 2) }}
    @endif
</p>

<p class="full_name3">
    {{ $csf->first_name }}
    {{ $csf->middle_name ? substr($csf->middle_name, 0, 1) . '.' : '' }}
    {{ $csf->last_name }}
    {{ $csf->name_extension }}
</p>

<p class="date_today6">
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

<p class="nurse2">MAR A. PANLILIO</p>
<p class="designation2">ABTC NURSE</p>

<p class="date_today7">
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