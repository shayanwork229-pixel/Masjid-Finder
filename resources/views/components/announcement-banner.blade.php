@if(isset($announcements) && $announcements->count())
<section class="container mt-4 animate-fadeInDown">
  <div class="alert alert-success shadow-sm border-0 rounded-4 d-flex align-items-center" role="alert">
    <i class="fas fa-bullhorn fa-2x me-3 text-success"></i>
    <div>
      <h6 class="alert-heading fw-bold mb-1">Announcements:</h6>
      <ul class="mb-0 list-unstyled">
        @foreach($announcements as $announcement)
          <li><i class="fas fa-caret-right text-success me-1"></i> {{ $announcement->title }}</li>
        @endforeach
      </ul>
    </div>
  </div>
</section>
@endif
