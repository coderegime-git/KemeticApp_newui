@extends('web.default.layouts.app')

@section('content')

{{-- ===== CDN Dependencies (loaded BEFORE any scripts that depend on them) ===== --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.css">

<form method="post" id="bookAddToCartForm">
    {{ csrf_field() }}

    <div class="bookdetail-page">
        <main class="bookdetail-layout">

            <input type="hidden" name="item_id" value="{{ $book->id }}">

            {{-- ===== LEFT: Cover & Media Panel ===== --}}
            <section class="bookdetail-panel bookdetail-media-panel">
                <div class="bookdetail-media-cover">
                    <img src="{{ $book->getImage() }}" alt="{{ $book->title }}" />
                </div>

                <div class="bookdetail-media-badges">
                    @if($book->is_free)
                        <div class="bookdetail-pill bookdetail-gold">Free Scrolls</div>
                    @else
                        <div class="bookdetail-pill bookdetail-gold">Included with Membership</div>
                    @endif
                    <div class="bookdetail-pill">PDF • Digital Format</div>
                </div>

                <div class="bookdetail-media-actions">
                    @if($hasBought && $book->type === 'Audio Book')
                        <button type="button" class="bookdetail-btn bookdetail-btn-gold"
                            onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                            ▶ Preview
                        </button>
                        <button type="button" class="bookdetail-btn bookdetail-btn-ghost"
                            onclick="readOnline('{{ url($book->url) }}')">
                            📖 Read Description
                        </button>
                    @elseif($hasBought && ($book->type === 'E-book' || $book->type === 'digital'))
                        <button type="button" class="bookdetail-btn bookdetail-btn-gold"
                            onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                            📖 Preview PDF
                        </button>
                        <button type="button" class="bookdetail-btn bookdetail-btn-ghost"
                            onclick="readOnline('{{ url($book->url) }}')">
                            🌐 Read Online
                        </button>
                    @elseif($hasBought && ($book->type === 'Print' || $book->type === 'physical'))
                        <button type="button" class="bookdetail-btn bookdetail-btn-gold"
                            onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                            📖 Preview PDF
                        </button>
                        <button type="button" class="bookdetail-btn bookdetail-btn-ghost"
                            onclick="showPrintDetails()">
                            📦 Shipping Info
                        </button>
                    @endif
                </div>
            </section>

            {{-- ===== MIDDLE: Info & Description Panel ===== --}}
            <section class="bookdetail-panel">
                <h1 class="bookdetail-title">{{ $book->title }}</h1>
                <div class="bookdetail-subtitle">
                    By {{ $book->creator->full_name ?? 'Unknown Author' }} • Kemetic App Library
                </div>

                <div class="bookdetail-rating-row">
                    <div class="bookdetail-chakra-stars">
                        @php
                            $rate = $book->getRate();
                            $i = 5;
                        @endphp
                        @while(--$i >= 5 - $rate)
                            ★
                        @endwhile
                        @while($i-- >= 0)
                            ☆
                        @endwhile
                    </div>
                    <div class="bookdetail-stat-text">
                        {{ $book->reviews->count() }} • {{ number_format($totalEngagement) }}+ engagements
                    </div>
                </div>

                <div class="bookdetail-tag-row">
                    @if($book->categories)
                        <div class="bookdetail-tag">{{ $book->categories->title }}</div>
                    @endif
                </div>

                <div class="bookdetail-section-title">About this Scrolls</div>
                <p class="bookdetail-description">
                    {!! nl2br($book->description) !!}
                </p>

                @if($book->content)
                    <div class="bookdetail-section-title">What you'll discover</div>
                    <div class="bookdetail-description">
                        {!! nl2br($book->content) !!}
                    </div>
                @endif

                <div class="bookdetail-section-title">Scrolls Details</div>
                <div class="bookdetail-meta-grid">
                    <div>
                        <div class="bookdetail-meta-label">Format</div>
                        <div>
                            @if($book->type === 'Audio Book')
                                Audio Book • Audio Listening
                            @elseif($book->type === 'E-book')
                                E-book • Digital PDF
                            @elseif($book->type === 'Print')
                                Print Book • Physical Copy
                            @elseif($book->type === 'digital')
                                Digital PDF • Online Reading
                            @elseif($book->type === 'physical')
                                Hardcover • Physical Book
                            @else
                                Digital PDF • Online Reading
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="bookdetail-meta-label">Language</div>
                        <div>
                            @php
                                $languages = [
                                    'en' => 'English',
                                    'es' => 'Spanish',
                                    'fr' => 'French',
                                    'de' => 'German',
                                    'it' => 'Italian',
                                    'pt' => 'Portuguese',
                                    'ru' => 'Russian',
                                    'zh' => 'Chinese',
                                    'ja' => 'Japanese',
                                    'ar' => 'Arabic',
                                    'hi' => 'Hindi',
                                ];
                            @endphp
                            {{ $languages[$book->language ?? 'en'] ?? 'English' }}
                        </div>
                    </div>

                    <div>
                        <div class="bookdetail-meta-label">Instant Access</div>
                        <div>
                            @if($book->type === 'Audio Book')
                                Stream Online • Download MP3
                            @elseif($book->type === 'E-book' || $book->type === 'digital')
                                Download PDF • Online Reading
                            @elseif($book->type === 'Print' || $book->type === 'physical')
                                Shipping Worldwide • Download Included
                            @else
                                Download + Library in Kemetic App
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="bookdetail-meta-label">Community</div>
                        <div>{{ number_format($commentCount) }} comments • {{ number_format($likeCount) }} likes</div>
                    </div>
                </div>

                {{-- Comments Section --}}
                @if($book->comments && $book->comments->count() > 0)
                    <div class="bookdetail-section-title">Community Comments ({{ $commentCount }})</div>
                    <div class="bookdetail-description">
                        @foreach($book->comments->take(3) as $comment)
                            <div style="margin-bottom: 12px; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                    @if($comment->user->avatar)
                                        <img src="{{ $comment->user->getAvatar(50) }}"
                                             alt="{{ $comment->user->full_name }}"
                                             style="width: 24px; height: 24px; border-radius: 50%;">
                                    @endif
                                    <strong>{{ $comment->user->full_name }}</strong>
                                </div>
                                <div>{{ $comment->comment }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ===== RIGHT: Purchase & Membership Panel ===== --}}
            <aside class="bookdetail-panel">
                <div class="bookdetail-price-row">
                    @if($book->is_free)
                        <div class="bookdetail-price-main">FREE</div>
                    @else
                        <div class="bookdetail-price-main">€{{ $formattedPrice }}</div>
                        @if($book->price > 0)
                            <div class="bookdetail-price-old">€{{ number_format($book->price * 1.5, 2) }}</div>
                        @endif
                    @endif
                </div>

                @if(!$book->is_free)
                    <div class="bookdetail-price-note">
                        Or read it free with Kemetic Membership.
                    </div>
                @endif

                <div class="bookdetail-primary-buy">
                    @if($book->is_free)
                        <button class="bookdetail-btn bookdetail-btn-gold bookdetail-btn-lg" type="button">
                            Download Free Scrolls
                        </button>
                    @else
                        @if($book->type != 'Print' && ($hasBought || $book->price == 0 || $activeSubscribe))
                            <button class="bookdetail-btn bookdetail-btn-gold bookdetail-btn-lg"
                                type="button"
                                data-book-type="{{ $book->type }}"
                                data-book-title="{{ $book->title }}"
                                onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                                Download
                            </button>
                        @else
                            <button class="bookdetail-btn bookdetail-btn-gold bookdetail-btn-lg js-book-direct-payment"
                                type="button"
                                data-book-type="{{ $book->type }}"
                                data-book-title="{{ $book->title }}">
                                Buy Scrolls Only - €{{ $formattedPrice }}
                            </button>
                        @endif
                    @endif
                </div>

                <div class="bookdetail-membership-card">
                    <div class="bookdetail-membership-header">
                        <div class="bookdetail-badge-star">★</div>
                        <div>
                            <div style="font-weight:800;font-size:13px;">Kemetic Membership</div>
                            <div style="font-size:12px;">€10 / year • €33 lifetime</div>
                        </div>
                    </div>
                    <ul>
                        <li>Unlimited access to all Kemetic courses.</li>
                        <li>All eScrolls & PDFs included while active.</li>
                        <li>Exclusive reels, livestreams & TV sessions.</li>
                        <li>Member-only discounts in the physical shop.</li>
                    </ul>
                </div>

                <div class="bookdetail-section-title" style="margin-top:18px;">Included with your purchase</div>
                <ul class="bookdetail-description">
                    <li>Immediate download of the PDF version.</li>
                    <li>Automatic unlock in your Kemetic App library.</li>
                    <li>Lifetime updates when new content is added.</li>
                </ul>

                <p class="bookdetail-small-note">
                    After payment you'll receive an email with your download link and the Scrolls will appear
                    in your in-app "Scrolls" section. If you already have a membership, the system will skip
                    charging you twice and only process the physical Scrolls or extra items in your cart.
                </p>
            </aside>

        </main>
    </div>
</form>

{{-- ===== E-Book Confirmation Modal ===== --}}
<div id="ebookConfirmationModal"
     style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--panel, #1a1a1a); padding: 30px; border-radius: 16px;
                max-width: 500px; width: 90%; border: 2px solid var(--chakra-gold, #FFD700);">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="font-size: 48px; margin-bottom: 15px;">📚</div>
            <h2 style="color: var(--chakra-gold, #FFD700); margin-bottom: 10px;">E-Book Purchase</h2>
            <p id="confirmationMessage"
               style="color: #ccc; line-height: 1.6; margin-bottom: 25px;">
                This is an e-book in PDF format. After purchase, you can download it immediately.
            </p>
        </div>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <button id="confirmCancel"
                    style="padding: 12px 30px; background: transparent; border: 2px solid #666;
                           color: #ccc; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
                Cancel
            </button>
            <button id="confirmProceed"
                    style="padding: 12px 30px; background: var(--chakra-gold, #FFD700); border: none;
                           color: #000; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
                Continue Purchase
            </button>
        </div>
    </div>
</div>

{{-- ===== Scripts ===== --}}

{{-- Load jQuery first, then toast plugin, then our code --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function () {

    let currentButton = null;
    let currentForm   = null;

    // ── Purchase button click ──────────────────────────────────────────────
    $('body').on('click', '.js-book-direct-payment', function (e) {
        e.preventDefault();

        const $this     = $(this);
        const bookType  = $this.data('book-type')  || '';
        const bookTitle = $this.data('book-title') || 'This book';

        currentButton = $this;
        currentForm   = $this.closest('form');

        const isEbook     = ['E-book', 'digital', 'e-book'].includes(bookType);
        const isAudioBook = ['Audio Book', 'audio', 'Audio'].includes(bookType);

        if (isEbook || isAudioBook) {
            // Show confirmation modal
            const message = isEbook
                ? `"${bookTitle}" is an e-book in PDF format. After purchase, you can download it immediately.`
                : `"${bookTitle}" is an audio book. After purchase, you can download the audio files immediately.`;

            $('#confirmationMessage').text(message);
            $('#ebookConfirmationModal').css('display', 'flex');
        } else {
            // Physical / other — proceed directly
            proceedWithPurchase($this, currentForm);
        }
    });

    // ── Modal: Proceed ─────────────────────────────────────────────────────
    $('#confirmProceed').on('click', function () {
        $('#ebookConfirmationModal').hide();
        if (currentButton && currentForm) {
            proceedWithPurchase(currentButton, currentForm);
        }
    });

    // ── Modal: Cancel ──────────────────────────────────────────────────────
    $('#confirmCancel').on('click', function () {
        closeModal();
    });

    // ── Modal: Click outside to close ─────────────────────────────────────
    $('#ebookConfirmationModal').on('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function closeModal() {
        $('#ebookConfirmationModal').hide();
        currentButton = null;
        currentForm   = null;
    }

    // ── Core purchase logic ────────────────────────────────────────────────
    function proceedWithPurchase($button, $form) {
        $button.addClass('loadingbar danger').prop('disabled', true);

        // Show toast notification (guarded in case plugin still fails)
        if (typeof $.toast === 'function') {
            $.toast({
                heading:  'Processing',
                text:     'Processing your purchase. Please wait...',
                bgColor:  '#FFD700',
                textColor: '#000',
                hideAfter: 3000,
                position: 'bottom-right',
                icon:     'info'
            });
        } else {
            console.info('Toast plugin not available — skipping notification.');
        }

        $form.attr('action', '/book/direct-payment');
        $form.trigger('submit');
    }

    // ── Session flash toasts ───────────────────────────────────────────────
    @if(session()->has('toast'))
    (function () {
        const toastData = @json(session()->get('toast'));

        if (typeof $.toast === 'function') {
            $.toast({
                heading:   toastData.title  || '',
                text:      toastData.msg    || '',
                bgColor:   toastData.status === 'success' ? '#43d477' : '#f63c3c',
                textColor: 'white',
                hideAfter: 10000,
                position:  'bottom-right',
                icon:      toastData.status
            });
        } else if (typeof Swal !== 'undefined') {
            // Fallback to SweetAlert2 if toast plugin is somehow unavailable
            Swal.fire({
                title: toastData.title || '',
                text:  toastData.msg   || '',
                icon:  toastData.status === 'success' ? 'success' : 'error',
                timer: 10000,
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false
            });
        }
    })();
    @endif

}); // end document.ready
</script>

<script>
// ── Media / preview helpers ────────────────────────────────────────────────

function previewPdf(pdfUrl) {
    if (!pdfUrl || pdfUrl === '#') {
        alert('PDF preview not available yet.');
        return;
    }
    window.open(pdfUrl, '_blank');
}

function readOnline(bookUrl) {
    if (!bookUrl || bookUrl === '#') {
        alert('Online reading not available yet.');
        return;
    }
    window.location.href = bookUrl + '/read';
}

function playAudioSample(audioUrl) {
    if (!audioUrl || audioUrl === '#') {
        alert('Audio sample not available yet.');
        return;
    }
    showPreviewModal(audioUrl, 'audio');
}

function showPrintDetails() {
    alert('Print Book Details:\n• Hardcover: 420 pages\n• Paper Quality: Premium\n• Shipping: 3–5 business days\n• Free PDF included');
}

function showPhysicalDetails() {
    alert('Physical Scrolls Details:\n• Format: Hardcover\n• Pages: 420\n• Dimensions: 9×6 inches\n• Weight: 1.2 kg\n• Free PDF included with purchase');
}

function showAudioDetails() {
    alert('Audio Scrolls Details:\n• Total Duration: 6 hours 25 min\n• Format: MP3\n• Chapters: 24\n• File Size: 350MB');
}

function downloadSample(pdfUrl) {
    if (!pdfUrl || pdfUrl === '#') {
        alert('Sample PDF not available yet.');
        return;
    }
    const link = document.createElement('a');
    link.href     = pdfUrl;
    link.download = 'book-sample.pdf';
    link.click();
}

// ── Generic preview modal (PDF iframe or audio player) ────────────────────
function showPreviewModal(contentUrl, type) {
    // Remove any existing preview modal first
    const existing = document.getElementById('bookPreviewModal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'bookPreviewModal';
    modal.style.cssText = [
        'position:fixed', 'top:0', 'left:0', 'width:100%', 'height:100%',
        'background:rgba(0,0,0,0.85)', 'display:flex',
        'align-items:center', 'justify-content:center', 'z-index:10000'
    ].join(';');

    modal.innerHTML = `
        <div style="background:var(--panel,#1a1a1a);padding:20px;border-radius:12px;
                    max-width:90%;max-height:90%;overflow:auto;
                    border:1px solid var(--chakra-gold,#FFD700);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                <h3 style="margin:0;color:var(--chakra-gold,#FFD700);">Scrolls Preview</h3>
                <button onclick="document.getElementById('bookPreviewModal').remove()"
                        style="background:none;border:none;color:white;font-size:24px;
                               cursor:pointer;line-height:1;">×</button>
            </div>
            ${type === 'pdf'
                ? `<iframe src="${contentUrl}" width="800" height="500"
                           style="border:none;max-width:100%;"></iframe>`
                : `<audio controls style="width:400px;max-width:100%;">
                       <source src="${contentUrl}" type="audio/mpeg">
                       Your browser does not support the audio element.
                   </audio>`
            }
        </div>`;

    // Close on backdrop click
    modal.addEventListener('click', function (e) {
        if (e.target === modal) modal.remove();
    });

    document.body.appendChild(modal);
}
</script>

@endsection