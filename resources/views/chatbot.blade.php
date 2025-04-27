<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ST Engineering Chatbot</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(to bottom right, #00205B, #1a1a2e);
            color: white;
            overflow-x: hidden;
            font-family: 'Open Sans', sans-serif;
        }

        header {
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: transparent;
            box-shadow: none; /* Remove the shadow */
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #C8102E;
        }

        nav {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 600;
            position: relative;
            transition: all 0.3s ease;
        }

        nav a::after {
            content: "";
            display: block;
            width: 0;
            height: 2px;
            background: #C8102E; /* red underline */
            transition: width 0.3s;
            margin-top: 5px;
        }

        nav a:hover::after {
            width: 100%;
        }

        /* Mobile View (smaller than 768px) */
        @media (max-width: 767px) {
            header {
                padding: 10px 20px;
            }

            .logo {
                font-size: 18px;
            }

            nav a {
                margin-left: 15px;
                font-size: 14px;
            }
        }

        /* Tablet View (768px to 1199px) */
        @media (min-width: 768px) and (max-width: 1199px) {
            header {
                padding: 15px 30px;
            }

            .logo {
                font-size: 22px;
            }

            nav a {
                margin-left: 20px;
                font-size: 16px;
            }
        }

        /* Desktop View (1200px and larger) */
        @media (min-width: 1200px) {
            .logo {
                font-size: 24px;
            }

            nav a {
                font-size: 16px;
            }
        }

        .splash-screen-container{
            display:flex;
            flex-direction: column;
            height:100vh;
            background: url('{{ asset('storage/img/skyscraper_skyline.jpg') }}') no-repeat center center/cover;
        }

        .splash-screen-container::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
            z-index: 1;
        }

        .splash-screen-container > * {
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7); /* optional, makes text pop */
        }

        .hero {
            position: relative;
            display: flex;
            flex-grow:1;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 100px 20px;
            color: white;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ffffff;
            transition: all 0.5s ease;
        }

        .hero p {
            font-size: 18px;
            max-width: 600px;
            margin-bottom: 30px;
            color: #e0e0e0;
        }

        .hero button {
            background-color: #C8102E;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 30px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        .hero button:hover {
            background-color: #a00d22;
        }

        .section {
            padding: 60px 20px;
            text-align: center;
            background-color: #f1f1f1;
            color: #333;
            opacity: 0;
            transform: translateY(100px);
            transition: all 1s ease-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 20px 0;
        }

        .section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section.hidden {
            opacity: 0;
            transform: translateY(100px);
        }

        .section h2 {
            font-size: 36px;
            color: #00205B;
            margin-bottom: 20px;
        }

        .section p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto;
        }

        .section-icon {
            font-size: 50px;
            margin-bottom: 20px;
            color: #C8102E;
            transition: all 0.3s ease;
        }

        .section:hover {
            background-color: #e6e6e6;
        }

        .section:hover .section-icon {
            transform: scale(1.2);
        }

        .section:hover h2 {
            color: #C8102E;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #001a4d;
            font-size: 14px;
            color: #ccc;
        }

        #chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        #toggle-chat {
            background-color: #C8102E;
            color: white;
            padding: 15px;
            border-radius: 50%;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        #toggle-chat:hover {
            background-color: #a00d22;
        }

        #chat-box {
            position: absolute;
            bottom: 70px;
            right: 0;
            max-height: 400px;
            background: #fff;
            color: black;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: none;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        @media (max-width: 767px) {
            #chat-box {
                min-width:200;
            }
        }

        /* For tablets and small laptops */
        @media (min-width: 768px) {
            #chat-box {
                min-width: 420px;
            }
        }

        #chat-box.show {
            display: flex;
        }

        #header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #C8102E;
            color: white;
            padding: 10px 15px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        #header .text {
            flex-grow: 1;
            text-align: center;
            margin: 0px;
        }

        #close-chat {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s;
        }

        #close-chat:hover {
            color: #f1f1f1;
        }

        #messages {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            max-height: 300px;
            border-bottom: 1px solid #ddd;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px 15px;
            border-radius: 15px;
            font-size: 14px;
            display: block;
            width: fit-content;
        }

        .message.user {
            background-color: #00205B;
            color: white;
            align-self: flex-end;
            max-width:80%;
        }

        .message.bot {
            background-color: #f1f1f1;
            color: black;
            align-self: flex-start;
            max-width: 100%;
        }

        #chat-input-wrapper {
            display: flex;
            border-top: 1px solid #ddd;
            padding: 10px;
        }

        #chat-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            font-size: 14px;
            outline: none;
        }

        #send-btn {
            background-color: #C8102E;
            color: white;
            padding: 10px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }

        #send-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        #send-btn:hover {
            background-color: #a00d22;
        }

        #messages {
            scroll-behavior: smooth;
        }

        .bot-message-wrapper {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
            max-width:85%;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message.bot.typing {
            display: inline-block;
            padding: 10px 15px;
            background: #f0f0f0;
            border-radius: 15px;
            margin: 5px 0;
            font-size: 16px;
        }

        .typing .dot {
            height: 8px;
            width: 8px;
            margin: 0 2px;
            background-color: #333;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .typing .dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing .dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        .typing .dot:nth-child(3) {
            animation-delay: 0;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            } 40% {
                transform: scale(1);
            }
        }

        .message-read-status{
            text-align:right;
            margin: 4px 0px 0px 0px;
            font-size: 0.65em;
            font-style: italic;
            padding-right: 6px;
        }

        .message-read-status.read {
            color: #4caf50; /* green for "read" */
        }

        .message-read-status.unread {
            color: #999; /* subtle grey for "unread" */
            font-style: normal;
            font-weight: 500;
        }

        #unread-count-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            background-color: #e0e0e0;
            color: #d32f2f;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            display: none;
            z-index: 10;
            box-shadow: 0 0 0 2px #fff;
            align-items: center;
            justify-content: center;
        }

        .cta-button {
            background-color: #0052cc;       /* Deep blue or pick a theme color */
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            border: none;
            border-radius: 30px;             /* Smooth pill shape */
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        .cta-button:hover {
            background-color: #003d99;       /* Slightly darker on hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        .cta-button:active {
            transform: translateY(0);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        #work-in-progress-modal {
            display:none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            z-index: 9999;
            justify-content: center;
        }

        #work-in-progress-modal .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-family: 'Segoe UI', sans-serif;
        }

        #work-in-progress-modal .modal-header {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        #work-in-progress-modal .modal-body {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1.5rem;
        }

        #work-in-progress-modal #close-modal {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        #work-in-progress-modal #close-modal:hover {
            background-color: #e0a800;
        }

    </style>
