<p class="pan">E03040585</p>
<p class="nhci">Concepcion Rural Health Unit 1</p>
<p class="brgy">L. Cortez Street San Nicolas Poblacion</p>
<p class="city">Concepcion</p>
<p class="province">Tarlac</p>

<p class="last_name3">{{ $csf->last_name }}</p>
    <p class="first_name3">{{ $csf->first_name }}</p>
    <p class="name_extension3">{{ $csf->name_extension }}</p>
    <p class="middle_name3">{{ $csf->middle_name }}</p>

    <p class="date_admitted2">
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

<p class="time_admitted">
    @if($dependent && $dependent->date_admitted)
        @php
            $time = \Carbon\Carbon::parse($dependent->date_admitted);
            $hour = $time->format('h'); // 12-hour format
            $minute = $time->format('i'); // minutes
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
    @endif
</p>


<p class="time_discharged">
    @if($dependent && $dependent->date_discharged)
        @php
            $time = \Carbon\Carbon::parse($dependent->date_discharged);
            $hour = $time->format('h'); // 12-hour format
            $minute = $time->format('i'); // minutes
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
    @endif
</p>



    <p class="second_date_today">
    @if($dependent && $dependent->date_discharged)
        @php
            $month = \Carbon\Carbon::parse($dependent->date_discharged)->format('m');
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

<p class="date_procedure">
    @if($dependent && $dependent->date_admitted)
        {{ \Carbon\Carbon::parse($dependent->date_admitted)->format('m-d-Y') }}
    @endif
</p>



<p class="check3">✔</p>

<p class="check4">✔</p>


    <div class="page-break"></div>
    @include('dependentsThird')