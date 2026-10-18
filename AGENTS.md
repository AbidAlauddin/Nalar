# AI Coding Agent Instructions — Nalar

## 1. Purpose

These instructions define how AI coding agents must work within the Nalar repository.

Follow these instructions for every task, including feature development, bug fixes, UI improvements, refactoring, testing, and documentation.

## 2. Mandatory Context Reading

Before starting any task:

1. Read `PROJECT_CONTEXT.md` to understand Nalar's architecture, design decisions, constraints, and development direction.
2. Read `PROGRESS.md` to understand the current implementation status, known issues, and next priorities.
3. Read `README.md` when project setup, dependencies, architecture overview, or general project documentation is relevant.
4. Inspect the existing source code, routes, controllers, models, views, migrations, middleware, and tests relevant to the requested task.
5. Read existing documentation related to the feature being changed.

If a required document does not exist, do not pretend it was read. Inspect the repository and report the missing document when relevant.

Existing documentation is a reference, not proof that a feature is implemented. Verify the actual code before making assumptions.

## 3. Project Architecture and Technology

- Preserve the existing technology stack and architecture documented in `PROJECT_CONTEXT.md`.
- Nalar uses Laravel with Blade for its web interface.
- Do not introduce React, Vue, Next.js, Inertia.js, or another frontend framework unless explicitly requested and the project architecture is deliberately changed.
- Follow existing Laravel conventions and the repository's current implementation.
- Reuse existing components, layouts, controllers, services, middleware, and utilities when appropriate.
- Do not introduce new dependencies for functionality that can reasonably be implemented using the existing stack.
- Do not change dependency versions or perform major upgrades unless explicitly requested or necessary for the task.
- Never assume a route, database table, model attribute, environment variable, or package exists without checking.

## 4. Task Scope and Change Management

- Work only on the scope requested by the user.
- Prefer small, focused, maintainable changes over broad rewrites.
- Inspect related code before modifying it.
- Preserve existing behavior unless the task explicitly requires a behavior change.
- Do not rewrite working features just to match personal coding preferences.
- Avoid unrelated refactoring, file renaming, route restructuring, schema changes, and dependency changes.
- Do not overwrite user work or discard unrelated changes.
- Never run destructive commands without explicit authorization.
- If the requested task requires changes outside its original scope, explain why and seek clarification when the impact is significant.
- If requirements are ambiguous, ask one focused question when the ambiguity prevents safe implementation. Otherwise, follow the most consistent existing project conventions.

## 5. UI and Design Guidelines

Follow the design system documented in `PROJECT_CONTEXT.md`.

Nalar's current visual direction is modern, minimal, bright, and professional.

Use the established palette:

- Primary: `#EA580C`
- Accent: `#FB923C`
- Soft Orange: `#FFEDD5`
- Background: `#FFFCF8`
- Text: `#292524`
- Highlight: `#2563EB`

### Anti-AI-Slop Rules

- Do not use gradients unless explicitly requested.
- Avoid excessive icons. Use icons only when they improve clarity or usability.
- Avoid unnecessary cards, badges, borders, shadows, decorative blobs, glow effects, and glassmorphism.
- Do not add emojis as UI decorations unless explicitly requested.
- Avoid repetitive layouts where every section is presented as an identical card.
- Do not use fake statistics, fake activity, fake notifications, fake testimonials, or fabricated user data.
- Do not add decorative charts that do not communicate real application data.
- Avoid unnecessary animations and heavy visual effects.
- Prioritize typography, spacing, alignment, contrast, hierarchy, and clear interaction states.
- Make interfaces responsive and accessible.
- Use real application data where available and honest empty states where data is unavailable.
- Do not redesign unrelated pages unless explicitly requested.

## 6. Backend and Data Integrity

- Do not fabricate database records or pretend placeholder data is real.
- Inspect the relevant models, migrations, schema, relationships, controllers, and services before changing data-related functionality.
- Preserve authorization and authentication rules.
- Validate user input using established project conventions.
- Handle empty states, invalid input, errors, and missing data appropriately.
- Do not silently swallow exceptions or hide implementation failures.
- Do not modify database schemas or migrations for a UI-only task.
- Do not change controllers, routes, middleware, models, authentication, or business logic when the request explicitly limits work to presentation.
- If backend changes are required, keep them minimal and explain their purpose.

## 7. Security and Privacy

- Never expose secrets, credentials, API keys, tokens, or private environment values in source code or documentation.
- Do not copy actual `.env` secrets into examples, logs, or reports.
- Use `.env.example` for documenting environment configuration when appropriate.
- Preserve access control and role restrictions.
- Do not weaken validation, authorization, CSRF protection, or other existing security measures to make a feature work.
- Do not log sensitive user information unnecessarily.
- Never claim a security issue is resolved without checking the relevant implementation.

## 8. Required Progress Tracking

`PROGRESS.md` is the single source of truth for feature-level development status.

### Before a Task

