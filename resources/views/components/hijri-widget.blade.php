<div class="stat-card h-100 d-flex flex-column justify-content-center bg-light border-0 shadow-sm">
    <div class="stat-icon bg-success text-white"><i class="fas fa-moon"></i></div>
    <div class="stat-number fs-4 text-success">{{ \Carbon\Carbon::now()->format('d M, Y') }}</div>
    <div class="stat-label">Islamic Date (Approx)</div>
    <a href="{{ route('hijri.calendar') }}" class="text-decoration-none small text-muted mt-2">View Full Calendar <i class="fas fa-arrow-right"></i></a>
</div>
