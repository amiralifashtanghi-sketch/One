# AGENTS.md — Project & Agent Development Instructions

## Mandatory Workflow Directive
For every task:
1. **Plan First**: Thoroughly analyze requirements and formulate a step-by-step plan before making changes.
2. **Write or Update Tests**: Practice test-driven or test-supported development. Ensure tests cover new logic and edge cases.
3. **Implement**: Write clean, modular, and maintainable code adhering to project standards.
4. **Review Diff**: Before opening a PR or submitting, review the git diff for potential security vulnerabilities, regressions, or unintended side effects.

---

## ECC Guidelines & Rules

### Common Engineering Standards (`rules/common`)

#### Coding Style & Maintainability
- Keep functions small and focused (< 50 lines).
- Soft limit source files to under 800 lines; modularize large files into smaller components.
- Max 4 levels of control flow nesting; use guard clauses and early returns.
- Explicitly handle all errors; avoid empty catch blocks or swallowing exceptions.
- Never hardcode credentials, secrets, or API keys.

#### Security Principles
- Validate and sanitize all incoming external data.
- Enforce strict authorization checks for all actions and data accesses.
- Prevent common web vulnerabilities: SQL Injection, XSS, CSRF, Path Traversal, and SSRF.
- Use parameterized queries / prepared statements for all database operations.

#### Development Workflow & Testing
- Search existing code (`search-first`) before writing new utilities or reinventing functions.
- Maintain test coverage (aim for >= 80% coverage on new features).
- Use TDD cycle where applicable: Write failing test -> Implement minimum code -> Refactor.

---

### PHP & WordPress Guidelines (`rules/php`)

- **Standards**: Follow PSR coding standards and WordPress Coding Standards where applicable.
- **Database Safety**: Always use prepared statements or `$wpdb->prepare()` for dynamic SQL queries.
- **Input & Output**:
  - Sanitize all input (`sanitize_text_field`, `sanitize_email`, etc.).
  - Escape all output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses`).
- **State Security**: Enforce Nonce verification (`wp_verify_nonce` / `check_admin_referer`) on all forms and AJAX/REST endpoints.

---

## Installed Skills

The following ECC skills are installed in `.agents/skills/`:
- **`coding-standards`**: `.agents/skills/coding-standards/SKILL.md`
- **`security-review`**: `.agents/skills/security-review/SKILL.md`
- **`search-first`**: `.agents/skills/search-first/SKILL.md`
- **`tdd-workflow`**: `.agents/skills/tdd-workflow/SKILL.md`
