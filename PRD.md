SYSTEM REQUIREMENTS
Project: Sistem Informasi Destinasi Wisata Kota Ambon Berbasis Web dengan Chatbot AI (NLP - LLM)
Stack: PHP Native (MVC), MySQL, PDO, Bootstrap

==================================================
1. FUNCTIONAL REQUIREMENTS
==================================================

[PUBLIC USER - NO LOGIN REQUIRED]
- Access website without authentication
- View list of tourism destinations
- View destination details:
  * name
  * description
  * location (map/link)
  * images
  * opening hours
  * ticket price
- Search destinations (keyword-based)
- Filter destinations by category
- Use AI chatbot:
  * ask about destinations
  * get recommendations
  * get travel info

[REGISTERED USER - OPTIONAL LOGIN]
- Register account
- Login / Logout
- Save favorite destinations
- View favorite list
- View chat history
- Get personalized chatbot responses (optional)

[ADMIN]
- Login / Logout
- Manage destinations (CRUD)
- Manage categories (CRUD)
- Manage users
- View chatbot logs

[CHATBOT SYSTEM]
- Accept user text input
- Process natural language (NLP)
- Send request to LLM API (OpenAI/Gemini)
- Return contextual responses about Ambon tourism
- Store:
  * chat logs (all users)
  * chat history (logged-in users only)

==================================================
2. NON-FUNCTIONAL REQUIREMENTS
==================================================

[PERFORMANCE]
- Response time < 3 seconds (non-chatbot)
- Optimized queries using PDO prepared statements

[SECURITY]
- Prevent SQL Injection (PDO prepared statements)
- Input validation & sanitization
- Password hashing (bcrypt)
- Secure session management

[USABILITY]
- Responsive UI using Bootstrap
- Simple and user-friendly navigation

[MAINTAINABILITY]
- MVC architecture
- Clean code principles
- Modular structure

[SCALABILITY]
- Modular system design
- Easy API integration

==================================================
3. SYSTEM REQUIREMENTS
==================================================

Backend:
- PHP Native (OOP, MVC)

Database:
- MySQL

Frontend:
- HTML, CSS, JavaScript
- Bootstrap

Server:
- Apache / Nginx

API:
- LLM API (OpenAI or Gemini)

==================================================
4. ARCHITECTURE
==================================================

Pattern:
- MVC (Model-View-Controller)
- Client-Server Architecture

==================================================
5. DATABASE REQUIREMENTS
==================================================

Tables:
- users
- admin
- destinasi
- kategori
- chat_logs
- favorites

Relations:
- users (1) -> (N) favorites
- users (1) -> (N) chat_logs
- kategori (1) -> (N) destinasi

==================================================
6. ACCESS CONTROL
==================================================

Feature Access Matrix:

- View destinations       : Public, User, Admin
- Chatbot                : Public, User, Admin
- Save favorites         : User only
- Chat history           : User only
- Manage destinations    : Admin only
- Manage categories      : Admin only

==================================================
7. CHATBOT INTEGRATION
==================================================

- API communication via HTTP POST (JSON)
- Context: Ambon tourism domain
- Response displayed in real-time (AJAX / Fetch API)

Context Handling:
- Public user → stateless
- Logged-in user → use chat history

==================================================
8. PROJECT STRUCTURE (MVC)
==================================================

/app
    /controllers
        HomeController.php
        DestinasiController.php
        ChatbotController.php
        AuthController.php
        AdminController.php

    /models
        User.php
        Admin.php
        Destinasi.php
        Kategori.php
        Chat.php
        Favorite.php

    /views
        /layouts
        /home
        /destinasi
        /chatbot
        /auth
        /admin

/config
    database.php
    app.php

/core
    Controller.php
    Model.php
    Database.php

/public
    /assets
        /css
        /js
        /images
    index.php

/routes
    web.php

/storage
    logs/

==================================================
9. CODING STANDARDS
==================================================

- Use OOP (Class-based)
- Use PDO for database access
- Follow MVC pattern strictly
- Clear naming conventions
- Reusable functions/components
- Avoid duplicate code
- Separate logic, view, and data layers

==================================================
END OF REQUIREMENTS
==================================================