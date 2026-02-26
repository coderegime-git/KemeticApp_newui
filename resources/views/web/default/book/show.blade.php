@extends('web.default.layouts.app')

@section('content')
<form method="post" id="bookAddToCartForm">
        {{ csrf_field() }}
  <div class="bookdetail-page">
    <main class="bookdetail-layout">

      
        <input type="hidden" name="item_id" value="{{ $book->id }}">
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
            <button type="button" class="bookdetail-btn bookdetail-btn-gold" onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                ▶ Preview
            </button>
            <button type="button" class="bookdetail-btn bookdetail-btn-ghost" onclick="readOnline('{{ url($book->url) }}')">
                📖 Read Description
                </button>
            @elseif($hasBought && $book->type === 'E-book' || $book->type === 'digital')
                <button type="button" class="bookdetail-btn bookdetail-btn-gold" onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                    📖 Preview PDF
                </button>
                <button type="button" class="bookdetail-btn bookdetail-btn-ghost" onclick="readOnline('{{ url($book->url) }}')">
                    🌐 Read Online
                </button>
            @elseif($hasBought && $book->type === 'Print' || $book->type === 'physical')
                <button type="button" class="bookdetail-btn bookdetail-btn-gold" onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                    📖 Preview PDF
                </button>
                <button class="bookdetail-btn bookdetail-btn-ghost" onclick="showPrintDetails()">
                    📦 Shipping Info
                </button>
          @endif
        </div>

        <div class="bookdetail-mini-actions">
          <button class="bookdetail-like">❤️ {{ number_format($likeCount) }}</button>
          <button class="bookdetail-save">🔖 {{ number_format($savedCount) }}</button>
          <button>📤 {{ number_format($shareCount) }}</button>
        </div>
      </section>

      <!-- MIDDLE: info & description -->
      <section class="bookdetail-panel">
        <h1 class="bookdetail-title">{{ $book->title }}</h1>
        <div class="bookdetail-subtitle">
          By {{ $book->creator->full_name ?? 'Unknown Author' }} • Kemetic App Library
        </div>

        <div class="bookdetail-rating-row">
          <div class="bookdetail-chakra-stars">
            <span style="color:var(--chakra-red)">★</span>
            <span style="color:var(--chakra-orange)">★</span>
            <span style="color:var(--chakra-yellow)">★</span>
            <span style="color:var(--chakra-green)">★</span>
            <span style="color:var(--chakra-blue)">★</span>
          </div>
          <div class="bookdetail-stat-text">{{ $ratingDisplay }} • {{ number_format($totalEngagement) }}+ engagements</div>
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

        <!-- Comments Section -->
        @if($book->comments && $book->comments->count() > 0)
        <div class="bookdetail-section-title">Community Comments ({{ $commentCount }})</div>
        <div class="bookdetail-description">
          @foreach($book->comments->take(3) as $comment)
            <div style="margin-bottom: 12px; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                @if($comment->user->avatar)
                  <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->full_name }}" style="width: 24px; height: 24px; border-radius: 50%;">
                @endif
                <strong>{{ $comment->user->full_name }}</strong>
              </div>
              <div>{{ $comment->comment }}</div>
            </div>
          @endforeach
        </div>
        @endif
      </section>

      <!-- RIGHT: purchase & membership -->
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
            <button class="bookdetail-btn bookdetail-btn-gold bookdetail-btn-lg">
              Download Free Scrolls
            </button>
          @else
           @if($hasBought or $book->price == 0 or $activeSubscribe and $book->type != 'Print')
            <button class="bookdetail-btn bookdetail-btn-gold bookdetail-btn-lg" type="button" 
                data-book-type="{{ $book->type }}" data-book-title="{{ $book->title }}" onclick="previewPdf('{{ url($book->url) ?? '#' }}')">
                  Download
              </button>
            @else
            <button class="bookdetail-btn bookdetail-btn-gold bookdetail-btn-lg js-book-direct-payment" type="button" 
            data-book-type="{{ $book->type }}" data-book-title="{{ $book->title }}">
              Buy Scrolls Only - €{{ $formattedPrice }}
            </button>
            @endif
            <!-- @if(auth()->check())
              <button class="bookdetail-btn bookdetail-btn-outline bookdetail-btn-lg"><a href="/membership">Unlock with Membership</a></button>
            @else
              <button class="bookdetail-btn bookdetail-btn-outline bookdetail-btn-lg"><a href="/membership"><a href="/login">Become a Member</a></button>
            @endif -->
          @endif
        </div>

        <!-- <div class="bookdetail-pay-icons">
          <span class="bookdetail-pay-pill">Apple Pay</span>
          <span class="bookdetail-pay-pill">Google Pay</span>
          <span class="bookdetail-pay-pill">Card</span>
        </div> -->

        <div class="bookdetail-membership-card">
          <div class="bookdetail-membership-header">
            <div class="bookdetail-badge-star">★</div>
            <div>
              <div style="font-weight:800;font-size:13px;">Kemetic Membership</div>
              <div style="font-size:12px;">€1 / month • €10 / year • €33 lifetime</div>
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

  <div id="ebookConfirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--panel, #1a1a1a); padding: 30px; border-radius: 16px; max-width: 500px; width: 90%; border: 2px solid var(--chakra-gold, #FFD700);">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="font-size: 48px; margin-bottom: 15px;">📚</div>
            <h2 style="color: var(--chakra-gold, #FFD700); margin-bottom: 10px;">E-Book Purchase</h2>
            <p id="confirmationMessage" style="color: #ccc; line-height: 1.6; margin-bottom: 25px;">
                This is an e-book in PDF format. After purchase, you can download it immediately.
            </p>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center;">
            <button id="confirmCancel" 
                    style="padding: 12px 30px; background: transparent; border: 2px solid #666; color: #ccc; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
                Cancel
            </button>
            <button id="confirmProceed" 
                    style="padding: 12px 30px; background: var(--chakra-gold, #FFD700); border: none; color: #000; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
                Continue Purchase
            </button>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Fallback to CDN if local files don't work -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<script>
   $(document).ready(function() {
    
    let currentButton = null;
    let currentForm = null;

    $('body').on('click', '.js-book-direct-payment', function (e) {
      
      const $this = $(this);
      const bookType = $this.data('book-type');
      const bookTitle = $this.data('book-title');

      currentButton = $this;
      currentForm = $this.closest('form');
      
      // Check if it's e-book or audio book
      const isEbook = ['E-book', 'digital', 'e-book'].includes(bookType);
      const isAudioBook = ['Audio Book', 'audio', 'Audio'].includes(bookType);
      
      if (isEbook || isAudioBook) {
          // Show confirmation modal
          const message = isEbook 
              ? `"${bookTitle}" is an e-book in PDF format. After purchase, you can download it immediately.`
              : `"${bookTitle}" is an audio book. After purchase, you can download the audio files immediately.`;
          
          $('#confirmationMessage').text(message);
          $('#ebookConfirmationModal').css('display', 'flex');
      } else {
          // For physical books or other types, proceed directly
          proceedWithPurchase($this, currentForm);
      }

      // $this.addClass('loadingbar danger').prop('disabled', true);
      // alert('Processing your purchase. Please wait...');
      // const $form = $this.closest('form');
      // $form.attr('action', '/book/direct-payment');
      // $form.trigger('submit');
    });

    $('#confirmProceed').click(function() {
        $('#ebookConfirmationModal').hide();
        if (currentButton && currentForm) {
            proceedWithPurchase(currentButton, currentForm);
        }
    });
    
    // Handle cancel
    $('#confirmCancel').click(function() {
        $('#ebookConfirmationModal').hide();
        currentButton = null;
        currentForm = null;
    });
    
    // Also close modal when clicking outside
    $('#ebookConfirmationModal').click(function(e) {
        if (e.target === this) {
            $(this).hide();
            currentButton = null;
            currentForm = null;
        }
    });
    
    function proceedWithPurchase($button, $form) {
        $button.addClass('loadingbar danger').prop('disabled', true);
        
        // Show processing message
        $.toast({
            heading: 'Processing',
            text: 'Processing your purchase. Please wait...',
            bgColor: '#FFD700',
            textColor: '#000',
            hideAfter: 3000,
            position: 'bottom-right',
            icon: 'info'
        });
        
        // Submit the form
        $form.attr('action', '/book/direct-payment');
        $form.trigger('submit');
    }
  });

   @if(session()->has('toast'))
    (function() {
        const toastData = @json(session()->get('toast'));
        $.toast({
            heading: toastData.title || '',
            text: toastData.msg || '',
            bgColor: toastData.status === 'success' ? '#43d477' : '#f63c3c',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: toastData.status
        });
    })();
    @endif
