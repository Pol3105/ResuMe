# 📚 ResuMe

## 💡 Project Overview

A web platform designed to transform the way users consume local business reviews. Instead of forcing users to read hundreds of comments, **ResuMe\*** uses Artificial Intelligence (AI) to generate a **concise, thematic summary** of all opinions about a location, quickly highlighting the strong points, recurring criticisms, and the actual average rating.

## 🎯 Motivation and Challenge

The primary purpose of this project is to demonstrate the ability to effectively integrate complex services. The goal is to create a visible and functional product that showcases the practical application of:

1. **API Integration:** Connecting with external services for data import (reviews).

2. **Natural Language Processing (NLP):** Using an AI API (Hugging Face) to perform the automatic summarization of large volumes of text.

## ⚙️ Technology Stack

| Component | Technology | Purpose |
| :--- | :--- | :--- |
| **Backend/API** | PHP (Class Structure) | Server logic and data handling, utilizing `Business` and `Review` classes. |
| **Frontend** | PHP/HTML | Rendering of views and presentation logic. |
| **Styling** | CSS with Semantic Classes | Responsive, clean, and consistent design (based on Tailwind principles). |
| **Database** | MySQL/MariaDB | Persistent storage for businesses and reviews. |
| **Artificial Intelligence** | Hugging Face API | Text summary generation (NLP). |

## 🚀 Current Status (MVP)

### Structure and Aesthetics

The project features a well-defined PHP class structure that simulates interaction with the database. The responsive design for the two key views (`index.php` and `business.php`) is fully implemented, ensuring a professional aesthetic and a good user experience.

### Database Schema (SQL)

Two essential tables have been defined for the functionality of the MVP:

```sql
-- Table for the businesses
CREATE TABLE businesses (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
category VARCHAR(50) NOT NULL
);

-- Table for the reviews
CREATE TABLE reviews (
id INT AUTO_INCREMENT PRIMARY KEY,
business_id INT NOT NULL,
user_name VARCHAR(100) NOT NULL,
rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
comment TEXT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_business
FOREIGN KEY (business_id)
REFERENCES businesses(id)
ON DELETE CASCADE
);
