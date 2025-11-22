---

# **AI Quiz Generator – MVP**

A lightweight MVP application that automatically generates **multiple-choice quizzes** based on a user-provided topic.
This project is completely **Docker-based**, so setup is quick and easy — no need to install PHP or SQLite locally.

---

## 🚀 **Features**

* Generate quizzes automatically from a given topic
* Store quiz history in SQLite
* Dockerized application — runs anywhere
* Simple and easy-to-use UI

---

## 🛠 **Prerequisites**

Make sure you have the following installed on your machine:

* **Docker**
* **Docker Compose**

---

## 📦 **Setup Instructions**

Follow the steps below to get the application running:

### **1. Install Docker**

If Docker is not installed, download it from:
[https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)

---

### **2. Clone the Repository**

```sh
git clone <your-repo-url>
cd <project-folder>
```

---

### **3. Create the SQLite Database**

Inside the project root:

```sh
mkdir -p database
touch database/quiz.db
chmod 777 database/quiz.db
```

This step is required because SQLite needs write access inside Docker.

---

### **4. Copy Environment File**

```sh
cp env.example .env
```

---

### **5. Update `.env`**

Open the file:

```sh
nano .env
```

Update any necessary environment variables, such as API keys or app settings.

---

### **6. Build Docker Images**

```sh
sudo docker compose build
```

---

### **7. Start the Application**

```sh
sudo docker compose up -d
```

---

### **8. Verify Containers Are Running**

```sh
sudo docker ps
```

Ensure your PHP container is **Up**.

---

### **9. Open the App**

Your application is ready at:

👉 **[http://13.ai-quiz.mit:9090/](http://13.ai-quiz.mit:9090/)**

---

## 🔗 **Useful Links**

| Feature            | URL                                                                      |
| ------------------ | ------------------------------------------------------------------------ |
| **Quiz Generator** | [http://13.ai-quiz.mit:9090/](http://13.ai-quiz.mit:9090/)               |
| **Quiz History**   | [http://13.ai-quiz.mit:9090/history](http://13.ai-quiz.mit:9090/history) |

---

## 📄 **License**

This project is for MVP/demo purposes only.

---
