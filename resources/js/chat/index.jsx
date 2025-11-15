import React from "react";
import { createRoot } from "react-dom/client";
import ChatWidget from "./chatwidgets.jsx";

const root = document.getElementById("chat-root");

if (root) {
    createRoot(root).render(<ChatWidget />);
}
