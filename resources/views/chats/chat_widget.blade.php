<div id="chat-toggle-btn">
    <i class="fas fa-comments"></i>
    <span id="chat-notif-badge" class="notif-badge">0</span>
</div>

<div id="chat-widget">
    
    <!-- ========================
         MODE: CONTACT LIST
    ========================= -->
    <div id="contact-list-section" class="chat-section">
        <div class="chat-header">
            <span>Pilih Kontak</span>
            <button id="close-chat">&times;</button>
        </div>

        <div class="contact-list" id="contact-list"></div>
    </div>

    <!-- ========================
         MODE: CHAT ROOM
    ========================= -->
    <div id="chat-room-section" class="chat-section" style="display: none;">
        <div class="chat-room-header">
            <button id="back-to-contacts" class="back-btn"><i class="fas fa-arrow-left"></i></button>

            <img src="{{asset('img/undraw_profile.svg') }}"class="chat-avatar" id="chat-avatar">

            <div class="chat-room-title">
                <span id="chat-username">Nama</span>
                <small id="chat-role">Role</small>
            </div>

            <button id="close-chat-2" class="close-btn">&times;</button>
        </div>

        <div class="chat-messages" id="chat-messages"></div>

        <div class="chat-input-area">
            <input type="text" id="chat-message-input" placeholder="Tulis pesan...">
            <button id="chat-send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

</div>
