import React, { useState } from "react";
import ChatInput from "./chatinput";

export default function ChatWindow({ selectedUser, onBack, onClose }) {
    const [messages, setMessages] = useState([]);

    const sendMessage = (msg) => {
        setMessages([...messages, { fromMe: true, text: msg }]);
    };

    return (
        <>
            <div className="chat-header">
                <button onClick={onBack} style={{ background:"transparent", border:"none", color:"white" }}>
                    ←
                </button>
                <div className="avatar">{selectedUser.name[0]}</div>
                <div style={{ flex: 1, fontWeight: "bold" }}>{selectedUser.name}</div>

                <button onClick={onClose} style={{ color: "white", background: "transparent", border: "none" }}>✖</button>
            </div>

            <div className="chat-messages">
                {messages.map((m, i) => (
                    <div key={i} className={`chat-bubble ${m.fromMe ? "me" : "other"}`}>
                        {m.text}
                    </div>
                ))}
            </div>

            <ChatInput onSend={sendMessage} />
        </>
    );
}
