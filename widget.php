<!-- Chat Widget HTML -->
    <button class="ai-widget-btn" id="aiWidgetButton" aria-label="Open chat">
        <svg viewBox="0 0 24 24">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
        </svg>
        <span class="ai-unread-badge" id="aiUnreadBadge" style="display: none;">1</span>
    </button>

    <div class="ai-chat-window" id="aiChatWindow" aria-hidden="true" role="dialog" aria-label="Chat window">
        <div class="ai-chat-header" id="aiChatHeader">
            <h3>
                <span class="online-indicator"></span>
                AI Assistant
            </h3>
            <div class="ai-header-actions">
                <button id="aiMinimizeBtn" aria-label="Minimize">−</button>
                <button id="aiCloseBtn" aria-label="Close">×</button>
            </div>
        </div>
        
        <div class="ai-chat-messages" id="aiChatMessages" role="list">
            <!-- Welcome message will be added by JavaScript -->
        </div>
        
        <div class="ai-quick-actions" id="aiQuickActions">
            <button class="ai-quick-action" data-message="Hello!">👋 Hello</button>
            <button class="ai-quick-action" data-message="What can you do?">🤔 Help</button>
            <button class="ai-quick-action" data-message="Contact support">📧 Support</button>
            <button class="ai-quick-action" data-message="Pricing">💰 Pricing</button>
        </div>
        
        <div class="ai-chat-input-area">
            <input type="text" class="ai-user-input" id="aiUserInput" 
                   placeholder="Type your message..." 
                   aria-label="Type your message"
                   maxlength="500">
            <button class="ai-send-btn" id="aiSendBtn" aria-label="Send message">
                <svg viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>
