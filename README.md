# Chatbot Project

## Overview
This website serves as a demonstration of a chatbot powered by Google's Gemini API. It provides users with an interactive experience, allowing them to chat with an AI assistant that can answer questions, assist with various tasks, and provide useful information. The website features a modern and user-friendly design, showcasing the real-time capabilities of the Gemini API.

---

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Setting Up the Gemini API](#setting-up-the-gemini-api)
4. [Running Locally](#running-locally)

---

## Prerequisites

Before you begin, ensure you have the following software installed on your local machine:

- **PHP**
- **Composer**
- **MySQL or MariaDB** (or any other database you're using)
- **Laravel**

For deploying the application, you'll need access to a server with support for PHP and the ability to run Laravel.

---

## Installation

To get started with the project, follow these steps:

1. Clone the repository:

   ```bash
   git clone https://github.com/5partace/chatbot_project.git

2. CD to project folder:

   cd st-engineering-ai

3. Install PHP dependencies using Composer:

   composer install

4. Copy the .env.example file to .env

   cp .env.example .env

5. Generate the application key:

   php artisan key:generate

6. Set Up Storage Symbolic Link:

    php artisan storage:link

## Setting Up The Gemini API

1. Create a Google Gemini Account && Obtain a usable API key:

   GEMINI_API_KEY=your_api_key_here ( Update the env file )

## Running The Project Locally

1. php artisan serve
