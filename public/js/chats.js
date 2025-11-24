document.addEventListener("DOMContentLoaded", () => {

    const widget = document.getElementById("chat-widget");
    const btnToggle = document.getElementById("chat-toggle-btn");
    const btnClose = document.getElementById("close-chat");
    const btnClose2 = document.getElementById("close-chat-2");
    const backBtn = document.getElementById("back-to-contacts");
    const notifBadge = document.getElementById("chat-notif-badge");

    const contactListSection = document.getElementById("contact-list-section");
    const chatRoomSection = document.getElementById("chat-room-section");

    let currentChatId = null;
    let unreadCount = 0;

    // =====================
    // UPDATE BADGE
    // =====================
    function updateBadge(count) {
        if (count > 0) {
            notifBadge.innerText = count;
            notifBadge.style.display = "flex";
        } else {
            notifBadge.style.display = "none";
        }
    }

    // =====================
    // WIDGET TOGGLE
    // =====================
    btnToggle.onclick = () => {
        widget.style.display = "flex";
        unreadCount = 0;
        updateBadge(unreadCount);
    };

    btnClose.onclick = () => widget.style.display = "none";
    btnClose2.onclick = () => widget.style.display = "none";
    backBtn.onclick = () => {
        chatRoomSection.style.display = "none";
        contactListSection.style.display = "block";
        loadContacts();
    };

    // =====================
    // Safe JSON Parser
    // =====================
    async function safeJson(res) {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error("Server returned HTML instead of JSON:", text);
            throw new Error("Invalid JSON response");
        }
    }

    // =====================
    // Load Contacts
    // =====================
    function loadContacts() {
        fetch("/chat/contacts")
            .then(safeJson)
            .then(contacts => {
                const list = document.getElementById("contact-list");
                list.innerHTML = "";

                if (!Array.isArray(contacts) || contacts.length === 0) {
                    list.innerHTML = "<div class='no-contact'>Tidak ada kontak.</div>";
                    return;
                }

                contacts.forEach(user => {
                    list.innerHTML += `
                        <div class="contact-item" onclick="openChat(${user.id})">
                            <div class="contact-left">
                                <img src="${user.avatar ?? '/img/undraw_profile.svg'}" class="contact-avatar">
                                <div class="contact-info">
                                    <div class="contact-name-role">
                                        <span>${user.nama}</span>
                                        <span class="contact-role">${user.role}</span>
                                    </div>
                                    <div class="contact-last-message">${user.last_message ?? '-'}</div>
                                </div>
                            </div>
                            ${user.unread > 0 ? `<span class="unread-badge">${user.unread}</span>` : ""}
                        </div>
                    `;
                });
            })
            .catch(err => console.error("CONTACT ERROR:", err));
    }

    loadContacts();

    // =====================
    // OPEN CHAT ROOM
    // =====================
    window.openChat = function(userId) {
        if (!userId) return console.error("openChat() error: userId undefined");

        currentChatId = userId;

        fetch(`/chat/messages/${userId}`)
            .then(safeJson)
            .then(messages => {

                contactListSection.style.display = "none";
                chatRoomSection.style.display = "block";

                // Load contact header
                fetch("/chat/contacts")
                    .then(safeJson)
                    .then(list => {
                        const contact = list.find(u => u.id == userId);
                        if (contact) {
                            document.getElementById("chat-username").innerText = contact.nama;
                            document.getElementById("chat-role").innerText = contact.role;
                        }
                    });

                const messagesBox = document.getElementById("chat-messages");
                messagesBox.innerHTML = "";

                messages.forEach(m => {
                    const isMe = (m.sender_id == window.currentUserId);
                    messagesBox.innerHTML += `
                        <div class="msg-bubble ${isMe ? "msg-me" : "msg-other"}">
                            ${m.message}
                        </div>
                    `;
                });

                messagesBox.scrollTop = messagesBox.scrollHeight;

                // Mark as read
                fetch(`/chat/messages/read/${userId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
                    }
                });
            })
            .catch(err => console.error("CHAT LOAD ERROR:", err));
    };

    // =====================
    // SEND MESSAGE
    // =====================
    document.getElementById("chat-send-btn").onclick = () => {
        const input = document.getElementById("chat-message-input");
        const msg = input.value.trim();
        if (!msg || !currentChatId) return;

        fetch("/chat/messages/send", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                receiver_id: currentChatId,
                message: msg
            })
        })
        .then(safeJson)
        .then(sent => {
            const messagesBox = document.getElementById("chat-messages");
            messagesBox.innerHTML += `<div class="msg-bubble msg-me">${msg}</div>`;
            input.value = "";
            messagesBox.scrollTop = messagesBox.scrollHeight;
            loadContacts();
        })
        .catch(err => console.error("SEND ERROR:", err));
    };

    // =============================
    // DRAGGABLE ELEMENT FUNCTION
    // =============================
    function makeDraggable(element) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

        element.addEventListener("mousedown", dragMouseDown);

        function dragMouseDown(e) {
            e = e || window.event;
            const blockedTags = ["INPUT", "TEXTAREA", "BUTTON"];
            if (blockedTags.includes(e.target.tagName)) return;
            if (e.target.classList.contains("msg-bubble")) return;
            if (e.target.closest("button")) return;

            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;

            element.style.top = (element.offsetTop - pos2) + "px";
            element.style.left = (element.offsetLeft - pos1) + "px";
            element.style.bottom = "auto";
            element.style.right = "auto";
            element.style.position = "fixed";
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

    // Aktifkan drag
    makeDraggable(document.getElementById("chat-widget"));
    makeDraggable(document.querySelector(".chat-header"));
    makeDraggable(document.querySelector(".chat-room-header"));
    makeDraggable(document.getElementById("chat-toggle-btn"));

    // =====================
    // REALTIME LISTENER
    // =====================
    if (window.Echo) {
        const userId = window.currentUserId;

        Echo.private(`chat.${userId}`)
            .listen('.MessageSent', (e) => {
                const isMe = e.sender.id == userId;
                const messagesBox = document.getElementById("chat-messages");
                const bubbleClass = isMe ? "msg-me" : "msg-other";

                if (currentChatId == e.sender.id || currentChatId == e.receiver_id) {
                    messagesBox.innerHTML += `<div class="msg-bubble ${bubbleClass}">${e.message}</div>`;
                    messagesBox.scrollTop = messagesBox.scrollHeight;
                    loadContacts();
                } else {
                    unreadCount++;
                    updateBadge(unreadCount);
                }
            });
    }
});
