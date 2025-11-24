import React from "react";
import { createRoot } from "react-dom/client";
import ChatWidget from "./chat/ChatWidget.jsx";

const el = document.getElementById("chat-root");
if (el) {
    createRoot(el).render(<ChatWidget />);
}