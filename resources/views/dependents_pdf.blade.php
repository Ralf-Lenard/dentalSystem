<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CSF Member - {{ $csf->philhealth_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: monospace;
            /* width: 8.5in;
            height: 13in; */
            text-transform: uppercase;
        }

        .philhealth-number {
            position: absolute;
            top: 2.22in;
            left: 3.64in;
            display: flex;
            align-items: center;
            width: 2.27in;
            /* border: 1px red solid; */
        }

        .philhealth-number span {
            width: 15px;
            height: 22px;
            /* match your box height */
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
        }

        .last_name {
            position: absolute;
            top: 2.6in;
            left: 0.5in;
        }

        .first_name {
            position: absolute;
            top: 2.6in;
            left: 2in;
        }

        .name_extension {
            position: absolute;
            top: 2.6in;
            left: 3.48in;
        }

        .middle_name {
            position: absolute;
            top: 2.6in;
            left: 4.96in;
        }

        .birthdate {
            position: absolute;
            top: 2.66in;
            left: 6.30in;
            display: flex;
            align-items: center;
            width: 1.51in;

        }

        .birthdate span {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
        }

        .philhealth-number2 {
            position: absolute;
            top: 3.12in;
            left: 3.79in;
            display: flex;
            align-items: center;
            width: 2.3in;
        }

        .philhealth-number2 span {
            width: 15px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
        }


        .last_name2 {
            position: absolute;
            top: 3.5in;
            left: 0.5in;
        }

        .first_name2 {
            position: absolute;
            top: 3.5in;
            left: 1.97in;
        }

        .name_extension2 {
            position: absolute;
            top: 3.5in;
            left: 3.48in;
        }

        .middle_name2 {
            position: absolute;
            top: 3.5in;
            left: 4.95in;
        }

        .date_admitted {
            position: absolute;
            top: 4.18in;
            left: 1.33in;
            display: flex;
            align-items: center;
            width: 1.51in;
        }

        .date_admitted span {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
        }


        .date_today {
            position: absolute;
            top: 4.18in;
            left: 4.1in;
            display: flex;
            align-items: center;
            gap: 6.2px;
           justify-content: center
        }

        .date_today span {
            width: 10.5px;
            height: 20px;
            display: flex;
            align-items: center;
            font-family: monospace;
            font-size: 13px;
        }


        .birthdate2 {
            position: absolute;
            top: 4.18in;
            left: 6.30in;
            display: flex;
            align-items: center;
            width: 1.51in;
        }

        .birthdate2 span {
            width: 19px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
        }

        .full_name {
            position: absolute;
            top: 4.85in;
            left: 4.55in;
        }

        .date_today2 {
            position: absolute;
            top: 5.20in;
            left: 5.46in;
            display: flex;
            align-items: center;
            gap: 6.2px;
           justify-content: center
        }

        .date_today2 span {
            width: 10.5px;
            height: 20px;
            display: flex;
            align-items: center;
            font-family: monospace;
            font-size: 13px;
        }

        .full_name2 {
            position: absolute;
            top: 8.87in;
            left: 1.2in;
        }

        .date_today3 {
            position: absolute;
            top: 8.88in;
            left: 5.53in;
            display: flex;
            align-items: center;
            gap: 6.2px;
           justify-content: center
        }

        .date_today3 span {
            width: 10.5px;
            height: 20px;
            display: flex;
            align-items: center;
            font-family: monospace;
            font-size: 13px;
        }

        .accreditation{
            position: absolute;
            top: 10.25in;
            left: 1.27in;
            display: flex;
            align-items: center;
            /* width: 2.2in; */
            }

            .accreditation span {
            width: 15px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
            }
       
        .doc {
            position: absolute;
            top: 10.26in;
            left: 3.7in;
            font-size: 12px;
        }


        .date_today4 {
            position: absolute;
            top: 10.25in;
            left: 6.45in;
            display: flex;
            align-items: center;
            gap: 6.2px;
           justify-content: center
        }

        .date_today4 span {
            width: 10.5px;
            height: 20px;
            display: flex;
            align-items: center;
            font-family: monospace;
            font-size: 13px;
        }

        .fcr{
            position: absolute;
            top: 11.53in;
            left: 4in;
        }

        .nurse{
            position: absolute;
            top: 11.91in;
            left: 0.5in;
        }

        .designation{
            position: absolute;
            top: 11.91in;
            left: 3.75in;
        }

        .date_today5 {
            position: absolute;
            top: 11.92in;
            left: 6.45in;
            display: flex;
            align-items: center;
            gap: 6.2px;
           justify-content: center
        }

        .date_today5 span {
            width: 10.5px;
            height: 20px;
            display: flex;
            align-items: center;
            font-family: monospace;
            font-size: 13px;
        }

        .check{
            position: absolute;
            top: 6.04in;
            left: 1.14in;
        }

        .check_patient{
            position: absolute;
            top: 9.74in;
            left: 0.47in;
        }

        .check_representative{
            position: absolute;
            top: 9.74in;
            left: 1.14in;
        }

        .child{
            position: absolute;
            top: 3.52in;
            left: 6.18in;
        }

        .parent{
            position: absolute;
            top: 3.51in;
            left: 6.75in;
        }

        .spouse{
            position: absolute;
            top: 3.5in;
            left: 7.37in;
        }

        .rep_spouse{
            position: absolute;
            top: 5.48in;
            left: 5.5in;
        }

        .rep_sibling{
            position: absolute;
            top: 5.67in;
            left: 5.5in;
        }

        .rep_child{
            position: absolute;
            top: 5.49in;
            left: 6.13in;
        }

        .rep_parent{
            position: absolute;
            top: 5.48in;
            left: 6.66in;
        }

        .rep_others{
            position: absolute;
            top: 5.67in;
            left: 6.14in;
        }

        .other_specify{
            position: absolute;
            top: 5.68in;
            left: 6.98in;
            font-size: 10px;
        }

        .rep_members{
            position: absolute;
            top: 5.87in;
            left: 5.5in;
        }

        .rep_other_reason{
            position: absolute;
            top: 6.08in;
            left: 5.49in;
        }

        .rep_other_reason_text{
            position: absolute;
            top: 6.16in;
            left: 6.34in;
            font-size: 10px;
        }

        .rep_spouse2{
            position: absolute;
            top: 9.22in;
            left: 5.5in;
        }

        .rep_sibling2{
            position: absolute;
            top: 9.41in;
            left: 5.5in;
        }

        .rep_child2{
            position: absolute;
            top: 9.22in;
            left: 6.13in;
        }

        .rep_parent2{
            position: absolute;
            top: 9.22in;
            left: 6.66in;
        }

        .rep_others2{
            position: absolute;
            top: 9.41in;
            left: 6.13in;
        }

        .other_specify2{
            position: absolute;
            top: 9.45in;
            left: 6.98in;
            font-size: 10px;
        }

        .rep_members2{
            position: absolute;
            top: 9.62in;
            left: 5.5in;
        }

        .rep_other_reason2{
            position: absolute;
            top: 9.81in;
            left: 5.5in;
        }

        .rep_other_reason_text2{
            position: absolute;
            top: 9.86in;
            left: 6.34in;
            font-size: 10px;
        }



        .page-break {
            page-break-after: always;
        }


       
        
    </style>
     @include('/Dependent/dependentSecondStyle')
     @include('/Dependent/dependentThirdStyle')
</head>

<body>


    <p class="philhealth-number">
        @php
            preg_match('/(\d{2})(\d{9})(\d)/', $csf->philhealth_number, $m);
        @endphp

        {{-- First 2 digits --}}
        @foreach(str_split($m[1] ?? '') as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap2"></span>

        {{-- Middle 9 digits --}}
        @foreach(str_split($m[2] ?? '') as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap2"></span>

        {{-- Last digit --}}
        @foreach(str_split($m[3] ?? '') as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>




    <!-- 1 and 2 -->
    <p class="last_name">{{ $csf->last_name }}</p>
    <p class="first_name">{{ $csf->first_name }}</p>
    <p class="name_extension">{{ $csf->name_extension }}</p>
    <p class="middle_name">{{ $csf->middle_name }}</p>
    <p class="birthdate">
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


    <!-- 4, 5, and 6 -->
    <p class="philhealth-number2">
    @if($dependent && $dependent->philhealth_number)
        @php
            $number = $dependent->philhealth_number;
            preg_match('/(\d{2})(\d{9})(\d)/', $number, $m);
        @endphp

        {{-- First 2 digits --}}
        @foreach(str_split($m[1] ?? '') as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Middle 9 digits --}}
        @foreach(str_split($m[2] ?? '') as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Last digit --}}
        @foreach(str_split($m[3] ?? '') as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    @endif
</p>


    @if($dependent)
    <p class="last_name2">{{ $dependent->last_name }}</p>
    <p class="first_name2">{{ $dependent->first_name }}</p>
    <p class="name_extension2">{{ $dependent->name_extension }}</p>
    <p class="middle_name2">{{ $dependent->middle_name }}</p>
    @endif

    <p class="child">
        @if($dependent->relationship === 'child')
            ✔
        @endif
    </p>

    <p class="parent">
        @if($dependent->relationship === 'parent')
            ✔
        @endif
    </p>

    <p class="spouse">
        @if($dependent->relationship === 'spouse')
            ✔
        @endif
    </p>

    <!-- 7 and 8 -->
    <p class="date_admitted">
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

    <p class="date_today">
    @if($dependent && $dependent->date_discharged)
        @php
            $month = \Carbon\Carbon::parse($dependent->date_discharged)->format('m');
            $day = \Carbon\Carbon::parse($dependent->date_discharged)->format('d');
            $year = \Carbon\Carbon::parse($dependent->date_discharged)->format('Y');
        @endphp

        {{-- Month --}}
        @foreach(str_split($month) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>
        <span class="group-gap"></span>
        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    @endif
</p>


    <p class="birthdate2">
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
    <p class="full_name">{{ $dependent->representative }}</p>

    @endif

    <p class="date_today2">
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


    @php
    $standardTypes = ['spouse', 'sibling', 'child', 'parent'];
    $rel = strtolower($dependent->representative_type ?? '');
    $isOther = !in_array($rel, $standardTypes) && $rel !== '';
    @endphp

    <p class="rep_spouse">@if($rel === 'spouse') ✔ @endif</p>
    <p class="rep_sibling">@if($rel === 'sibling') ✔ @endif</p>
    <p class="rep_child">@if($rel === 'child') ✔ @endif</p>
    <p class="rep_parent">@if($rel === 'parent') ✔ @endif</p>

    <!-- Others / Custom -->
    <p class="rep_others">
        @if($isOther) ✔ @endif
        @if($isOther)
            <p class="other_specify">{{ ucfirst($rel) }}</p>
        @endif
    </p>


    @php
    $incap = $dependent->member_incapacitated ?? null;
    @endphp

    <!-- Check for Yes -->
    <p class="rep_members">
        @if($incap === 'Yes') ✔ @endif
    </p>

    <!-- Check for Other -->
    <p class="rep_other_reason">
        @if($incap && $incap !== 'Yes') ✔ @endif
    </p>

    <!-- Show the reason text (only if not Yes) -->
    <p class="rep_other_reason_text">
        @if($incap && $incap !== 'Yes')
            {{ $incap }}
        @endif
    </p>


    <p class="check">✔</p>

    @php
        use Carbon\Carbon;

        $age = $dependent && $dependent->birthdate
            ? Carbon::parse($dependent->birthdate)->age
            : null;
    @endphp

    @if($dependent && $age !== null)

        @if($age < 18)
            {{-- Below 18: Use Representative --}}
            <p class="full_name2">{{ $dependent->representative }}</p>
            <p class="check_patient"></p>
            <p class="check_representative">✔</p>
        @else
            {{-- 18 and above: Use Dependent (Patient) --}}
            <p class="full_name2">
                {{ $dependent->first_name }}
                {{ $dependent->middle_name ? strtoupper(substr($dependent->middle_name, 0, 1)) . '.' : '' }}
                {{ $dependent->last_name }}
                {{ $dependent->name_extension }}
            </p>

            <p class="check_patient">✔</p>
            <p class="check_representative"></p>
        @endif

    @endif


    <p class="date_today3">
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

        @php

        $age = $dependent && $dependent->birthdate
            ? Carbon::parse($dependent->birthdate)->age
            : null;
        @endphp

        @if($dependent && $age !== null && $age <= 18)

        @php
            $standardTypes = ['spouse', 'sibling', 'child', 'parent'];
            $rel = strtolower($dependent->representative_type ?? '');
            $isOther = !in_array($rel, $standardTypes) && $rel !== '';
        @endphp

        <p class="rep_spouse2">@if($rel === 'spouse') ✔ @endif</p>
        <p class="rep_sibling2">@if($rel === 'sibling') ✔ @endif</p>
        <p class="rep_child2">@if($rel === 'child') ✔ @endif</p>
        <p class="rep_parent2">@if($rel === 'parent') ✔ @endif</p>

        <p class="rep_others2">@if($isOther) ✔ @endif</p>

        @if($isOther)
            <p class="other_specify2">{{ ucfirst($rel) }}</p>
        @endif

        @php
            $incap = $dependent->member_incapacitated ?? null;
        @endphp

        <!-- Check for Yes -->
        <p class="rep_members2">
            @if($incap === 'Yes') ✔ @endif
        </p>
    
        <!-- Check for Other -->
        <p class="rep_other_reason2">
            @if($incap && $incap !== 'Yes') ✔ @endif
        </p>
    
        <!-- Show the reason text -->
        <p class="rep_other_reason_text2">
            @if($incap && $incap !== 'Yes')
                {{ $incap }}
            @endif
        </p>

    @endif



    <p class="accreditation">
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


    <p class="doc">
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

    <p class="fcr">90375</p>
    <p class="nurse">MAR A. PANLILIO</p>
    <p class="designation">ABTC NURSE</p>

    <p class="date_today5">
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

        @include('/Dependent/dependentSecond')
        @include('/Dependent/dependentThird')
</body>

</html>