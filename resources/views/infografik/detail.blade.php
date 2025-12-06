@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-3">{{ $infografik->created_at->format('d F Y') }}</span>
                        <span class="text-muted mr-3">|</span>
                        <span class="text-muted">
                            <i class="fa fa-eye"></i> {{ number_format(rand(1000, 10000), 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-2">Bagikan:</span>

                        <a href="{{ route('infografik.ckan', $infografik->id) }}" class="btn btn-sm btn-outline-secondary"
                            title="SEND CKAN">
                            <i class="ik ik-send"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="infografik-section">
                    @if ($infografik->hasGallery && $infografik->hasGallery->count() > 0)
                        <div class="gallery-main-container">
                            <div class="owl-container">
                                <div class="owl-carousel gallery-main" id="galleryMain">
                                    @foreach ($infografik->hasGallery as $gallery)
                                        <div class="gallery-item">
                                            <img src="{{ asset('storage/gallery/' . $gallery->gambar) }}"
                                                class="img-fluid rounded shadow"
                                                alt="{{ $gallery->gambar ?? 'Gallery Image' }}"
                                                style="width: 100%; max-height: 500px; object-fit: contain;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="gallery-thumbnails-wrapper mt-3">
                                <div class="owl-container">
                                    <div class="owl-carousel gallery-thumbnails" id="galleryThumbnails">
                                        @foreach ($infografik->hasGallery as $index => $gallery)
                                            <div class="gallery-thumbnail-item" data-index="{{ $index }}">
                                                <img src="{{ asset('storage/gallery/' . $gallery->gambar) }}"
                                                    class="img-fluid" alt="{{ $gallery->gambar ?? 'Gallery Image' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="gallery-description mt-3">
                                <p id="mainGalleryDescription" class="text-muted mb-2">
                                    {{ $infografik->hasGallery->first()->deskripsi ?? '' }}
                                </p>
                                <small class="text-muted">Sumber: Open Data Jabar, {{ date('Y') }}</small>
                            </div>
                        </div>
                    @elseif ($infografik->thumbnail)
                        <div class="infografik-image mb-3">
                            <img src="{{ asset('storage/gallery/' . $infografik->thumbnail) }}"
                                class="img-fluid rounded shadow" alt="{{ $infografik->judul }}"
                                style="width: 100%; max-height: 500px; object-fit: contain;">
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="article-section">

                    @if ($infografik->isi_infografik)
                        <div class="content-infografik">
                            {!! $infografik->isi_infografik !!}
                        </div>
                    @else
                        <p class="text-muted">Tidak ada konten artikel tersedia.</p>
                    @endif

                    <div class="article-meta mt-4 pt-3 border-top">
                        <div class="mb-2">
                            <strong>Topik:</strong>
                            <span class="badge badge-primary">
                                {{ $kategoriInfografik && is_object($kategoriInfografik) ? $kategoriInfografik->nama : 'Umum' }}
                            </span>
                        </div>
                        <div>
                            <strong>Disusun oleh:</strong>
                            <span class="text-muted">Jabar Digital Service</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('infografik.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if (isset($relatedInfografik) && $relatedInfografik->count() > 0)
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="mb-0 mr-3">Infografik Terkait</h4>
                        <div class="flex-grow-1"
                            style="height: 2px; background: linear-gradient(to right, #28a745, transparent);"></div>
                    </div>
                </div>
                @foreach ($relatedInfografik as $related)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <a href="{{ route('infografik.detail', $related->slug) }}" class="text-decoration-none">
                            <div class="card h-100">
                                @if ($related->thumbnail)
                                    <img src="{{ asset('storage/gallery/' . $related->thumbnail) }}" class="card-img-top"
                                        alt="{{ $related->judul }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i class="fa fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title text-dark mb-2">
                                        {{ Str::limit($related->judul, 60) }}
                                    </h6>
                                    @if ($related->kategoriInfografik && is_object($related->kategoriInfografik))
                                        <span class="badge badge-primary badge-sm">
                                            {{ $related->kategoriInfografik->nama ?? 'Umum' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@stop

@push('style')
    <style>
        .content-infografik {
            line-height: 1.8;
            font-size: 16px;
        }

        .content-infografik img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }

        .content-infografik p {
            margin-bottom: 1rem;
        }

        .infografik-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo-placeholder {
            width: 50px;
            height: 50px;
        }

        .logo-box {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            padding: 5px;
            line-height: 1.2;
        }

        .logo-box::before {
            content: "DISKOMINF JABAR";
        }

        .gallery-main-container {
            position: relative;
        }

        .gallery-main-container .owl-container {
            position: relative;
        }

        .gallery-main-container .owl-container .owl-carousel {
            position: relative;
        }

        .gallery-main {
            position: relative;
        }

        .gallery-main .owl-stage-outer {
            position: relative;
        }

        .gallery-main .owl-stage {
            position: relative;
        }

        .gallery-main .gallery-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .gallery-main .gallery-item img {
            border-radius: 8px;
        }

        .gallery-thumbnails-wrapper {
            margin-top: 15px;
        }

        .gallery-thumbnails .gallery-thumbnail-item {
            cursor: pointer;
            padding: 3px;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            opacity: 0.6;
            margin: 0 5px;
        }

        .gallery-thumbnails .gallery-thumbnail-item:hover {
            opacity: 1;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .gallery-thumbnails .gallery-thumbnail-item.active {
            opacity: 1;
            border-color: #28a745;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .gallery-thumbnails .gallery-thumbnail-item img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            transition: transform 0.3s ease;
        }

        .gallery-thumbnails .gallery-thumbnail-item:hover img {
            transform: scale(1.05);
        }

        .gallery-description {
            padding: 0 10px;
        }

        .gallery-main-container .owl-container .owl-nav,
        .gallery-main-container #galleryMain .owl-nav,
        .gallery-main .owl-nav,
        .gallery-main-container .owl-theme .owl-nav {
            position: absolute !important;
            top: 50% !important;
            left: 0 !important;
            right: 0 !important;
            transform: translateY(-50%) !important;
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
            padding: 0 20px !important;
            pointer-events: none !important;
            z-index: 10 !important;
            margin: 0 !important;
            margin-top: 0 !important;
            height: 0 !important;
            bottom: auto !important;
        }

        .gallery-main-container .owl-container .owl-nav button,
        .gallery-main-container #galleryMain .owl-nav button,
        .gallery-main .owl-nav button,
        .gallery-main-container .owl-theme .owl-nav [class*=owl-] {
            background: rgba(255, 255, 255, 0.9) !important;
            border: none !important;
            width: 45px !important;
            height: 45px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s ease !important;
            pointer-events: all !important;
            color: #333 !important;
            font-size: 18px !important;
            margin: 0 !important;
            margin-top: 0 !important;
            position: absolute !important;
            top: auto !important;
            bottom: auto !important;
        }

        .gallery-main-container .owl-container .owl-nav button.owl-prev,
        .gallery-main-container #galleryMain .owl-nav button.owl-prev,
        .gallery-main .owl-nav button.owl-prev,
        .gallery-main-container .owl-theme .owl-nav .owl-prev {
            left: 20px !important;
            right: auto !important;
        }

        .gallery-main-container .owl-container .owl-nav button.owl-next,
        .gallery-main-container #galleryMain .owl-nav button.owl-next,
        .gallery-main .owl-nav button.owl-next,
        .gallery-main-container .owl-theme .owl-nav .owl-next {
            right: 20px !important;
            left: auto !important;
        }

        .gallery-main-container .owl-container .owl-nav button:hover,
        .gallery-main .owl-nav button:hover {
            background: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
            transform: scale(1.1) !important;
        }

        .gallery-thumbnails .owl-nav {
            display: none;
        }

        .gallery-thumbnails .owl-dots {
            text-align: center;
            margin-top: 10px;
        }

        #galleryMain+.owl-nav,
        .gallery-main-container .owl-carousel+.owl-nav {
            position: absolute !important;
            top: 50% !important;
            left: 0 !important;
            right: 0 !important;
            transform: translateY(-50%) !important;
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
            padding: 0 20px !important;
            pointer-events: none !important;
            z-index: 10 !important;
            margin: 0 !important;
            margin-top: 0 !important;
            height: 0 !important;
            bottom: auto !important;
        }

        #galleryMain+.owl-nav button,
        .gallery-main-container .owl-carousel+.owl-nav button {
            background: rgba(255, 255, 255, 0.9) !important;
            border: none !important;
            width: 45px !important;
            height: 45px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s ease !important;
            pointer-events: all !important;
            color: #333 !important;
            font-size: 18px !important;
            margin: 0 !important;
            margin-top: 0 !important;
            position: absolute !important;
            top: auto !important;
            bottom: auto !important;
        }

        #galleryMain+.owl-nav button.owl-prev,
        .gallery-main-container .owl-carousel+.owl-nav button.owl-prev {
            left: 20px !important;
            right: auto !important;
        }

        #galleryMain+.owl-nav button.owl-next,
        .gallery-main-container .owl-carousel+.owl-nav button.owl-next {
            right: 20px !important;
            left: auto !important;
        }

        .article-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .article-title {
            color: #333;
            font-weight: 600;
        }

        .article-meta {
            font-size: 14px;
        }

        .row.justify-content-center .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .row.justify-content-center .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .row.justify-content-center .card-img-top {
            border-radius: 8px 8px 0 0;
        }

        .row.justify-content-center .card-body {
            padding: 15px;
        }

        .badge-sm {
            font-size: 11px;
            padding: 4px 8px;
        }

        @media (max-width: 768px) {
            .gallery-thumbnails .gallery-thumbnail-item img {
                height: 60px;
            }

            .gallery-main .owl-nav button {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const galleryDescriptions = @json($infografik->hasGallery->pluck('deskripsi')->toArray());
            const mainCarousel = $('#galleryMain');
            const thumbnailsCarousel = $('#galleryThumbnails');
            let isMainCarouselChanging = false;
            let isThumbnailCarouselChanging = false;

            if (mainCarousel.length === 0 || thumbnailsCarousel.length === 0) return;

            mainCarousel.owlCarousel({
                items: 1,
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                autoplay: false,
                navText: [
                    '<i class="fa fa-chevron-left"></i>',
                    '<i class="fa fa-chevron-right"></i>'
                ],
                onInitialized: function() {
                    const nav = mainCarousel.find('.owl-nav');
                    if (nav.length) {
                        nav.css({
                            'position': 'absolute',
                            'top': '50%',
                            'left': '0',
                            'right': '0',
                            'transform': 'translateY(-50%)',
                            'width': '100%',
                            'margin': '0',
                            'margin-top': '0',
                            'height': '0',
                            'z-index': '10'
                        });

                        const prevBtn = nav.find('.owl-prev');
                        const nextBtn = nav.find('.owl-next');
                        if (prevBtn.length) {
                            prevBtn.css({
                                'position': 'absolute',
                                'left': '20px',
                                'right': 'auto'
                            });
                        }
                        if (nextBtn.length) {
                            nextBtn.css({
                                'position': 'absolute',
                                'right': '20px',
                                'left': 'auto'
                            });
                        }
                    }
                }
            });

            thumbnailsCarousel.owlCarousel({
                items: 5,
                loop: false,
                margin: 10,
                nav: false,
                dots: true,
                responsive: {
                    0: {
                        items: 3
                    },
                    600: {
                        items: 4
                    },
                    1000: {
                        items: 5
                    }
                }
            });

            mainCarousel.on('changed.owl.carousel', function(event) {
                if (isThumbnailCarouselChanging) return;

                isMainCarouselChanging = true;
                const currentIndex = event.item.index;
                const itemCount = event.item.count || $('.gallery-item').length;
                let realIndex = currentIndex;

                if (itemCount > 0) {
                    if (realIndex >= itemCount) {
                        realIndex = realIndex % itemCount;
                    }
                    if (realIndex < 0) {
                        realIndex = itemCount + realIndex;
                    }
                }

                if (realIndex < 0) realIndex = 0;
                if (realIndex >= itemCount) realIndex = itemCount - 1;

                updateThumbnailActive(realIndex);
                updateDescription(realIndex);

                setTimeout(function() {
                    isMainCarouselChanging = false;
                }, 100);
            });

            function updateThumbnailActive(index) {
                $('.gallery-thumbnail-item').removeClass('active');
                const thumbnailItem = $('.gallery-thumbnail-item').eq(index);

                if (thumbnailItem.length > 0) {
                    thumbnailItem.addClass('active');

                    if (thumbnailsCarousel.data('owl.carousel')) {
                        const carousel = thumbnailsCarousel.data('owl.carousel');
                        const currentIndex = carousel.current();

                        if (index !== currentIndex && index >= 0) {
                            thumbnailsCarousel.trigger('to.owl.carousel', [index, 300]);
                        }
                    }
                }
            }

            function updateDescription(index) {
                if (galleryDescriptions && galleryDescriptions[index] !== undefined) {
                    $('#mainGalleryDescription').text(galleryDescriptions[index] || '');
                }
            }

            $(document).on('click', '.gallery-thumbnail-item', function() {
                if (isMainCarouselChanging) return;

                isThumbnailCarouselChanging = true;
                const index = $(this).data('index');

                if (index !== undefined && index >= 0) {
                    mainCarousel.trigger('to.owl.carousel', [index, 300]);
                }

                setTimeout(function() {
                    isThumbnailCarouselChanging = false;
                }, 300);
            });

            updateThumbnailActive(0);
            updateDescription(0);
        });
    </script>
@endpush
