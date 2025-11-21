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

```


## 📝 Next Steps

The next critical phase of the project is the **AI API Integration**:

1. **Hugging Face Integration:** Develop the PHP logic (or an endpoint) to aggregate all reviews for a business, send them to the Hugging Face API, and process the resulting summary.

2. **Summary Caching:** Implement a column or table to store the AI-generated summaries. This is vital to optimize speed and reduce costs and redundant API calls.

---

## 🧠 AI Integration

The system features an established connection with the **Hugging Face** API, utilizing the model:

> **`philschmid/bart-large-cnn-samsum`**

This free model allows for text summary generation. Although it **does not reach the precision level of premium models**, its integration demonstrates the complete functionality of the flow **(review extraction → send to AI → return processed summary)**.

The main function (`resumirResenas`) is located in `config/huggingface.php` and implements:
- Text cleaning prior to sending.
- Connection via `cURL` to the Hugging Face API.
- Processing of the returned JSON.
- Automatic removal of redundant model phrases (e.g., “Summarize the customer reviews…”).

---

### Summary Caching Strategy

To implement this feature, I modified the database with three new variables: one to track the total reviews for a business (updated via a trigger), one for `actual_reviews` (the number of reviews present when the last summary was generated), and a final variable to store the AI summary to avoid unnecessary resource usage.

**SQL Modifications:**

```sql
    ALTER TABLE businesses ADD COLUMN summary TEXT DEFAULT NULL;
    ALTER TABLE businesses ADD COLUMN total_reviews INT DEFAULT 0;
    ALTER TABLE businesses ADD COLUMN actual_reviews INT DEFAULT 0;


    CREATE TRIGGER after_review_insert
    AFTER INSERT ON reviews
    FOR EACH ROW
    BEGIN
        UPDATE businesses
        SET total_reviews = total_reviews + 1
        WHERE id = NEW.business_id;
    END;
    //

    DELIMITER ;

    DELIMITER //

    CREATE TRIGGER after_review_delete
    AFTER DELETE ON reviews
    FOR EACH ROW
    BEGIN
        UPDATE businesses
        SET total_reviews = total_reviews - 1
        WHERE id = OLD.business_id;
    END;
    //

    DELIMITER ;

```


