@extends('layouts.public')

@section('title', $campaign->title)

@section('content')
<section class="breadcrumb-section">
  <div class="container">
    <h2 class="breadcrumb-title">{{ $campaign->title }}</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $campaign->title }}</li>
      </ol>
    </nav>
  </div>
</section>

<div class="container my-5 animate-fadeInUp">
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        @if($campaign->image_path)
          <img src="{{ asset('storage/'.$campaign->image_path) }}" class="img-fluid w-100" alt="{{ $campaign->title }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/donation-placeholder.jpg') }}'">
        @else
          <div class="card-img-top"><i class="fas fa-hand-holding-heart"></i></div>
        @endif
        <div class="card-body">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
              <span class="badge badge-sect badge-{{ $campaign->masjid->sect ? strtolower($campaign->masjid->sect) : 'sunni' }}">{{ $campaign->masjid->sect }}</span>
              @if($campaign->is_emergency)
                <span class="badge bg-danger text-white">URGENT</span>
              @endif
              @if($campaign->is_featured)
                <span class="badge bg-warning text-dark">Featured</span>
              @endif
            </div>
            <div class="text-end">
              <small class="text-secondary">{{ $campaign->start_date?->format('d M Y') ?? 'N/A' }} - {{ $campaign->end_date?->format('d M Y') ?? 'N/A' }}</small>
            </div>
          </div>
          <p class="text-secondary mb-4">{{ $campaign->description ?: 'No campaign description provided.' }}</p>

          <div class="mb-4">
            <div class="d-flex justify-content-between mb-2">
              <small>Raised</small>
              <small>{{ $campaign->progress_label }}</small>
            </div>
            <div class="progress" style="height: 18px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $campaign->percentage_completed }}%;" aria-valuenow="{{ $campaign->percentage_completed }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>

          <div class="row g-3 mb-4 text-secondary">
            <div class="col-sm-4"><strong>Target</strong><br>₹{{ number_format($campaign->target_amount, 2) }}</div>
            <div class="col-sm-4"><strong>Raised</strong><br>₹{{ number_format($campaign->collected_amount, 2) }}</div>
            <div class="col-sm-4"><strong>Remaining</strong><br>₹{{ number_format($campaign->remaining_amount, 2) }}</div>
          </div>

          <div class="card rounded-4 border-success-subtle bg-light p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div><strong>Assigned Masjid</strong></div>
              <div>{{ $campaign->masjid->name }}</div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div><strong>Status</strong></div>
              <div class="text-capitalize">{{ $campaign->status }}</div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div><strong>Category</strong></div>
              <div>{{ $campaign->is_emergency ? 'Emergency Fund' : 'Standard Fund' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card shadow-sm border-0 rounded-4 p-4">
        <h5 class="text-success fw-bold mb-3">Donate via WhatsApp</h5>
        <p class="text-secondary small mb-4">Enter an amount in PKR, then tap the button to send a WhatsApp message with campaign details.</p>

        <div class="row g-2 mb-3">
          @foreach([5000, 10000, 25000, 50000, 100000] as $preset)
            <div class="col-6 col-sm-4">
              <button type="button" class="btn btn-outline-custom w-100 btn-sm preset-amount">PKR {{ number_format($preset) }}</button>
            </div>
          @endforeach
        </div>

        <div class="mb-3">
          <label class="form-label">Enter Amount</label>
          <div class="input-group">
            <span class="input-group-text">PKR</span>
            <input id="whatsappAmount" type="number" min="1" step="0.01" class="form-control" placeholder="3,500" value="5000">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Optional Note</label>
          <textarea id="whatsappNote" rows="4" class="form-control" placeholder="Add any brief message or request."></textarea>
        </div>

        <a id="whatsappDonateButton" href="#" class="btn btn-success w-100" target="_blank" rel="noopener noreferrer">
          Donate via WhatsApp
        </a>
        <small class="text-muted d-block mt-2">A WhatsApp message will open with the campaign name and amount included.</small>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const phone = '92338956547';
        const campaignTitle = @json($campaign->title);
        const masjidName = @json($campaign->masjid->name);
        const amountInput = document.getElementById('whatsappAmount');
        const noteInput = document.getElementById('whatsappNote');
        const donateButton = document.getElementById('whatsappDonateButton');
        const presetButtons = document.querySelectorAll('.preset-amount');

        const formatNumber = value => {
          const number = Number(value);
          if (!Number.isFinite(number) || number <= 0) return value;
          return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(number);
        };

        const buildMessage = () => {
          const amountValue = amountInput.value.trim() || '0';
          const formattedAmount = formatNumber(amountValue);
          let message = `Assalamu Alaikum,\n\nI would like to donate.\n\nMasjid: ${masjidName}\n\nCampaign: ${campaignTitle}\n\nDonation Amount: PKR ${formattedAmount}\n\nPlease provide the payment details.`;
          const note = noteInput.value.trim();
          if (note) {
            message += `\n\n${note}`;
          }
          message += `\n\nJazakAllah Khair.`;
          return encodeURIComponent(message);
        };

        const updateLink = () => {
          donateButton.href = `https://wa.me/${phone}?text=${buildMessage()}`;
        };

        amountInput.addEventListener('input', updateLink);
        noteInput.addEventListener('input', updateLink);
        presetButtons.forEach(button => {
          button.addEventListener('click', () => {
            amountInput.value = button.textContent.replace(/[^0-9]/g, '');
            updateLink();
          });
        });

        updateLink();
      });
    </script>
  </div>
</div>
@endsection
