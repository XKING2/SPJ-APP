import React from "react";

export default function ChatList({ onSelect, onClose }) {
    const dummyUsers = [
        { id: 1, name: "Admin" },
        { id: 2, name: "Kasubag" },
        { id: 3, name: "User A" },
    ];

    return (
        <>
            <div className="chat-header">
                <div className="avatar">C</div>
                <div style={{ fontWeight: "bold", flex: 1 }}>Daftar Chat</div>
                <button onClick={onClose} style={{ color: "white", background: "transparent", border: "none" }}>✖</button>
            </div>

            <div className="chat-messages" style={{ background: "#fff" }}>
                {dummyUsers.map(u => (
                    <div
                        key={u.id}
                        onClick={() => onSelect(u)}
                        style={{
                            padding: "12px",
                            borderBottom: "1px solid #eee",
                            cursor: "pointer",
                            display: "flex",
                            alignItems: "center",
                            gap: "10px"
                        }}
                    >
                        <div className="avatar">{u.name[0]}</div>
                        <div>{u.name}</div>
                    </div>
                ))}
            </div>
        </>
    );
}
