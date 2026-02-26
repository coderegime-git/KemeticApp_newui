@extends('web.default.layouts.newapp')

@section('content')
  

    <!-- MAIN CONTENT -->
    <main class="notifications-content">
      <header class="notifications-content-header">
        <div class="notifications-content-title-block">
          <h1>Notifications & Alerts</h1>
          <p>Choose how Kemetic App speaks to you across devices.</p>
        </div>
        
      </header>

      <input type="hidden" name="push_notifications" value="0">
        <input type="hidden" name="email_updates" value="0">
        <input type="hidden" name="sms_whatsapp" value="0">
        <input type="hidden" name="in_app_banners" value="0">
        <input type="hidden" name="reels" value="0">
        <input type="hidden" name="courses" value="0">
        <input type="hidden" name="books" value="0">
        <input type="hidden" name="live_tv" value="0">
        <input type="hidden" name="shop_orders" value="0">
        <input type="hidden" name="chat_cowatch" value="0">
        <input type="hidden" name="global_ranking" value="0">

      <section class="notifications-content-grid">
        <!-- LEFT: CHANNELS & FEATURE TOGGLES -->
        <section class="notifications-panel">
            <form id="notificationSettingsForm" method="POST">
                 @csrf
          <div class="notifications-panel-header">
            <div class="notifications-panel-header-title">
              <h2>Notification Channels</h2>
              <span>Fine-tune how you receive guidance from the app.</span>
            </div>
            <div class="notifications-chakra-pill">
              <span class="notifications-chakra-dot"></span>
              Notification Alerts
            </div>
          </div>

          <!-- Channels -->
          <div class="notifications-toggle-row">
            <div class="notifications-toggle-text">
              <strong>Push Notifications</strong>
              <span>Lock screen alerts & system banners.</span>
            </div>	
            <label class="notifications-switch">
              <input type="checkbox" name="push_notifications" value="1" {{ $settings->push_notifications ? 'checked' : '' }} />
              <span class="notifications-slider"></span>
            </label>
          </div>

          <div class="notifications-toggle-row">
            <div class="notifications-toggle-text">
              <strong>Email Updates</strong>
              <span>Summaries of courses, lives, and books.</span>
            </div>
            <label class="notifications-switch">
              <input type="checkbox" name="email_updates" value="1" {{ $settings->email_updates ? 'checked' : '' }} />
              <span class="notifications-slider"></span>
            </label>
          </div>

          <div class="notifications-toggle-row">
            <div class="notifications-toggle-text">
              <strong>SMS / WhatsApp</strong>
              <span>Only for important reminders and live events.</span>
            </div>
            <label class="notifications-switch">
               <input type="checkbox" name="sms_whatsapp" value="1" {{ $settings->sms_whatsapp ? 'checked' : '' }} />
              <span class="notifications-slider"></span>
            </label>
          </div>

          <div class="notifications-toggle-row" style="border-bottom:0; margin-bottom:4px;">
            <div class="notifications-toggle-text">
              <strong>In-App Banners</strong>
              <span>Chakra cards at the top of your screen.</span>
            </div>
            <label class="notifications-switch">
               <input type="checkbox" name="in_app_banners" value="1" {{ $settings->in_app_banners ? 'checked' : '' }} />
              <span class="notifications-slider"></span>
            </label>
          </div>

          <!-- Features -->
          <div style="margin-top: 14px;">
            <div class="notifications-panel-header-title" style="margin-bottom:8px;">
              <h2 style="font-size:14px;">Feature Notifications</h2>
              <span>Choose what you want to be notified about.</span>
            </div>

            <div class="notifications-feature-grid">

                <div class="notifications-feature-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-reels"></span>
                    Reels
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->reels ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="reels" value="1" {{ $settings->reels ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->reels ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                <div class="notifications-feature-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-courses"></span>
                    Courses
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->courses ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="courses" value="1" {{ $settings->courses ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->courses ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                <div class="notifications-feature-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-books"></span>
                    Books
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->books ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="books" value="1" {{ $settings->books ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->books ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                <div class="notifications-feature-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-live"></span>
                    Lives / TV
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->live_tv ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="live_tv" value="1" {{ $settings->live_tv ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->live_tv ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                <div class="notifications-feature-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-shop"></span>
                    Shop Orders
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->shop_orders ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="shop_orders" value="1" {{ $settings->shop_orders ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->shop_orders ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                <div class="notifications-feature-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-chat"></span>
                    Chat & Co-Watch
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->chat_cowatch ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="chat_cowatch" value="1" {{ $settings->chat_cowatch ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->chat_cowatch ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                <div class="notifications-feature-pill notifications-global-ranking-pill">
                  <span>
                    <span class="notifications-feature-dot notifications-global"></span>
                    Global Ranking Updates
                  </span>
                  <label class="notifications-feature-toggle-pill {{ $settings->global_ranking ? 'notifications-on' : 'notifications-off' }}">
                    <input type="checkbox" name="global_ranking" value="1" {{ $settings->global_ranking ? 'checked' : '' }} style="display: none;">
                    <span class="notifications-toggle-text">{{ $settings->global_ranking ? 'On' : 'Off' }}</span>
                  </label>
                </div>
                
            </div>

             <div class="notifications-mt-4">
                            <button type="submit" class="profile-follow" id="saveSettingsBtn">
                                <span class="notifications-btn-text">Save Settings</span>
                                <div class="notifications-btn-loading notifications-d-none">
                                    <span style="display:inline-block;width:16px;height:16px;border:2px solid transparent;border-top:2px solid currentColor;border-radius:50%;animation:spin 1s linear infinite;margin-right:8px;"></span>
                                    Saving...
                                </div>
                            </button>
                        </div>
                    
          </div>
          </form>
        </section>

        <!-- RIGHT: FREQUENCY & PREVIEW -->
        <section class="notifications-panel">
          <div class="notifications-panel-header">
            <div class="notifications-panel-header-title">
              <h2>{{ trans('panel.notifications') }}</h2>
              <span>Your latest updates and alerts</span>
            </div>
          </div>

          <!-- <div class="notifications-frequency-row">
            <div class="notifications-chip notifications-active">Instant</div>
            <div class="notifications-chip">Daily Digest</div>
            <div class="notifications-chip">Muted</div>
          </div> -->

          <div class="notifications-panel-header-title" style="margin:4px 0 6px;">
            <h2 style="font-size:14px;">Preview</h2>
            <a href="#" id="markAllReadBtn" class="notifications-cursor-pointer notifications-text-hover-primary" style="display:flex;align-items:center;">
              <span style="margin-left:5px;font-size:16px;">Mark all notifications as read</span>
            </a>
          </div>
            
             @if(!empty($notifications) and !$notifications->isEmpty())
                @foreach($notifications as $notification)
                    <div class="notifications-preview-card  {{ $notification->is_read ? 'notifications-read' : 'notifications-unread' }}" data-notification-id="{{ $notification->id }}">
                        <div class="notifications-preview-header">
                            @if(empty($notification->notificationStatus))
                                <div class="notifications-preview-icon">★</div>
                            @endif
                            <div class="notifications-preview-text">
                                <strong>{{ $notification->title }}</strong>
                                <span>{{ dateTimeFormat($notification->created_at,'j M Y | H:i') }}</span>
                            </div>
                        </div>
                        <div class="notifications-preview-body">
                           {!! truncate($notification->message, 150, true) !!}
                        </div>
                       
                        <div class="notifications-preview-footer">
                            <span>
                            <span data-id="{{ $notification->id }}" id="showNotificationMessage{{ $notification->id }}" class="notifications-preview-footer-pill @if(!empty($notification->notificationStatus)) notifications-seen-at @endif">Tap to open</span>
                            <input type="hidden" class="notifications-notification-message" value="{!! $notification->message !!}">
                           
                        </div>
                    </div>
                @endforeach
                <div class="my-30" style="padding: 10px;">
                  {{ $notifications->appends(request()->input())->links('vendor.pagination.panel') }}
                </div>
            @else
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'webinar.png',
                    'title' => trans('panel.notification_no_result'),
                    'hint' => nl2br(trans('panel.notification_no_result_hint')),
                ])
            @endif
        </section>
      </section>
 <!-- <div class="notifications-preview-stars">
                        <div class="notifications-star"></div>
                        <div class="notifications-star"></div>
                        <div class="notifications-star"></div>
                        <div class="notifications-star"></div>
                        <div class="notifications-star"></div>
                        <span style="margin-left:6px; font-size:12px;">5.0 • Chakra Review</span>
                        </div> -->
                         <!-- <span>Press & hold to manage</span> -->
        <!-- Notification Modal -->
        <div id="notificationModal" class="notifications-modal-overlay notifications-d-none">
        <div class="notifications-modal-content">
            <div class="notifications-modal-header">
            <h3 id="modalTitle">Notification Details</h3>
            <button id="closeModal" class="notifications-modal-close">&times;</button>
            </div>
            <div class="notifications-modal-body">
            <span id="modalTime" class="notifications-modal-time notifications-d-block notifications-font-12 notifications-text-gray notifications-mt-5"></span>
            <p id="modalMessage" class="notifications-modal-message notifications-text-gray notifications-mt-20"></p>
            </div>
        </div>
        </div>
    </main>
  @endsection

@push('scripts_bottom')
    <script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM loaded - initializing event listeners');

      // Notification Settings Toggle Handling
      document.querySelectorAll('.notifications-feature-toggle-pill input[type="checkbox"]')
        .forEach(checkbox => {

          checkbox.addEventListener('change', function () {
            const pill = this.closest('.notifications-feature-toggle-pill');
            const text = pill.querySelector('.notifications-toggle-text');

            if (this.checked) {
              pill.classList.remove('notifications-off');
              pill.classList.add('notifications-on');
              text.textContent = 'On';
            } else {
              pill.classList.remove('notifications-on');
              pill.classList.add('notifications-off');
              text.textContent = 'Off';
            }
          });

      });


      // Save Settings Form Submission - USING GET METHOD
      document.getElementById('notificationSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Save settings form submitted');
        
        const form = this;
        const submitBtn = document.getElementById('saveSettingsBtn');
        const btnText = submitBtn.querySelector('.notifications-btn-text');
        const btnLoading = submitBtn.querySelector('.notifications-btn-loading');
        
        // Show loading state
        btnText.classList.add('notifications-d-none');
        btnLoading.classList.remove('notifications-d-none');
        submitBtn.disabled = true;
        
        // Collect form data and convert to URL parameters for GET request
        const formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');
    
        // Define all possible fields with their default values
        const allFields = [
            'push_notifications', 'email_updates', 'sms_whatsapp', 'in_app_banners',
            'reels', 'courses', 'books', 'live_tv', 'shop_orders', 'chat_cowatch', 'global_ranking'
        ];
        
        // Add all fields with their current values
        allFields.forEach(field => {
            const checkbox = form.querySelector(`[name="${field}"]`);
            const value = checkbox && checkbox.checked ? '1' : '0';
            formData.append(field, value);
        });
        
        console.log('Sending POST request with form data');
        
        // Send GET request to match your route
        fetch('/panel/notifications/settings/update', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token in header
            },
            body: formData
        })
        .then(response => {
          console.log('Response status:', response.status);
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
          }
          return response.json();
        })
        .then(data => {
          console.log('Success data:', data);
          if (data.success) {
            showToast('success', data.message || 'Settings saved successfully!');
          } else {
            showToast('error', data.message || 'Failed to save settings');
          }
        })
        .catch(error => {
          console.error('Error saving settings:', error);
          showToast('error', 'An error occurred while saving settings: ' + error.message);
        })
        .finally(() => {
          // Restore button state
          btnText.classList.remove('notifications-d-none');
          btnLoading.classList.add('notifications-d-none');
          submitBtn.disabled = false;
        });
      });

      // Show notification details
      document.querySelectorAll('[id^="showNotificationMessage"]').forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          console.log('Notification clicked');

          const notificationId = this.getAttribute('data-id');
          const card = this.closest('.notifications-preview-card');
          const title = card.querySelector('.notifications-preview-text strong').textContent;
          const time = card.querySelector('.notifications-preview-text span').textContent;
          const message = card.querySelector('.notifications-notification-message').value;
          
          console.log('Notification ID:', notificationId);

          const plainTextMessage = message.replace(/<[^>]*>/g, '');
          
          // Mark as read if unread - Using GET method
          if (card.classList.contains('notifications-unread')) {
            fetch(`/panel/notifications/${notificationId}/saveStatus`, {
              method: 'GET',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              }
            })
            .then(response => {
              if (!response.ok) {
                throw new Error('Network response was not ok');
              }
              return response.json();
            })
            .then(data => {
              console.log('Mark as read response:', data);
              if (data.success) {
                // location.reload();
                card.classList.remove('notifications-unread');
                card.classList.add('notifications-read');
                console.log('Notification marked as read');
              }
            })
            .catch(error => {
              console.error('Error marking notification as read:', error);
            });
          }
          // location.reload();
          // Show modal with notification details
          document.getElementById('modalTitle').textContent = title;
          document.getElementById('modalTime').textContent = time;
          document.getElementById('modalMessage').textContent = plainTextMessage;
          document.getElementById('notificationModal').classList.remove('notifications-d-none');
        });
      });

      // Close modal
      document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('notificationModal').classList.add('notifications-d-none');
        location.reload();
      });

      // Close modal when clicking outside
      document.getElementById('notificationModal').addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.add('notifications-d-none');
        }
      });

      // Mark all as read - USING GET METHOD
      document.getElementById('markAllReadBtn').addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Mark all as read clicked');
        const btn = this;
        const originalText = btn.textContent;
        
        btn.disabled = true;
        btn.textContent = 'Marking...';
        
        console.log('Sending mark all as read request');
        
        // Send GET request to match your route
        fetch('/panel/notifications/mark-all-as-read', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        })
        .then(response => {
          console.log('Mark all read response status:', response.status);
          console.log('Content-Type:', response.headers.get('content-type'));
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
          }
          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            // If not JSON, get text and treat as success if status is 200
            return response.text().then(text => {
              console.log('Response is not JSON, text:', text.substring(0, 200));
              // Assume success if status is 200
              return { code: "200", text: "All notifications marked as read" };
            });
          }
        })
        .then(data => {
          console.log('Mark all read response data:', data);
          document.querySelectorAll('.notifications-preview-card.notifications-unread').forEach(card => {
            card.classList.remove('notifications-unread');
            card.classList.add('notifications-read');
          });
          showToast('success', 'All notifications marked as read');
          location.reload();
          // if (data.code == "200") {
          //   document.querySelectorAll('.notifications-preview-card.notifications-unread').forEach(card => {
          //     card.classList.remove('notifications-unread');
          //     card.classList.add('notifications-read');
          //   });
          //   showToast('success', data.text || 'All notifications marked as read');
          //   // Optionally remove the button after success
          //   // btn.remove();
          // } else {
          //   showToast('error', data.text || 'Failed to mark all as read');
          // }
        })
        .catch(error => {
          console.error('Error marking all as read:', error);
          document.querySelectorAll('.notifications-preview-card.notifications-unread').forEach(card => {
            card.classList.remove('notifications-unread');
            card.classList.add('notifications-read');
          });
          
          showToast('success', 'All notifications marked as read');
          location.reload();
          // showToast('error', 'An error occurred while marking notifications as read: ' + error.message);
        })
        .finally(() => {
          btn.disabled = false;
          btn.textContent = originalText;
        });
      });

      // Toast notification function
      function showToast(type, message) {
        console.log('Showing toast:', type, message);
        // Remove existing toasts
        document.querySelectorAll('.toast-message').forEach(toast => toast.remove());
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.style.cssText = `
          position: fixed;
          top: 20px;
          right: 20px;
          padding: 12px 20px;
          border-radius: 8px;
          color: white;
          font-weight: 600;
          z-index: 1001;
          box-shadow: 0 4px 12px rgba(0,0,0,0.3);
          transition: all 0.3s ease;
        `;
        
        if (type === 'success') {
          toast.style.background = 'linear-gradient(135deg, var(--chakra-green), #2ecc71)';
        } else {
          toast.style.background = 'linear-gradient(135deg, var(--chakra-red), #e74c3c)';
        }
        
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
          toast.style.opacity = '0';
          toast.style.transform = 'translateX(100%)';
          setTimeout(() => {
            if (toast.parentNode) {
              document.body.removeChild(toast);
            }
          }, 300);
        }, 3000);
      }

      // Add CSS animation for spinner
      const style = document.createElement('style');
      style.textContent = `
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    });

    // Debug function to check if elements exist
    function debugElements() {
      console.log('Form exists:', !!document.getElementById('notificationSettingsForm'));
      console.log('Save button exists:', !!document.getElementById('saveSettingsBtn'));
      console.log('Mark all button exists:', !!document.getElementById('markAllReadBtn'));
      console.log('Notification buttons:', document.querySelectorAll('.show-notification').length);
    }

    // Run debug on load
    setTimeout(debugElements, 100);

     window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
  </script>
@endpush