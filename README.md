# RPGate - The Realm Marketplace Gateway

RPGate is a SaaS platform and management ecosystem built for RPG enthusiasts and service providers. Designed under the pillars of Clean Architecture, high performance, and real-time reactivity, the project serves both as a scalable commercial product and as an unassailable senior software engineering portfolio.

The backend is engineered with modern PHP leveraging the Laravel ecosystem integrated with Redis, while the frontend is a reactive SPA (Single Page Application) powered by Vue 3 and Pinia.

---

## 🛠️ Tech Stack & Infrastructure

* **Backend:** PHP 8.3+ | Laravel 11+
* **Frontend:** TypeScript | Vue 3 (Composition API) | Pinia (Setup Stores) | TailwindCSS
* **Real-Time & Cache:** Laravel Reverb (WebSockets) | Redis
* **Database:** PostgreSQL (Production) / MySQL (Development via Sail)
* **Testing Suite:** Pest (Backend) | Vitest / Playwright (Frontend)
* **Isolated Environment:** Laravel Sail (Docker)

---

## 🗺️ Development Roadmap

Listed below are the ecosystem modules, highlighting what has already been consolidated and what is currently on the implementation track:

### 🪙 Core & Base Infrastructure
- [x] Isolated development environment via Docker (Laravel Sail).
- [x] Secure and persistent authentication architecture (Laravel Sanctum / Fortify).
- [x] Standardized API responses using Form Requests, DTOs (Data Transfer Objects), and Json Resources.
- [x] Automated feature testing coverage using Pest PHP.

### 🎨 Customization & User Experience (UX)
- [x] Interface preference management (Dark / Light / System Theme).
- [x] Decoupled Pinia Stores architecture (Modern Setup Store syntax).
- [x] **Optimistic Updates:** Instant visual mutation on the frontend with asynchronous background persistence via the Laravel API.
- [x] Automated session state and preference synchronization via Global Subscribers on F5.

### ⚔️ Upcoming Expansions
- [ ] **Social Module:** Self-referencing friendship system with status control and real-time private Chat via Laravel Reverb.
- [ ] **Lobbies Module:** Real-time room creation and management using presence channels.
- [ ] **Character Sheets:** Polymorphic modeling of attributes and inventories for RPG systems.

---

## 📐 Architecture Principles & Design Decisions

The software design of RPGate was engineered to balance **maintainability** and **Time-to-Market**. Instead of adopting industry patterns dogmatically, architectural decisions were driven by technical pragmatism:

### 🎯 Pragmatism over Patterns: Why do we prioritize Actions?
The project utilizes the **Actions** pattern as the primary driver of backend business logic, precluding the premature introduction of traditional *Services* and *Repositories* due to the following factors:

1. **True Single Responsibility Principle (SRP):** Each system use case has its own isolated class with a single public method. This eliminates monolithic *Service* files that accumulate dozens of methods lacking a direct relationship to one another.
2. **High Testability:** Single Action classes are far better to mock and test in isolation, ensuring that side effects are intercepted before hitting production.
3. **Overengineering Mitigation:**
   - **No Repositories:** Laravel's Eloquent ORM natively implements the *Active Record* and *Data Mapper* patterns (via Query Builders). For this business model, introducing a repository layer simply creates redundancy and boilerplate.
   - **No Generic Services:** Separating clear boundaries across general Service Layers is a common anti-pattern. Actions keep the data flow clean and map out processes identically to the ubiquitous business language.

### 🛑 When do Services and Repositories come into play?
Adhering to the YAGNI (*You Ain't Gonna Need It*) philosophy, these layers are strictly implemented only when a real architectural necessity arises:
- **Services:** Introduced exclusively when there is a technical requirement to integrate with multiple external drivers or third-party SDKs, such as payment gateways.
- **Repositories:** Adopted only if the core business demands highly complex raw SQL telemetry queries, or if there is a real strategic decision to completely decouple from the relational database—which does not apply to the native CRUD operations and relationships of this ecosystem.

---

## 🚀 How to Initialize the Realm (Development)

To clone and run the project locally using Docker, execute the following commands in your terminal:

```bash
# Clone the repository
git clone [https://github.com/your-username/rpgate.git](https://github.com/your-username/rpgate.git)

# Enter the directory
cd rpgate

# Install Composer dependencies via a temporary container
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php8.3-composer:latest \
    composer install --ignore-platform-reqs

# Copy environment file
cp .env.example .env

# Initialize the Laravel Sail environment
./vendor/bin/sail up -d

# Generate the application key
./vendor/bin/sail artisan key:generate

# Run database migrations and seeders
./vendor/bin/sail artisan migrate --seed
```

To boot up the frontend ecosystem and watch for changes:

```bash
# Install Node dependencies
./vendor/bin/sail npm install

# Run the Vite development server
./vendor/bin/sail npm run dev
```

---

## 🧪 Running the Test Suite

The stability and integrity of RPGate's business rules are continually validated through automated testing. To run the backend suite:

```bash
./vendor/bin/sail test
```

## 📄 License
This project is open-source software licensed under the MIT license.