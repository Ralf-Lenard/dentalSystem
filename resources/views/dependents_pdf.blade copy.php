<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSF Member + Dependents - {{ $csf->philhealth_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        h1 { font-size: 20px; }
        h2 { font-size: 16px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>

<h1>CSF Member Details</h1>

<p><strong>PhilHealth #: </strong>{{ $csf->philhealth_number }}</p>
<p><strong>Name: </strong>{{ $csf->last_name }}, {{ $csf->first_name }} {{ $csf->middle_name }} {{ $csf->name_extension }}</p>
<p><strong>Birthdate: </strong>{{ $csf->birthdate->format('F d, Y') }}</p>

@if($csf->dependents->count())
    <h2>Dependents</h2>
    <table>
        <thead>
        <tr>
            <th>PhilHealth #</th>
            <th>Name</th>
            <th>Birthdate</th>
            <th>Relationship</th>
        </tr>
        </thead>
        <tbody>
        @foreach($csf->dependents as $dep)
            <tr>
                <td>{{ $dep->philhealth_number }}</td>
                <td>{{ $dep->last_name }}, {{ $dep->first_name }} {{ $dep->middle_name }} {{ $dep->name_extension }}</td>
                <td>{{ $dep->birthdate->format('F d, Y') }}</td>
                <td>{{ ucfirst($dep->relationship) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

</body>
</html>
