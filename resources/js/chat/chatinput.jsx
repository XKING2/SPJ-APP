import React, { useState } from "react";

export default function ChatInput({ onSend }) {
    const [text, setText] = useState("");

    const submit = () => {
        if (!text.trim()) return;
        onSend(text);
        setText("");
    };

    return (
        <div className="chat-input">
            <input
                type="text"
                placeholder="Ketik pesan..."
                value={text}
                onChange={(e) => setText(e.target.value)}
                onKeyDown={(e) => e.key === "Enter" && submit()}
            />
            <button onClick={submit}>➤</button>
        </div>
    );
}