</script>
<script>
  
// Audio Book Functions
function playAudioSample(audioUrl) {
    if (audioUrl === '#') {
        alert('Audio sample not available yet.');
        return;
    }
    // Implement audio player logic here
    console.log('Playing audio sample:', audioUrl);
    // You can use: new Audio(audioUrl).play();
    alert('Playing audio sample: ' + audioUrl);
}

function showAudioDetails() {
    alert('Audio Scrolls Details:\n• Total Duration: 6 hours 25 min\n• Format: MP3\n• Chapters: 24\n• File Size: 350MB');
}

// E-book/PDF Functions
function previewPdf(pdfUrl) {
    if (pdfUrl === '#') {
        alert('PDF preview not available yet.');
        return;
    }
    // Open PDF in new tab or modal
    window.open(pdfUrl, '_blank');
}

function readOnline(bookUrl) {
    // Navigate to online reading page
    window.location.href = bookUrl + '/read';
}

// Print Book Functions
function showPrintDetails() {
    alert('Print Book Details:\n• Hardcover: 420 pages\n• Paper Quality: Premium\n• Shipping: 3-5 business days\n• Free PDF included');
}

// You can also add more sophisticated modals instead of alerts
function showPreviewModal(contentUrl, type) {
    // Implement a modal for preview
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    `;
    
    modal.innerHTML = `
        <div style="background: var(--panel); padding: 20px; border-radius: 12px; max-width: 90%; max-height: 90%; overflow: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Scrolls Preview</h3>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">×</button>
            </div>
            ${type === 'pdf' ? 
                `<iframe src="${contentUrl}" width="800" height="500" style="border: none;"></iframe>` :
                `<audio controls style="width: 400px;">
                    <source src="${contentUrl}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>`
            }
        </div>
    `;
    
    document.body.appendChild(modal);
}
</script>

  <script>
function downloadSample(pdfUrl) {
    if (pdfUrl === '#') {
        alert('Sample PDF not available yet.');
        return;
    }
    // Trigger download
    const link = document.createElement('a');
    link.href = pdfUrl;
    link.download = 'book-sample.pdf';
    link.click();
}

function showPhysicalDetails() {
    alert('Physical Scrolls Details:\n• Format: Hardcover\n• Pages: 420\n• Dimensions: 9x6 inches\n• Weight: 1.2 kg\n• Free PDF included with purchase');
}
</script>