1. Read the current progress document.
2. Identify the relevant feature and its existing status.
3. Check whether the task depends on unfinished work or known issues.
4. Determine the completion criteria for the requested task.

### After a Meaningful Task

Update `PROGRESS.md` in the same task before providing the final report.

The update is mandatory when a task results in a meaningful implementation change, bug fix, completed feature, verified discovery, blocker, or change in project direction.

Update the following sections when applicable:

- Current project phase and priority.
- Relevant feature status.
- Completion criteria and notes.
- Recent changes.
- Known issues and blockers.
- Next steps.

Do not create a progress entry for every trivial formatting edit. Group small related changes into one meaningful entry.

### Allowed Statuses

Use only these status values:

- `NOT STARTED`
- `IN PROGRESS`
- `COMPLETED`
- `BLOCKED`
- `NEEDS VERIFICATION`

Definitions:

- `NOT STARTED`: Implementation has not begun.
- `IN PROGRESS`: Implementation has started but is incomplete.
- `COMPLETED`: The defined requirements have been implemented and the relevant checks have passed.
- `BLOCKED`: Work cannot proceed because of an identified blocker.
- `NEEDS VERIFICATION`: Implementation may exist, but sufficient verification has not been completed.

Do not mark a feature `COMPLETED` simply because its page, route, controller, or placeholder exists.

A feature may be completed even if unrelated project-wide work remains unfinished. Evaluate completion against that feature's defined criteria.

### When Work Is Incomplete

- Use `IN PROGRESS` if implementation is partially complete.
- Use `BLOCKED` if a specific blocker prevents further work.
- Use `NEEDS VERIFICATION` if implementation exists but relevant checks remain outstanding.
- Record meaningful partial work when a task is interrupted or fails.
- Never claim that a test, browser check, or command passed unless it was actually executed and its result was checked.

### Progress History

Each meaningful entry in the recent changes log should include:

- Date.
- Task or feature.
- Summary of changes.
- Important files affected when useful.
- Validation performed and its actual result.

Keep entries concise. Do not duplicate entire task reports or source code.

## 9. Documentation Responsibilities

Each document has a distinct purpose.

### `README.md`

Use it for:

- Project overview.
- Main capabilities.
- Technology stack.
- Setup and execution instructions.
- Links to important documentation.
- A concise current progress summary.

Update it when those details materially change.

### `PROJECT_CONTEXT.md`

Use it for:

- Persistent project context.
- Architectural decisions and constraints.
- Design principles.
- Important implementation decisions.
- Confirmed project-specific information.

Update it when a persistent decision or important project fact changes.

### `PROGRESS.md`

Use it for:

- Current feature status.
- Completed work.
- Work in progress.
- Known issues and blockers.
- Recent changes.
- Next development priorities.

Update it after every meaningful implementation task.

### `docs/`

Use it for detailed technical documentation, such as architecture, database design, feature specifications, or implementation workflows.

Do not create duplicate documentation when an existing file already serves the same purpose.

### `AGENTS.md`

Use it for instructions governing AI coding agents.

Do not copy the full project description or feature progress table into this file.

## 10. Testing and Verification

After making changes:

1. Review the diff and modified files.
2. Check for unintended changes.
3. Run relevant tests or validation commands when possible.
4. For UI changes, inspect the rendered result when a browser or preview is available.
5. Check responsive layouts and important interaction states when relevant.
6. Confirm that existing behavior remains intact.
7. Update documentation to reflect what was actually implemented and verified.

Use the repository's actual tooling and scripts. Inspect `composer.json`, available test files, and project configuration before assuming which commands are supported.

If a test cannot be run because of missing services, unavailable tools, or environment limitations, state that explicitly.

Do not claim that visual, functional, integration, or end-to-end testing passed when only a code inspection was performed.

## 11. Git and Existing Changes

- Inspect the current working tree before making changes when Git is available.
- Preserve unrelated user modifications.
- Do not reset, force-push, delete branches, or discard work unless explicitly authorized.
- Do not commit or push changes unless requested.
- Do not include generated files, credentials, or local environment artifacts unnecessarily.

## 12. Final Response Format

After completing a task, provide a concise report containing:

1. **Implemented:** What was changed.
2. **Files Changed:** The important files created or modified.
3. **Verification:** Tests or checks actually performed and their results.
4. **Progress Updated:** Which entries in `PROGRESS.md` were updated.
5. **Remaining Work:** Known issues, blockers, or unverified requirements.
6. **Next Step:** The most useful next task, if appropriate.

If no implementation change was made, do not report the task as completed.

## 13. Priority Rule

When instructions conflict, prioritize:

1. Explicit user instructions for the current task.
2. Security, data integrity, and preservation of user work.
3. This file's working rules.
4. Project-specific decisions in `PROJECT_CONTEXT.md`.
5. Existing repository conventions.
6. General coding preferences.

Do not silently change the project's architecture or scope to resolve a conflict. Explain material conflicts and request clarification when necessary.