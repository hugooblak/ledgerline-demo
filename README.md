# Ledger Line — accounting firm demo (WordPress)

A WordPress twin of the Ledger Line concept site. **Ledger Line is a fictional accounting firm.**
The name, founder, figures and photographs are sample content, and the form sends nothing.

**Live demo:** https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/hugooblak/ledgerline-demo/main/blueprint.json

It runs in WordPress Playground: a whole WordPress site in the browser tab, nothing installed.
Login is `admin` / `password`.

## What it shows

- **One-page firm site** with the same sections as the static version: hero, how it works,
  services, numbers, why, founder note and FAQ, book a call, footer.
- **Hero panels** that play one month once on load: receipts settle, a month-end checklist ticks
  itself off, a profit and loss counts up. The checklist and the figures are edited in the block
  sidebar, and gross and net profit are calculated, so the sheet always adds up.
- **How it works**: a line fills as you scroll and each step lights up.
- **Services** with small moving graphics: transactions getting categories, the four US estimated
  tax dates, a cash-flow line.
- **Numbers** that count up, and a **Why** paragraph whose words go from faint to ink as you read.
- **Founder note** with a signature that writes itself in, and an **FAQ** on WordPress's own
  Details block.
- **Book a call** form with plain-English error messages. Demo only: nothing is sent, and the form
  says so.
- Reduced motion is respected everywhere. Without JavaScript every word and number is still there.

## Deliberately not here

A real working form (saving enquiries in the admin and emailing the firm), more pages, prices, a
blog. A real job connects the form block to a form plugin or the firm's calendar.

## How it is built

- Block theme, `theme.json`, native blocks and patterns. No page builder, no Custom HTML blocks.
- Plugin **Ledger Line Blocks** holds the three parts normal blocks cannot do: hero panels, service
  graphic, book-a-call form. Each loads its script only where it is used.
- Self-hosted fonts (Figtree, DM Sans, DM Mono, Caveat). No third-party scripts.
- Checks: see `proof/`.

## Folder layout

```
theme/ledgerline/                 block theme
plugins/ledgerline-blocks/        hero panels, service graphic, book form blocks
plugins/ledgerline-demo-notes/    portfolio-only bar and notes. Deleted on a real site.
content/setup.php                 builds the demo on a brand-new install
blueprint.json                    what WordPress Playground runs
proof/                            test results and screenshots
```
