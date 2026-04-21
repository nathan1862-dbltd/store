#!/usr/bin/env python3
from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "docs" / "page-css"
INVENTORY = ROOT / "docs" / "page-css-inventory.md"
MASTER_CSS = ROOT / "docs" / "master.css"
MASTER_MAP = ROOT / "docs" / "master-css-sources.md"

SKIP_PATTERNS = (
    "ajax",
    "update",
    "delete",
    "toggle",
    "process",
    "config",
    "auth",
    "helpers",
    "_",
)

link_re = re.compile(r"<link[^>]*rel=[\"']?stylesheet[\"']?[^>]*>", re.IGNORECASE)
href_re = re.compile(r"href=[\"']([^\"']+)[\"']", re.IGNORECASE)
style_re = re.compile(r"<style[^>]*>(.*?)</style>", re.IGNORECASE | re.DOTALL)
comment_re = re.compile(r"/\*.*?\*/", re.DOTALL)


def is_likely_page(path: Path) -> bool:
    name = path.name.lower()
    if name in {"header.php", "footer.php", "functions.php", "init.php", "mobile-bottom-menu.php"}:
        return False
    if any(tok in name for tok in SKIP_PATTERNS):
        return False
    text = path.read_text(encoding="utf-8", errors="ignore").lower()
    return any(tag in text for tag in ("<html", "<!doctype", "<body", "<main", "<section", "<style", "stylesheet"))


def extract_css_refs(text: str) -> list[str]:
    refs: list[str] = []
    for tag in link_re.findall(text):
        m = href_re.search(tag)
        if m:
            refs.append(m.group(1).strip())
    return refs


def resolve_local_css(page: Path, href: str) -> Path | None:
    cleaned = href.split("?", 1)[0].split("#", 1)[0]
    if cleaned.startswith(("http://", "https://", "//")):
        return None

    candidates = []
    if cleaned.startswith("/"):
        candidates.append(ROOT / cleaned.lstrip("/"))
    else:
        candidates.append((page.parent / cleaned).resolve())
        candidates.append(ROOT / cleaned)

    for candidate in candidates:
        if candidate.exists() and candidate.suffix == ".css":
            return candidate
    return None


def page_slug(path: Path) -> str:
    return str(path.relative_to(ROOT)).replace("/", "__").replace(".php", "")


def minify_css(css: str) -> str:
    css = comment_re.sub("", css)
    css = re.sub(r"\s+", " ", css)
    css = re.sub(r"\s*([{}:;,>+~])\s*", r"\1", css)
    css = css.replace(";}", "}")
    return css.strip()


def main() -> None:
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    pages = sorted([p for p in ROOT.rglob("*.php") if ".git" not in p.parts and is_likely_page(p)])
    inventory_lines = [
        "# Page CSS Inventory",
        "",
        "Generated from `scripts/gather_page_css.py`.",
        "",
    ]

    master_sources: list[tuple[str, str]] = []
    master_blocks: list[str] = []
    seen_minified_blocks: set[str] = set()

    for page in pages:
        rel = page.relative_to(ROOT)
        text = page.read_text(encoding="utf-8", errors="ignore")

        hrefs = extract_css_refs(text)
        raw_inline_blocks = style_re.findall(text)
        inline_blocks = [b for b in raw_inline_blocks if "<?php" not in b and "?>" not in b]

        bundle_path = OUT_DIR / f"{page_slug(page)}.css"
        bundle_lines = [f"/* CSS bundle for {rel} */", ""]

        resolved_count = 0
        for href in hrefs:
            css_file = resolve_local_css(page, href)
            if css_file:
                resolved_count += 1
                css_rel = css_file.relative_to(ROOT)
                css_content = css_file.read_text(encoding="utf-8", errors="ignore").strip()
                bundle_lines.extend([
                    f"/* Source link: {href} */",
                    f"/* Resolved file: {css_rel} */",
                    css_content,
                    "",
                ])
                minified = minify_css(css_content)
                if minified and minified not in seen_minified_blocks:
                    seen_minified_blocks.add(minified)
                    master_blocks.append(minified)
                    master_sources.append((str(rel), f"linked:{href} -> {css_rel}"))
            else:
                bundle_lines.extend([
                    f"/* Source link: {href} */",
                    "/* Not resolved to a local CSS file; skipped. */",
                    "",
                ])

        for idx, block in enumerate(inline_blocks, start=1):
            cleaned = block.strip()
            bundle_lines.extend([
                f"/* Inline style block {idx} from {rel} */",
                cleaned,
                "",
            ])
            minified = minify_css(cleaned)
            if minified and minified not in seen_minified_blocks:
                seen_minified_blocks.add(minified)
                master_blocks.append(minified)
                master_sources.append((str(rel), f"inline:block-{idx}"))

        bundle_path.write_text("\n".join(bundle_lines).rstrip() + "\n", encoding="utf-8")

        inventory_lines.append(f"## `{rel}`")
        inventory_lines.append(f"- CSS bundle: `docs/page-css/{bundle_path.name}`")
        inventory_lines.append(f"- Linked stylesheets found: {len(hrefs)} (resolved locally: {resolved_count})")
        if hrefs:
            for href in hrefs:
                inventory_lines.append(f"  - `{href}`")
        inventory_lines.append(f"- Inline `<style>` blocks: {len(inline_blocks)}")
        inventory_lines.append("")

    INVENTORY.write_text("\n".join(inventory_lines), encoding="utf-8")

    MASTER_CSS.write_text("\n".join(master_blocks) + "\n", encoding="utf-8")
    map_lines = [
        "# Master CSS Sources",
        "",
        "Order of CSS blocks included in `docs/master.css`:",
        "",
    ]
    for idx, (page_ref, source) in enumerate(master_sources, start=1):
        map_lines.append(f"{idx}. `{page_ref}` - `{source}`")
    MASTER_MAP.write_text("\n".join(map_lines) + "\n", encoding="utf-8")

    print(f"Wrote {len(pages)} bundles to {OUT_DIR.relative_to(ROOT)}")
    print(f"Wrote master CSS with {len(master_blocks)} unique blocks to {MASTER_CSS.relative_to(ROOT)}")


if __name__ == "__main__":
    main()
