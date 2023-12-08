# News Portal

The News Portal is a web application designed to provide users with a seamless and feature-rich experience for staying informed about the latest news. It caters to both general users and administrators, offering a range of functionalities to enhance the news-reading experience.

### Technologies Used

-   **Backend**: [Laravel](https://laravel.com/docs/10.x)
-   **Frontend**: [Vue.js](https://vuejs.org/)
-   **Database**: [PostgreSQL](https://www.postgresql.org/)
-   **Authentication**: [Laravel Breeze](https://laravel.com/docs/10.x/starter-kits#laravel-breeze)
-   **External API**: [News API](https://newsapi.org/)
-   **UI Framework**: [Tailwind CSS](https://tailwindcss.com/)

### Installation

1. **Clone the repository:**

    ```bash
    git clone [repository_url]
    ```

2. **Install dependencies:**

    ```bash
    composer install
    npm install
    ```

3. **Copy the `.env.example` file to `.env`:**

    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

5. **Configure the database and other settings in the `.env` file.**

6. **Run migrations and seed the database:**

    ```bash
    php artisan migrate --seed
    ```

7. **Build front-end assets:**

    ```bash
    npm run dev
    ```

8. **Start the development server:**

    ```bash
    php artisan serve
    ```

### Usage

1. **Run Laravel Development Server:**

    ```bash
    php artisan serve
    ```

    This will start the Laravel development server.

2. **Compile Assets with NPM:**

    ```bash
    npm run dev
    ```

    Run the npm dev script to compile assets. Make sure you have Node.js and npm installed. Adjust the scripts in `package.json` based on your environment.

3. **Access the Application:**
   Navigate to [http://127.0.0.1:8000](http://127.0.0.1:8000) in your web browser to access the application.

### Features

-   **User Authentication**: Users can create accounts, log in, and reset passwords.
-   **Language and Country Preferences**: Users can customize their language and country settings.
-   **News Categories**: View articles from various categories like politics, sports, and entertainment.
-   **Favorite Articles**: Users can favorite articles for easy access.
-   **Favorite News Category**: Users can choose and change their favorite news category.
-   **Comments and Discussions**: Users can comment on articles and engage in discussions.
-   **Search Functionality**: Users can search for articles based on keywords or topics.
-   **Article Filtering**: Users can filter articles based on date, and popularity.
-   **Admin Panel**: Admins can log in with predefined credentials and manage categories.
-   **User Management**: Admins can view and manage registered user information, including articles and comments.
