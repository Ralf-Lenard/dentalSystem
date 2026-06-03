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
            left: 3.63in;
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
            top: 2.64in;
            left: 6.31in;
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
            top: 4.19in;
            left: 1.32in;
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
            left: 6.3in;
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
            left: 1in;
        }

        .date_today2 {
            position: absolute;
            top: 5.19in;
            left: 1.8in;
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
            left: 5.54in;
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

        /* .accreditation {
            position: absolute;
            top: 10.25in;
            left: 1.25in;
            display: flex;
            align-items: center;
            width: 2.18in;
        }

        .accreditation span {
            width: 15.5px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: monospace;
            font-size: 13px;
        } */

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
            left: 3.58in;
            font-size: 12px;
        }


        .date_today4 {
            position: absolute;
            top: 10.25in;
            left: 6.44in;
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
            top: 6.05in;
            left: 0.5in;
        }

        .check2{
            position: absolute;
            top: 9.74in;
            left: 0.49in;
        }

        .page-break {
            page-break-after: always;
        }


       
    </style>

@include('/Member/memberSecondStyle')
@include('/Member/memberThirdStyle')
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
        @php
            preg_match('/(\d{2})(\d{9})(\d)/', $csf->philhealth_number, $m);
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
    </p>

    <p class="last_name2">{{ $csf->last_name }}</p>
    <p class="first_name2">{{ $csf->first_name }}</p>
    <p class="name_extension2">{{ $csf->name_extension }}</p>
    <p class="middle_name2">{{ $csf->middle_name }}</p>

    <!-- 7 and 8 -->
    <p class="date_admitted">
        @php
            $month = $csf->date_admitted->format('m');
            $day = $csf->date_admitted->format('d');
            $year = $csf->date_admitted->format('Y');
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

    <p class="date_today">
        
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

        {{-- Day (blank) --}}
        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>


    <p class="birthdate2">
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

    <!-- <p class="full_name">
        {{ $csf->first_name }}
        {{ $csf->middle_name ? substr($csf->middle_name, 0, 1) . '.' : '' }}
        {{ $csf->last_name }}
        {{ $csf->name_extension }}
    </p> -->

    <p class="date_today2">
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

        {{-- Day (blank) --}}
        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>

    <p class="check">✔</p>

    <!-- <p class="full_name2">
        {{ $csf->first_name }}
        {{ $csf->middle_name ? substr($csf->middle_name, 0, 1) . '.' : '' }}
        {{ $csf->last_name }}
        {{ $csf->name_extension }}
    </p> -->

    <p class="check2">✔</p>

    <p class="date_today3">
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

        {{-- Day (blank) --}}
        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>


    <p class="accreditation">
        {{-- First 4 digits --}}
        @foreach(str_split('2000') as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Middle 7 digits --}}
        @foreach(str_split('1636682') as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>
        <!-- <span class="group-gap"></span> -->

        {{-- Last digit --}}
        @foreach(str_split('2') as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>


    <p class="doc">
        HAROLD P. DELA VEGA, DMD.
    </p>

    <p class="date_today4">
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

        {{-- Day (blank) --}}
        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>

    <p class="fcr">OPH01a</p>
    <p class="nurse">ROXAN B. VILLANUEVA</p>
    <p class="designation">ADMINISTRATIVE AIDE I</p>

    <p class="date_today5">
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

        {{-- Day (blank) --}}
        <span class="group-gap"></span>

        {{-- Year --}}
        @foreach(str_split($year) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>

    <div class="page-break"></div>

        @include('/Member/memberSecond')
</body>

</html>