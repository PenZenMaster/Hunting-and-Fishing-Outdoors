"""
Module/Script Name: CLAUDE.md
Path: D:/local/HNFO-DEV/app/public/CLAUDE.md

Description:
Project-level Claude Code configuration for the Hunting and Fishing Outdoors
WordPress site. Defines startup, checkpoint, and shutdown protocols, quality
gates, environment info, and project-specific conventions.

Author(s):
Rank Rocket Co (C) Copyright 2026 - All Rights Reserved

Created Date:
2026-02-23

Last Modified Date:
2026-02-23

Comments:
* v1.00 - Initial project-level config
"""

---

# HNFO Project - Claude Code Configuration

## Project Overview

**Project:** Hunting and Fishing Outdoors (HNFO) WordPress Site
**Environment:** http://hnfo-development.local/
**Repository:** https://github.com/[username]/Hunting-and-Fishing-Outdoors
**Branch:** main (single-branch workflow)
**Theme:** WPRentals v3.11.4 (modified) + wprentals-child
**PHP:** 8.4.2 | **Platform:** Windows / Cygwin

---

## Trigger Phrases

| Phrase | Action |
|---|---|
| `Project start` | Run Startup protocol |
| `Checkpoint now` or `Prepare for rollover` | Run Checkpoint protocol |
| `Project shutdown` | Run Shutdown protocol |

---

## 1) Startup Protocol

When the user says **"Project start"**, execute these steps in order:

### Step 1 - Read State Files
- Read `docs/projectStatus.md`
- Read the latest file in `docs/archive/checkpoints/` (sort by filename desc)

### Step 2 - Read Git Log
Run `git log --oneline -10` to see recent commits since last checkpoint.

### Step 3 - Post Briefing
Respond with this structure (concise, no fluff):

```
## HNFO Session Start - {date}

### Last Session Wins
- <bullet per completed item from checkpoint>

### Current Blockers
- <any red/blocked items>

### Remaining Work (priority order)
1. <High priority item>
2. <Medium priority item>
...

### Today's Recommended Focus
<1-3 sentence suggested plan for this session>
```

Do NOT start any implementation work until the user confirms the plan.

---

## 2) Checkpoint Protocol

When the user says **"Checkpoint now"** or **"Prepare for rollover"**, execute
these steps in order:

### Step 1 - Gather State
- Run `git log --oneline` since the last checkpoint commit
- Run `git status` for uncommitted changes
- Note all files modified this session

### Step 2 - Write Checkpoint File
Create `docs/archive/checkpoints/CheckPoint-YYYY-MM-DD_HHMM.md` using the
current local date/time. Use this structure:

```markdown
# Checkpoint - YYYY-MM-DD HH:MM

## Context Summary
<2-3 sentences on what was being worked on and why>

## Accomplishments
### Completed
- <item with commit hash if applicable>

### In Progress
- <item + current state + what remains>

## Technical Changes
### Files Modified
- <path> - <what changed>

### Files Created
- <path> - <purpose>

## Commits This Session
- `<hash>` - <message>

## Known Issues
### <Priority> - <Issue Name>
<description, root cause, next step>

## Next Session Priorities
### High
1. <item>

### Medium
2. <item>

## Git Status
Branch: main
Last Commit: <hash> - <message>
Uncommitted Changes: <None | list>

## Environment
<any env changes worth noting>

---
*Checkpoint created: YYYY-MM-DD HH:MM*
```

### Step 3 - Update projectStatus.md
Rewrite `docs/projectStatus.md` to reflect current state:
- Move newly completed items to Completed section
- Update In Progress items
- Update Blockers
- Set "Last Updated" to current date/time

### Step 4 - Commit
Stage and commit with:
```
git add docs/archive/checkpoints/CheckPoint-YYYY-MM-DD_HHMM.md docs/projectStatus.md
git commit -m "chore(checkpoint): YYYY-MM-DD_HHMM - <short summary>"
```
Do NOT push unless the user explicitly asks to push.

---

## 3) Shutdown Protocol

When the user says **"Project shutdown"**, execute in order:

1. Run the full **Checkpoint Protocol** (steps 1-4 above).
2. Run `git push` and confirm success.
3. Respond with:

```
## HNFO Session Shutdown - {date}

Checkpoint committed and pushed to main.
Last commit: <hash>

### Next Session - Start Here
1. <Top priority item>
2. <Second priority item>
3. <Third priority item>
```

---

## 4) Quality Gates

### PHP / WordPress

```bash
phpcs --standard=phpcs.xml.dist
```

- WordPress Coding Standards enforced via pre-commit hook.
- Pre-push hook runs full PHPUnit suite (currently 14 tests).
- **Never skip hooks** (`--no-verify`) without user approval.

### Security Checklist (apply to every PHP change)
- Sanitize all inputs (`sanitize_text_field`, `absint`, etc.)
- Escape all outputs (`esc_html`, `esc_attr`, `esc_url`, etc.)
- Nonce verification on all AJAX handlers
- Capability checks (`current_user_can`) before any data mutation
- Use `$wpdb->prepare()` for all raw queries - no string interpolation

### Cache
- Clear `wpestate_get_features_array` transient after any amenity taxonomy change:
  `delete_transient('wpestate_get_features_array');`

---

## 5) Project Conventions

- **Child theme path:** `wp-content/themes/wprentals-child/`
- **Main theme path:** `wp-content/themes/wprentals/`
- **Custom amenity code:** `wp-content/themes/wprentals/wqs/`
- **Docs:** `docs/` (status, checkpoints, ADRs)
- **Temp/diagnostic files** (e.g. `check-*.php`) must be removed before
  checkpoint; do not commit diagnostic files to main.
- **No emojis in source code.** No Unicode symbols. ASCII only in PHP/JS.
- **No hardcoded secrets.** Use `wp-config.php` or `.env` for credentials.
- **No versioned filenames** (e.g., `functions_v2.php`). Edit in place.

---

## 6) Known Technical Context

### Amenity System
- Table: `new_amenities` (pending requests)
- Approval creates taxonomy term + sets term meta for image
- Two-tier image storage: term meta -> option table fallback
- Cache transient: `wpestate_get_features_array` (4 hours)
- Category filtering controlled by `taxonomy_terms` meta field

### Current Blocker
- Category filtering needs property_action_category IDs
- Source: `http://hnfo-development.local/check-property-categories.php`
  OR Administration Guide .docx at `D:/local/HNFO-DEV/`

### Upwork Legacy Debt
- Pattern: features 10-50% complete
- Always audit surrounding code before assuming it works
- 301 coding standard violations remain in child theme (warning-only, non-blocking)

---

## 7) Environment Quick Reference

| Item | Value |
|---|---|
| Local URL | http://hnfo-development.local/ |
| WP Admin | http://hnfo-development.local/wp-admin/ |
| DB Table (custom) | new_amenities |
| PHP version | 8.4.2 |
| Shell | Cygwin bash (use Unix paths) |
| Working dir | D:/local/HNFO-DEV/app/public |