</head>
<body>
    <div class="splash-screen-container">
        <header>
            <div class="logo">ST Engineering</div>
            <nav>
                <a href="#">Home</a>
                <a href="#">Solutions</a>
                <a href="#">Contact</a>
            </nav>
        </header>

        <section class="hero">
            <h1>Welcome to ST Engineering AI</h1>
            <p>Chat with our AI assistant powered by Google's Gemini API. Secure, fast, and futuristic.</p>
            <button id="get-started-btn">Get Started</button>
        </section>
    </div>

    <!-- Digital Tech Section -->
    <section class="section digital-tech" id="digital-tech">
        <i class="section-icon fas fa-laptop-code"></i>
        <h2>Digital Technology</h2>
        <p>Explore the cutting-edge digital technologies powering the future, from AI to IoT solutions, and beyond. We provide the tools for a digital transformation that will drive innovation.</p>
        <button class="cta-button">Learn More</button>
    </section>

    <!-- Aerospace Section -->
    <section class="section aerospace" id="aerospace">
        <i class="section-icon fas fa-rocket"></i>
        <h2>Aerospace</h2>
        <p>Innovating in aerospace with advanced solutions for aircraft design, engineering, and manufacturing. Our technologies redefine the future of air travel and space exploration.</p>
        <button class="cta-button">Explore Aerospace</button>
    </section>

    <!-- Maritime Section -->
    <section class="section maritime" id="maritime">
        <i class="section-icon fas fa-ship"></i>
        <h2>Maritime</h2>
        <p>Exploring the future of maritime technology with smarter and safer vessel systems. Our solutions offer sustainable, efficient, and advanced navigation systems.</p>
        <button class="cta-button">Discover Maritime</button>
    </section>

    <!-- Smart City Section -->
    <section class="section smart-city" id="smart-city">
        <i class="section-icon fas fa-city"></i>
        <h2>Smart City</h2>
        <p>Building sustainable and efficient cities with IoT, automation, and integrated solutions. Our approach ensures that cities are both smart and livable for generations to come.</p>
        <button class="cta-button">Learn About Smart Cities</button>
    </section>

    <footer class="footer">
        <p>&copy; 2025 ST Engineering. All Rights Reserved.</p>
    </footer>

    <div id="chat-widget">
        <button id="toggle-chat">
            <i class="fas fa-comment"></i>
            <span id="unread-count-badge" class="badge">0</span>
        </button>
        <div id="chat-box">
            <div id="header">
                <p class="text">Gemini</p>
                <button id="close-chat"><i class="fas fa-times"></i></button>
            </div>
            <div id="messages"></div>
            <div id="chat-input-wrapper">
                <input type="text" id="chat-input" placeholder="Ask me anything...">
                <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <div id="work-in-progress-modal">
        <div class="modal-content">
            <div class="modal-header">🚧 Work In Progress</div>
            <div class="modal-body">
                Oops! This page is still under development... 🛠️<br>
                Come back soon for something awesome!
            </div>
            <button id="close-modal">Got it!</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            /*
                Key Value Object to store whether the sections allow for toggling of visibility status
            */
            var sectionAllowVisibilityChange = {
                "aerospace": true,
                "maritime": true,
                "smart-city": true,
                "digital-tech": true,
            }

            const $sections = $('.section');
            const options = {
                root: null,
                rootMargin: '0px 0px 0px 0px',
                threshold: 0.5
            };

            /*
                Issue: Flickering visibility loop

                When a hidden section becomes visible (after crossing the threshold),
                its layout may shift, lowering the intersection ratio below the threshold.
                This triggers it to hide again, causing an endless toggle loop — even without scrolling.

                Solution:

                After changing a section's visibility, temporarily disable further visibility changes
                until the user performs an action (e.g., scrolling), which re-enables updates.
            */
            // To identify if the element being observed has exceeded the threshold specified and should be rendered visible or vice versa
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    const $section = $(entry.target);
                    const sectionID = $section.attr('id');

                    // console.log(`Section isIntersecting: ${entry.isIntersecting}, ratio: ${entry.intersectionRatio}, target:`, $section[0], 'classList:', [...entry.target.classList].join(' '));

                    if (sectionAllowVisibilityChange[sectionID]) {
                        // console.log("Process changes - " + sectionID);
                        if (entry.isIntersecting) {
                            changeVisibility($section, 'add');
                        } else {
                            changeVisibility($section, 'remove');
                        }
                    } else {
                        // console.log("Dont process changes " + sectionID);
                    }
                });
            }, options);

            /*
                Observer each section of the page to identify if 'enough' of the section has come into view and if they be rendered visible and vice versa
            */
            $sections.each(function() {
                observer.observe(this);
            });

            /*
                Window on scroll event handler to reset all the section's visibility change toggle back to true
                To allow for section's visibility to be toggled
            */
            $(window).on('scroll', function() {
                sectionAllowVisibilityChange = {
                    "aerospace": true,
                    "maritime": true,
                    "smart-city": true,
                    "digital-tech": true,
                };
            });

            const toggleChatBtn = $('#toggle-chat');
            const chatBox = $('#chat-box');
            const sendBtn = $('#send-btn');
            const chatInput = $('#chat-input');
            const messages = $('#messages');
            const closeChatBtn = $("#close-chat");
            const getStartedBtn = $("#get-started-btn");
            var botResponseTypingInterval = null;
            var botResponse = null;
            var canSendChatbotQuestion = true;

            $('nav a').on('click', function(e) {
                e.preventDefault();
            /*
                Workaround to ensure the modal's display is set to "flex" before fading in,
                This works because fadeIn() will set the item back to its previous non-hidden display value.
                So setting flex and re-hiding it will set this "default" for it to be set back to.
            */
                $('#work-in-progress-modal')
                    .css("display", "flex")
                    .hide()
                    .fadeIn(200);
            });

            $('#close-modal').on('click', function(e) {
                e.preventDefault();
                $('#work-in-progress-modal').fadeOut(200);
            });

            // Close the modal if clicking outside the modal content
            $('#work-in-progress-modal').on('click', function(e) {
                // Check if the click is on the overlay (background), not on the modal content
                if ($(e.target).is('#work-in-progress-modal')) {
                    $('#work-in-progress-modal').fadeOut();
                }
            });


            /*
                Chat Button On Click Event Handler
                    Toggle visibility of the chat window
                        If window is being made visible
                            Focus and scroll to bottom of chat
                            Update all unread message's to read
                            Update unread message badge counter to 0
                        Else If window is being made hidden
                            Clear botResponseTypingInterval
                            Indicate latest bot response as unread
                            Increment unread message badge counter
            */
            toggleChatBtn.on('click', () => {
                const isVisible = chatBox.toggleClass('show').hasClass('show');
                if (isVisible) {
                     // Mark all .message-read-status.unread as read
                     $('.message-read-status.unread').each(function() {
                        $(this).removeClass('unread').addClass('read').html('✔✔'); // Double tick = read
                    });

                    // Reset unread badge count
                    var $badge = $('#unread-count-badge');
                    $badge.text(0).css('display', 'none');
                    setTimeout(() => {
                        enableChatBotQuestion();
                        chatInput.focus();
                        scrollToBottom();
                    }, 100);
                }
                else{
                    if(botResponseTypingInterval){
                        clearInterval(botResponseTypingInterval);
                        botResponseTypingInterval = null;

                        // Check if bot message exists and append single tick for incomplete message
                        var botMsgEl = $('#chat-box .message.bot:last'); // Adjust selector to find the latest bot message
                        botMsgEl.html(botResponse);
                        botResponse = null;

                        if (botMsgEl.length) {
                            var botMsgReadStatusEl = $('<p>', {
                                class: 'message-read-status unread',
                                html: '✔' // single tick = message interrupted before completion
                            });

                            botMsgEl.append(botMsgReadStatusEl);

                            // Increment unread badge count
                            var $badge = $('#unread-count-badge');
                            var count = parseInt($badge.text()) || 0;
                            $badge.text(count + 1).css('display', 'flex');
                        }
                    }
                }
            });

            getStartedBtn.on('click',() => {
                const isChatboxVisible = chatBox.hasClass('show');
                if(!isChatboxVisible){
                    chatBox.toggleClass('show')
                    // Mark all .message-read-status.unread as read
                    $('.message-read-status.unread').each(function() {
                        $(this).removeClass('unread').addClass('read').html('✔✔'); // Double tick = read
                    });

                    // Reset unread badge count
                    var $badge = $('#unread-count-badge');
                    $badge.text(0).css('display', 'none');
                    setTimeout(() => {
                        enableChatBotQuestion();
                        chatInput.focus();
                        scrollToBottom();
                    }, 100);
                }
            })

            $('.cta-button').on('click', function() {
                const sectionId = $(this).closest('section').attr('id');

                const sectionQuestions = {
                    'digital-tech': 'Tell me more about the digital tech segment of ST engineering',
                    'aerospace': 'Tell me more about the aerospace segment of ST engineering',
                    'maritime': 'Tell me more about the maritime segment of ST engineering',
                    'smart-city': 'Tell me more about the the smart-city segment of ST engineering',
                };

                const question = sectionQuestions[sectionId] || 'Can you tell me more about this topic?';

                const isChatboxVisible = chatBox.hasClass('show');
                if(!isChatboxVisible){
                    chatBox.toggleClass('show')
                    // Mark all .message-read-status.unread as read
                    $('.message-read-status.unread').each(function() {
                        $(this).removeClass('unread').addClass('read').html('✔✔'); // Double tick = read
                    });

                    // Reset unread badge count
                    var $badge = $('#unread-count-badge');
                    $badge.text(0).css('display', 'none');
                    setTimeout(() => {
                        enableChatBotQuestion();
                        chatInput.focus();
                        scrollToBottom();

                        chatInput.val(question);
                        sendMessage();
                    }, 100);
                }
                else{
                    if(canSendChatbotQuestion){
                        chatInput.val(question);
                        sendMessage();
                    }
                }
            })

            /*
                Cloes Chat Button On Click Event Handler
                    Hide visibility of the chat window
                        Clear botResponseTypingInterval
                        Indicate latest bot response as unread
                        Increment unread message badge counter
            */
            closeChatBtn.on('click', () => {
                if(botResponseTypingInterval){
                    clearInterval(botResponseTypingInterval);
                    botResponseTypingInterval = null;

                    // Check if bot message exists and append single tick for incomplete message
                    var botMsgEl = $('#chat-box .message.bot:last'); // Adjust selector to find the latest bot message
                    botMsgEl.html(botResponse);
                    botResponse = null;

                    if (botMsgEl.length) {
                        var botMsgReadStatusEl = $('<p>', {
                            class: 'message-read-status unread',
                            html: '✔' // single tick = message interrupted before completion
                        });

                        botMsgEl.append(botMsgReadStatusEl);

                        // Increment unread badge count
                        var $badge = $('#unread-count-badge');
                        var count = parseInt($badge.text()) || 0;
                        $badge.text(count + 1).css('display', 'flex');
                    }
                }

                chatBox.toggleClass('show');
            })

            // Send message on button click
            sendBtn.on('click', () => {
                if (canSendChatbotQuestion) {
                    sendMessage();
                }
            });

            // Send message on Enter key
            chatInput.on('keydown', (e) => {
                if (e.key === 'Enter' && canSendChatbotQuestion) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            function disableChatBotQuestion() {
                canSendChatbotQuestion = false;
                sendBtn.prop('disabled', true).addClass('disabled');
            }

            function enableChatBotQuestion() {
                canSendChatbotQuestion = true;
                sendBtn.prop('disabled', false).removeClass('disabled');
            }

            // Toggle visibility of sections being observed
            function changeVisibility($section, action) {
                const sectionID = $section.attr('id');

                if (action === 'add') {
                    // console.log("make visible - " + sectionID);
                    $section.addClass('visible').removeClass('hidden');
                } else if (action === 'remove') {
                    // console.log("hide - " + sectionID);
                    $section.addClass('hidden').removeClass('visible');
                }

                sectionAllowVisibilityChange[sectionID] = false;
            }

            /*
                Handles the forwarding of user input to backend to facilitate conversation between user and chatbot
                    Creates user message in chat window upon submission of user input
                    Creates bot '...' temporary thinking message as it processes a reply
                    Upon successfully receiving a reply from the bot, remove the temporary thinking message
                    Transform symbol's used by the bots response to appropriate HTML elements
                    Create interval to simulate bot's typing out its response
            */
            function sendMessage() {
                const userMessage = chatInput.val().trim();
                if (!userMessage) return;

                // Disable sendBtn until bot has responded
                disableChatBotQuestion();

                // Display user message
                var userMsgEl = $('<div>', { class: 'message user' });
                userMsgEl.text(userMessage);
                messages.append(userMsgEl);

                chatInput.val('');  // Clear the input field

                var userMsgReadStatusEl = $('<p>', {
                    class: 'message-read-status read',
                    html: '✔✔' // double tick = read
                });

                userMsgEl.append(userMsgReadStatusEl);

                // console.log("CONTENT = " + $('meta[name="csrf-token"]').attr('content'));  // Get CSRF token

                // Show loading placeholder while waiting for response
                var loadingWrapper = $('<div>', { class: 'bot-message-wrapper loading' });

                var botImg = $('<img>', {
                    src: '{{ asset('storage/img/chatbot_icon.png') }}',
                    alt: 'Chatbot Icon',
                    class: 'avatar'
                });

                var loadingDots = $('<div>', {
                    class: 'message bot typing',
                    html: '<span class="dot"></span><span class="dot"></span><span class="dot"></span>'
                });

                loadingWrapper.append(botImg).append(loadingDots);
                messages.append(loadingWrapper);
                scrollToBottom();

                // Send to backend using AJAX
                $.ajax({
                    url: '/chat',  // API endpoint (adjust as needed)
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // CSRF token for security
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        message: userMessage  // Sending the message to the backend
                    }),
                    success: function(data) {
                        var visibleChatWindow = chatBox.hasClass('show');
                        if(!visibleChatWindow){
                            loadingWrapper.remove();
                            return ;
                        }
                        // console.log('Response:', data);  // Handle the response from the server

                        // Remove loading message
                        loadingWrapper.remove();

                        // Bot's response div
                        var wrapper = $('<div>', { class: 'bot-message-wrapper' });

                        // Create the avatar
                        var botImg = $('<img>', {
                            src: '{{ asset('storage/img/chatbot_icon.png') }}',
                            alt: 'Chatbot Icon',
                            class: 'avatar'
                        });

                        // Create the message bubble
                        var botMsgEl = $('<div>', { class: 'message bot' });

                        // Format the reply (convert **text** to <strong>text</strong>)
                        // botMsgEl.html(formatText(data.reply));

                        // Append avatar and message to wrapper
                        wrapper.append(botImg).append(botMsgEl);

                        // Append wrapper to the messages container
                        messages.append(wrapper);

                        // Scroll to the bottom to show the latest message
                        scrollToBottom();

                        // Simulate typing effect
                        botResponse = formatText(data.reply);  // Get the full bot reply after formatting
                        let index = 0;
                        let buffer = '';
                        botMsgEl.html("");  // Clear the message bubble initially

                        botResponseTypingInterval = setInterval(function () {
                            // Append normally one char or HTML tag
                            let char = botResponse.charAt(index);
                            if (char === '<') {
                                let tagEnd = botResponse.indexOf('>', index);
                                let tag = botResponse.slice(index, tagEnd + 1);
                                buffer += tag;
                                index = tagEnd + 1;
                            } else {
                                buffer += char;
                                index++;
                            }
                            botMsgEl.html(buffer);

                            // Stop typing when done
                            if (index >= botResponse.length) {
                                clearInterval(botResponseTypingInterval);
                                botResponseTypingInterval = null;

                                var botMsgReadStatusEl = $('<p>', {
                                    class: 'message-read-status read',
                                    html: '✔✔' // double tick = read
                                });

                                botMsgEl.append(botMsgReadStatusEl);
                                enableChatBotQuestion();
                            }
                        }, 20);  // Adjust typing speed here
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);  // Handle the error (like invalid endpoint, etc.)
                        enableChatBotQuestion();
                    }
                });
            }

            function scrollToBottom() {
                messages.scrollTop(messages[0].scrollHeight);  // Use jQuery to scroll to the bottom
            }

            // Function to convert **bold**, __underline__, and \n to HTML tags
            function formatText(text) {
                // Replace bold **text**
                text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

                // Replace underline __text__
                text = text.replace(/__(.*?)__/g, '<u>$1</u>');

                // Replace italic *text* (surrounded by non-space or line)
                text = text.replace(/(^|[^*])\*(?!\s)([^*]+?)\*(?!\*)/g, '$1<em>$2</em>');

                // Handle bullet points including nested ones
                const lines = text.split('\n');
                const stack = [];
                let result = '';

                for (let line of lines) {
                    const match = line.match(/^(\s*)\* (.+)/);
                    if (match) {
                        const indent = match[1].length;
                        const content = match[2];

                        while (stack.length && stack[stack.length - 1] >= indent) {
                            result += '</ul>';
                            stack.pop();
                        }

                        if (!stack.length || indent > stack[stack.length - 1]) {
                            result += '<ul>';
                            stack.push(indent);
                        }

                        result += `<li>${content}</li>`;
                    }
                    else {
                        // Close any open lists
                        while (stack.length) {
                            result += '</ul>';
                            stack.pop();
                        }

                        result += line + '<br>';
                    }
                }

                // Close any remaining lists
                while (stack.length) {
                    result += '</ul>';
                    stack.pop();
                }

                return result;
            }

        });
    </script>
</body>
</html>
