#!/usr/bin/env python3
from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUTPUT_CSS = ROOT / "docs" / "storefront-merged.css"
OUTPUT_REPORT = ROOT / "docs" / "storefront-css-sources.md"

PAGE_EXCLUDES = {
    "add_to_cart.php",
    "add-reward-to-cart.php",
    "apply_coupon.php",
    "cart_update.php",
    "config.php",
    "coupon_ajax.php",
    "functions.php",
    "get_shipping.php",
    "get_townships.php",
    "header.php",
    "footer.php",
    "init.php",
    "logout.php",
    "mobile-bottom-menu.php",
    "place_order.php",
    "search_ajax.php",
    "validate_coupon.php",
    "validate_product_variant_connections.php",
    "widget.php",
}

PAGE_EXCLUDE_PATTERNS = ("ajax", "update", "validate", "process")

link_re = re.compile(r"<link[^>]*rel=[\"']?stylesheet[\"']?[^>]*>", re.IGNORECASE)
href_re = re.compile(r"href=[\"']([^\"']+)[\"']", re.IGNORECASE)
style_re = re.compile(r"<style[^>]*>(.*?)</style>", re.IGNORECASE | re.DOTALL)
include_re = re.compile(r"(?:include|require)(?:_once)?\s*\(?\s*['\"]([^'\"]+\.php)['\"]", re.IGNORECASE)


def is_storefront_page(path: Path) -> bool:
    if path.parent != ROOT:
        return False

    name = path.name.lower()
    if name in PAGE_EXCLUDES:
        return False
    if any(token in name for token in PAGE_EXCLUDE_PATTERNS):
        return False

    text = path.read_text(encoding="utf-8", errors="ignore").lower()
    return any(marker in text for marker in ("<html", "<!doctype", "<body", "<main", "<section", "<style", "stylesheet"))


def resolve_php_path(current: Path, target: str) -> Path | None:
    cleaned = target.split("?", 1)[0].split("#", 1)[0]
    candidate = (current.parent / cleaned).resolve()
    if candidate.exists() and candidate.suffix == ".php" and (candidate == ROOT or ROOT in candidate.parents):
        return candidate

    fallback = ROOT / cleaned.lstrip("/")
    if fallback.exists() and fallback.suffix == ".php":
        return fallback

    return None


def iter_php_dependencies(entry: Path) -> list[Path]:
    ordered: list[Path] = []
    seen: set[Path] = set()

    def walk(node: Path) -> None:
        if node in seen:
            return
        seen.add(node)
        ordered.append(node)

        text = node.read_text(encoding="utf-8", errors="ignore")
        for inc in include_re.findall(text):
            resolved = resolve_php_path(node, inc)
            if resolved:
                walk(resolved)

    walk(entry)
    return ordered


def resolve_css_path(current: Path, href: str) -> Path | None:
    cleaned = href.split("?", 1)[0].split("#", 1)[0]
    if cleaned.startswith(("http://", "https://", "//")):
        return None

    if cleaned.startswith("/"):
        candidate = ROOT / cleaned.lstrip("/")
        if candidate.exists() and candidate.suffix == ".css":
            return candidate
        return None

    candidate = (current.parent / cleaned).resolve()
    if candidate.exists() and candidate.suffix == ".css":
        return candidate

    fallback = ROOT / cleaned
    if fallback.exists() and fallback.suffix == ".css":
        return fallback

    return None


def collect_css() -> tuple[list[str], list[str]]:
    storefront_pages = sorted([p for p in ROOT.glob("*.php") if is_storefront_page(p)])

    report: list[str] = [
        "# Storefront CSS Sources",
        "",
        "Generated from `scripts/gather_page_css.py`.",
        "",
        "## Storefront pages",
    ]
    report.extend([f"- `{p.name}`" for p in storefront_pages])
    report.append("")

    merged: list[str] = ["/* Merged storefront CSS (generated) */", ""]
    consumed_css_files: set[Path] = set()

    for page in storefront_pages:
        report.append(f"## `{page.name}`")
        php_files = iter_php_dependencies(page)
        report.append(f"- PHP files scanned: {len(php_files)}")

        for php_file in php_files:
            rel_php = php_file.relative_to(ROOT)
            text = php_file.read_text(encoding="utf-8", errors="ignore")

            for tag in link_re.findall(text):
                href_match = href_re.search(tag)
                if not href_match:
                    continue
                href = href_match.group(1).strip()
                css_path = resolve_css_path(php_file, href)
                if not css_path:
                    report.append(f"  - Unresolved stylesheet: `{href}` in `{rel_php}`")
                    continue
                if css_path in consumed_css_files:
                    continue

                consumed_css_files.add(css_path)
                rel_css = css_path.relative_to(ROOT)
                report.append(f"  - Linked stylesheet: `{rel_css}` (from `{rel_php}`)")

                css_content = css_path.read_text(encoding="utf-8", errors="ignore").strip()
                merged.extend([
                    f"/* Source file: {rel_css} */",
                    css_content,
                    "",
                ])

            inline_blocks = style_re.findall(text)
            for idx, block in enumerate(inline_blocks, start=1):
                cleaned = block.strip()
                if not cleaned:
                    continue
                report.append(f"  - Inline style block {idx} from `{rel_php}`")
                merged.extend([
                    f"/* Inline styles from {rel_php} (block {idx}) */",
                    cleaned,
                    "",
                ])

        report.append("")

    return merged, report


def main() -> None:
    OUTPUT_CSS.parent.mkdir(parents=True, exist_ok=True)
    merged, report = collect_css()
    OUTPUT_CSS.write_text("\n".join(merged).rstrip() + "\n", encoding="utf-8")
    OUTPUT_REPORT.write_text("\n".join(report).rstrip() + "\n", encoding="utf-8")
    print(f"Wrote merged CSS: {OUTPUT_CSS.relative_to(ROOT)}")
    print(f"Wrote report: {OUTPUT_REPORT.relative_to(ROOT)}")


if __name__ == "__main__":
    main()
