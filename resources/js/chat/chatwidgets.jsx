import React, { useState } from "react";
import ChatList from "./chatlist";
import ChatWindow from "./chatwindow";
import "./chat.css";

export default function ChatWidget() {
    const [open, setOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState(null);

    return (
        <>
            {/* Floating Button */}
            {!open && (
                <div className="chat-toggle-btn" onClick={() => setOpen(true)}>
                    💬
                </div>
            )}

            {/* Chat Window */}
            {open && (
                <div className="chat-wrapper">
                    {selectedUser ? (
                        <ChatWindow
                            selectedUser={selectedUser}
                            onBack={() => setSelectedUser(null)}
                            onClose={() => setOpen(false)}
                        />
                    ) : (
                        <ChatList
                            onSelect={(u) => setSelectedUser(u)}
                            onClose={() => setOpen(false)}
                        />
                    )}
                </div>
            )}
        </>
    );
}
