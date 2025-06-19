# BEAssignment
NCCLV5Assignment
# 🍽️ Food Recipe & Blog — Backend Assignment

## 📖 Overview

This project is a simple **Food Recipe and Blog** website built using **HTML**, **CSS**, and **PHP**. It allows users to browse food recipes and read related blog articles. The backend is powered by PHP to handle dynamic content and database operations.

This repository was created as part of a backend development assignment.

---

## 📌 Features

- 📃 View a list of food recipes with images and cooking instructions
- ✍️ Browse blog articles related to food, cooking, and kitchen tips
- 📑 Individual recipe and blog detail pages
- 🔍 Basic search functionality for recipes and blogs
- 📂 Organized PHP includes for header, footer, and database connection
- 🎨 Custom responsive design using CSS

---

## 🛠️ Technologies Used

- **HTML5**
- **CSS3**
- **PHP**
- **MySQL** (for storing recipe and blog data)

---

## 📂 Project Structure

/food-recipe-blog/
├── /assets/
│ ├── /css/
│ └── /images/
├── /includes/
├── /recipes/
├── /blogs/
├── config.php
├── index.php
├── recipe-list.php
├── blog-list.php
├── about.php
├── contact.php
└── README.md


---

## 📦 Setup Instructions

1. Clone this repository:

git clone https://github.com/-


2. Move the project folder to your web server directory (e.g. `htdocs` for XAMPP)

3. Create a MySQL database and import the provided `food_blog_db.sql` file (if available)

4. Update the database connection settings inside `config.php`:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_blog_db";

Open the browser and run:

http://localhost/food-recipe-blog/
