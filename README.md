# Nalar

**AI-Powered Learning Management System for Indonesian Schools**

Nalar is a web-based Learning Management System (LMS) designed to support teaching and learning activities for elementary (SD), junior high (SMP), and senior high school (SMA) students in Indonesia.

The platform aims to help teachers prepare learning materials, create quizzes, explain answers, and discover relevant educational media with the assistance of AI.

The project focuses on a practical, accessible, and responsive learning experience, with AI-powered capabilities integrated into the core LMS workflow.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Core Features](#core-features)
- [Technology Stack](#technology-stack)
- [Design System](#design-system)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Installation and Setup](#installation-and-setup)
- [Development Workflow](#development-workflow)
- [Project Documentation](#project-documentation)
- [Project Progress](#project-progress)
- [Development Principles](#development-principles)

---

## Project Overview

Nalar is being developed as a responsive web application for school-based learning management.

The main goals are to:

- Help teachers prepare and manage educational content.
- Support quiz creation and student assessment.
- Use AI to assist with generating learning materials, questions, explanations, and relevant educational media recommendations.
- Provide a clear and accessible interface for teachers and students.
- Establish a maintainable foundation for future educational features.

The initial development priority is to complete the core LMS functionality before introducing additional gamification features.

## Core Features

The following items describe the intended product scope. They do not automatically indicate that the features have already been implemented.

### 1. Authentication and User Access

User authentication and access control for the application.

The exact roles, permissions, and access rules must follow the current implementation and the decisions documented in `PROJECT_CONTEXT.md`.

### 2. Dashboard

A dashboard that presents relevant information and navigation based on the user's role.

Dashboard data must reflect actual application data. Empty states should be displayed honestly when information is unavailable.

### 3. Learning Materials

A system for creating, viewing, and managing educational materials organized around the needs of SD, SMP, and SMA learning.

### 4. Categories

Organization and categorization of learning materials to make educational content easier to manage and discover.

### 5. Question Bank and Quizzes

Question management and a straightforward, interactive quiz experience.

The initial quiz experience should prioritize core functionality, usability, and reliable assessment rather than advanced gamification.

### 6. AI-Assisted Learning Content

Planned AI-assisted capabilities include:

- Generating and elaborating learning materials.
- Generating quiz questions.
- Generating explanations and answer discussions.
- Recommending relevant educational media, including videos and teaching aids.
- Working from a short user prompt or uploaded learning materials where supported.

Actual AI capabilities depend on the implemented integrations, configuration, and validation.

### 7. Learning Progress

A foundation for recording and displaying student learning activity and assessment progress.

The exact metrics and tracking behavior must be defined and verified during implementation.

### 8. Future Enhancements

Advanced gamification, mini-games, leaderboards, and other engagement features are planned for a later phase.

These features must not take priority over unfinished core LMS functionality unless the project direction is explicitly changed.

## Technology Stack

The project currently uses the following technologies based on the established development context.

| Technology | Purpose |
|---|---|
| Laravel | Backend framework and application foundation |
| PHP | Server-side application logic |
| Blade | Server-rendered web interface |
| Laravel Fortify | Authentication features |
| MySQL | Relational database |
| Docker | Local development environment |
| Laravel Sail | Docker-based Laravel development workflow |
| Vite | Frontend asset development and bundling, subject to current repository configuration |
| phpMyAdmin | Database administration during local development |

Verify the actual versions and installed packages from the repository before making dependency or environment changes.

Do not introduce another frontend framework without an explicit architectural decision.

## Design System

Nalar follows a modern, minimal, bright, and professional visual direction.

### Color Palette

| Token | Hex | Purpose |
|---|---|---|
| Primary | `#EA580C` | Primary actions and key interface elements |
| Accent | `#FB923C` | Secondary emphasis |
| Soft Orange | `#FFEDD5` | Soft highlights and selected surfaces |
| Background | `#FFFCF8` | Main page background |
| Text | `#292524` | Primary text |
| Highlight | `#2563EB` | Supporting emphasis when appropriate |

### Interface Principles

- Prioritize readability, visual hierarchy, spacing, and alignment.
- Keep layouts clean and responsive.
- Use icons sparingly and only when they improve usability.
- Avoid gradients, excessive decorative effects, glassmorphism, and unnecessary animations.
- Avoid repetitive card-based layouts and excessive badges.
- Use real application data rather than fabricated statistics or activity.
- Provide clear loading, empty, error, and success states when relevant.
- Preserve accessibility and usable contrast across supported screen sizes.

## Project Structure

The repository is expected to follow the existing Laravel structure.

Common directories include:

| Path | Purpose |
|---|---|
| `app/` | Application logic, controllers, models, and related classes |
| `bootstrap/` | Framework bootstrap and application configuration |
| `config/` | Application configuration |
| `database/` | Migrations, factories, and seeders |
| `public/` | Public assets and application entry point |
| `resources/` | Blade views, frontend assets, and styles |
| `routes/` | Application route definitions |
| `storage/` | Application-generated files and logs |
| `tests/` | Automated tests |
| `docs/` | Additional technical documentation, when present |

The following root-level documents support project development:

| File | Purpose |
|---|---|
| `README.md` | Project overview and setup instructions |
| `PROJECT_CONTEXT.md` | Persistent project context and technical decisions |
| `PROGRESS.md` | Current implementation status and development history |
| `AGENTS.md` | Instructions for AI coding agents |

This is a reference, not a guarantee that every directory or file already exists. Inspect the repository for its actual structure.

## Requirements

Before running the application, verify the requirements for the current project configuration.

Typical requirements include:

- A compatible PHP version.
- Composer.
- Docker Desktop or a compatible Docker environment when using Sail.
- Node.js and npm when frontend asset installation or development requires them.
- Access to the configured database service.
- A configured environment file.

Check `composer.json`, `package.json`, `compose.yaml` or `docker-compose.yml`, and the Laravel configuration to determine the exact versions and requirements.

## Installation and Setup

The following workflow describes the general local development process. Confirm the actual scripts, services, and configuration in the repository before executing commands.

### 1. Clone the Repository

```bash
git clone <repository-url>
cd nalar
```

Replace `<repository-url>` with the actual repository URL.

### 2. Configure Environment Variables

Check whether `.env` exists.

If the project provides `.env.example` and `.env` has not been created, copy the example:

```bash
cp .env.example .env
```

Configure the environment variables required by the current application, including database connection details and any necessary external service credentials.

Never commit `.env` or expose secret values.

### 3. Start Docker Services

If the project uses Laravel Sail, start its configured services using the existing Sail setup.

For example:

```bash
./vendor/bin/sail up -d
```

This command assumes Composer dependencies are already installed and the Sail executable is available. Follow the repository's actual setup procedure if they are not.

### 4. Install Dependencies

Install PHP and JavaScript dependencies according to the existing project configuration.

Typical commands are:

```bash
composer install
npm install
```

Run these commands in the appropriate environment. If Composer dependencies require the Docker environment, use the project's established Sail workflow instead.

### 5. Generate the Application Key

If the application key has not been configured, run:

```bash
php artisan key:generate
```

Use the appropriate Sail command if PHP is intended to run inside Docker.

Do not regenerate an existing key in an established environment without understanding the consequences.

### 6. Run Database Migrations

After configuring the database connection and starting the database service, run the migrations if the environment is ready:

```bash
php artisan migrate
```

Use the appropriate Sail command when required.

Review pending migrations before running them against any database containing important data. Do not assume that all required tables already exist.

### 7. Start Frontend Development

If the project uses Vite and npm scripts, start the frontend development server:

```bash
npm run dev
```

### 8. Access the Application

Use the application URL and port configured by the local development environment.

Check the Docker configuration and terminal output rather than assuming a particular port.

### Notes

- The commands above are a general reference, not a guaranteed one-command installation procedure.
- Do not duplicate setup commands that are already automated by the repository.
- Verify the actual environment before running commands that modify the database or application configuration.

## Development Workflow

Use the following workflow for new features, improvements, and bug fixes.

1. Read `PROJECT_CONTEXT.md` and `PROGRESS.md`.
2. Review `AGENTS.md` and the relevant source files.
3. Confirm the existing implementation and requirements.
4. Make changes within the requested scope.
5. Run relevant tests or validation commands.
6. Update `PROGRESS.md` after every meaningful task.
7. Update `PROJECT_CONTEXT.md` if a persistent architectural decision or project constraint changes.
8. Update this README when the project overview, setup procedure, technology stack, or other general documentation materially changes.
9. Report the changes, verification results, remaining issues, and next step.

See `AGENTS.md` for the complete coding and documentation rules.

## Project Documentation

### `PROJECT_CONTEXT.md`

Contains the persistent project context, technical decisions, architecture constraints, design guidelines, and other information required to maintain consistency across development sessions.

### `PROGRESS.md`

Contains the current project phase, feature-level statuses, recent changes, known issues, blockers, and next development priorities.

This file is the single source of truth for feature-level progress.

### `AGENTS.md`

Contains the rules that AI coding agents must follow when inspecting, modifying, testing, and documenting the project.

### `docs/`

Contains detailed technical documentation, such as database design, architecture, feature specifications, and implementation workflows when those documents are available.

## Project Progress

Nalar is under active development.

The current feature status, completed work, unresolved issues, and next priorities are maintained in **[PROGRESS.md](PROGRESS.md)**.

Consult that document before starting a new task. Do not infer completion from this README or from the existence of a page, route, controller, or placeholder.

## Development Principles

The project follows these principles:

- **Consistency:** Preserve established architecture, naming conventions, and design decisions.
- **Maintainability:** Prefer readable, focused, and reusable code where appropriate.
- **Data integrity:** Use actual application data and avoid fabricated results.
- **Security:** Preserve authentication, authorization, validation, and secret-management practices.
- **Accessibility:** Make core interactions usable across supported screen sizes and input methods.
- **Verification:** Validate changes and report limitations honestly.
- **Documentation:** Keep context, progress, and technical documentation accurate without duplicating information.
- **Incremental development:** Complete and verify core functionality before expanding the scope.

---

## License

Add the project's actual license information here once a license has been selected and included in the repository.