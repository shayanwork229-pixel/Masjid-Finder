<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Prayer Timetable</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    .page { padding: 24px; }
  </style>
</head>
<body>
  <div class="page">
    <h2 class="mb-3">Prayer Timetable</h2>
    @if($masjid)
      <h4>{{ $masjid->name }}</h4>
      <table class="table table-bordered">
        <tr><th>Fajr</th><td>{{ $masjid->fajr }}</td></tr>
        <tr><th>Zuhr</th><td>{{ $masjid->zuhr }}</td></tr>
        <tr><th>Asr</th><td>{{ $masjid->asr }}</td></tr>
        <tr><th>Maghrib</th><td>{{ $masjid->maghrib }}</td></tr>
        <tr><th>Isha</th><td>{{ $masjid->isha }}</td></tr>
      </table>
    @else
      <div class="row g-3">
        @foreach($masjids as $item)
          <div class="col-md-6">
            <div class="border rounded p-3 mb-3">
              <h5>{{ $item->name }}</h5>
              <div>Fajr: {{ $item->fajr }}</div>
              <div>Zuhr: {{ $item->zuhr }}</div>
              <div>Asr: {{ $item->asr }}</div>
              <div>Maghrib: {{ $item->maghrib }}</div>
              <div>Isha: {{ $item->isha }}</div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</body>
</html>
