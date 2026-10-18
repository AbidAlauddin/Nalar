# Nalar — Project Context

## 1. Product Overview

**Nalar** is an AI-powered Learning Management System (LMS) designed for Indonesian elementary (SD), junior high (SMP), and senior high (SMA) schools.

The platform supports teachers and students through structured learning materials, quizzes, assessment explanations, and AI-assisted educational content creation.

### Product Goals

- Help teachers prepare and manage learning materials efficiently.
- Assist teachers in generating educational content using AI.
- Simplify the creation and delivery of quizzes.
- Support students in understanding learning materials and assessment results.
- Provide an accessible, responsive, and maintainable learning platform.
- Establish a solid core LMS before introducing advanced engagement features.

### Target Users

- **Teachers:** Prepare materials, create questions and quizzes, and use AI-assisted educational tools.
- **Students:** Access learning materials, complete quizzes, and review explanations and learning progress.
- **Administrators:** Manage users and platform operations according to the application's implemented role and permission system.

The exact roles, permissions, and workflows must follow verified project requirements and the existing implementation.

## 2. Product Scope and Feature Decisions

### Core LMS

The initial product focuses on these functional areas:

1. Authentication and access control.
2. Role-appropriate dashboards.
3. Learning material management.
4. Learning material categories.
5. Question bank management.
6. Quiz creation and participation.
7. Quiz results and answer explanations.
8. AI-assisted educational content generation.
9. Educational media recommendations.
10. Student learning progress.

These items define the intended product scope. Their actual implementation status is tracked exclusively in `PROGRESS.md`.

### AI-Assisted Learning

Nalar is intended to support AI-assisted generation and elaboration of:

- Learning materials.
- Quiz questions.
- Answer explanations and discussions.
- Recommendations for relevant educational media, including videos and teaching aids.

The input may consist of a short prompt or uploaded learning materials, where supported by the implemented workflow.

AI-generated content must be treated as content requiring appropriate validation. The interface should communicate failures clearly and must not present generated content as successfully saved or processed when the operation has failed.

The specific AI provider, model, API integration, upload formats, generation limits, and processing workflow must be determined from the actual implementation or explicit project decisions. Do not assume these details.

### Quiz Experience

The initial quiz experience should be straightforward and familiar, inspired by the simplicity of Quizizz-style quizzes.

Prioritize:

- Clear questions and answer choices.
- Reliable answer submission.
- Accurate scoring.
- Clear result summaries.
- Useful answer explanations.
- A responsive experience across supported screen sizes.

Advanced mini-games, elaborate rewards, and competitive gamification are not part of the initial core implementation.

### Deferred Gamification

Advanced gamification is planned as a future enhancement after the core website and LMS workflows are sufficiently complete.

Potential future features include:

- Mini-games.
- Class leaderboards.
- Learning engagement metrics.
- Additional rewards and interactive challenges.

Do not expand the initial implementation with these features unless the product direction is explicitly changed.

## 3. Technology and Architecture Decisions

### Established Stack

The project uses the following established technologies:

| Technology | Responsibility |
|---|---|
| Laravel | Application framework and backend |
| PHP | Server-side application logic |
| Blade | Server-rendered interface |
| Laravel Fortify | Authentication functionality |
| MySQL | Relational database |
| Docker | Containerized local development |
| Laravel Sail | Laravel development environment through Docker |
| Vite | Frontend asset tooling, subject to repository configuration |
| phpMyAdmin | Local database administration |

The exact versions, installed packages, and configuration must be verified in the repository when relevant.

### Architectural Constraints

- Keep Laravel as the primary application framework.
- Keep Blade as the frontend rendering approach.
- Follow the repository's existing Laravel conventions.
- Prefer standard Laravel controllers and clear separation of responsibilities where consistent with the existing codebase.
- Reuse existing layouts, components, models, middleware, and services when appropriate.
- Avoid introducing unnecessary dependencies or architectural complexity.
- Do not migrate the frontend to React, Vue, Next.js, Inertia.js, or another framework without an explicit architectural decision.
- Do not assume that a planned feature already has a corresponding database table, route, model, or service.

### Backend and Frontend Separation

Frontend presentation and backend behavior should remain appropriately separated.

A presentation-only change should not require unnecessary changes to controllers, models, routes, middleware, migrations, or authentication logic.

When a feature genuinely requires backend changes, implement only the changes necessary to support the intended behavior.

### Authentication and Authorization

Laravel Fortify is the established authentication solution.

Authentication and authorization must be treated as separate concerns:

- Authentication establishes the user's identity.
- Authorization determines which resources and actions the user may access.

The actual role definitions, middleware, access rules, login redirects, and registration behavior must be verified from the repository.

Do not assume that every authenticated user has the same permissions.

## 4. Design System

### Visual Direction

Nalar uses a **modern, minimal, bright, and professional** visual style.

The interface should feel suitable for an educational platform used by teachers and students, without relying on excessive decoration or childish visual treatments.

### Color Palette

| Token | Hex | Intended Use |
|---|---|---|
| Primary | `#EA580C` | Primary actions and key interactive elements |
| Accent | `#FB923C` | Secondary emphasis |
| Soft Orange | `#FFEDD5` | Subtle highlights and selected surfaces |
| Background | `#FFFCF8` | Main page background |
| Text | `#292524` | Primary text and content |
| Highlight | `#2563EB` | Supporting emphasis where appropriate |

