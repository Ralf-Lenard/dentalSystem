<p class="pan">B03036538</p>
<p class="nhci">Concepcion Rural Health Unit 1 Animal Bite Treatment Center</p>
<p class="brgy">L. Cortez Street San Nicolas Poblacion</p>
<p class="city">Concepcion</p>
<p class="province">Tarlac</p>

<p class="last_name3">{{ $csf->last_name }}</p>
    <p class="first_name3">{{ $csf->first_name }}</p>
    <p class="name_extension3">{{ $csf->name_extension }}</p>
    <p class="middle_name3">{{ $csf->middle_name }}</p>

    <p class="date_admitted2">
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

    <p class="time_admitted">
        @php
            $hour = $csf->date_admitted->format('h'); // 12-hour format
            $minute = $csf->date_admitted->format('i'); // minutes
        @endphp

        {{-- Hour --}}
        @foreach(str_split($hour) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Minute --}}
        @foreach(str_split($minute) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>

    <p class="time_discharged">
        @php
            $hour = $csf->date_discharged->format('h'); // 12-hour format
            $minute = $csf->date_discharged->format('i'); // minutes
        @endphp

        {{-- Hour --}}
        @foreach(str_split($hour) as $digit)
            <span>{{ $digit }}</span>
        @endforeach

        <span class="group-gap"></span>

        {{-- Minute --}}
        @foreach(str_split($minute) as $digit)
            <span>{{ $digit }}</span>
        @endforeach
    </p>


    <p class="second_date_today">
        @php
            $month = $csf->date_discharged->format('m'); // e.g., "02"
            $year = $csf->date_discharged->format('Y'); // e.g., "2026"
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

    <p class="adDiag">{{ $csf->diagnosis }}</p>

    <ul class="oral">
    @if($csf->oral_examination && is_array($csf->oral_examination))
        @foreach($csf->oral_examination as $exam)
            <li>{{ $exam }}</li>
        @endforeach
    @else
        <li>Not done</li>
    @endif
</ul>

<p class="code">T14.1</p>

<p class="procedure">DENTAL</p>

<p class="rvs_code">90375</p>

<p class="date_procedure">{{ $csf->date_admitted->format('m-d-Y') }}</p>


<p class="check3">✔</p>

<p class="check4">✔</p>


    <div class="page-break"></div>
    @include('memberThirdPage')