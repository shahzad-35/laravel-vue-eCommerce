# 🚀 Project Setup Guide
Welcome to our fantastic project! Follow these simple steps to clone and run the repository seamlessly.

##### 🌀 Clone the Repository 
Run this command to clone repository.
``` sh
git clone https://github.com/your-username/your-repo.git
```

##### 📦 Install Composer Dependencies
Run the following command to install Composer dependencies:
- composer install

##### Move to Backend Folder
Run the following command to install npm packages:
``` sh
cd backend
```
##### ⚙️Install Node.js Dependencies
``` sh
npm install
```
##### 🔑 Create Environment Files
Create a .env file in the backend folder with the key `VITE_API_BASE_URL` to connect to APIs. Add the following line to your .env file:
```
VITE_API_BASE_URL=https://api.example.com
```
Replace https://api.example.com with the actual base URL of your APIs.

#####  📁Laravel Main Directory
Create another .env file in the main directory of the Laravel application. This file is typically located in the root of your Laravel project. Copy the example .env file and customize it as needed.

##### 🌟 You're All Set!
You've successfully set up the project. Now you can proceed with running and exploring the awesome features of our application. If you encounter any issues, refer to our troubleshooting guide or reach out to our support team.

Happy coding!