Use these colors consistently. Maintain readable contrast and avoid using accent colors indiscriminately.

### UI Principles

- Establish clear typography and visual hierarchy.
- Use consistent spacing, alignment, and component proportions.
- Keep interfaces responsive and accessible.
- Prefer simple layouts over unnecessary decorative containers.
- Use icons sparingly and only when they communicate meaning or improve navigation.
- Use clear loading, empty, error, and success states when relevant.
- Favor meaningful content over decorative elements.
- Keep interactions predictable and feedback understandable.

### Anti-AI-Slop Constraints

The following are explicit design preferences:

- No gradients unless explicitly requested.
- No unnecessary glassmorphism.
- No decorative blobs, excessive glow effects, or ornamental backgrounds.
- No excessive icons, emojis, badges, or repetitive cards.
- No fabricated statistics, activity, notifications, or user data.
- No decorative charts without meaningful underlying data.
- No unnecessary animations or heavy visual effects.
- No arbitrary UI elements added merely to make a page appear more elaborate.

The quality of the interface should come from composition, typography, spacing, usability, and accurate content rather than decoration.

## 5. Data and Application Integrity

All application interfaces must reflect the actual data and behavior supported by the backend.

### Data Rules

- Use real application data where available.
- Display honest empty states when data is absent.
- Distinguish missing data from a genuine zero value.
- Do not fabricate counts, records, quiz attempts, activity histories, or user information.
- Do not assume a database schema without checking migrations and the current database structure.
- Preserve existing relationships, validation rules, and business logic.
- Handle errors and missing data without silently presenting misleading results.

### Database Decisions

MySQL is the established database technology.

The exact schema, table names, relationships, indexes, and migration history must be derived from the actual repository.

Do not treat example table names or planned data structures as confirmed implementation details.

## 6. Development Environment

The project has been developed locally on macOS using Docker Desktop and Laravel Sail.

phpMyAdmin has also been included in the local development environment for database administration.

The exact configuration is defined by the current repository, including:

- Docker Compose configuration.
- Environment variables.
- Laravel configuration.
- Composer dependencies.
- Frontend package configuration.
- Database migrations.

Do not assume that a particular port, container name, database credential, or environment setting is permanent. Inspect the current configuration when troubleshooting or changing the environment.

Never include real credentials, API keys, or other secrets in this document.

## 7. Feature Design Expectations

### Dashboard

Dashboards should present information and actions relevant to the current user's role.

Use existing backend data and implemented routes. Do not create fake metrics or nonfunctional navigation merely to fill the interface.

The exact dashboard layout and available widgets may evolve as the application develops, while preserving the established design system and authorization rules.

### Learning Materials and Categories

Learning content should be organized clearly so that users can identify relevant subjects and materials.

The eventual workflow should support the operations defined by the product requirements, with appropriate validation and access control.

Do not assume specific curriculum structures, grade-level hierarchies, or subject taxonomies unless these have been explicitly established.

### Question Bank and Quizzes

Question management and quiz participation should use consistent data structures and reliable scoring behavior.

Question types, time limits, randomization, retake rules, grading policies, and quiz access rules must follow verified requirements rather than assumptions.

### Learning Progress

Progress tracking should reflect actual learning activities and assessment results.

Metrics such as completion rates, quiz scores, or engagement duration should only be displayed when their definitions and data sources are established.

## 8. Quality and Maintainability

Nalar should remain maintainable as the number of features grows.

Prefer:

- Readable and focused code.
- Reusable components when they provide genuine value.
- Clear responsibilities between controllers, models, views, and supporting services.
- Consistent naming and project conventions.
- Appropriate validation and authorization.
- Responsive and accessible interfaces.
- Tests for important business rules and user workflows.
- Documentation that accurately reflects the implementation.

Avoid premature abstractions, unnecessary dependencies, duplicated business logic, and broad refactors unrelated to the requested work.

## 9. Context Boundaries

This file records persistent product context, architectural decisions, technical constraints, and design principles.

The other project documents have separate responsibilities:

- `AGENTS.md`: mandatory instructions for AI coding agents.
- `README.md`: project introduction, technology overview, and setup instructions.
- `PROGRESS.md`: actual feature status, recent changes, known issues, blockers, and next development priorities.
- `docs/`: detailed technical specifications and supporting documentation where needed.

Do not duplicate complete progress tables, task histories, installation procedures, or coding-agent rules in this file.

When an important product or architectural decision changes, update this document to reflect the new decision and remove outdated contradictory information.

## 10. Source of Truth

When working on Nalar, distinguish between:

1. **Confirmed decisions:** Explicitly established product requirements and architectural constraints recorded here.
2. **Current implementation:** What is actually present in the repository.
3. **Development status:** What has been completed, remains unfinished, or requires verification according to `PROGRESS.md`.
4. **Unresolved decisions:** Details that have not yet been confirmed.

Do not convert a planned feature into a claim of completion.

When documentation and source code disagree, inspect the implementation and relevant history, identify the discrepancy, and update the appropriate document after the correct state is established.

The purpose of this file is to maintain a consistent understanding of Nalar across development sessions without replacing repository inspection or verification.