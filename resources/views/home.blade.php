@extends('layouts.app')
@section('title','Home')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-20">
    <div class="p-5 hero">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <h1 class="display-5 fw-bold text-dark mb-3">Swap your snacks, keep the joy.</h1>
          <p class="lead text-dark-50 mb-4">
            Cari snack favoritmu dan temukan alternatif yang lebih sehat.
          </p>
          <form method="GET" action="{{ route('search') }}" class="row g-2">
            <div class="col-md-8">
              <input class="form-control form-control-lg" type="text" name="query"
                     placeholder="Search any snack or food…" required>
            </div>
            <div class="col-md-3">
              <select class="form-select form-select-lg" name="region">
                <option value="global" selected>Global</option>
                <option value="id">Prefer Indonesia</option>
              </select>
            </div>
            <div class="col-md-1 d-grid">
              <button class="btn btn-dark btn-lg"><i class="bi bi-search"></i></button>
            </div>
          </form>
          <div class="mt-3 small text-dark">
            Popular:
            <span class="badge badge-chip rounded-pill me-1">chips → almonds</span>
            <span class="badge badge-chip rounded-pill me-1">soda → sparkling water</span>
            <span class="badge badge-chip rounded-pill">cookies → oat cookies</span>
          </div>
        </div>
        <div class="col-lg-5 text-center">
          <img src="https://info.totalwellnesshealth.com/hs-fs/hubfs/SnackSwap.png?width=1200&height=628&name=SnackSwap.png"
               class="img-fluid rounded-4 shadow-sm" alt="Hero">
        </div>
        
        <div class="row justify-content-center section-gap">
        <div class="col-lg-10">
            <div class="p-5 glass-card">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Why <span class="gradient-text">SnackSwap</span>?</h3>
                <p class="text-muted mb-0">Data real-time + rekomendasi sehat terkurasi, tampilan super simple.</p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-4">
                <div class="feature-emoji mx-auto">🔎</div>
                <h5 class="mt-3 mb-1">Global + Indonesia</h5>
                <p class="text-muted small mb-0">Cari produk dari API global & database lokal brand Indo.</p>
                </div>
                <div class="col-md-4">
                <div class="feature-emoji mx-auto">🔄</div>
                <h5 class="mt-3 mb-1">Smart Swaps</h5>
                <p class="text-muted small mb-0">Alternatif sehat siap pakai per kategori.</p>
                </div>
                <div class="col-md-4">
                <div class="feature-emoji mx-auto">📊</div>
                <h5 class="mt-3 mb-1">Nutrition Compare</h5>
                <p class="text-muted small mb-0">Bandingkan kalori, gula, lemak, sodium—jelas & cepat.</p>
                </div>
            </div>
            </div>
        </div>
        </div>
        {{-- POPULAR SWAPS --}}
        <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-3 text-center">
            <span class="pill"><i class="bi bi-stars"></i> Popular swaps</span>
            </div>

            <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 glass-card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                    <div class="fw-semibold">Chips → Almonds</div>
                    <span class="badge badge-source">LOWER FAT</span>
                    </div>
                    <div class="soft-divider my-3"></div>
                    <div class="d-flex align-items-center gap-3">
                    <img src="https://picsum.photos/seed/chips/96/72" class="rounded-3" alt="">
                    <i class="bi bi-arrow-right fs-4 text-secondary"></i>
                    <img src="https://picsum.photos/seed/almond/96/72" class="rounded-3" alt="">
                    </div>
                    <p class="text-muted small mt-3 mb-0">Kalori & sodium umumnya lebih rendah dibanding keripik kentang.</p>
                </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 glass-card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                    <div class="fw-semibold">Soda → Sparkling Water</div>
                    <span class="badge badge-source">LOW SUGAR</span>
                    </div>
                    <div class="soft-divider my-3"></div>
                    <div class="d-flex align-items-center gap-3">
                    <img src="https://picsum.photos/seed/soda/96/72" class="rounded-3" alt="">
                    <i class="bi bi-arrow-right fs-4 text-secondary"></i>
                    <img src="https://picsum.photos/seed/sparkling/96/72" class="rounded-3" alt="">
                    </div>
                    <p class="text-muted small mt-3 mb-0">Kurangi gula tambahan tanpa kehilangan sensasi “fizz”.</p>
                </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 glass-card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                    <div class="fw-semibold">Cookies → Oat Cookies</div>
                    <span class="badge badge-source">MORE FIBER</span>
                    </div>
                    <div class="soft-divider my-3"></div>
                    <div class="d-flex align-items-center gap-3">
                    <img src="https://picsum.photos/seed/cookies/96/72" class="rounded-3" alt="">
                    <i class="bi bi-arrow-right fs-4 text-secondary"></i>
                    <img src="https://picsum.photos/seed/oats/96/72" class="rounded-3" alt="">
                    </div>
                    <p class="text-muted small mt-3 mb-0">Serat lebih tinggi → kenyang lebih lama.</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        {{-- CTA --}}
        <div class="row justify-content-center section-gap">
        <div class="col-lg-9">
            <div class="p-4 p-md-5 cta-wrap text-center">
            <h4 class="fw-bold mb-2">Ready to swap smarter?</h4>
            <p class="text-muted mb-4">Coba cari snack favoritmu—lihat alternatifnya dalam hitungan detik.</p>
            <form method="GET" action="{{ route('search') }}" class="row g-2 justify-content-center">
                <div class="col-md-6">
                <input type="text" name="query" class="form-control form-control-lg"
                        placeholder="e.g. Indomie, Chitato, Oreo…" required>
                </div>
                <div class="col-md-3">
                <select class="form-select form-select-lg" name="region">
                    <option value="global" selected>Global</option>
                    <option value="id">Prefer Indonesia</option>
                </select>
                </div>
                <div class="col-md-2 d-grid">
                <button class="btn btn-dark btn-lg"><i class="bi bi-search me-1"></i></button>
                </div>
            </form>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection