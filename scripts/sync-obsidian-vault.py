#!/usr/bin/env python3
"""
Re-syncs all project .md files into ./vault as a structured Obsidian graph.

Each note gets:
  - Flattened name: 'a/b/c.md' -> 'a__b__c.md', leading dots stripped
  - Frontmatter: source path, area (semantic domain), tags
  - Trailing "Related" block with [[wikilinks]] to top-K most-similar siblings
  - "See also" footer linking to MoC + INDEX

After file sync, generates Map-of-Content (MoC) files per area (with optional
"Start here" priority sections) and refreshes INDEX.md so Claude can use the
vault as structured prompt context:
  1. Open INDEX.md → overview of all areas
  2. Open MoC-{Area}.md → list of all notes in that domain (priority first)
  3. Open individual notes → cross-linked via Related block

Hand-written notes (Glossary.md, Conventions.md) are NEVER overwritten by this
script — they are user-curated and skipped during the wipe. INDEX.md IS
regenerated unconditionally (see write_index docstring).
"""

from __future__ import annotations
from datetime import datetime, timezone
from pathlib import Path
import re
import sys

ROOT = Path(__file__).resolve().parent.parent
VAULT = ROOT / "vault"
NOW = datetime.now(timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")

# Files preserved across syncs (hand-curated). INDEX.md is NOT in this set
# because it embeds counts and area lists that must stay current.
PROTECTED_NAMES = {"Glossary.md", "Conventions.md"}

# (regex, area) — first match wins. Order matters.
# Note on `cars|cities|vehicle`: routes Car/City/vehicle-related notes into
# `cargo` area since they're FK-related to cargo lifecycle.
AREA_RULES: list[tuple[str, str]] = [
    (r"PLESK|DEPLOY|MYSQL|CI_CD|QUICK_START", "infrastructure"),
    (r"driver_passwordless|driver_login|driver_whatsapp|unified_login|unified_register|driver_reg", "auth"),
    (r"profile", "auth"),
    (r"cmr", "cargo"),
    (r"public_cargo|cargo|cars|cities|vehicle", "cargo"),
    (r"documents_feature|driver_documents|admin_documents|docs_api|docs_batch", "documents"),
    (r"admin_translation|translation", "admin"),
    (r"whatsapp_otp|wa-api", "whatsapp"),
    (r"status_audit|silk_way", "overview"),
    (r"^database__", "database"),
    (r"^README", "overview"),
]

AREA_TAGS = {
    "infrastructure": ["infra", "deploy"],
    "auth":           ["auth", "backend"],
    "cargo":          ["cargo", "backend", "domain"],
    "documents":      ["documents", "backend", "frontend"],
    "admin":          ["admin", "backend", "frontend"],
    "whatsapp":       ["whatsapp", "integration", "backend"],
    "overview":       ["overview"],
    "database":       ["database", "backend"],
    "misc":           ["misc"],
}

AREA_TITLES = {
    "infrastructure": "Инфраструктура и деплой",
    "auth":           "Авторизация и регистрация",
    "cargo":          "Грузы (cargo lifecycle, CMR)",
    "documents":      "Документы водителя",
    "admin":          "Админ-инструменты",
    "whatsapp":       "WhatsApp интеграция (WAHA)",
    "overview":       "Обзор проекта",
    "database":       "База данных",
    "misc":           "Прочее",
}

# Stable display order on INDEX.
AREA_ORDER = [
    "overview", "auth", "cargo", "documents", "whatsapp",
    "admin", "database", "infrastructure", "misc",
]

# "Start here" — foundational notes per area, surfaced at the top of the MoC.
# Match by basename without extension. Order within the list is preserved.
AREA_PRIORITY_NOTES: dict[str, list[str]] = {
    "auth": [
        "claude__agent-memory__senior-laravel-dev__project_whatsapp_otp_driver_reg",
        "claude__agent-memory__senior-laravel-dev__project_driver_login_flow",
        "claude__agent-memory__senior-laravel-dev__project_driver_passwordless",
        "claude__agent-memory__senior-laravel-dev__project_profile_and_approval_notify",
    ],
    "cargo": [
        "claude__agent-memory__senior-laravel-dev__project_cargo_schema_extras",
        "claude__agent-memory__senior-laravel-dev__project_cmr_flow",
        "claude__agent-memory__senior-laravel-dev__project_cars_domain",
        "claude__agent-memory__senior-laravel-dev__project_cities_domain",
    ],
    "documents": [
        "claude__agent-memory__senior-laravel-dev__project_docs_api_contract",
        "claude__agent-memory__senior-laravel-dev__project_docs_batch_upload",
    ],
    "overview": [
        "claude__agent-memory__senior-laravel-dev__project_status_audit",
        "claude__agent-memory__senior-ux-product-designer__project_silk_way",
    ],
}

# Paths excluded from sync (whole-prefix match on relative path).
EXCLUDED_PREFIXES = (
    "vendor/",
    "node_modules/",
    "vault/",
    "storage/",
    ".claude/commands/",   # Claude Code skill definitions — tooling, not project knowledge
    ".claude/agents/",     # Agent system prompts — tooling, not project knowledge
)

# Path patterns excluded by relative-path match (anchored, not just basename).
# Using path patterns avoids accidentally hiding a domain file just because
# someone named a project file MEMORY.md somewhere else in the tree.
EXCLUDED_PATH_PATTERNS = (
    re.compile(r"^\.claude/agent-memory/[^/]+/MEMORY\.md$"),
)

# Tokens too generic to count when measuring note similarity for the Related
# block. Without filtering these, every two notes share "claude / project /
# senior-laravel-dev" and the Related block degenerates to "all siblings".
SIMILARITY_STOPWORDS = {
    "claude", "agent-memory", "agent", "memory",
    "senior-laravel-dev", "senior-ux-product-designer",
    "senior", "laravel", "ux", "product", "designer",
    "project", "domain", "flow",
    "MoC", "INDEX", "page",
}

# Cap the Related block at K most-similar siblings.
RELATED_TOP_K = 5


def classify_area(flat_name: str) -> str:
    for pattern, area in AREA_RULES:
        if re.search(pattern, flat_name):
            return area
    return "misc"


def collect_sources() -> list[Path]:
    sources = []
    for p in ROOT.rglob("*.md"):
        rel = p.relative_to(ROOT).as_posix()
        if any(rel.startswith(prefix) for prefix in EXCLUDED_PREFIXES):
            continue
        if any(pattern.match(rel) for pattern in EXCLUDED_PATH_PATTERNS):
            continue
        sources.append(p)
    return sorted(sources)


def flatten_path(rel: str) -> str:
    flat = rel.replace("/", "__")
    return flat.lstrip(".")  # leading dot would hide the file


def yaml_tag_list(tags: list[str]) -> str:
    return "[" + ", ".join(tags) + "]"


def tokenize_for_similarity(flat: str) -> set[str]:
    """Extract meaningful tokens from a flattened filename for Related ranking."""
    base = flat[:-3] if flat.endswith(".md") else flat
    raw_tokens = re.split(r"__|_|-", base)
    return {t.lower() for t in raw_tokens if t and t.lower() not in {s.lower() for s in SIMILARITY_STOPWORDS}}


def rank_related(self_flat: str, all_siblings: list[str], k: int = RELATED_TOP_K) -> list[str]:
    """Return up to k siblings ranked by token overlap with self_flat."""
    self_tokens = tokenize_for_similarity(self_flat)
    scored: list[tuple[int, str]] = []
    for s in all_siblings:
        if s == self_flat:
            continue
        overlap = len(self_tokens & tokenize_for_similarity(s))
        scored.append((overlap, s))
    # Sort by overlap desc, then by name asc (stable, deterministic).
    scored.sort(key=lambda item: (-item[0], item[1]))
    # Keep ties even if they push past k, but cap at k+2 to avoid huge lists.
    top = scored[:k]
    # If everyone scored 0 (no shared meaningful tokens), still return top-k
    # alphabetically — better some signal than none.
    return [s for _, s in top]


def write_note(src: Path, flat: str, area: str, siblings: list[str]) -> None:
    rel = src.relative_to(ROOT).as_posix()
    tags = AREA_TAGS.get(area, ["misc"])
    body = src.read_text(encoding="utf-8")

    related_targets = rank_related(flat, siblings, k=RELATED_TOP_K)
    related_lines = "\n".join(f"- [[{s[:-3]}]]" for s in related_targets)

    parts = [
        "---",
        f"source: {rel}",
        f"area: {area}",
        f"tags: {yaml_tag_list(tags)}",
        f"synced: {NOW}",
        "---",
        "",
        body.rstrip(),
    ]
    if related_lines:
        parts += ["", "---", "## Related (by token similarity)", "", related_lines]
    parts += [
        "",
        "## See also",
        "",
        f"- [[MoC-{area}]] — карта домена",
        "- [[INDEX]] — оглавление vault",
        "",
    ]

    (VAULT / flat).write_text("\n".join(parts), encoding="utf-8")


def write_moc(area: str, files_in_area: list[str]) -> None:
    title = AREA_TITLES.get(area, area)
    priority = AREA_PRIORITY_NOTES.get(area, [])
    priority_set = set(priority)
    rest = sorted(f for f in files_in_area if f[:-3] not in priority_set)

    lines = [
        "---",
        "type: moc",
        f"area: {area}",
        f"tags: [moc, {area}]",
        f"synced: {NOW}",
        "---",
        "",
        f"# MoC — {title}",
        "",
        f"Карта-оглавление домена `{area}`.",
        "",
    ]

    if priority:
        # Filter to those that actually exist in this area (defensive).
        present_priority = [p for p in priority if any(f[:-3] == p for f in files_in_area)]
        if present_priority:
            lines += [
                "## Start here",
                "",
                "Foundational notes — открыть в первую очередь:",
                "",
            ]
            for p in present_priority:
                lines.append(f"- [[{p}]]")
            lines.append("")

    if rest:
        lines += ["## Все заметки", ""] if priority else []
        for f in rest:
            lines.append(f"- [[{f[:-3]}]]")

    lines += [
        "",
        "## См. также",
        "",
        "- [[INDEX]] — корневое оглавление vault",
        "- [[Glossary]] — термины и сокращения",
        "- [[Conventions]] — конвенции проекта",
        "",
    ]
    (VAULT / f"MoC-{area}.md").write_text("\n".join(lines), encoding="utf-8")


def write_index(area_to_files: dict[str, list[str]], total: int) -> None:
    """
    Always regenerated. INDEX is purely auto — embeds note counts, area list,
    and last-sync timestamp. To customize the "Use as Claude prompt context"
    section, edit this function (not the file itself, since this overwrites it).
    """
    lines = [
        "---",
        "type: index",
        "tags: [index]",
        f"synced: {NOW}",
        "---",
        "",
        "# Silk Way — Knowledge Vault",
        "",
        "Корневой индекс. Все заметки сгруппированы по доменам через MoC-файлы.",
        "",
        "## Use as Claude prompt context",
        "",
        "При работе с Claude:",
        "",
        "1. Дай ссылку на этот файл (`vault/INDEX.md`) как стартовую точку.",
        "2. Claude может развернуть нужный домен через MoC-карту.",
        "3. Каждая заметка содержит `area`, `tags` и блок `Related (by token similarity)` для расширения контекста.",
        "4. Все термины — в [[Glossary]]; общие правила — в [[Conventions]].",
        "5. Большие MoC-карты (auth, cargo) имеют секцию `Start here` с приоритетными заметками.",
        "",
        "## Карты доменов",
        "",
    ]
    for area in AREA_ORDER:
        if area not in area_to_files:
            continue
        title = AREA_TITLES.get(area, area)
        n = len(area_to_files[area])
        lines.append(f"- [[MoC-{area}]] — **{title}** ({n} заметок)")
    # any areas not in AREA_ORDER (defensive)
    for area in sorted(set(area_to_files) - set(AREA_ORDER)):
        title = AREA_TITLES.get(area, area)
        n = len(area_to_files[area])
        lines.append(f"- [[MoC-{area}]] — **{title}** ({n} заметок)")
    lines += [
        "",
        "## Правила синхронизации",
        "",
        "- Все заметки регенерируются скриптом `scripts/sync-obsidian-vault.py`.",
        "- **Hand-curated:** `Glossary.md`, `Conventions.md` — скрипт их не трогает.",
        "- **Auto-generated:** `INDEX.md`, `MoC-*.md`, отдельные заметки — перезаписываются при каждом запуске.",
        "- Чтобы изменить INDEX.md, редактируй функцию `write_index()` в скрипте, а не сам файл.",
        "- Frontmatter каждой заметки содержит `source:` — путь к исходнику в проекте.",
        "",
        "## Метаданные",
        "",
        f"- Всего заметок: **{total}**",
        f"- MoC-карт: **{len(area_to_files)}**",
        f"- Последняя синхронизация: `{NOW}`",
        "",
    ]
    (VAULT / "INDEX.md").write_text("\n".join(lines), encoding="utf-8")


def wipe_vault() -> None:
    """Delete all .md files in vault root except hand-curated ones."""
    if not VAULT.exists():
        VAULT.mkdir(parents=True, exist_ok=True)
        return
    for f in VAULT.glob("*.md"):
        if f.name in PROTECTED_NAMES:
            continue
        f.unlink()


def main() -> int:
    if not VAULT.exists():
        VAULT.mkdir(parents=True, exist_ok=True)

    wipe_vault()

    sources = collect_sources()
    if not sources:
        print("No source .md files found.", file=sys.stderr)
        return 1

    # Pass 1: classify
    by_flat: dict[str, tuple[Path, str]] = {}
    area_to_files: dict[str, list[str]] = {}
    for src in sources:
        rel = src.relative_to(ROOT).as_posix()
        flat = flatten_path(rel)
        area = classify_area(flat)
        by_flat[flat] = (src, area)
        area_to_files.setdefault(area, []).append(flat)

    # Pass 2: write notes
    for flat, (src, area) in by_flat.items():
        siblings = area_to_files[area]
        write_note(src, flat, area, siblings)

    # Pass 3: MoC files
    for area, files in area_to_files.items():
        write_moc(area, files)

    # Pass 4: INDEX
    write_index(area_to_files, total=len(sources))

    print(f"Synced {len(sources)} notes into {len(area_to_files)} areas.")
    print(f"Vault: {VAULT}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
